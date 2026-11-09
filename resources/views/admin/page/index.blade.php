@extends('admin.master')
@section('title', 'Pages')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-file-lines" aria-hidden="true"></i><span>Pages</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Pages</h1>
            <p class="text-secondary mb-0">Create and manage website pages.</p>
        </div>
        @can('Page Add')
            <a href="{{ route('admin.page.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create page</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Page list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $loop->index }}</th>
                            <td>
                                <span class="fw-semibold">{{ $item->title }}</span>
                                @if ($item->description)
                                    <span class="d-block small text-secondary">{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 90) }}</span>
                                @endif
                            </td>
                            <td>{{ $item->slug }}</td>
                            <td>
                                @can('Page Status Change')
                                    <form action="{{ route('admin.page.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->title }} status" @checked((bool) $item->getRawOriginal('status')) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->getRawOriginal('status') ? 'success' : 'secondary' }}">{{ $item->getRawOriginal('status') ? 'Active' : 'Inactive' }}</span>
                                @endcan
                            </td>
                            <td>{{ $item->created_at->format('M j, Y') }}</td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Page Update')
                                        <a href="{{ route('admin.page.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->title }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('Page Delete')
                                        <form action="{{ route('admin.page.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->title }}"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No pages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
