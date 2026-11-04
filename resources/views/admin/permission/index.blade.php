@extends('admin.master')
@section('title', __('Permission List'))
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i><span>Permissions</span></span></li>
        </ol>
    </nav>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Permissions</h1>
            <p class="text-secondary mb-0">Manage permissions and assign them to roles.</p>
        </div>
        @can('Permission Add')
            <a href="{{ route('admin.permission.create') }}" class="btn btn-primary">Create permission</a>
        @endcan
    </div>
    <section class="admin-card p-3 p-md-4" aria-label="Permission list">
        <div class="table-responsive">
                <table class="table table-borderless table-striped table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($items as $key => $item)
                        <tr>
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Permission Update')
                                        <a
                                            href="{{ route('admin.permission.edit', $item) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="Edit {{ $item->name }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-title="Edit permission"
                                        >
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Permission Delete')
                                        <form action="{{ route('admin.permission.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this permission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                aria-label="Delete {{ $item->name }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-title="Delete permission"
                                            >
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No permissions have been created yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
    </section>

@endsection
