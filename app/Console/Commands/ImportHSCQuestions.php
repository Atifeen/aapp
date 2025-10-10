<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Board;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ImportHSCQuestions extends Command
{
    protected $signature = 'import:hsc-questions {folders?* : Specific folders to import (optional)} {--dry-run : Show what would be imported without actually importing}';
    protected $description = 'Import HSC questions from subject folders (PHY1, PHY2, HM1, HM2, CHE1, CHE2)';

    // Folder to subject mapping
    private $folderSubjectMapping = [
        'PHY1' => 'Physics I',
        'PHY2' => 'Physics II', 
        'HM1' => 'Higher Math I',
        'HM2' => 'Higher Math II',
        'CHE1' => 'Chemistry I',
        'CHE2' => 'Chemistry II',
    ];

    // Bengali to English board name mapping
    private $boardMapping = [
        'ঢাকা বোর্ড' => 'Dhaka',
        'চট্টগ্রাম বোর্ড' => 'Chittagong', 
        'কুমিল্লা বোর্ড' => 'Cumilla',
        'যশোর বোর্ড' => 'Jashore',
        'বরিশাল বোর্ড' => 'Barishal',
        'সিলেট বোর্ড' => 'Sylhet',
        'রাজশাহী বোর্ড' => 'Rajshahi',
        'দিনাজপুর বোর্ড' => 'Dinajpur',
        'ময়মনসিংহ বোর্ড' => 'Mymensingh',
    ];

    // Answer mapping
    private $answerMapping = [
        'ক' => 'A',
        'খ' => 'B', 
        'গ' => 'C',
        'ঘ' => 'D'
    ];

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificFolders = $this->argument('folders');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No data will be imported');
            $this->newLine();
        }

        $this->info('🚀 Starting HSC Questions Import...');
        $this->newLine();

        // Get available folders
        $basePath = database_path('data');
        $availableFolders = [];
        
        foreach ($this->folderSubjectMapping as $folder => $subject) {
            if (File::isDirectory($basePath . '/' . $folder)) {
                $availableFolders[] = $folder;
            }
        }

        if (empty($availableFolders)) {
            $this->error('❌ No subject folders found in database/data/');
            return 1;
        }

        // Determine which folders to process
        $foldersToProcess = $specificFolders ?: $availableFolders;
        
        // Validate requested folders
        foreach ($foldersToProcess as $folder) {
            if (!in_array($folder, $availableFolders)) {
                $this->error("❌ Folder '{$folder}' not found. Available folders: " . implode(', ', $availableFolders));
                return 1;
            }
        }

        $this->info('📁 Found folders: ' . implode(', ', $availableFolders));
        $this->info('🎯 Processing folders: ' . implode(', ', $foldersToProcess));
        $this->newLine();

        // Get subject mappings
        $subjectMappings = $this->getSubjectMappings($foldersToProcess);
        if (empty($subjectMappings)) {
            return 1;
        }

        // Process each folder
        $totalStats = ['folders' => 0, 'files' => 0, 'questions' => 0];
        
        foreach ($foldersToProcess as $folder) {
            if (!isset($subjectMappings[$folder])) {
                continue;
            }

            $this->info("📚 Processing {$folder} folder ({$subjectMappings[$folder]['name']})...");
            $stats = $this->processFolder($folder, $subjectMappings[$folder]['id'], $isDryRun);
            
            $totalStats['folders']++;
            $totalStats['files'] += $stats['files'];
            $totalStats['questions'] += $stats['questions'];
            
            $this->newLine();
        }

        // Show summary
        $this->info('📊 Import Summary:');
        foreach ($foldersToProcess as $folder) {
            if (isset($subjectMappings[$folder])) {
                $this->info("   {$folder} ({$subjectMappings[$folder]['name']}): processed");
            }
        }
        $this->info("   Total: {$totalStats['folders']} folders, {$totalStats['files']} files, {$totalStats['questions']} questions");
        
        if (!$isDryRun) {
            $this->info('✅ Import completed successfully!');
        } else {
            $this->info('🔍 Dry run completed. Use without --dry-run to actually import.');
        }

        return 0;
    }

    private function getSubjectMappings($folders)
    {
        $mappings = [];
        
        $this->info('🔍 Checking subjects in database...');
        foreach ($folders as $folder) {
            $subjectName = $this->folderSubjectMapping[$folder];
            $subject = Subject::where('name', $subjectName)->where('class', 'HSC')->first();
            
            if (!$subject) {
                $this->error("❌ Subject '{$subjectName}' not found in database for folder '{$folder}'");
                return [];
            }
            
            $mappings[$folder] = [
                'id' => $subject->id,
                'name' => $subject->name
            ];
            $this->info("   ✅ {$folder} → {$subjectName} (ID: {$subject->id})");
        }
        
        return $mappings;
    }

    private function processFolder($folderName, $subjectId, $isDryRun)
    {
        $basePath = database_path("data/{$folderName}");
        $files = File::files($basePath);
        
        $fileCount = 0;
        $questionCount = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $filename = $file->getFilenameWithoutExtension();
            $this->info("   📄 Processing: {$filename}");

            // Parse board name and year from filename
            $parsedData = $this->parseFilename($filename);
            
            if (!$parsedData) {
                $boardNameBengali = trim(str_replace([' ২০২৪', ' ২০২৫', ' 2024', ' 2025'], '', $filename));
                $this->warn("   ⚠️  Could not parse filename: {$filename}");
                $this->warn("      Extracted board: '{$boardNameBengali}'");
                continue;
            }

            // Ensure board exists
            if (!$isDryRun) {
                $board = Board::firstOrCreate(['name' => $parsedData['board']]);
                $this->info("   🏛️  Board: {$parsedData['board']} (ID: {$board->id})");
            } else {
                $this->info("   🏛️  Would create/find board: {$parsedData['board']}");
            }

            // Read and process JSON
            $jsonContent = File::get($file->getPathname());
            $questions = json_decode($jsonContent, true);

            if (!$questions) {
                $this->error("   ❌ Invalid JSON in file: {$filename}");
                continue;
            }

            $questionsInFile = count($questions);
            $this->info("   📝 Questions found: {$questionsInFile}");

            if (!$isDryRun) {
                $imported = $this->importQuestions($questions, $subjectId, $parsedData);
                $this->info("   ✅ Imported: {$imported} questions");
            }

            $fileCount++;
            $questionCount += $questionsInFile;
        }

        return ['files' => $fileCount, 'questions' => $questionCount];
    }

    private function parseFilename($filename)
    {
        // Bengali to English number mapping
        $bengaliNumbers = ['০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4', '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9'];
        
        // Convert Bengali numbers to English
        $englishFilename = strtr($filename, $bengaliNumbers);
        
        // Extract year (last 4 digits from English converted filename)
        if (!preg_match('/(\d{4})$/', $englishFilename, $yearMatches)) {
            return null;
        }
        $year = $yearMatches[1];

        // Get board name by removing the year part (both Bengali and English versions)
        $boardNameBengali = trim(str_replace([' ২০২৪', ' ২০২৫', ' 2024', ' 2025'], '', $filename));
        
        // Map to English board name
        $boardNameEnglish = $this->boardMapping[$boardNameBengali] ?? null;
        
        if (!$boardNameEnglish) {
            return null;
        }

        return [
            'board' => $boardNameEnglish,
            'year' => (int) $year,
            'original_filename' => $filename
        ];
    }

    private function importQuestions($questions, $subjectId, $parsedData)
    {
        $imported = 0;

        DB::beginTransaction();
        try {
            foreach ($questions as $q) {
                $correctOption = $this->answerMapping[$q['answer'] ?? 'ক'] ?? 'A';

                Question::create([
                    'question_text' => $q['question'] ?? '',
                    'option_a' => $q['options'][0] ?? '',
                    'option_b' => $q['options'][1] ?? '',
                    'option_c' => $q['options'][2] ?? '',
                    'option_d' => $q['options'][3] ?? '',
                    'correct_option' => $correctOption,
                    'image' => $q['image'] ?? null,
                    'subject_id' => $subjectId,
                    'chapter_id' => null, // No chapter info in JSON
                    'source_name' => $parsedData['board'], // Just the board name (e.g., "Dhaka")
                    'source_type' => 'Board', // The type is "Board"
                    'year' => $parsedData['year'],
                ]);

                $imported++;
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("   ❌ Error importing questions: " . $e->getMessage());
            return 0;
        }

        return $imported;
    }
}
