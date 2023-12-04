<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlotItem extends Model
{
    use HasFactory;
    public function plotModules(): HasMany
    {
        return $this->hasMany(PlotModule::class, 'item_id', 'id');
    }
}