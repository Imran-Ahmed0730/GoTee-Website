@extends('admin.master')
@section('title', 'FAQ categories')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-folder-open" aria-hidden="true"></i><span>FAQ categories</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">FAQ categories</h1>
            <p class="text-secondary mb-0">Organize frequently asked questions by topic.</p>
        </div>
        @can('FAQ Category Add')
            <a href="{{ route('admin.faq.category.create') }}" class="btn btn-primary"> <i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create Category</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="FAQ category list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">FAQs</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td>{{ $item->faqs_count }}</td>
                            <td>
                                @can('FAQ Category Status Change')
                                    <form action="{{ route('admin.faq.category.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->name }} status" @checked($item->status) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->status ? 'success' : 'secondary' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('FAQ Category Update')
                                        <a href="{{ route('admin.faq.category.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->name }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('FAQ Category Delete')
                                        <form action="{{ route('admin.faq.category.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->name }}">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No FAQ categories have been created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
