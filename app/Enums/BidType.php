<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static const LINE
 * @method static const LOT
 */
final class BidType extends Enum
{
    /**
     * Bidding is per Item in Purchase Request
     */
    public const LINE = 'LINE';

    /**
     * Bidding is for the whole Purchase Request
     */
    public const LOT = 'LOT';
}
