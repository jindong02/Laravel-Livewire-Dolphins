<?php

namespace App\Models;

use App\Enums\BidType;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemRequest extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mode_id',
        'fund_source_id',
        'supply_type_id',
        'method',
        'status',
        'bid_type',
        'is_allowed_to_update',
        'rejection_remarks',
        'department_id',
        'created_by',
        'submitted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_rejected' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get all of the items for the ItemRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ItemRequestDetail::class, 'item_request_id', 'id');
    }

    /**
     * Get the mode that owns the ItemRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mode()
    {
        return $this->belongsTo(Mode::class, 'mode_id', 'id');
    }

    /**
     * Get the fundSource that owns the ItemRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fundSource()
    {
        return $this->belongsTo(FundSource::class, 'fund_source_id', 'id');
    }

    /**
     * Get the supplyType that owns the ItemRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplyType()
    {
        return $this->belongsTo(SupplyType::class, 'supply_type_id', 'id');
    }

    /**
     * Get the department that owns the ItemRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get the createdBy that owns the ItemRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Activity Logs settings
     *
     * @return \Spatie\Activitylog\LogOptions
     * @see https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Item Request has been {$eventName}")
            ->useLogName('system')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Check if submitted
     *
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->status != ItemRequestStatus::DRAFT;
    }

    /**
     * Check if draft
     *
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status == ItemRequestStatus::DRAFT;
    }

    /**
     * Get items total cost
     *
     * @return float
     */
    public function totalCost(): float
    {
        return $this->items->sum('total_cost');
    }

    /**
     * Is Allowed to Update
     *
     * @return bool
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231023 - Created
     */
    public function isAllowedToUpdate(): bool
    {
        $isStatusAllowed = ItemRequestStatus::isAllowedUpdate($this->status);

        return $isStatusAllowed && $this->is_allowed_to_update;
    }

    /**
     * Get items status description
     *
     * @return string
     */
    public function statusDescription(): string
    {
        return ItemRequestStatus::getDescription($this->status) ?? $this->status;
    }

    /**
     * Get rejected item count
     *
     * @param string $status
     * @return int
     */
    public function itemStatusCount(string $status): int
    {
        return $this->items()->whereStatus($status)->count();
    }


    /**
     * Get submitted status
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     */
    public function scopeSubmitted($q)
    {
        return $q->where('item_requests.status', '<>', ItemRequestStatus::DRAFT);
    }

    /**
     * Get draft status
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     */
    public function scopeDraft($q)
    {
        return $q->where('item_requests.status', ItemRequestStatus::DRAFT);
    }

    /**
     * Get current user's department
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     */
    public function scopeCurrentUserDepartment(Builder $q)
    {
        $user = auth()->user();
        return $q->where('item_requests.department_id', $user->department_id);
    }

    /**
     * Common filters
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param array $filters
     */
    public function scopeCommonFilters(Builder $q, array $filters)
    {
        $status = $filters['status'] ?? null;
        $bidType = $filters['bid_type'] ?? null;

        $q->when($status, function ($q) use ($status) {
            $q->where('item_requests.status', $status);
        })->when($bidType, function ($q) use ($bidType) {
            $q->where('item_requests.bid_type', $bidType);
        });
    }

    /**
     * Line display scope
     * SELECT is included in the query
     * Selected info are sku, name, quantity, average unit cost, total cost
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231023 - Created
     */
    public function scopeLineView(Builder $q)
    {
        $q->join('item_request_details', 'item_request_details.item_request_id', '=', 'item_requests.id')
            ->join('items', 'items.sku', '=', 'item_request_details.sku')
            ->where('bid_type', BidType::LINE)
            ->select([
                DB::raw('item_request_details.id as id'),
                DB::raw('item_request_details.item_request_id as item_request_id'),
                'items.sku',
                'items.name',
                'item_requests.status',
                'item_requests.is_allowed_to_update',
                DB::raw('item_request_details.purchase_request_id as purchase_request_id'),
                DB::raw('item_request_details.quantity'),
                DB::raw('item_request_details.unit_cost'),
                DB::raw('item_request_details.total_cost'),
                DB::raw('item_request_details.created_at'),
            ])
        ;
    }

    /**
     * Approver status filter
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param string $status
     */
    public function scopeApproverStatusFilter($q, string $status)
    {
        $includedStatus = [];
        switch ($status) {
            case ItemRequestStatus::FOR_DEPARTMENT_APPROVAL:
                $includedStatus = [
                    ItemRequestStatus::FOR_DEPARTMENT_APPROVAL,
                    // ItemRequestStatus::FOR_BUDGET_APPROVAL,
                    // ItemRequestStatus::FOR_BAC_1_APPROVAL,
                    // ItemRequestStatus::FOR_PR_CREATION,
                    // ItemRequestStatus::COMPLETED,
                ];
                break;
            case ItemRequestStatus::FOR_BUDGET_APPROVAL:
                $includedStatus = [
                    ItemRequestStatus::FOR_BUDGET_APPROVAL,
                    // ItemRequestStatus::FOR_BAC_1_APPROVAL,
                    // ItemRequestStatus::FOR_PR_CREATION,
                    // ItemRequestStatus::COMPLETED,
                ];
                break;
            case ItemRequestStatus::FOR_BAC_1_APPROVAL:
                $includedStatus = [
                    ItemRequestStatus::FOR_BAC_1_APPROVAL,
                    // ItemRequestStatus::FOR_PR_CREATION,
                    // ItemRequestStatus::COMPLETED,
                ];

                break;
            case ItemRequestStatus::FOR_PR_CREATION:
                $includedStatus = [
                    ItemRequestStatus::FOR_PR_CREATION,
                    // ItemRequestStatus::COMPLETED,
                ];
                break;
        }

        $q->whereIn('item_requests.status', $includedStatus);
    }

}
