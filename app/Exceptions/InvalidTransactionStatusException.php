<?php

namespace App\Exceptions;

use App\Enums\ErrorCodes;
use Exception;

class InvalidTransactionStatusException extends Exception
{
    /**
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function render()
    {
        return response()->json(
            [
                'message' => ErrorCodes::getDescription(ErrorCodes::INVALID_STATUS),
                'error_code' => ErrorCodes::INVALID_STATUS,
            ],
            422
        );
    }
}
