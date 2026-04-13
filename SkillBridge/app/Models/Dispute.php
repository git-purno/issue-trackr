<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    protected $fillable = ['job_id', 'raised_by', 'assigned_admin_id', 'reason', 'status', 'resolution'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }
}
