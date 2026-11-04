@extends('admin.master')
@section('title', 'Assign permissions')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}"><i class="fa-solid fa-users-gear" aria-hidden="true"></i><span>Roles</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-key" aria-hidden="true"></i><span>Assign permissions</span></span></li>
        </ol>
    </nav>
    <div class="mb-4">
        <a href="{{ route('admin.role.index') }}" class="text-decoration-none">&larr; Back to roles</a>
        <h1 class="h3 mt-3 mb-1">Permissions for {{ $role->name }}</h1>
        <p class="text-secondary mb-0">Choose the permissions this role can use.</p>
    </div>

    <form action="{{ route('admin.role.assign.permission.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $role->id }}">

        @error('permission')
            <div class="alert alert-danger" role="alert">{{ $message }}</div>
        @enderror
        @foreach ($errors->get('permission.*') as $messages)
            @foreach ($messages as $message)
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @endforeach
        @endforeach

        @forelse ($permissionsGrouped as $group => $permissions)
            <section class="admin-card p-3 p-md-4 mb-3" aria-labelledby="permission-group-{{ $loop->index }}">
                <h2 id="permission-group-{{ $loop->index }}" class="h5 mb-3">{{ $group }}</h2>
                <div class="row g-2">
                    @foreach ($permissions as $permission)
                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="permission[]"
                                    value="{{ $permission->id }}"
                                    @checked(in_array($permission->id, (array) old('permission', $selectedPermissionIds)))
                                >
                                <span class="form-check-label">{{ $permission->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="admin-card p-3 p-md-4 mb-3">
                <p class="text-secondary mb-0">No permissions are available to assign.</p>
            </div>
        @endforelse

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save permissions</button>
            <a href="{{ route('admin.role.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection
