@extends('admin.master')
@section('title', 'Testimonials')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-quote-left" aria-hidden="true"></i><span>Testimonials</span></span></li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Testimonials</h1>
            <p class="text-secondary mb-0">Manage client feedback and ratings.</p>
        </div>
        @can('Testimonial Add')
            <a href="{{ route('admin.testimonial.create') }}" class="btn btn-primary"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Add testimonial</a>
        @endcan
    </div>

    <section class="admin-card p-3 p-md-4" aria-label="Testimonial list">
        <form action="{{ route('admin.testimonial.index') }}" method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Search testimonials</label>
                <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Company, person, designation, or comment" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Visibility</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Show and hidden</option>
                    <option value="1" @selected(($filters['status'] ?? '') === '1')>Show</option>
                    <option value="0" @selected(($filters['status'] ?? '') === '0')>Hidden</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
                <a href="{{ route('admin.testimonial.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-borderless table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Client / Company</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Visibility</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $key => $item)
                        <tr>
                            <th scope="row">{{ $items->firstItem() + $key }}</th>
                            <td>
                                @if ($item->image && is_file(public_path($item->image)))
                                    <img src="{{ asset($item->image) }}" alt="" width="42" height="42" class="rounded-circle me-2 object-fit-cover">
                                @endif
                                {{ $item->client_name }}
                            </td>
                            <td>{{ $item->first_name }} {{ $item->last_name }}<br><small class="text-muted">{{ $item->designation }}</small></td>
                            <td>{{ \Illuminate\Support\Str::limit($item->comment, 100) }}</td>
                            <td>{{ $item->rating }} / 5</td>
                            <td>
                                @can('Testimonial Status Change')
                                    <form action="{{ route('admin.testimonial.status', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="0">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" aria-label="Show {{ $item->client_name }} testimonial" @checked($item->getRawOriginal('status') == 1) onchange="this.form.submit()">
                                        </div>
                                    </form>
                                @else
                                    <span class="badge text-bg-{{ $item->getRawOriginal('status') == 1 ? 'success' : 'secondary' }}">{{ $item->status }}</span>
                                @endcan
                            </td>
                            <td class="text-end">
                                <div class="admin-table-actions d-inline-flex flex-wrap justify-content-end gap-2">
                                    @can('Testimonial Update')
                                        <a href="{{ route('admin.testimonial.edit', $item) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $item->client_name }} testimonial"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('Testimonial Delete')
                                        <form action="{{ route('admin.testimonial.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-delete" aria-label="Delete {{ $item->client_name }} testimonial"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No testimonials match this selection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
    </section>
@endsection
