<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;

/**
 * @property int|null $team_id
 */
class Activity extends SpatieActivity
{
    protected $fillable = [
        'team_id',
        // ... other fillable attributes
    ];
}
