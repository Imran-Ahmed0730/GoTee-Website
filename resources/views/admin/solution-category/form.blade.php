<div>
    <!-- It always seems impossible until it is done. - Nelson Mandela -->
</div>
@extends('admin.master')
@section('title', isset($item) ? 'Edit solution category' : 'Create solution category')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.solution-category.index') }}"><i class="fa-solid fa-layer-group" aria-hidden="true"></i><span>Solution categories</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit solution category' : 'Create solution category' }}</h1>
                <a href="{{ route('admin.solution-category.index') }}" class="btn btn-primary">&larr; Back to categories</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.solution-category.update', $item) : route('admin.solution-category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $item->name ?? '') }}" placeholder="Enter a solution category name" class="form-control @error('name') is-invalid @enderror" maxlength="255" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="5" maxlength="10000" placeholder="Describe this solution category" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        @foreach (['image' => 'Category image', 'icon' => 'Category icon'] as $field => $label)
                            <div class="col-md-6 mb-3">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <input id="{{ $field }}" name="{{ $field }}" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="image-input form-control @error($field) is-invalid @enderror">
                                <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                                @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if (!empty($item?->{$field}) && file_exists(public_path($item->{$field})))
                                    <img src="{{ asset($item->{$field}) }}" alt="{{ $label }}" class="img-thumbnail mt-3" id="preview-{{ $field }}" style="max-width: 180px; max-height: 140px;">
                                @else
                                    <img src="{{ asset('images/blog-category-placeholder.svg') }}" alt="{{ $label }} preview" class="img-thumbnail mt-3" id="preview-{{ $field }}" style="max-width: 180px; max-height: 140px;">
                                @endif
                            </div>
                        @endforeach
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
                        <a href="{{ route('admin.solution-category.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create category' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        document.querySelectorAll('.image-input').forEach((input) => {
            input.addEventListener('change', () => {
                const file = input.files[0];

                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.addEventListener('load', (event) => {
                    document.querySelector(`#preview-${input.id}`).src = event.target.result;
                });
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
