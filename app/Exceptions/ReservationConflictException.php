<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;
use Override;

class ReservationConflictException extends Exception
{
    #[Override]
    public function __construct(string $message = "The selected time slot overlaps with an existing reservation.", 
                                int $code = Response::HTTP_CONFLICT, 
                                Throwable|null $previous = null)
    {
        return parent::__construct($message, $code, $previous);
    }
}
