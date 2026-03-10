<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an action against an optional subject model.
     */
    public function log(
        string $action,
        string $description,
        ?Model $subject = null,
        array $metadata = [],
        ?string $organizationId = null
    ): ActivityLog {
        $org = $organizationId ?? app('currentOrganization')?->id;

        return ActivityLog::create([
            'organization_id' => $org,
            'user_id'         => Auth::id(),
            'action'          => $action,
            'subject_type'    => $subject ? get_class($subject) : null,
            'subject_id'      => $subject?->getKey(),
            'description'     => $description,
            'metadata'        => $metadata ?: null,
        ]);
    }
}
