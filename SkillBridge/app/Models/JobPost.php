<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    protected $table = 'jobs';

    protected $fillable = ['client_id', 'freelancer_id', 'title', 'description', 'budget', 'deadline', 'category', 'skills', 'status'];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'skills' => 'array',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'job_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'job_id');
    }
}
