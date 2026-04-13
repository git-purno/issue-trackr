<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = ['user_id', 'bio', 'skills', 'portfolio_links', 'university', 'department', 'city', 'avatar_path'];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'portfolio_links' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
