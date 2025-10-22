<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDatabase extends Command
{
    protected $signature = 'db:export {--file=database_backup.json}';
    protected $description = 'Export all database tables to a JSON file';

    public function handle()
    {
        $filename = $this->option('file');
        $filepath = storage_path('app/' . $filename);

        $this->info('Starting database export...');

        $tables = [
            'users',
            'subjects',
            'chapters',
            'questions',
            'exams',
            'exam_question',
            'exam_attempts',
            'exam_answers',
        ];

        $data = [];

        foreach ($tables as $table) {
            $this->info("Exporting table: {$table}");
            
            try {
                $records = DB::table($table)->get()->toArray();
                $data[$table] = json_decode(json_encode($records), true);
                $this->info("✓ Exported {$table}: " . count($records) . " records");
            } catch (\Exception $e) {
                $this->warn("✗ Failed to export {$table}: " . $e->getMessage());
            }
        }

        File::put($filepath, json_encode($data, JSON_PRETTY_PRINT));

        $this->info('');
        $this->info("✅ Database exported successfully to: {$filepath}");
        $this->info("File size: " . round(File::size($filepath) / 1024, 2) . " KB");
        $this->info('');
        $this->info('To import on another device, copy this file and run:');
        $this->info("php artisan db:import --file={$filename}");

        return 0;
    }
}
