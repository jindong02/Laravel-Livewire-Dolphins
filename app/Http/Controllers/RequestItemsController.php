<?php

namespace App\Http\Controllers;

use App\Enums\BidType;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestItemsController extends Controller
{
    public function __construct()
    {
        // Auth::login(User::find(1));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemRequests = ItemRequest::commonFilters(request()->all())
            ->currentUserDepartment()
            ->when(request()->get('view') === BidType::LINE, function($query){
                $query->lineView();
            })
            ->when(request()->get('view') === BidType::LOT || !request()->has('view'), function($query){
                $query->where('bid_type', BidType::LOT);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate();

        return view('pages.request-items.list', compact('itemRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.request-items.form');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $itemRequest = ItemRequest::whereId($id)->with(['items.item'])->firstOrFail();

        return view('pages.request-items.form', compact('itemRequest'));
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
