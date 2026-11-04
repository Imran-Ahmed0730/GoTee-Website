@extends('admin.master')
@section('title', isset($item) ? 'Edit blog category' : 'Create blog category')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.blog.category.index') }}"><i class="fa-solid fa-folder-open" aria-hidden="true"></i><span>Blog categories</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit blog category' : 'Create blog category' }}</h1>
                <a href="{{ route('admin.blog.category.index') }}" class="btn btn-primary">&larr; Back to Categories</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.blog.category.update', $item) : route('admin.blog.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" placeholder="Enter a category name" class="form-control @error('name') is-invalid @enderror" maxlength="255" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Describe this category (optional)" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Category icon image</label>
                        <input id="icon" name="icon" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control @error('icon') is-invalid @enderror">
                        <div class="form-text">Choose an icon image (JPG, PNG, or WebP; maximum 5 MB). The placeholder is shown until an image is uploaded.</div>
                        @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <img
                            src="{{ asset(isset($item) && $item->icon ? $item->icon : 'images/blog-category-placeholder.svg') }}"
                            alt="{{ $item->name ?? 'Category icon' }}"
                            class="img-thumbnail mt-3"
                            style="max-width: 160px; max-height: 120px;"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="cover_photo" class="form-label">Cover photo</label>
                        <input id="cover_photo" name="cover_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control @error('cover_photo') is-invalid @enderror">
                        <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                        @error('cover_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if (!empty($item?->cover_photo))
                            <img src="{{ asset($item->cover_photo) }}" alt="{{ $item->name }} cover" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 160px;">
                        @endif
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
                        <a href="{{ route('admin.blog.category.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create category' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
