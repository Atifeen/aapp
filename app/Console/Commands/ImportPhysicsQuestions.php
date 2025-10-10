<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Board;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ImportPhysicsQuestions extends Command
{
    protected $signature = 'import:physics-questions {--dry-run : Show what would be imported without actually importing}';
    protected $description = 'Import physics questions from PHY1 and PHY2 JSON files';

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
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No data will be imported');
            $this->newLine();
        }

        $this->info('🚀 Starting Physics Questions Import...');
        $this->newLine();

        // Get subject IDs
        $physicsI = Subject::where('name', 'Physics I')->where('class', 'HSC')->first();
        $physicsII = Subject::where('name', 'Physics II')->where('class', 'HSC')->first();

        if (!$physicsI || !$physicsII) {
            $this->error('❌ Physics I or Physics II subject not found in database');
            return 1;
        }

        $this->info("📚 Found subjects:");
        $this->info("   - Physics I (ID: {$physicsI->id})");
        $this->info("   - Physics II (ID: {$physicsII->id})");
        $this->newLine();

        // Process PHY1 folder
        $this->info('📁 Processing PHY1 folder (Physics I)...');
        $phy1Stats = $this->processFolder('PHY1', $physicsI->id, $isDryRun);

        // Process PHY2 folder  
        $this->info('📁 Processing PHY2 folder (Physics II)...');
        $phy2Stats = $this->processFolder('PHY2', $physicsII->id, $isDryRun);

        // Show summary
        $this->newLine();
        $this->info('📊 Import Summary:');
        $this->info("   PHY1 (Physics I): {$phy1Stats['files']} files, {$phy1Stats['questions']} questions");
        $this->info("   PHY2 (Physics II): {$phy2Stats['files']} files, {$phy2Stats['questions']} questions");
        $this->info("   Total: " . ($phy1Stats['files'] + $phy2Stats['files']) . " files, " . ($phy1Stats['questions'] + $phy2Stats['questions']) . " questions");
        
        if (!$isDryRun) {
            $this->info('✅ Import completed successfully!');
        } else {
            $this->info('🔍 Dry run completed. Use without --dry-run to actually import.');
        }

        return 0;
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
                // Debug output
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
