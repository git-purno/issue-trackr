<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'user_id',
        'assigned_to',
        'attachment'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

public function assignedEngineer()
{
    return $this->belongsTo(User::class, 'assigned_to');
}

}