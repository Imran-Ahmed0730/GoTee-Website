@extends('admin.master')
@section('title', 'General settings')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.setting.index') }}"><i class="fa-solid fa-gear" aria-hidden="true"></i><span>Settings</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-globe" aria-hidden="true"></i><span>General</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header"><h1 class="h3 mt-3 mb-1">General settings</h1></div>
            <div class="card-body">
                <form action="{{ route('admin.setting.update', 'general') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site name <span class="text-danger">*</span></label>
                        <input id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="Enter the site name" class="form-control @error('site_name') is-invalid @enderror" required>
                        @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="business_name" class="form-label">Business name <span class="text-danger">*</span></label>
                        <input id="business_name" name="business_name" value="{{ old('business_name', $settings['business_name'] ?? '') }}" placeholder="Enter the business name" class="form-control @error('business_name') is-invalid @enderror" required>
                        @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="site_url" class="form-label">Site URL</label>
                        <input id="site_url" name="site_url" type="url" value="{{ old('site_url', $settings['site_url'] ?? '') }}" placeholder="https://example.com" class="form-control @error('site_url') is-invalid @enderror">
                        @error('site_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="short_bio" class="form-label">Short description <span class="text-danger">*</span></label>
                        <textarea id="short_bio" name="short_bio" rows="3" placeholder="Briefly describe your business" class="form-control @error('short_bio') is-invalid @enderror" required>{{ old('short_bio', $settings['short_bio'] ?? '') }}</textarea>
                        @error('short_bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta description <span class="text-danger">*</span></label>
                        <textarea id="meta_description" name="meta_description" rows="3" placeholder="Write the search engine description" class="form-control @error('meta_description') is-invalid @enderror" required>{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                        @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="developed_by" class="form-label">Developed by</label>
                        <input id="developed_by" name="developed_by" value="{{ old('developed_by', $settings['developed_by'] ?? '') }}" placeholder="Developer or company name" class="form-control @error('developed_by') is-invalid @enderror">
                        @error('developed_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="developed_by_url" class="form-label">Developer URL</label>
                        <input id="developed_by_url" name="developed_by_url" type="url" value="{{ old('developed_by_url', $settings['developed_by_url'] ?? '') }}" placeholder="https://example.com" class="form-control @error('developed_by_url') is-invalid @enderror">
                        @error('developed_by_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save general settings</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
