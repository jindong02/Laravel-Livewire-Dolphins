<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\PurchaseRequestCreateMinuteRequest;
use App\Models\MinuteTemplate;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestMinuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($purchaseRequest)
    {
        $purchaseRequest = PurchaseRequest::where('purchase_request_number', $purchaseRequest)->with(['minutes'])->firstOrFail();

        $minutes = $purchaseRequest->minutes()->with('templates')->orderBy('status', 'DESC')->get();

        $currentMinute = $purchaseRequest->currentMinute;
        $currentMinuteTemplate = $purchaseRequest->currentTemplates;

        return view('pages.purchase-requests.minutes.list', compact('purchaseRequest', 'minutes', 'currentMinute', 'currentMinuteTemplate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequestCreateMinuteRequest $request)
    {
        $data = $request->validated();
        dd($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
}
