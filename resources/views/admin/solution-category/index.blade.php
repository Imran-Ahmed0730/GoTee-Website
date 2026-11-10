<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div>
@extends('admin.master')
@section('title', 'Solution categories')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-layer-group" aria-hidden="true"></i><span>Solution categories</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Solution categories</h1>
            <p class="text-secondary mb-0">Create and manage solution categories.</p>
        </div>
        @can('Solution Category Add')
            <a href="{{ route('admin.solution-category.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create category</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Solution category list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
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
                                @if ($item->image && file_exists(public_path($item->image)))
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="img-thumbnail rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-secondary">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $item->name }}</span>
                                @if ($item->description)
                                    <span class="d-block small text-secondary">{{ \Illuminate\Support\Str::limit($item->description, 90) }}</span>
                                @endif
                            </td>
                            <td>{{ $item->slug }}</td>
                            <td>
                                @can('Solution Category Status Change')
                                    <form action="{{ route('admin.solution-category.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->name }} status" @checked((bool) $item->getRawOriginal('status')) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->getRawOriginal('status') ? 'success' : 'secondary' }}">{{ $item->getRawOriginal('status') ? 'Active' : 'Inactive' }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Solution Category Update')
                                        <a href="{{ route('admin.solution-category.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->name }}"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('Solution Category Delete')
                                        <form action="{{ route('admin.solution-category.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->name }}"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No solution categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
