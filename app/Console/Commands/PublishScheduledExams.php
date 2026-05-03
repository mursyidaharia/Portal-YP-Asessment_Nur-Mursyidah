<?php

namespace App\Console\Commands;

use App\Models\Exam;
use Illuminate\Console\Command;

class PublishScheduledExams extends Command
{
    protected $signature = 'exams:publish-scheduled';
    protected $description = 'Auto publish scheduled exams when publish_at time has passed';

    public function handle()
    {
        $exams = Exam::where('is_published', false)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->get();

        foreach ($exams as $exam) {
            $exam->update(['is_published' => true]);
            $this->info('Published exam: ' . $exam->title);
        }

        $this->info('Done. Published ' . $exams->count() . ' exam(s).');
    }
}