<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ItemRequestDetail extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use LogsActivity;

    public const MEDIA_COLLECTION = 'item-request-details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_request_id',
        'sku',
        'description',
        'unit_of_measure',
        'quantity',
        'unit_cost',
        'total_cost',
        'status', //Every change of status in item_requests.status, approved items will be reset to for approval
        'rejection_remarks',
        'purchase_request_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the itemRequest that owns the ItemRequestDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class, 'item_request_id', 'id');
    }

    /**
     * Get the item that owns the ItemRequestDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'sku', 'sku');
    }

    /**
     * Attachment relationship
     *
     */
    public function attachment()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', static::MEDIA_COLLECTION);
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
            ->setDescriptionForEvent(fn(string $eventName) => "Item Request Details has been {$eventName}")
            ->useLogName('system')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
