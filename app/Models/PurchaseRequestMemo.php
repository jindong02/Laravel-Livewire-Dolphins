<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestMemo extends Model
{
    use HasFactory;


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

    /**
     * Get the statusDetail that owns the PurchaseRequestMemo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusDetail()
    {
        return $this->belongsTo(PurchaseRequestStatus::class, 'status', 'code');
    }

    /**
     * Get the purchaseRequest that owns the PurchaseRequestMemo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id', 'id');
    }

    /**
     * Get the createdBy that owns the PurchaseRequestMemo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
