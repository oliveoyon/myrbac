<?php

namespace Database\Seeders;

use App\Models\Act;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActSeeder extends Seeder
{
    public function run(): void
    {
        $acts = json_decode(file_get_contents(database_path('data/acts.json')), true, 512, JSON_THROW_ON_ERROR);
        DB::transaction(function () use ($acts) {
            foreach ($acts as $act) {
                Act::firstOrCreate(['url' => $act['url']], $act);
            }
        });
    }
}
