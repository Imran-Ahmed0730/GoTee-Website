@extends('admin.master')
@section('title', isset($item) ? 'Edit blog' : 'Create blog')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}"><i class="fa-solid fa-newspaper" aria-hidden="true"></i><span>Blogs</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit blog' : 'Create blog' }}</h1>
                <a href="{{ route('admin.blog.index') }}" class="btn btn-primary">&larr; Back to Blogs</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.blog.update', $item) : route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $item->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" placeholder="Enter a blog title" class="form-control @error('title') is-invalid @enderror" maxlength="255" required autofocus>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="6" placeholder="Write the blog content or summary" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail {{ isset($item) ? '' : '*' }}</label>
                        <input id="thumbnail" name="thumbnail" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control @error('thumbnail') is-invalid @enderror" @required(!isset($item))>
                        <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                        @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if (!empty($item?->thumbnail) && file_exists($item->thumbnail))
                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }} thumbnail" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 160px;">
                        @else
                            <img src="{{ asset('images/blog-category-placeholder.svg') }}" alt="{{ $item->title }} thumbnail" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 160px;">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="cover_photo" class="form-label">Cover photo</label>
                        <input id="cover_photo" name="cover_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control @error('cover_photo') is-invalid @enderror">
                        <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                        @error('cover_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if (!empty($item?->cover_photo) && file_exists($item->cover_photo))
                            <img src="{{ asset($item->cover_photo) }}" alt="{{ $item->title }} cover" class="img-thumbnail mt-3" style="max-width: 320px; max-height: 180px;">
                        @else
                            <img src="{{ asset('images/blog-category-placeholder.svg') }}" alt="{{ $item->title }} cover" class="img-thumbnail mt-3" style="max-width: 320px; max-height: 180px;">
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
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create blog' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
