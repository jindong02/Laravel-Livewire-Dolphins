<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static const PENDING
 * @method static const FOR_APPROVAL
 * @method static const APPROVED
 * @method static const REJECTED
 */
final class ItemRequestDetailStatus extends Enum implements LocalizedEnum
{
    public const FOR_APPROVAL = 'FOR_APPROVAL';
    public const APPROVED = 'APPROVED';
    public const REJECTED = 'REJECTED';
}
