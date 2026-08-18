<?php

namespace App\Enums;

enum PlayerStatus: string
{
    case ACTIVE = "active";
    case INACTIVE = "inactive";
    case SUSPENDED = "suspended";
}