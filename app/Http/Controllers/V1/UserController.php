<?php

namespace App\Http\Controllers\V1;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserCreateRequest;
use App\Http\Requests\V1\UserEditRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231015 - Created
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231015 - Created
     */
    public function store(UserCreateRequest $request)
    {

        $data = $request->validated();

        $data['password'] = Hash::make($request->password);
        $data['name'] = "{$request->first_name} {$request->last_name}";

        $user = User::create($data);
        $user->assignRole(Role::USER);

        $user->refresh();

        return UserResource::make($user);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231015 - Created
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231015 - Created
     */
    public function update(UserEditRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validated();
        $data['name'] = "{$request->first_name} {$request->last_name}";

        $user->update($data);
        $user->refresh();

        return UserResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Reset user password
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231015 - Created
     */
    public function resetPassword(string $id)
    {
        $user = User::findOrFail($id);

        $password = Str::random(8);

        $user->password = Hash::make($password);
        $user->save();

        activity()->performedOn($user)->log('User Reset Password');

        return response()->json(['new_password' => $password]);
    }
}
