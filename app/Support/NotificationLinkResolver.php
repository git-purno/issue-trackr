<?php

namespace App\Support;

use App\Models\ChangeRequest;
use App\Models\Issue;

class NotificationLinkResolver
{
    public static function resolve(array $data): ?string
    {
        $targetType = $data['target_type'] ?? null;
        $targetId = $data['target_id'] ?? null;

        if ($targetType === 'issue' && $targetId && Issue::whereKey($targetId)->exists()) {
            return route('issues.show', $targetId);
        }

        if ($targetType === 'change_request' && $targetId && ChangeRequest::whereKey($targetId)->exists()) {
            return route('change-requests.show', $targetId);
        }

        $url = $data['url'] ?? null;

        if (!$url) {
            return null;
        }

        if (preg_match('#/issues/(\d+)$#', $url, $matches) && Issue::whereKey((int) $matches[1])->exists()) {
            return route('issues.show', (int) $matches[1]);
        }

        if (preg_match('#/change-requests/(\d+)$#', $url, $matches) && ChangeRequest::whereKey((int) $matches[1])->exists()) {
            return route('change-requests.show', (int) $matches[1]);
        }

        return null;
    }
}
