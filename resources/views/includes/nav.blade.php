<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 side-nav" style="height: 100vh; overflow-y: auto">
    <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
        <a href="/" class="mx-auto text-white text-decoration-none">
            <img src="{{ asset('logo.png') }}" class="img-fluid d-none d-sm-inline rounded-start p-4" width="140px"
                alt="">
        </a>
        <a href="/" class="mx-auto text-white text-decoration-none">
            <div class="fs-5 d-none d-sm-inline">{{ config('app.name') }}</div>
        </a>
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
                <a href="{{route('request-items.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-card-heading"></i> <span class="ms-1 d-none d-sm-inline">Request Items</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <div class="fs-6 text-secondary">Request Item Approval</div>
            </li>
            <li class="nav-item">
                <a href="{{route('approvals.department.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-people"></i> <span class="ms-1 d-none d-sm-inline">Department Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('approvals.budget.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-wallet"></i> <span class="ms-1 d-none d-sm-inline">Budget Approval</span>
                </a>
            </li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-title="Bid and Award Committee 1 - Approval">
                <a href="{{route('approvals.bac-1.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-award"></i> <span class="ms-1 d-none d-sm-inline">BAC 1 Aproval</span>
                </a>
            </li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-title="Bid and Award Committee 2 - Approval">
                <a href="{{route('approvals.bac-2.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-award"></i> <span class="ms-1 d-none d-sm-inline">BAC 2 Aproval</span>
                </a>
            </li>
            <li class="nav-item" title="Bid and Award Committee">
                <a href="{{route('purchase-requests.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-bag"></i> <span class="ms-1 d-none d-sm-inline">Purchase Request</span>
                </a>
            </li>

            <li class="nav-item mt-4">
                <div class="fs-6 text-secondary">ModuleGroup</div>
            </li>
            <li class="nav-item">
                <a href="{{route('modulegroup.plotmodule.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-person-gear"></i> <span class="ms-1 d-none d-sm-inline">Plot Module</span>
                </a>
            </li>

            <li class="nav-item mt-4">
                <div class="fs-6 text-secondary">Settings</div>
            </li>
            <li class="nav-item">
                <a href="{{route('settings.bidders.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-person-vcard"></i> <span class="ms-1 d-none d-sm-inline">Bidders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('settings.departments.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-people"></i> <span class="ms-1 d-none d-sm-inline">Departments</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <div class="fs-6 text-secondary">Account Management</div>
            </li>
            <li class="nav-item">
                <a href="{{route('users.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-person-gear"></i> <span class="ms-1 d-none d-sm-inline">Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('roles.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi bi-person-lock"></i> <span class="ms-1 d-none d-sm-inline">Roles</span>
                </a>
            </li>
            
        </ul>
        <div class="row g-1 pb-3">
            <div class="col-12 d-none d-sm-inline">{{ auth()->user()->name }}</div>
            <div class="col-12 d-none d-sm-inline pb-3">{{ auth()->user()->email }}</div>
            <div class="col-12">
                <livewire:auth.logout-form />
            </div>
        </div>
    </div>
</div>