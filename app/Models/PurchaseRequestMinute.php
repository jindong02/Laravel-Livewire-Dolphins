<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseRequestMinute extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_request_id',
        'status',
        'notes',
        'memo_date',
        'options',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'object',
        'memo_date' => 'date:Y-m-d',
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
            ->setDescriptionForEvent(fn(string $eventName) => "Purchase Request - Minute has been {$eventName}")
            ->useLogName('system')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the statusDetail that owns the PurchaseRequestMinute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusDetail()
    {
        return $this->belongsTo(PurchaseRequestStatus::class, 'status', 'code');
    }

    /**
     * Get the purchaseRequest that owns the PurchaseRequestMinute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id', 'id');
    }

    /**
     * Get the createdBy that owns the PurchaseRequestMinute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get all of the templates for the PurchaseRequestMinute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(MinuteTemplate::class, 'status', 'status');
    }
}
