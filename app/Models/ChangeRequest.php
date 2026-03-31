<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
   protected $fillable = [
    'title',
    'description',
    'justification',
    'risk_analysis',
    'affected_systems',
    'impact_level',
    'status',
    'user_id',
    'scheduled_at',
    'rollback_plan',
    'verified',
    'analyst_id',
    'manager_id',
    'admin_id'
];
}
