<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(?int $userId, ?Model $subject, string $event, string $description, array $properties = []): void
    {
        ActivityLog::create([
            'user_id' => $userId,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'event' => $event,
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
