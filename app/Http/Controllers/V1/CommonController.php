<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DepartmentResource;
use App\Http\Resources\V1\FundSourceResource;
use App\Http\Resources\V1\ItemResource;
use App\Http\Resources\V1\ModeResource;
use App\Http\Resources\V1\SupplyTypeResource;
use App\Models\Department;
use App\Models\FundSource;
use App\Models\Item;
use App\Models\Mode;
use App\Models\SupplyType;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Item List
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function items()
    {
        $items = Item::active()->orderBy('name')->get();

        return ItemResource::collection($items);
    }

    /**
     * Modes
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function modes()
    {
        $items = Mode::active()->orderBy('name')->get();

        return ModeResource::collection($items);
    }

    /**
     * Source of Fund
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function fundSources()
    {
        $sources = FundSource::active()->orderBy('name')->get();

        return FundSourceResource::collection($sources);
    }

    /**
     * Supply Types
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function supplyTypes()
    {
        $types = SupplyType::active()->orderBy('name')->get();

        return SupplyTypeResource::collection($types);
    }

    /**
     * Departments
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function departments()
    {
        $items = Department::active()->orderBy('name')->get();

        return DepartmentResource::collection($items);
    }
}
