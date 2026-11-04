@extends('admin.master')
@section('title', 'Roles')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-users-gear" aria-hidden="true"></i><span>Roles</span></span></li>
        </ol>
    </nav>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Roles</h1>
            <p class="text-secondary mb-0">Manage roles and the permissions assigned to them.</p>
        </div>
        @can('Role Add')
            <a href="{{ route('admin.role.create') }}" class="btn btn-primary">Create role</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Role list">
        <div class="table-responsive">
                <table class="table table-borderless table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Permissions</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $key => $item)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->permissions_count }}</td>
                                <td class="text-end">
                                    <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                        @can('Role Permission Add/Update')
                                            <a
                                                href="{{ route('admin.role.assign.permission', $item->id) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                aria-label="Assign permissions to {{ $item->name }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-title="Assign permissions"
                                            >
                                                <i class="fa-solid fa-key" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                        @can('Role Update')
                                            <a
                                                href="{{ route('admin.role.edit', $item) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                aria-label="Edit {{ $item->name }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                data-bs-title="Edit role"
                                            >
                                                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                        @can('Role Delete')
                                            <form action="{{ route('admin.role.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger btn-delete"
                                                    aria-label="Delete {{ $item->name }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-title="Delete role"
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
                                    No roles have been created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </section>
@endsection
