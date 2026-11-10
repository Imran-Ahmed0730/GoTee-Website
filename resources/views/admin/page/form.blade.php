@extends('admin.master')
@section('title', isset($item) ? 'Edit page' : 'Create page')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.css" integrity="sha512-WpRCKdt0qii+bQN3PAOsKoj34HaDb9jfYXIyFGPOYyY3AMWKAtkZmr49dj6dCAfQYUk8Ye4O8vl3SqxC904+BQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
@endpush
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}"><i class="fa-solid fa-file-lines" aria-hidden="true"></i><span>Pages</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit page' : 'Create page' }}</h1>
                <a href="{{ route('admin.page.index') }}" class="btn btn-primary">&larr; Back to pages</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.page.update', $item) : route('admin.page.store') }}" method="POST">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" placeholder="Enter a page title" class="form-control @error('title') is-invalid @enderror" maxlength="255" required autofocus>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="8" maxlength="10000" placeholder="Write a page description" class="summernote form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <a href="{{ route('admin.page.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create page' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.js" integrity="sha512-qTQLA91yGDLA06GBOdbT7nsrQY8tN6pJqjT16iTuk08RWbfYmUz/pQD3Gly1syoINyCFNsJh7A91LtrLIwODnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
            });

        });
    </script>
@endpush
