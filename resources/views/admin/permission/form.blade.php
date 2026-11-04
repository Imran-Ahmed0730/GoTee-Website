@extends('admin.master')
@section('title', __( isset($item) ? 'Edit ' : 'Create' ) . 'Permission')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.permission.index') }}"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i><span>Permissions</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit ' : 'Create' }} </span></span></li>
        </ol>
    </nav>
    <section class="admin-card">
        <div class="card ">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit ' : 'Create' }} Permission</h1>
                <a href="{{ route('admin.permission.index') }}" class="btn btn-primary text-end">&larr; Back to permissions</a>

            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.permission.update', $item) : route('admin.permission.store') }}" method="POST">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Permission name <span class="text-danger">*</span></label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $item->name ?? '') }}"
                            placeholder="Enter a permission name"
                            class="form-control @error('name') is-invalid @enderror"
                            maxlength="255"
                            required
                            autofocus
                        >
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.permission.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create permission' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


@endsection
