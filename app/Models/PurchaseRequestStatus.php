<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseRequestStatus extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Activity Logs settings
     *
     * @return \Spatie\Activitylog\LogOptions
     * @see https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "Purchase Request Status has been {$eventName}")
            ->useLogName('system')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeInactive($q)
    {
        return $q->where('is_active', false);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isInactive(): bool
    {
        return !$this->is_active;
    }

    /**
     * Get Next Status
     *
     * @return \App\Models\PurchaseRequestStatus|null
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231018 - Created
     */
    public function nextStatus(): ?PurchaseRequestStatus
    {
        $next = PurchaseRequestStatus::where('order', '>',  $this->order)->orderBy('order', 'ASC')->first();

        return $next;
    }
}
