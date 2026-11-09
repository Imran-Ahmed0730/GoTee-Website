@extends('admin.master')
@section('title', isset($item) ? 'Edit Testimonial' : 'Create Testimonial')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.testimonial.index') }}"><i class="fa-solid fa-quote-left" aria-hidden="true"></i><span>Testimonials</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page">{{ isset($item) ? 'Edit' : 'Create' }}</span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit Testimonial' : 'Create Testimonial' }}</h1>
                <a href="{{ route('admin.testimonial.index') }}" class="btn btn-primary">&larr; Back to Testimonials</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ isset($item) ? route('admin.testimonial.update', $item) : route('admin.testimonial.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="client_name" class="form-label">Client / Company <span class="text-danger">*</span></label>
                            <input id="client_name" name="client_name" type="text" value="{{ old('client_name', $item->client_name ?? '') }}" placeholder="Enter the client or company name" class="form-control @error('client_name') is-invalid @enderror" maxlength="255" required>
                            @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                            <input id="designation" name="designation" type="text" value="{{ old('designation', $item->designation ?? '') }}" placeholder="Enter the designation" class="form-control @error('designation') is-invalid @enderror" maxlength="255" required>
                            @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First name <span class="text-danger">*</span></label>
                            <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $item->first_name ?? '') }}" placeholder="Enter the first name" class="form-control @error('first_name') is-invalid @enderror" maxlength="255" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last name</label>
                            <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $item->last_name ?? '') }}" placeholder="Enter the last name" class="form-control @error('last_name') is-invalid @enderror" maxlength="255">
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                            <textarea id="comment" name="comment" rows="5" placeholder="Enter the testimonial comment" class="form-control @error('comment') is-invalid @enderror" maxlength="10000" required>{{ old('comment', $item->comment ?? '') }}</textarea>
                            @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <select id="rating" name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="">Choose rating</option>
                                @foreach (range(5, 1) as $rating)
                                    <option value="{{ $rating }}" @selected((string) old('rating', $item->rating ?? '') === (string) $rating)>{{ $rating }} / 5</option>
                                @endforeach
                            </select>
                            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Image (optional)</label>
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text">JPG, PNG, or WebP; maximum 5 MB.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if (!empty($item?->image) && is_file(public_path($item->image)))
                                <img src="{{ asset($item->image) }}" alt="{{ $item->client_name }}" class="img-thumbnail mt-3" style="max-width: 180px; max-height: 120px;">
                            @endif
                        </div>
                    </div>

                    <fieldset class="mb-4">
                        <legend class="form-label">Visibility</legend>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked((string) old('status', isset($item) ? (int) $item->getRawOriginal('status') : '1') === '1')>
                            <label class="form-check-label" for="status">Show testimonial</label>
                        </div>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.testimonial.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create testimonial' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
