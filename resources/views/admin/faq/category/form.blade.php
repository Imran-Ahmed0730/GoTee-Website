@extends('admin.master')
@section('title', isset($item) ? 'Edit FAQ category' : 'Create FAQ category')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.faq.category.index') }}"><i class="fa-solid fa-folder-open" aria-hidden="true"></i><span>FAQ categories</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit FAQ category' : 'Create FAQ category' }}</h1>
                <a href="{{ route('admin.faq.category.index') }}" class="btn btn-primary">&larr; Back to Categories</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.faq.category.update', $item) : route('admin.faq.category.store') }}" method="POST">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" placeholder="Enter a category name" class="form-control @error('name') is-invalid @enderror" maxlength="255" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <fieldset class="mb-4">
                        <legend class="form-label">Status</legend>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked((bool) old('status', $item->status ?? 1))>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.faq.category.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create category' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
