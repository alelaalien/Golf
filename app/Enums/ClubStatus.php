<?php

namespace App\Enums;

enum ClubStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING_PAYMENT = 'pending_payment';
}