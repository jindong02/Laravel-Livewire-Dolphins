<?php

namespace App\Actions\ItemRequests;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BudgetForApprovalData
{
    /**
     * Get item request for Budget Head
     * Formatted data per department
     *
     * @return array
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function __invoke(): array
    {

        $data = $this->getItemRequestData();

        // Create an empty result array
        $result = [];

        // Iterate through the data and group it
        foreach ($data as $item) {
            $departmentId = $item->department_id;
            $departmentName = $item->department_name;
            $bidType = $item->bid_type;

            // Check if the department already exists in the result array
            if (!isset($result[$departmentId])) {
                $result[$departmentId] = [
                    'department_id' => $departmentId,
                    'department_name' => $departmentName,
                    'count' => 0,
                    'total_cost' => 0,
                    'approved_count' => 0,
                    'approved_total_cost' => 0,
                    'details' => [],
                ];
            }

            // Check if the bid_type already exists in the department's details
            if (!isset($result[$departmentId]['details'][$bidType])) {
                $result[$departmentId]['details'][$bidType] = [
                    'count' => 0,
                    'total_cost' => 0,
                    'approved_count' => 0,
                    'approved_total_cost' => 0,
                ];
            }

            // Update the counts and total costs based on 'items_for_approval_count' and 'items_approved_count'
            $result[$departmentId]['details'][$bidType]['count'] += (int)$item->items_for_approval_count;
            $result[$departmentId]['details'][$bidType]['total_cost'] += (float)$item->items_for_approval_total_cost;
            $result[$departmentId]['details'][$bidType]['approved_count'] += (int)$item->items_approved_count;
            $result[$departmentId]['details'][$bidType]['approved_total_cost'] += (float)$item->items_approved_total_cost;


            $result[$departmentId]['count'] += (int)$item->items_for_approval_count;
            $result[$departmentId]['total_cost'] += (float)$item->items_for_approval_total_cost;
            $result[$departmentId]['approved_count'] += (int)$item->items_approved_count;
            $result[$departmentId]['approved_total_cost'] += (float)$item->items_approved_total_cost;
        }

        // Convert the associative array into an indexed array
        return array_values($result);
    }

    /**
     * Get item request data
     *
     * @return \Illuminate\Support\Collection
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    private function getItemRequestData(): Collection
    {
        $statuses = [ItemRequestStatus::FOR_BAC_1_APPROVAL,ItemRequestStatus::FOR_PR_CREATION,ItemRequestStatus::COMPLETED,];

        // Use array_map to convert constants into their string values
        $statusStrings = implode(',', array_map(function ($status) {
            return "'$status'";
        }, $statuses));

        $forApproval = ItemRequestStatus::FOR_BUDGET_APPROVAL;
        $data = DB::table('departments')
            ->select(
                'departments.id as department_id',
                'departments.name as department_name',
                'item_requests.bid_type'
            )
            ->selectRaw("
                SUM(CASE WHEN item_requests.status = '{$forApproval}' THEN 1 ELSE 0 END) as items_for_approval_count,
                SUM(CASE WHEN item_requests.status = '{$forApproval}' THEN item_request_details.total_cost ELSE 0 END) as items_for_approval_total_cost,
                SUM(CASE WHEN item_requests.status IN ({$statusStrings}) THEN 1 ELSE 0 END) as items_approved_count,
                SUM(CASE WHEN item_requests.status IN ({$statusStrings}) THEN item_request_details.total_cost ELSE 0 END) as items_approved_total_cost
            ")
            ->join('item_requests', function (JoinClause $join) use($statusStrings){
                $join->on('departments.id', '=', 'item_requests.department_id')
                    ->whereIn('item_requests.status', [
                        ItemRequestStatus::FOR_BUDGET_APPROVAL,
                        ItemRequestStatus::FOR_BAC_1_APPROVAL,
                        ItemRequestStatus::FOR_PR_CREATION,
                        ItemRequestStatus::COMPLETED,
                    ]);
                    // ->where('item_requests.bid_type', '=', 'LOT'); // Change to 'LINE' for LINE bid_type
            })
            ->leftJoin('item_request_details', function (JoinClause $join){
                $join->on('item_requests.id', '=', 'item_request_details.item_request_id')
                    ->whereIn('item_request_details.status', [ItemRequestDetailStatus::APPROVED, ItemRequestDetailStatus::FOR_APPROVAL]);
            })
            ->groupBy('departments.id', 'departments.name', 'item_requests.bid_type')
            ->get();

            return $data;
    }
}
