@extends('admin.master')
@section('title', 'Case Studies')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Case studies</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Case Studies</h1>
            <p class="text-secondary mb-0">Manage client projects, challenges, solutions, and results.</p>
        </div>
        @can('Case Study Add')
            <a href="{{ route('admin.case-study.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create case study</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Case study list">
        <form action="{{ route('admin.case-study.index') }}" method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Search case studies</label>
                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Title, client, industry, or location" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Visibility</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Show and hidden</option>
                    <option value="1" @selected(($filters['status'] ?? '') === '1')>Show</option>
                    <option value="0" @selected(($filters['status'] ?? '') === '0')>Hidden</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
                <a href="{{ route('admin.case-study.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Case Study</th>
                        <th scope="col">Client / Partner</th>
                        <th scope="col">Industry / Location</th>
                        <th scope="col">Testimonial</th>
                        <th scope="col">Visibility</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->client->name }}</td>
                            <td>{{ $item->industry }}<br><small class="text-muted">{{ $item->location }}</small></td>
                            <td>{{ $item->testimonial?->client_name ?? '—' }}</td>
                            <td>
                                @can('Case Study Status Change')
                                    <form action="{{ route('admin.case-study.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="0">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" aria-label="Show {{ $item->title }}" @checked($item->getRawOriginal('status') == 1) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->getRawOriginal('status') == 1 ? 'success' : 'secondary' }}">{{ $item->status }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Case Study Update')
                                        <a href="{{ route('admin.case-study.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->title }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('Case Study Delete')
                                        <form action="{{ route('admin.case-study.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->title }}"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No case studies match this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
    </section>
@endsection
