<?php

namespace App\Exceptions;

use App\Enums\ErrorCodes;
use Exception;

class ItemNeedsValidationException extends Exception
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
                'message' => ErrorCodes::getDescription(ErrorCodes::ITEM_NEEDS_VALIDATION),
                'error_code' => ErrorCodes::ITEM_NEEDS_VALIDATION,
            ],
            422
        );
    }
}
