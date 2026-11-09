@extends('admin.master')
@section('title', 'Contact settings')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.setting.index') }}"><i class="fa-solid fa-gear" aria-hidden="true"></i><span>Settings</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-address-card" aria-hidden="true"></i><span>Contact</span></span></li>
        </ol>
    </nav>

    @php($selectedClosedDays = array_map('strval', old('closed_on', $settings['closed_on'] ?? [])))
    <section class="admin-card">
        <div class="card">
            <div class="card-header"><h1 class="h3 mt-3 mb-1">Contact settings</h1></div>
            <div class="card-body">
                <form action="{{ route('admin.setting.update', 'contact') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea id="address" name="address" rows="2" placeholder="Enter your business address" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $settings['address'] ?? '') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="map_location" class="form-label">Map Location <span class="text-danger">*</span></label>
                        <textarea id="map_location" name="map_location" rows="2" placeholder="Enter map location" class="form-control @error('map_location') is-invalid @enderror" required>{{ old('map_location', $settings['map_location'] ?? '') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $settings['email'] ?? '') }}" placeholder="name@example.com" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="Enter a phone number" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone (secondary)</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="Enter a phone number" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp <span class="text-danger">*</span></label>
                        <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" placeholder="Enter a WhatsApp number" class="form-control @error('whatsapp') is-invalid @enderror" required>
                        @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="opening_time" class="form-label">Opening time <span class="text-danger">*</span></label>
                            <input id="opening_time" name="opening_time" value="{{ old('opening_time', $settings['opening_time'] ?? '') }}" placeholder="For example, 09:00 AM" class="form-control @error('opening_time') is-invalid @enderror" required>
                            @error('opening_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="closing_time" class="form-label">Closing time <span class="text-danger">*</span></label>
                            <input id="closing_time" name="closing_time" value="{{ old('closing_time', $settings['closing_time'] ?? '') }}" placeholder="For example, 06:00 PM" class="form-control @error('closing_time') is-invalid @enderror" required>
                            @error('closing_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <fieldset class="mb-4">
                        <legend class="form-label">Closed days</legend>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ([1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'] as $day => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="closed_on[]" id="closed_on_{{ $day }}" value="{{ $day }}" @checked(in_array((string) $day, $selectedClosedDays, true))>
                                    <label class="form-check-label" for="closed_on_{{ $day }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('closed_on')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('closed_on.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save contact settings</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
