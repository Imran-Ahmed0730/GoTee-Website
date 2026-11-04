@extends('admin.master')
@section('title', 'Blogs')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-newspaper" aria-hidden="true"></i><span>Blogs</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Blogs</h1>
            <p class="text-secondary mb-0">Create and manage blog posts.</p>
        </div>
        @can('Blog Add')
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"> <i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create blog</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Blog list">
        <form action="{{ route('admin.blog.index') }}" method="GET" class="row g-3 mb-4">
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
                <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Status</th>
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
                                    <span class="d-block small text-secondary">{{ \Illuminate\Support\Str::limit($item->description, 90) }}</span>
                                @endif
                            </td>
                            <td>{{ $item->category->name }}</td>
                            <td><code>{{ $item->slug }}</code></td>
                            <td>
                                @can('Blog Status Change')
                                    <form action="{{ route('admin.blog.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->title }} status" @checked($item->status) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->status ? 'success' : 'secondary' }}">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Blog Update')
                                        <a href="{{ route('admin.blog.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->title }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Blog Delete')
                                        <form action="{{ route('admin.blog.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->title }}">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No blogs match this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
