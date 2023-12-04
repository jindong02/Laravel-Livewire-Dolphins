<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PurchaseRequestCreateMemoRequest;
use App\Http\Resources\V1\PurchaseRequestResource;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseRequests = PurchaseRequest::orderBy('created_at')->paginate();

        return PurchaseRequestResource::collection($purchaseRequests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);

        return PurchaseRequestResource::make($purchaseRequest->load(['items', 'minutes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Add Memo
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231018 - Created
     */
    public function addMemo(PurchaseRequestCreateMemoRequest $request)
    {
        $data = $request->validated();

        $purchaseRequest = PurchaseRequest::findOrFail($data['purchase_request_id']);

        $memo = $purchaseRequest->minutes()->where('status', $purchaseRequest->status)->first();

        if ($memo) {
            $memo->update($data);
        }
        else {
            $data['created_by'] = auth()->user()->id;
            $memo = $purchaseRequest->minutes()->create($data);
        }

        $memo->refresh();

        return PurchaseRequestResource::make($memo);
    }
}
