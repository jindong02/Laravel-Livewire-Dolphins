<?php

namespace App\Http\Controllers\V1;

use App\Actions\ItemRequests\CreateItemRequest;
use App\Actions\ItemRequests\SubmitRequest;
use App\Enums\BidType;
use App\Exceptions\InvalidTransactionStatusException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemRequestCreateRequest;
use App\Http\Requests\V1\ItemRequestUpdateRequest;
use App\Http\Resources\V1\ItemRequestLineResource;
use App\Http\Resources\V1\ItemRequestResource;
use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Http\Request;

class ItemRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function index(Request $request)
    {

        if ($request->has('view') && $request->view == BidType::LINE) {
            $itemRequests = ItemRequest::commonFilters($request->all())
                ->currentUserDepartment()
                ->lineView()
                ->orderBy('created_at', 'DESC')
                ->paginate();

            return ItemRequestLineResource::collection($itemRequests);
        }
        $itemRequests = ItemRequest::commonFilters($request->all())
            ->currentUserDepartment()
            ->orderBy('created_at', 'DESC')
            ->paginate();


        return ItemRequestResource::collection($itemRequests);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function store(ItemRequestCreateRequest $request)
    {
        $data = $request->validated();

        (new CreateItemRequest)($data);

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function show(string $id)
    {
        $itemRequest = ItemRequest::findOrFail($id);

        return ItemRequestResource::make($itemRequest->load('items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function update(ItemRequestUpdateRequest $request, string $id)
    {
        $itemRequest = ItemRequest::findOrFail($id);
        throw_if($itemRequest->isSubmitted(), InvalidTransactionStatusException::class);

        $data = $request->validated();

        $itemRequest->update($data);
        $itemRequest->refresh();

        return ItemRequestResource::make($itemRequest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function destroy(string $id)
    {
        $itemRequest = ItemRequest::findOrFail($id);
        throw_if($itemRequest->isSubmitted(), InvalidTransactionStatusException::class);

        $user = request()->user();
        abort_if($itemRequest->created_by != $user->id, 422, "You are not allowed to delete the record you didn't created");

        $itemRequest->items()->delete();
        $itemRequest->delete();

        return response()->noContent();
    }

    /**
     * Submit Item Request for Approval
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function submit(string $id)
    {
        $itemRequest = ItemRequest::findOrFail($id);
        throw_if($itemRequest->isSubmitted(), InvalidTransactionStatusException::class);

        $user = request()->user();
        abort_if($itemRequest->created_by != $user->id, 422, "You are not allowed to submit the record you didn't created");

        (new SubmitRequest)($itemRequest);

        return response()->noContent();
    }


}
