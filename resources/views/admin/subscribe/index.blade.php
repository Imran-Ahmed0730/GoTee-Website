@extends('admin.master')
@section('title', 'Subscribers')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-at" aria-hidden="true"></i><span>Subscribers</span></span></li>
        </ol>
    </nav>

    <div class="mb-4">
        <h1 class="h3 mb-1">Subscribers</h1>
        <p class="text-secondary mb-0">Manage newsletter subscribers.</p>
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Subscriber list">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Email</th>
                        <th scope="col">Subscribed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $loop->index }}</th>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->created_at->format('M j, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No subscribers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </section>
@endsection
