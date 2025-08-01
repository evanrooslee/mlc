<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing packets with codes based on subject and grade
        $packets = DB::table('packets')->whereNull('code')->orWhere('code', '')->get();
        
        foreach ($packets as $packet) {
            $subjectCode = $this->getSubjectCode($packet->subject);
            $code = $subjectCode . $packet->grade;
            
            // Handle duplicate codes by adding a suffix
            $originalCode = $code;
            $counter = 1;
            while (DB::table('packets')->where('code', $code)->where('id', '!=', $packet->id)->exists()) {
                $code = $originalCode . '_' . $counter;
                $counter++;
            }
            
            DB::table('packets')->where('id', $packet->id)->update(['code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set codes back to null for rollback
        DB::table('packets')->update(['code' => null]);
    }
    
    /**
     * Get subject code based on subject name
     */
    private function getSubjectCode($subject): string
    {
        $subjectMappings = [
            'matematika' => 'MTK',
            'math' => 'MTK',
            'fisika' => 'FIS',
            'physics' => 'FIS',
            'kimia' => 'KIM',
            'chemistry' => 'KIM',
            'biologi' => 'BIO',
            'biology' => 'BIO',
            'bahasa indonesia' => 'BIN',
            'indonesian' => 'BIN',
            'bahasa inggris' => 'BIG',
            'english' => 'BIG',
            'sejarah' => 'SEJ',
            'history' => 'SEJ',
            'geografi' => 'GEO',
            'geography' => 'GEO',
            'ekonomi' => 'EKO',
            'economics' => 'EKO',
            'sosiologi' => 'SOS',
            'sociology' => 'SOS',
        ];
        
        $subjectLower = strtolower($subject);
        return $subjectMappings[$subjectLower] ?? strtoupper(substr($subject, 0, 3));
    }
};
