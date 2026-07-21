<?php

namespace App\Exports;

use App\Models\FormalCase;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class FormalCaseExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new FormalCaseDataExportSheet(),
            new FormalCaseExportFieldGuideSheet(),
        ];
    }
}

class FormalCaseDataExportSheet implements FromQuery, WithHeadings, WithMapping, WithTitle, WithEvents
{
    public function title(): string
    {
        return 'Cases';
    }

    public function query(): Builder
    {
        class_exists(FormalCaseImportTemplateExport::class);

        $query = FormalCase::query()
            ->select($this->fieldKeys())
            ->orderBy('district_id')
            ->orderBy('pngo_id')
            ->orderBy('id');

        return auth()->user()->applyDistrictPngoScope($query);
    }

    public function headings(): array
    {
        return $this->fieldKeys();
    }

    public function map($case): array
    {
        return array_map(function (string $field) use ($case) {
            $value = $field === 'type_of_service'
                ? implode(', ', $case->type_of_service_list)
                : $case->{$field};

            if ($value instanceof DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            return $value;
        }, $this->fieldKeys());
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:{$lastColumn}1");
                $sheet->getStyle("A1:{$lastColumn}1")->getFont()->setBold(true)->getColor()->setARGB('FF173B2F');
                $sheet->getStyle("A1:{$lastColumn}1")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE8F5EE');
                $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(34);

                $lastColumnIndex = Coordinate::columnIndexFromString($lastColumn);

                for ($columnIndex = 1; $columnIndex <= $lastColumnIndex; $columnIndex++) {
                    $column = Coordinate::stringFromColumnIndex($columnIndex);
                    $sheet->getColumnDimension($column)->setWidth(18);
                }

                foreach (['F', 'G', 'H', 'J', 'K'] as $wideColumn) {
                    $sheet->getColumnDimension($wideColumn)->setWidth(24);
                }
            },
        ];
    }

    private function fieldKeys(): array
    {
        class_exists(FormalCaseImportTemplateExport::class);

        return array_column(FormalCaseImportTemplateFields::fields(), 'key');
    }
}

class FormalCaseExportFieldGuideSheet implements FromArray, WithHeadings, WithTitle, WithEvents, ShouldAutoSize
{
    public function title(): string
    {
        return 'Field Guide';
    }

    public function headings(): array
    {
        return ['Upload header', 'Form no.', 'Field label', 'Required', 'Notes / sample values'];
    }

    public function array(): array
    {
        class_exists(FormalCaseImportTemplateExport::class);

        return array_map(function (array $field) {
            return [
                $field['key'],
                $field['no'] ?? '',
                $field['label'],
                $field['required'] ?? '',
                $field['note'] ?? '',
            ];
        }, FormalCaseImportTemplateFields::fields());
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $sheet->setAutoFilter('A1:E1');
                $sheet->getStyle('A1:E1')->getFont()->setBold(true)->getColor()->setARGB('FF173B2F');
                $sheet->getStyle('A1:E1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE8F5EE');
                $sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
            },
        ];
    }
}
