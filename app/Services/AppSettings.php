<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppSettings
{
    public function definitions(): array
    {
        return [
            'case_entry_interview_date_cutoff_day' => [
                'label' => 'Case Entry Cutoff Day',
                'description' => 'Previous month interview data can be entered until this day of the next month.',
                'group' => 'Case Entry',
                'type' => 'integer',
                'default' => (int) config('a2j.case_entry.interview_date_cutoff_day', 5),
                'min' => 1,
                'max' => 28,
            ],
            'formal_case_upload_max_files' => [
                'label' => 'Maximum Attachment Files',
                'description' => 'Maximum number of files allowed with a formal case submission.',
                'group' => 'Attachments',
                'type' => 'integer',
                'default' => (int) config('a2j.formal_case_upload.max_files', 20),
                'min' => 1,
                'max' => 50,
            ],
            'formal_case_upload_max_size_mb' => [
                'label' => 'Maximum Attachment Size',
                'description' => 'Maximum size for each attachment file in MB.',
                'group' => 'Attachments',
                'type' => 'integer',
                'default' => (int) config('a2j.formal_case_upload.max_size_mb', 2),
                'min' => 1,
                'max' => 20,
            ],
            'report_header_title' => [
                'label' => 'Report Header Title',
                'description' => 'Primary title used in official report headers.',
                'group' => 'Reports',
                'type' => 'string',
                'default' => (string) config('a2j.reporting.header_title', 'Access to Justice for Women'),
                'max' => 255,
            ],
            'report_header_subtitle' => [
                'label' => 'Report Header Subtitle',
                'description' => 'Subtitle or implementation line used in official report headers.',
                'group' => 'Reports',
                'type' => 'string',
                'default' => (string) config('a2j.reporting.header_subtitle', ''),
                'max' => 500,
            ],
        ];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $definition = $this->definitions()[$key] ?? null;
        $fallback = $default ?? ($definition['default'] ?? null);

        if (! $this->settingsTableReady()) {
            return $fallback;
        }

        $settings = Cache::rememberForever('app_settings_values', function () {
            return AppSetting::query()->pluck('value', 'key')->all();
        });

        return array_key_exists($key, $settings) ? $settings[$key] : $fallback;
    }

    public function integer(string $key, int $default): int
    {
        $definition = $this->definitions()[$key] ?? [];
        $value = (int) $this->get($key, $default);

        if (isset($definition['min'])) {
            $value = max((int) $definition['min'], $value);
        }

        if (isset($definition['max'])) {
            $value = min((int) $definition['max'], $value);
        }

        return $value;
    }

    public function valuesForForm(): array
    {
        $values = [];

        foreach ($this->definitions() as $key => $definition) {
            $values[$key] = $this->get($key, $definition['default'] ?? null);
        }

        return $values;
    }

    public function syncDefinitions(): void
    {
        if (! $this->settingsTableReady()) {
            return;
        }

        foreach ($this->definitions() as $key => $definition) {
            AppSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => (string) ($this->get($key, $definition['default'] ?? '') ?? ''),
                    'type' => $definition['type'],
                    'label' => $definition['label'],
                    'description' => $definition['description'],
                    'group' => $definition['group'],
                ]
            );
        }

        $this->clearCache();
    }

    public function update(array $values, ?int $userId = null): void
    {
        if (! $this->settingsTableReady()) {
            return;
        }

        foreach ($this->definitions() as $key => $definition) {
            if (! array_key_exists($key, $values)) {
                continue;
            }

            AppSetting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => (string) ($values[$key] ?? ''),
                    'type' => $definition['type'],
                    'label' => $definition['label'],
                    'description' => $definition['description'],
                    'group' => $definition['group'],
                    'updated_by' => $userId,
                ]
            );
        }

        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::forget('app_settings_values');
    }

    private function settingsTableReady(): bool
    {
        try {
            return Schema::hasTable('app_settings');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
