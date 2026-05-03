<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subject_id',
        'created_by',
        'time_limit',
        'is_published',
        'publish_at',
        'due_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'publish_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function isAvailable(): bool
    {
        $now = now();

        // Check if published manually or scheduled publish time has passed
        if (!$this->is_published && (!$this->publish_at || $now->lt($this->publish_at))) {
            return false;
        }

        // Check if due date has passed
        if ($this->due_at && $now->gt($this->due_at)) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->due_at && now()->gt($this->due_at);
    }

    public function isScheduled(): bool
    {
        return !$this->is_published && $this->publish_at && now()->lt($this->publish_at);
    }
}