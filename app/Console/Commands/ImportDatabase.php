<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportDatabase extends Command
{
    protected $signature = 'db:import {--file=database_backup.json} {--fresh : Drop all tables before import}';
    protected $description = 'Import database tables from a JSON file';

    public function handle()
    {
        $filename = $this->option('file');
        $filepath = storage_path('app/' . $filename);

        if (!File::exists($filepath)) {
            $this->error("File not found: {$filepath}");
            $this->info("Please make sure the backup file exists in storage/app/");
            return 1;
        }

        $this->info('Starting database import...');
        $this->info("Reading file: {$filepath}");

        $data = json_decode(File::get($filepath), true);

        if ($data === null) {
            $this->error('Failed to parse JSON file!');
            return 1;
        }

        // Ask for confirmation
        if (!$this->confirm('This will import data into your database. Continue?', true)) {
            $this->info('Import cancelled.');
            return 0;
        }

        // If fresh option is set, truncate tables
        if ($this->option('fresh')) {
            $this->warn('Clearing existing data...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            foreach (array_keys($data) as $table) {
                try {
                    DB::table($table)->truncate();
                    $this->info("✓ Cleared table: {$table}");
                } catch (\Exception $e) {
                    $this->warn("✗ Failed to clear {$table}: " . $e->getMessage());
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Import data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($data as $table => $records) {
            $this->info("Importing table: {$table}");
            
            try {
                if (empty($records)) {
                    $this->warn("⊘ {$table} is empty, skipping...");
                    continue;
                }

                // Insert in chunks to avoid memory issues
                $chunks = array_chunk($records, 500);
                $totalImported = 0;

                foreach ($chunks as $chunk) {
                    DB::table($table)->insert($chunk);
                    $totalImported += count($chunk);
                }

                $this->info("✓ Imported {$table}: {$totalImported} records");
            } catch (\Exception $e) {
                $this->error("✗ Failed to import {$table}: " . $e->getMessage());
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('');
        $this->info('✅ Database import completed successfully!');
        $this->info('');

        return 0;
    }
}
