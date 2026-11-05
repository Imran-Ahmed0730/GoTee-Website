@extends('admin.master')
@section('title', 'Clients')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-newspaper" aria-hidden="true"></i><span>Clients</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Clients</h1>
            <p class="text-secondary mb-0">Create and manage client information.</p>
        </div>
        @can('Client Add')
            <a href="{{ route('admin.client.create') }}" class="btn btn-primary"> <i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Create Client</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Client list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $key => $item)
                        <tr>
                            <th scope="row">{{ 1 + $key }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->address }}</td>
                            <td>
                                @can('Client Status Change')
                                    <form action="{{ route('admin.client.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" aria-label="Change {{ $item->name }} status" @checked($item->status === 'Active') onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->status === 'Active' ? 'success' : 'secondary' }}">{{ $item->status }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Client Update')
                                        <a href="{{ route('admin.client.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->name }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    @can('Client Delete')
                                        <form action="{{ route('admin.client.destroy', $item) }}" method="POST">
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
                        <tr><td colspan="7" class="text-center text-muted py-4">No clients match this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
