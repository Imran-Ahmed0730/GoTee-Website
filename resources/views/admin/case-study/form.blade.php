@extends('admin.master')
@section('title', isset($item) ? 'Edit Case Study' : 'Create Case Study')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.case-study.index') }}"><i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Case studies</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page">{{ isset($item) ? 'Edit' : 'Create' }}</span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit Case Study' : 'Create Case Study' }}</h1>
                <a href="{{ route('admin.case-study.index') }}" class="btn btn-primary">&larr; Back to Case Studies</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form action="{{ isset($item) ? route('admin.case-study.update', $item) : route('admin.case-study.store') }}" method="POST">
                    @csrf
                    @if (isset($item))
                        @method('PATCH')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Case study title <span class="text-danger">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" placeholder="Enter a case study title" class="form-control @error('title') is-invalid @enderror" maxlength="255" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Client / Partner</label>
                            <select id="client_id" name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">No client selected</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected((string) old('client_id', $item->client_id ?? '') === (string) $client->id)>{{ $client->name }}{{ $client->getRawOriginal('status') == 1 ? '' : ' (Inactive)' }}</option>
                                @endforeach
                            </select>
                            @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="industry" class="form-label">Industry <span class="text-danger">*</span></label>
                            <input id="industry" name="industry" type="text" value="{{ old('industry', $item->industry ?? '') }}" placeholder="Enter the industry" class="form-control @error('industry') is-invalid @enderror" maxlength="255" required>
                            @error('industry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input id="location" name="location" type="text" value="{{ old('location', $item->location ?? '') }}" placeholder="Enter the location" class="form-control @error('location') is-invalid @enderror" maxlength="255" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="challenge" class="form-label">Challenge <span class="text-danger">*</span></label>
                            <textarea id="challenge" name="challenge" rows="4" placeholder="Describe the challenge" class="form-control @error('challenge') is-invalid @enderror" maxlength="10000" required>{{ old('challenge', $item->challenge ?? '') }}</textarea>
                            @error('challenge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="solution" class="form-label">Solution <span class="text-danger">*</span></label>
                            <textarea id="solution" name="solution" rows="4" placeholder="Describe the solution" class="form-control @error('solution') is-invalid @enderror" maxlength="10000" required>{{ old('solution', $item->solution ?? '') }}</textarea>
                            @error('solution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="results" class="form-label">Results / Metrics <span class="text-danger">*</span></label>
                            <textarea id="results" name="results" rows="4" placeholder="Describe the results and metrics" class="form-control @error('results') is-invalid @enderror" maxlength="10000" required>{{ old('results', $item->results ?? '') }}</textarea>
                            @error('results')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="testimonial_id" class="form-label">Related testimonial</label>
                            <select id="testimonial_id" name="testimonial_id" class="form-select @error('testimonial_id') is-invalid @enderror">
                                <option value="">No testimonial</option>
                                @foreach ($testimonials as $testimonial)
                                    <option value="{{ $testimonial->id }}" @selected((string) old('testimonial_id', $item->testimonial_id ?? '') === (string) $testimonial->id)>
                                        {{ $testimonial->client_name }} — {{ $testimonial->first_name }} {{ $testimonial->last_name }}{{ $testimonial->status === 'Hidden' ? ' (Hidden)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('testimonial_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <fieldset class="mb-4">
                        <legend class="form-label">Visibility</legend>
                        <div class="form-check form-switch">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" value="1" @checked((string) old('status', isset($item) ? (int) $item->getRawOriginal('status') : '1') === '1')>
                            <label class="form-check-label" for="status">Show case study</label>
                        </div>
                        @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </fieldset>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.case-study.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create case study' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
