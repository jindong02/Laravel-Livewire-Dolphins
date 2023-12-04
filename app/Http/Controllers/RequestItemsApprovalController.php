<?php

namespace App\Http\Controllers;

use App\Actions\ItemRequests\ApproveItemRequest;
use App\Actions\ItemRequests\BudgetForApprovalData;
use App\Actions\ItemRequests\RejectItemRequest;
use App\Actions\PurchaseRequest\CreateFromItemRequests;
use App\Enums\BidType;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Http\Requests\V1\ItemRequestBac1ValidationRequest;
use App\Http\Requests\V1\ItemRequestBudgetValidationPerDeptRequest;
use App\Http\Requests\V1\ItemRequestBudgetValidationRequest;
use App\Http\Requests\V1\ItemRequestCreatePurchaseRequestFromListRequest;
use App\Http\Requests\V1\ItemRequestDepartmentValidationRequest;
use App\Models\Department;
use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestStatus;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RequestItemsApprovalController extends Controller
{
    /**
     * Department Index
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function deptIndex(Request $request)
    {
        $itemRequests = ItemRequest::commonFilters($request->all())
            ->approverStatusFilter(ItemRequestStatus::FOR_DEPARTMENT_APPROVAL)
            ->currentUserDepartment()
            ->when(request()->get('view') === BidType::LINE, function($query){
                $query->lineView()
                    ->where('item_request_details.status', '<>', ItemRequestDetailStatus::REJECTED);
            })
            ->when(request()->get('view') === BidType::LOT || !request()->has('view'), function($query){
                $query->where('bid_type', BidType::LOT);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('pages.approvals.department.list', compact('itemRequests'));
    }

    /**
     * Department Item Request Validation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function deptValidate(ItemRequestDepartmentValidationRequest $request)
    {
        $data = $request->validated();
        $data['current_status'] = ItemRequestStatus::FOR_DEPARTMENT_APPROVAL;

        if ($data['validation_status'] == 'REJECTED') {
            (new RejectItemRequest)($data);
        }
        else {
            (new ApproveItemRequest)($data);
        }

        session()->flash('success', 'Item successfully ' . strtolower($data['validation_status']));

        $view = $request->has('view') ? $request->view : null;

        return response()->redirectTo(route('approvals.department.index', ['view' => $view]));
    }

    /**
     * View Department Item Request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function deptItemRequest($id)
    {
        $itemRequest = ItemRequest::whereId($id)->with(['items.item'])->firstOrFail();

        return view('pages.approvals.department.form', compact('itemRequest'));
    }


    /**
     * Budget Head Index
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231026 - Created
     */
    public function budgetIndex(Request $request)
    {
        $itemRequests = (new BudgetForApprovalData)();

        return view('pages.approvals.budget.list', compact('itemRequests'));
    }


    /**
     * Budget Item Request Validation per Department
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function budgetValidatePerDepartment(ItemRequestBudgetValidationPerDeptRequest $request)
    {
        $data = $request->validated();

        $data['current_status'] = ItemRequestStatus::FOR_BUDGET_APPROVAL;
        foreach ($data['item_requests'] as $deptId => $item) {
            $itemRequestIds = ItemRequest::where('department_id', $deptId)->whereIn('bid_type', $item['bid_type'])->pluck('id')->toArray();

            $param = $data;
            $param['item_requests'] = $itemRequestIds;

            if ($data['validation_status'] == 'REJECTED') {
                (new RejectItemRequest)($param);
            }
            else {
                (new ApproveItemRequest)($param);
            }
        }
        session()->flash('success', 'Item successfully ' . strtolower($data['validation_status']));

        return response()->redirectToRoute('approvals.budget.index');
    }

    /**
     * View Item Reqeust per Department per Bid Type
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function budgetViewPerDeptType($deptId, $view)
    {
        $department = Department::findOrFail($deptId);

        $itemRequests = ItemRequest::approverStatusFilter(ItemRequestStatus::FOR_BUDGET_APPROVAL)
            ->where('department_id', $department->id)
            ->when($view === BidType::LINE, function($query){
                $query->lineView()
                    ->where('item_request_details.status', '<>', ItemRequestDetailStatus::REJECTED);
            })
            ->when($view === BidType::LOT, function($query){
                $query->where('bid_type', BidType::LOT);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('pages.approvals.budget.department-list', compact('itemRequests', 'view', 'department'));
    }

    /**
     * View Budget Item Request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function budgetItemRequest($id)
    {
        $itemRequest = ItemRequest::whereId($id)->with(['items.item'])->firstOrFail();

        return view('pages.approvals.budget.form', compact('itemRequest'));

    }

    /**
     * Budget Item Request Validation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function budgetValidate(ItemRequestBudgetValidationRequest $request)
    {
        $data = $request->validated();
        $data['current_status'] = ItemRequestStatus::FOR_BUDGET_APPROVAL;

        if ($data['validation_status'] == 'REJECTED') {
            (new RejectItemRequest)($data);
        }
        else {
            (new ApproveItemRequest)($data);
        }

        $itemRequest = ItemRequest::whereIn('id', $data['item_requests'])->first();
        $view = $request->has('view') ? $request->view : 'LOT';
        session()->flash('success', 'Item successfully ' . strtolower($data['validation_status']));

        return response()->redirectToRoute('approvals.budget.department.index', ['department_id' => $itemRequest->department_id, 'view' => $view]);
    }


    /**
     * BAC-1 Index
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function bac1Index(Request $request)
    {
        $itemRequests = ItemRequest::commonFilters($request->all())
            ->approverStatusFilter(ItemRequestStatus::FOR_BAC_1_APPROVAL)
            ->currentUserDepartment()
            ->when(request()->get('view') === BidType::LINE, function($query){
                $query->lineView()
                    ->where('item_request_details.status', '<>', ItemRequestDetailStatus::REJECTED);
            })
            ->when(request()->get('view') === BidType::LOT || !request()->has('view'), function($query){
                $query->where('bid_type', BidType::LOT);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('pages.approvals.bac_1.list', compact('itemRequests'));
    }

    /**
     * BAC-1 Item Request Validation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function bac1Validate(ItemRequestBac1ValidationRequest $request)
    {
        $data = $request->validated();
        $data['current_status'] = ItemRequestStatus::FOR_BAC_1_APPROVAL;

        if ($data['validation_status'] == 'REJECTED') {
            (new RejectItemRequest)($data);
        }
        else {
            (new ApproveItemRequest)($data);
        }

        session()->flash('success', 'Item successfully ' . strtolower($data['validation_status']));

        $view = $request->has('view') ? $request->view : null;

        return response()->redirectTo(route('approvals.bac-1.index', ['view' => $view]));
    }


    /**
     * View BAC 1 Item Request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function bac1ItemRequest($id)
    {
        $itemRequest = ItemRequest::whereId($id)->with(['items.item'])->firstOrFail();

        return view('pages.approvals.bac_1.form', compact('itemRequest'));
    }


    /**
     * BAC-2 Index
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231031 - Created
     */
    public function bac2Index(Request $request)
    {
        $itemRequests = ItemRequest::commonFilters($request->all())
            ->approverStatusFilter(ItemRequestStatus::FOR_PR_CREATION)
            ->currentUserDepartment()
            ->when(request()->get('view') === BidType::LINE, function($query){
                $query->lineView()
                    ->where('item_request_details.status', '<>', ItemRequestDetailStatus::REJECTED);
            })
            ->when(request()->get('view') === BidType::LOT || !request()->has('view'), function($query){
                $query->where('bid_type', BidType::LOT);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('pages.approvals.bac_2.list', compact('itemRequests'));
    }

    /**
     * BAC-2 Create Purchase Request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231101 - Created
     */
    public function bac2CreatePrFromList(ItemRequestCreatePurchaseRequestFromListRequest $request)
    {
        $data = $request->validated();

        $purchaseRequest = (new CreateFromItemRequests)($data);

        $purchaseRequest->refresh();

        session()->flash('success', 'Purchase Request successfully created');

        return response()->redirectTo(route('approvals.bac-2.index', ['view' => $purchaseRequest->bid_type]));
    }

    /**
     * View BAC 2 Item Request
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231027 - Created
     */
    public function bac2ItemRequest($id)
    {
        $itemRequest = ItemRequest::whereId($id)->with(['items.item'])->firstOrFail();

        return view('pages.approvals.bac_2.form', compact('itemRequest'));
    }


}
