<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static const INVALID_STATUS
 * @method static const ITEM_NEEDS_VALIDATION
 */
final class ErrorCodes extends Enum implements LocalizedEnum
{
    /**
     * When action is not allowed in the current status
     */
    public const INVALID_STATUS = 'INVALID_STATUS';

    /**
     * When there is still item that needs validation
     */
    public const ITEM_NEEDS_VALIDATION = 'ITEM_NEEDS_VALIDATION';
}
