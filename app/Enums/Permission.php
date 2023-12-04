<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static const USERS
 * @method static const ROLES
 * @method static const DEPARTMENT_APPROVAL
 * @method static const BUDGET_APPROVAL
 * @method static const BAC_1_APPROVAL
 * @method static const BAC_2_APPROVAL
 * @method static const PURCHASE_REQUEST
 * @method static const DEPARTMENTS
 * @method static const PLOTMODULE
 */
final class Permission extends Enum implements LocalizedEnum
{
    /**
     * User Management
     */
    public const USERS = 'USERS';

    /**
     * Role Management
     */
    public const ROLES = 'ROLES';

    /**
     * Item Request
     */
    public const ITEM_REQUEST = 'ITEM_REQUEST';

    /**
     * Department Approver
     */
    public const DEPARTMENT_APPROVAL = 'DEPARTMENT_APPROVAL';

    /**
     * Budget Approver
     */
    public const BUDGET_APPROVAL = 'BUDGET_APPROVAL';

    /**
     * Bid & Award Committee (BAC) 1 Approver
     */
    public const BAC_1_APPROVAL = 'BAC_1_APPROVAL';

    /**
     * Bid & Award Committee (BAC) 2 Approver
     */
    public const BAC_2_APPROVAL= 'BAC_2_APPROVAL';

    /**
     * Purchase Request
     */
    public const PURCHASE_REQUEST = 'PURCHASE_REQUEST';

    /**
     * Department
     */
    public const DEPARTMENTS = 'DEPARTMENTS';

     /**
     * PlotModule
     */
    public const PLOTMODULE = 'PLOTMODULE';

    /**
     * Bidders
     */
    public const BIDDERS = 'BIDDERS';
}
