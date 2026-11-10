@extends('admin.master')
@section('title', 'Contact requests')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-envelope" aria-hidden="true"></i><span>Contact requests</span></span></li>
        </ol>
    </nav>

    <div class="mb-4">
        <h1 class="h3 mb-1">Contact requests</h1>
        <p class="text-secondary mb-0">Review messages sent through the contact form.</p>
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Contact request list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name and email</th>
                        <th scope="col">Company</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Message</th>
                        <th scope="col">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $loop->index }}</th>
                            <td>
                                <span class="fw-semibold">{{ trim($item->first_name.' '.$item->last_name) }}</span>
                                <a class="d-block small" href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                            </td>
                            <td>{{ $item->company }}</td>
                            <td>{{ $item->phone }}</td>
                            <td><div class="text-break" style="white-space: pre-wrap;">{{ $item->note }}</div></td>
                            <td>{{ $item->created_at->format('M j, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No contact requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
