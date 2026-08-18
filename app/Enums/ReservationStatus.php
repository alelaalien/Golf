<?php

namespace App\Enums;

enum  ReservationStatus: string
{
    case CONFIRMED  =   "confirmed";
    case CANCELLED  =   "cancelled";
    case COMPLETED  =   "completed";    
    case NO_SHOW    =   "no_show";
}