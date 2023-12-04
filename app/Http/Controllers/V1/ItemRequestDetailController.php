<?php

namespace App\Http\Controllers\V1;

use App\Actions\ItemRequests\SetupItemData;
use App\Exceptions\InvalidTransactionStatusException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemRequestDetailCreateRequest;
use App\Http\Requests\V1\ItemRequestDetailUpdateRequest;
use App\Http\Resources\V1\ItemRequestDetailResource;
use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use Illuminate\Http\Request;

class ItemRequestDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $itemReqeust
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function index(string $itemRequest)
    {
        $itemRequest = ItemRequest::findOrFail($itemRequest);

        $items = $itemRequest->items;

        return ItemRequestDetailResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $itemReqeust
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function store(ItemRequestDetailCreateRequest $request, string $itemRequest)
    {
        $itemRequest = ItemRequest::findOrFail($itemRequest);
        throw_if($itemRequest->isSubmitted(), InvalidTransactionStatusException::class);

        $data = $request->validated();
        $data = (new SetupItemData)($data);

        $item = $itemRequest->items()->create($data);
        if ($request->hasFile('attachment')) {
            $item->addMedia($request->file('attachment'))
                ->toMediaCollection(ItemRequestDetail::MEDIA_COLLECTION);

            activity('manual')
                ->performedOn($item)
                ->causedBy(auth()->user())
                ->event('updated')
                ->log('Item Request Details Uploaded attachment');
        }
        $item->refresh();

        return ItemRequestDetailResource::make($item);
    }

    /**
     * Display the specified resource.
     *
     * @param string $itemReqeust
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function show(string $itemRequest, string $id)
    {
        $itemRequest = ItemRequest::findOrFail($itemRequest);

        $item = $itemRequest->items()->findOrFail($id);

        return ItemRequestDetailResource::make($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $itemReqeust
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function update(ItemRequestDetailUpdateRequest $request, string $itemRequest, string $id)
    {
        $itemRequest = ItemRequest::findOrFail($itemRequest);
        throw_if($itemRequest->isSubmitted(), InvalidTransactionStatusException::class);

        $data = $request->validated();
        $item = $itemRequest->items()->findOrFail($id);

        $data['sku'] = $item->sku;
        $data = (new SetupItemData)($data);

        $item->update($data);

        if ($request->hasFile('attachment')) {
            $item->addMedia($request->file('attachment'))
                ->toMediaCollection(ItemRequestDetail::MEDIA_COLLECTION);

            activity('manual')
                ->performedOn($item)
                ->causedBy(auth()->user())
                ->event('updated')
                ->log('Item Request Details Uploaded attachment');
        }
        $item->refresh();

        return ItemRequestDetailResource::make($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $itemReqeust
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function destroy(string $itemRequest, string $id)
    {
        $itemRequest = ItemRequest::findOrFail($itemRequest);
        throw_if($itemRequest->isSubmitted(), InvalidTransactionStatusException::class);

        $items =  $itemRequest->items()->count();
        abort_if($items == 1, 422, 'There will be no item left in the Item Request.');

        $item = $itemRequest->items()->findOrFail($id);
        $item->delete();

        return response()->noContent();
    }
}
