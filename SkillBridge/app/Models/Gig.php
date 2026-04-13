<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gig extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'price', 'category', 'tags', 'status'];

    protected function casts(): array
    {
        return ['tags' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
