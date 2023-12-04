<?php

namespace App\Http\Controllers\V1;

use App\Actions\ItemRequests\ApproveItemRequest;
use App\Actions\ItemRequests\RejectItemRequest;
use App\Actions\ItemRequests\ValidateItems;
use App\Enums\BidType;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemRequestBudgetValidationPerDeptRequest;
use App\Http\Requests\V1\ItemRequestDepartmentValidationRequest;
use App\Http\Requests\V1\ItemRequestDetailDepartmentValidationRequest;
use App\Http\Resources\V1\ItemRequestLineResource;
use App\Http\Resources\V1\ItemRequestResource;
use App\Models\ItemRequest;
use Illuminate\Http\Request;

class ItemRequestApprovalController extends Controller
{
    /**
     * Department Index
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    public function deptIndex(Request $request)
    {
        if ($request->has('view') && $request->view == BidType::LINE) {
            $itemRequests = ItemRequest::commonFilters($request->all())
                ->approverStatusFilter(ItemRequestStatus::FOR_DEPARTMENT_APPROVAL)
                ->currentUserDepartment()
                ->lineView()
                ->orderBy('created_at', 'DESC')
                ->paginate();

            return ItemRequestLineResource::collection($itemRequests);
        }

        $itemRequests = ItemRequest::commonFilters($request->all())
            ->approverStatusFilter(ItemRequestStatus::FOR_DEPARTMENT_APPROVAL)
            ->currentUserDepartment()
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return ItemRequestResource::collection($itemRequests);
    }

    /**
     * Department Item Request Validation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    public function deptValidation(ItemRequestDepartmentValidationRequest $request)
    {
        $data = $request->validated();
        $data['current_status'] = ItemRequestStatus::FOR_DEPARTMENT_APPROVAL;

        if ($data['validation_status'] == 'REJECTED') {
            (new RejectItemRequest)($data);
        }
        else {
            (new ApproveItemRequest)($data);
        }

        return response()->noContent();
    }

    /**
     * Department Item Request Details Validation
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    public function deptItemValidation(ItemRequestDetailDepartmentValidationRequest $request, string $id)
    {
        $data = $request->validated();
        $itemRequest = ItemRequest::currentUserDepartment()
            ->where('status', ItemRequestStatus::FOR_DEPARTMENT_APPROVAL)
            ->first();

        (new ValidateItems)($itemRequest, $data);

        return response()->noContent();

    }

    /**
     * Budget Index
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231025 - Created
     */
    public function budgetIndex(Request $request)
    {
        if ($request->has('view') && $request->view == BidType::LINE) {
            $itemRequests = ItemRequest::commonFilters($request->all())
                ->approverStatusFilter(ItemRequestStatus::FOR_BUDGET_APPROVAL)
                ->lineView()
                ->orderBy('created_at', 'DESC')
                ->paginate();

            return ItemRequestLineResource::collection($itemRequests);
        }

        $itemRequests = ItemRequest::commonFilters($request->all())
            ->approverStatusFilter(ItemRequestStatus::FOR_BUDGET_APPROVAL)
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return ItemRequestResource::collection($itemRequests);
    }


    /**
     * Department Item Request Validation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    public function budgetPerDepartmentValidation(ItemRequestBudgetValidationPerDeptRequest $request)
    {
        $data = $request->validated();
        /* Sample Data
        [
            'validation_status' => 'APPROVED',
            'remarks' => 'Total Cost is too large for the budget',
            'is_allowed_to_update' => true,
            'item_requests' => [
                [
                    'department_id' => 1,
                    'bid_type' => 'LINE'
                ],
                [
                    'department_id' => 1,
                    'bid_type' => 'LOT'
                ],
            ],
        ]
        */
        $status = ItemRequestStatus::FOR_BUDGET_APPROVAL;
        foreach ($data['item_request'] as $item) {
            // (array) item_requests, (string) current_status, (string) remarks, (bool) is_allowed_to_update
            $param = [];
            $param['item_requests'] = ItemRequest::where($item)->where('status', $status)->pluck('id')->toarray();
            $param['current_status'] = $status;
            $param['remarks'] = $data['remarks'];
            $param['is_allowed_to_update'] = $data['is_allowed_to_update'];

            if ($data['validation_status'] == 'REJECTED') {
                (new RejectItemRequest)($param);
            }
            else {
                (new ApproveItemRequest)($param);
            }
        }

        return response()->noContent();
    }
}
