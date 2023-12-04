<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PurchaseRequest extends Model implements HasMedia
{
    use HasFactory;
    use LogsActivity;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION = 'purchase-request';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_request_number',
        'fund',
        'code_pap',
        'program',
        'object_code',
        'bid_type',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model){
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        self::created(function ($model){
            $form = 'PR-'
                    . now()->format('y-m-d')
                    . str_pad($model->id, 5, 0, STR_PAD_LEFT);
            $model->update([
                'purchase_request_number' => $form,
            ]);
        });
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
            ->setDescriptionForEvent(fn(string $eventName) => "Purchase Request has been {$eventName}")
            ->useLogName('system')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the statusDetails that owns the PurchaseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusDetail()
    {
        return $this->belongsTo(PurchaseRequestStatus::class, 'status', 'code');
    }

    /**
     * Get all of the items for the PurchaseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_id', 'id');
    }

    /**
     * Get all of the minutes for the PurchaseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function minutes()
    {
        return $this->hasMany(PurchaseRequestMinute::class, 'purchase_request_id', 'id');
    }

    /**
     * Get the currentMinute associated with the PurchaseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentMinute()
    {
        return $this->hasOne(PurchaseRequestMinute::class, 'purchase_request_id', 'id')
            ->where('status', $this->status);
    }

    /**
     * Get all of the templates for the PurchaseRequestMinute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentTemplates()
    {
        return $this->hasMany(MinuteTemplate::class, 'status', 'status');
    }

    /**
     * Get the createdBy that owns the PurchaseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Attachment Relationship
     */
    public function attachment()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', static::MEDIA_COLLECTION);
    }

    public function totalCost(): float
    {
        return $this->items->sum('total_cost');
    }

    public function nextStatus($key = 'name')
    {
        $status = $this->statusDetail;
        if ($status) {
            $next = PurchaseRequestStatus::where('order', '>',  $status->order)->orderBy('order', 'ASC')->first();
            if ($next) {
                return $next->{$key};
            }
        }

        return null;
    }
}
