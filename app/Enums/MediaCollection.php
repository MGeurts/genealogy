<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaCollection: string
{
    case PHOTO    = 'photo';
    case DOCUMENT = 'document';
}
