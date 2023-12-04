<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static const DRAFT
 * @method static const FOR_DEPARTMENT_APPROVAL
 * @method static const FOR_BUDGET_APPROVAL
 * @method static const FOR_BAC_1_APPROVAL
 * @method static const FOR_PR_CREATION
 * @method static function nextStatus() - Get the next status
 * @method static function allowedApproval() - Status that is allowed for approval/rejection
 * @method static function allowedApproval() - Status that is allowed for approval/rejection
 */
final class ItemRequestStatus extends Enum implements LocalizedEnum
{
    public const DRAFT = 'DRAFT';
    public const FOR_DEPARTMENT_APPROVAL = 'FOR_DEPARTMENT_APPROVAL';
    public const FOR_BUDGET_APPROVAL = 'FOR_BUDGET_APPROVAL';
    public const FOR_BAC_1_APPROVAL = 'FOR_BAC_1_APPROVAL';
    public const FOR_PR_CREATION = 'FOR_PR_CREATION';
    public const COMPLETED = 'COMPLETED';
    public const REJECTED = 'REJECTED';

    /**
     * Get the next status
     *
     * @param string $current
     * @return string
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231017 - Created
     */
    public static function nextStatus ($current): string
    {

        switch ($current) {
            case static::DRAFT:
                $nextStatus = static::FOR_DEPARTMENT_APPROVAL;
                break;
            case static::FOR_DEPARTMENT_APPROVAL:
                $nextStatus = static::FOR_BUDGET_APPROVAL;
                break;
            case static::FOR_BUDGET_APPROVAL:
                $nextStatus = static::FOR_BAC_1_APPROVAL;
                break;
            case static::FOR_BAC_1_APPROVAL:
                $nextStatus = static::FOR_PR_CREATION;
                break;
            case static::FOR_PR_CREATION:
                $nextStatus = static::COMPLETED;
                break;

            default:
                $nextStatus = static::DRAFT;
                break;
        }

        return $nextStatus;
    }

    /**
     * Status that is allowed for updating
     *
     * @return array
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231019 - Created
     */
    public static function allowedUpdate(): array
    {
        return [
            static::DRAFT,
            static::FOR_DEPARTMENT_APPROVAL,
            static::FOR_BUDGET_APPROVAL,
            static::FOR_BAC_1_APPROVAL,
            static::FOR_PR_CREATION,
        ];
    }

    /**
     * Check if the status is allowed to update
     *
     * @param string $current
     * @return bool
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231022 - Created
     */
    public static function isAllowedUpdate(string $current): bool
    {
        $statuses = (array) static::allowedUpdate();
        return in_array($current, $statuses);
    }

    /**
     * Status that is allowed for approval/rejection
     *
     * @return array
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231019 - Created
     */
    public static function allowedApproval(): array
    {
        return [
            static::FOR_BUDGET_APPROVAL,
            static::FOR_BAC_1_APPROVAL,
        ];
    }

    /**
     * Check if the status is allowed to approve/reject
     *
     * @param string $current
     * @return bool
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231022 - Created
     */
    public static function isAllowedForApproval(string $current): bool
    {
        $statuses = (array) static::allowedApproval();
        return in_array($current, $statuses);
    }
}
