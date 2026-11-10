@extends('admin.master')
@section('title', isset($item) ? 'Edit' : 'Create' . ' Client')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.client.index') }}"><i class="fa-solid fa-users" aria-hidden="true"></i><span>Clients</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit Client' : 'Create Client' }}</h1>
                <a href="{{ route('admin.client.index') }}" class="btn btn-primary">&larr; Back to Clients</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.client.update', $item) : route('admin.client.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" placeholder="Enter client name" class="form-control @error('name') is-invalid @enderror" maxlength="255" autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email </label>
                            <input id="email" name="email" type="email" value="{{ old('email', $item->email ?? '') }}" placeholder="Enter client email" class="form-control @error('email') is-invalid @enderror" maxlength="255">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone', $item->phone ?? '') }}" placeholder="Enter client phone number" class="form-control @error('phone') is-invalid @enderror" maxlength="20">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" rows="6" placeholder="Enter client address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $item->address ?? '') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <fieldset class="mb-4">
                        <legend class="form-label">Status</legend>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked((string) old('status', isset($item) ? $item->getRawOriginal('status') : '1') === '1')>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.client.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create client' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
