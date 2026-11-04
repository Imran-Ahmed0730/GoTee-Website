@extends('admin.master')
@section('title', isset($item) ? 'Edit FAQ' : 'Create FAQ')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}"><i class="fa-solid fa-circle-question" aria-hidden="true"></i><span>FAQs</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid {{ isset($item) ? 'fa-pen-to-square' : 'fa-circle-plus' }}" aria-hidden="true"></i><span>{{ isset($item) ? 'Edit' : 'Create' }}</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h1 class="h3 mt-3 mb-1">{{ isset($item) ? 'Edit FAQ' : 'Create FAQ' }}</h1>
                <a href="{{ route('admin.faq.index') }}" class="btn btn-primary">&larr; Back to FAQs</a>
            </div>
            <div class="card-body">
                <form action="{{ isset($item) ? route('admin.faq.update', $item) : route('admin.faq.store') }}" method="POST">
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
                        <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                        <input id="question" name="question" type="text" value="{{ old('question', $item->question ?? '') }}" placeholder="Enter a frequently asked question" class="form-control @error('question') is-invalid @enderror" maxlength="255" required autofocus>
                        @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="answer" class="form-label">Answer <span class="text-danger">*</span></label>
                        <textarea id="answer" name="answer" rows="7" placeholder="Write the answer" class="form-control @error('answer') is-invalid @enderror" required>{{ old('answer', $item->answer ?? '') }}</textarea>
                        @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Save changes' : 'Create FAQ' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
