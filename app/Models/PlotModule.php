<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlotModule extends Model
{
    use HasFactory;
    public function item(): HasOne
    {

        return $this->hasOne(PlotItem::class, 'id', 'item_id');

        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}