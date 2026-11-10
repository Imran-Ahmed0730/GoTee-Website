@extends('admin.master')
@section('title', 'Demo requests')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i><span>Demo requests</span></span></li>
        </ol>
    </nav>

    <div class="mb-4">
        <h1 class="h3 mb-1">Demo requests</h1>
        <p class="text-secondary mb-0">Review product demo requests.</p>
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Demo request list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Company and contact</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Machines</th>
                        <th scope="col">Address</th>
                        <th scope="col">Status</th>
                        <th scope="col">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $loop->index }}</th>
                            <td>
                                <span class="fw-semibold">{{ $item->company }}</span>
                                <span class="d-block">{{ trim($item->contacting_person_first_name.' '.$item->contacting_person_last_name) }}</span>
                                <a class="d-block small" href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                            </td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->machine_qty }}</td>
                            <td>{{ $item->address }}</td>
                            <td>
                                @php($statusLabels = ['Pending', 'Contacted', 'Completed', 'Cancelled'])
                                <span class="badge text-bg-secondary">{{ $statusLabels[$item->status] ?? 'Unknown' }}</span>
                            </td>
                            <td>{{ $item->created_at->format('M j, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No demo requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
