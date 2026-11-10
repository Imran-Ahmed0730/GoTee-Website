@extends('admin.master')
@section('title', 'Logo & favicon settings')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.setting.index') }}"><i class="fa-solid fa-gear" aria-hidden="true"></i><span>Settings</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-image" aria-hidden="true"></i><span>Logo & favicon</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header">
                <h1 class="h3 mt-3 mb-1">Logo & favicon</h1>
                <p class="text-muted mb-0">Upload JPG, PNG, or WebP images up to 2 MB. Leave a file empty to keep the current image.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.update', 'logos-favicon') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        @foreach ([
                            'site_logo' => 'Site logo',
                            'site_favicon' => 'Site favicon',
                            'site_footer_logo' => 'Footer logo',
                            'site_dark_logo' => 'Dark-mode logo',
                        ] as $field => $label)
                            <div class="mb-4 col-md-6">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <input id="{{ $field }}" name="{{ $field }}" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-control setting-image-input @error($field) is-invalid @enderror">
                                @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if (!empty($settings[$field]) && file_exists($settings[$field]))
                                    <img src="{{ asset($settings[$field]) }}" alt="{{ $label }}" id="preview-{{ $field }}" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 120px;">
                                @else
                                    <img src="{{ asset('images/blog-category-placeholder.svg') }}" alt="{{ $label }}" id="preview-{{ $field }}" class="img-thumbnail mt-3" style="max-width: 240px; max-height: 120px;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save logo & favicon</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('.setting-image-input').on('change', function () {
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
