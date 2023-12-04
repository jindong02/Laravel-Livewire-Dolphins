<?php

namespace App\Console\Commands;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Permission as ModelsPermission;
use App\Models\Role as RoleModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AclSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:acl:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync roles and permissions to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->syncRoles();
            $this->syncPermissions();
            $this->syncRolesDefaultPermissions();
        });
    }

    /**
     * Syncing roles to database
     *
     * @return void
     */
    private function syncRoles()
    {
        $this->line("Syncing roles!");

        $roles = Role::getValues();

        foreach ($roles as $role) {
            RoleModel::firstOrCreate(['name' => $role]);
        }

        $this->info("Roles successfully synced!");
    }

    /**
     * Syncing permissions to database
     *
     * @return void
     */
    private function syncPermissions()
    {
        $this->line("Syncing permissions!");

        $permissions = Permission::getValues();

        $guards = RoleModel::select('guard_name')->distinct('guard_name')->get();
        foreach ($guards as $guard) {
            foreach ($permissions as $permission) {
                ModelsPermission::firstOrCreate(['name' => $permission, 'guard_name' => $guard->guard_name]);
            }
        }

        $this->info("Permissions successfully synced!");
    }

    /**
     * Sync all role default permissions
     *
     * @return void
     */
    private function syncRolesDefaultPermissions()
    {
        $this->line("Syncing role default permissions!");

        $roles = RoleModel::all();
        foreach ($roles as $role) {
            $this->syncRolePermissions($role, ['ALL']);
        }

        $this->info("Default role permissions successfully synced!");
    }

    /**
     * Sync permissions on given role
     *
     * @param RoleModel $role
     * @param array $permissions
     * @return void
     */
    private function syncRolePermissions(RoleModel $role, array $permissions)
    {
        if (in_array('ALL', $permissions)) {
            $allPermissions = Permission::getValues();
            $role->givePermissionTo(...$allPermissions);
        } else {
            $role->givePermissionTo(...$permissions);
        }
    }
}
