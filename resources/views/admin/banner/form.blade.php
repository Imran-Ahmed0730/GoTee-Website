@extends('admin.master')
@section('title', isset($item) ? 'Edit' : 'Create' . ' Banner')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.banner.index') }}"><i class="fa-solid fa-image" aria-hidden="true"></i><span>Banners</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit banner' : 'Create banner' }}</h1>
                <a href="{{ route('admin.banner.index') }}" class="btn btn-primary">&larr; Back to Banners</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.banner.update', $item) : route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label">Title </label>
                        <input id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" placeholder="Enter a banner title" class="form-control @error('title') is-invalid @enderror" maxlength="255" autofocus>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle </label>
                        <input id="subtitle" name="subtitle" type="text" value="{{ old('subtitle', $item->subtitle ?? '') }}" placeholder="Enter a banner subtitle" class="form-control @error('subtitle') is-invalid @enderror" maxlength="255">
                        @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="6" placeholder="Write a short banner description" class="form-control summernote @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Thumbnail <span class="text-danger">{{ isset($item) ? '' : '*' }}</span></label>
                        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="image-input form-control @error('image') is-invalid @enderror" @required(!isset($item))>
                        <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if (!empty($item?->image) && file_exists($item->image))
                            <img src="{{ asset($item->image) }}" alt="{{ $item->title }} thumbnail" class="img-thumbnail mt-3" id="preview-image" style="max-height: 160px;">
                        @else
                            <img src="{{ asset('images/blog-category-placeholder.svg') }}" alt="thumbnail" class="img-thumbnail mt-3" id="preview-image" style="max-height: 160px;">
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="position" class="form-label">Position</label>
                            <select name="position" id="position" class="form-select @error('position') is-invalid @enderror">
                                <option value="1" @selected((string) old('position', isset($item) ? $item->getRawOriginal('position') : '1') === '1')>Top</option>
                                <option value="2" @selected((string) old('position', isset($item) ? $item->getRawOriginal('position') : '1') === '2')>Middle</option>
                                <option value="3" @selected((string) old('position', isset($item) ? $item->getRawOriginal('position') : '1') === '3')>Bottom</option>
                            </select>
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <fieldset class="col-md-6 mb-4">
                            <legend class="form-label">Status</legend>
                            <div class="form-check form-switch">
                                <input type="hidden" name="status" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked((string) old('status', isset($item) ? $item->getRawOriginal('status') : '1') === '1')>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </fieldset>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create banner' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        $(document).ready(function() {

            $('.image-input').on('change', function () {
                const input = this;
                const file = input.files[0];

                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.addEventListener('load', function (event) {
                    $('#preview-' + input.id).attr('src', event.target.result);
                });
                reader.readAsDataURL(file);
            });

        });
    </script>
@endpush
