@extends('admin.master')
@section('title', 'Employees')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-users" aria-hidden="true"></i><span>Employees</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Employees</h1>
            <p class="text-secondary mb-0">Create and manage employee records.</p>
        </div>
        @can('Employee Add')
            <a href="{{ route('admin.employee.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create employee</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Employee list">
        <form action="{{ route('admin.employee.index') }}" method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Search employees</label>
                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search employee name, email, or phone" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status-filter" class="form-label">Status</label>
                <select id="status-filter" name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="1" @selected(($filters['status'] ?? '') === '1')>Active</option>
                    <option value="0" @selected(($filters['status'] ?? '') === '0')>Inactive</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
                <a href="{{ route('admin.employee.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Department</th>
                        <th scope="col">Designation</th>
                        <th scope="col">Joining date</th>
                        <th scope="col">Team</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $key => $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $key }}</th>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ [1 => 'Male', 2 => 'Female', 3 => 'Other'][$item->gender] ?? '—' }}</td>
                            <td>{{ $item->department?->name ?? '—' }}</td>
                            <td>{{ $item->designation?->name ?? '—' }}</td>
                            <td>{{ $item->joining_date->format('M j, Y') }}</td>
                            <td>
                                @can('Employee Update')
                                    <form action="{{ route('admin.employee.show-as-team.update', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="show_as_team" value="0">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" name="show_as_team" value="1" aria-label="Show {{ $item->user->name }} as a team member" @checked($item->show_as_team) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->show_as_team ? 'success' : 'secondary' }}">{{ $item->show_as_team ? 'Shown' : 'Hidden' }}</span>
                                @endcan
                            </td>
                            <td>
                                @can('Employee Status Change')
                                    <form action="{{ route('admin.employee.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->user->name }} status" @checked($item->getRawOriginal('status') == 1) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->getRawOriginal('status') == 1 ? 'success' : 'secondary' }}">{{ $item->status }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Employee Update')
                                        <a href="{{ route('admin.employee.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->user->name }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Employee Delete')
                                        <form action="{{ route('admin.employee.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->user->name }}">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center text-muted py-4">No employees match this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
    </section>
@endsection
