<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Gender extends Model
{
    protected $fillable = [
        'name',
    ];
}
