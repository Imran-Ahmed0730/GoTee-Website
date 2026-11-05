@extends('admin.master')
@section('title', 'Social media settings')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.setting.index') }}"><i class="fa-solid fa-gear" aria-hidden="true"></i><span>Settings</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-share-nodes" aria-hidden="true"></i><span>Social media</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header">
                <h1 class="h3 mt-3 mb-1">Social media</h1>
                <p class="text-muted mb-0">Leave a field empty to hide that social profile link.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.update', 'social-media') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    @foreach ([
                        'facebook_url' => 'Facebook URL',
                        'x_url' => 'X URL',
                        'instagram_url' => 'Instagram URL',
                        'linkedin_url' => 'LinkedIn URL',
                        'youtube_url' => 'YouTube URL',
                    ] as $field => $label)
                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="url" value="{{ old($field, $settings[$field] ?? '') }}" placeholder="https://..." class="form-control @error($field) is-invalid @enderror">
                            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save social media settings</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
