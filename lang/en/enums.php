<?php


use App\Enums\ErrorCodes;
use App\Enums\ItemApprovalDetailStatus;
use App\Enums\ItemApprovalLogStatus;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Enums\Permission;

return [
    ErrorCodes::class => [
        ErrorCodes::INVALID_STATUS => 'Your action is not permitted under the current transaction Status.',
        ErrorCodes::ITEM_NEEDS_VALIDATION => 'There are still items that are not yet validated.',
    ],

    ItemRequestStatus::class => [
        ItemRequestStatus::DRAFT => 'Draft',
        ItemRequestStatus::FOR_DEPARTMENT_APPROVAL => 'For Department Approval',
        ItemRequestStatus::FOR_BUDGET_APPROVAL => 'For Budget Approval',
        ItemRequestStatus::FOR_BAC_1_APPROVAL => 'For BAC 1 Approval',
        ItemRequestStatus::FOR_PR_CREATION => 'For Purchase Request Creation',
        ItemRequestStatus::COMPLETED => 'Completed',
    ],

    ItemRequestDetailStatus::class => [
        ItemRequestDetailStatus::FOR_APPROVAL => 'For Approval',
        ItemRequestDetailStatus::APPROVED => 'Approved',
        ItemRequestDetailStatus::REJECTED => 'Rejected',
    ],

    Permission::class => [
        Permission::USERS => 'Users',
        Permission::ROLES => 'Roles',
        Permission::DEPARTMENT_APPROVAL => 'Department Approval',
        Permission::BUDGET_APPROVAL => 'Budget Approval',
        Permission::BAC_1_APPROVAL => 'Bid and Award Committee (BAC) 1 Approval',
        Permission::BAC_2_APPROVAL => 'Bid and Award Committee (BAC) 2 Approval',
        Permission::PURCHASE_REQUEST => 'Purchase Request',
        Permission::DEPARTMENTS => 'Departments',
        Permission::BIDDERS => 'Bidders',
    ],
];
