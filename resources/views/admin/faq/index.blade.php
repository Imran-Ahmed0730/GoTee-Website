@extends('admin.master')
@section('title', 'FAQs')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-circle-question" aria-hidden="true"></i><span>FAQs</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">FAQs</h1>
            <p class="text-secondary mb-0">Manage frequently asked questions and their answers.</p>
        </div>
        @can('FAQ Add')
            <a href="{{ route('admin.faq.create') }}" class="btn btn-primary"> <i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create FAQ</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="FAQ list">
        <form action="{{ route('admin.faq.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-12 col-md-5">
                <label for="category_id" class="form-label">Category</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-5">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="1" @selected(($filters['status'] ?? '') === '1')>Active</option>
                    <option value="0" @selected(($filters['status'] ?? '') === '0')>Inactive</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
                <a href="{{ route('admin.faq.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Category</th>
                        <th scope="col">Question</th>
                        <th scope="col">Answer</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $loop->index }}</th>
                            <td>{{ $item->category->name }}</td>
                            <td class="fw-semibold">{{ $item->question }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->answer, 120) }}</td>
                            <td>
                                @can('FAQ Status Change')
                                    <form action="{{ route('admin.faq.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->question }} status" @checked($item->status) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->status ? 'success' : 'secondary' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('FAQ Update')
                                        <a href="{{ route('admin.faq.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit FAQ: {{ $item->question }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('FAQ Delete')
                                        <form action="{{ route('admin.faq.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete FAQ: {{ $item->question }}">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No FAQs match this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
