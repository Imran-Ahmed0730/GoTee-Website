@extends('admin.master')
@section('title', isset($item) ? 'Edit role' : 'Create role')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}"><i class="fa-solid fa-users-gear" aria-hidden="true"></i><span>Roles</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>
    <div class="card admin-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit role' : 'Create role' }}</h1>
            <a href="{{ route('admin.role.index') }}" class="btn btn-primary text-end">&larr; Back to roles</a>

        </div>
        <div class="card-body">
            <form action="{{ isset($item) ? route('admin.role.update', $item) : route('admin.role.store') }}" method="POST">
                @csrf
                @if (isset($item))
                    @method('PATCH')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Role name <span class="text-danger">*</span></label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $item->name ?? '') }}"
                        placeholder="Enter a role name"
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
                    <a href="{{ route('admin.role.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create role' }}</button>
                </div>
            </form>
        </div>
    </div>

@endsection
