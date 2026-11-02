@extends('admin.master')

@section('title', 'Engineering overview')

@push('css')
    <style>
        .admin-hero { background: linear-gradient(118deg, #14231e 0%, #1b332b 62%, #254337 100%); border-radius: 1.25rem; color: #fff; overflow: hidden; padding: 2rem; position: relative; }
        .admin-hero::after { background-image: linear-gradient(rgb(255 255 255 / 5%) 1px, transparent 1px), linear-gradient(90deg, rgb(255 255 255 / 5%) 1px, transparent 1px); background-size: 32px 32px; content: ""; inset: 0 0 0 45%; mask-image: linear-gradient(90deg, transparent, #000); pointer-events: none; position: absolute; }
        .admin-hero-content { position: relative; z-index: 1; }
        .admin-hero-kicker { color: var(--app-green); font-size: .6875rem; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; }
        .admin-hero-copy { color: rgb(255 255 255 / 65%); max-width: 35rem; }
        .admin-live-pill, .admin-monitoring-pill { align-items: center; border-radius: 999px; display: inline-flex; gap: .5rem; padding: .5rem .75rem; }
        .admin-live-pill { background: rgb(255 255 255 / 8%); border: 1px solid rgb(255 255 255 / 12%); color: #fff; }
        .admin-monitoring-pill { background: #f0f5e9; color: #5e7848; }
        .admin-live-dot { background: var(--app-green); border-radius: 50%; box-shadow: 0 0 0 .25rem rgb(196 240 107 / 14%); height: .5rem; width: .5rem; }
        .admin-hero-stat { border-left: 1px solid rgb(255 255 255 / 16%); padding-left: 1rem; }
        .admin-hero-stat-value { color: var(--app-green); font-size: 1.5rem; font-weight: 600; }
        .admin-hero-stat-label { color: rgb(255 255 255 / 56%); font-size: .75rem; }
        .admin-subtle { color: #929a94; }
        .admin-card-title { font-size: 1rem; font-weight: 600; }
        .admin-metric-value { font-size: 1.75rem; font-weight: 600; letter-spacing: -.04em; }
        .admin-icon-tile { align-items: center; border-radius: .75rem; display: inline-flex; height: 2.25rem; justify-content: center; width: 2.25rem; }
        .admin-icon-tile svg { height: 1.125rem; width: 1.125rem; }
        .admin-icon-green { background: #f0f5e9; color: #6b8c4c; }
        .admin-icon-orange { background: #fdf2e9; color: #ca8753; }
        .admin-icon-purple { background: #eff1fb; color: #7479b4; }
        .admin-icon-pink { background: #faedf1; color: #bd728a; }
        .admin-positive { color: #65864b; font-size: .75rem; font-weight: 600; }
        .admin-demo-badge { background: #f0f3e9; color: #647b4f; font-size: .625rem; letter-spacing: .12em; }
        .admin-period { background: #fff; border: 1px solid #e6eae3; border-radius: .75rem; color: #515d55; padding: .625rem .875rem; }
        .admin-chart { height: 196px; }
        .admin-chart-grid { background: repeating-linear-gradient(to bottom, transparent 0, transparent calc(25% - 1px), #edf0eb calc(25% - 1px), #edf0eb 25%); bottom: 1.5rem; left: 0; position: absolute; right: 0; top: 0; }
        .admin-chart-bar { background: #e6efd9; border-radius: .375rem .375rem 0 0; display: block; width: 100%; }
        .admin-chart-bar.current { background: #9abd69; }
        .admin-portfolio-progress { background: #edf1e9; border-radius: 999px; height: .5rem; overflow: hidden; }
        .admin-portfolio-progress span { background: #92b668; border-radius: inherit; display: block; height: 100%; }
        .admin-fleet-row + .admin-fleet-row { border-top: 1px solid #eff1ed; }
        .admin-fleet-icon { align-items: center; background: #f0f5e9; border-radius: .75rem; color: #6b8c4c; display: inline-flex; flex: 0 0 2.5rem; height: 2.5rem; justify-content: center; }
        .admin-fleet-icon svg { height: 1.25rem; width: 1.25rem; }
        .admin-service-icon { align-items: center; border-radius: .75rem; display: inline-flex; flex: 0 0 3rem; height: 3rem; justify-content: center; }
        .admin-service-icon svg { height: 1.75rem; width: 1.75rem; }
        .admin-service-one { background: #f0f5e9; color: #6b8c4c; }
        .admin-service-two { background: #eff1fb; color: #7479b4; }
        .admin-service-three { background: #e9edf0; color: #57636c; }
        .admin-table-heading { background: #fafbf9; color: #929a94; font-size: .625rem; letter-spacing: .12em; }
        .admin-status { font-size: .75rem; font-weight: 500; }
        .admin-status-processing { background: #fdf3e3; color: #a87a31; }
        .admin-status-shipped, .admin-status-delivered { background: #eaf4e8; color: #628251; }

        @media (max-width: 575.98px) {
            .admin-metric-value { font-size: 1.6rem; }
        }
    </style>
@endpush

@section('content')
    <section class="admin-hero mb-4" aria-labelledby="dashboard-title">
        <div class="admin-hero-content row align-items-center g-4">
            <div class="col-12 col-xl-7">
                <div class="admin-hero-kicker mb-2">GoTee engineering console</div>
                <h1 id="dashboard-title" class="display-6 fw-semibold mb-2">Good morning, {{ auth()->user()->name }}</h1>
                <p class="admin-hero-copy mb-3">One view across the software, embedded hardware, IoT deployments, and production systems we build and support.</p>
                <span class="admin-live-pill small"><span class="admin-live-dot"></span> Operations snapshot <span class="text-white-50">·</span> {{ now()->format('M j, Y') }}</span>
            </div>
            <div class="col-12 col-xl-5">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="admin-hero-stat">
                            <div class="admin-hero-stat-value">24</div>
                            <div class="admin-hero-stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="admin-hero-stat">
                            <div class="admin-hero-stat-value">486</div>
                            <div class="admin-hero-stat-label">Devices online</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="admin-hero-stat">
                            <div class="admin-hero-stat-value">98.6%</div>
                            <div class="admin-hero-stat-label">System uptime</div>
                        </div>
                    </div>
                </div>
                <div class="admin-hero-stat mt-4">
                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-white-50">Fleet connectivity</span>
                        <span class="text-white">486 / 512 devices</span>
                    </div>
                    <div class="progress bg-white bg-opacity-10" style="height: 6px" role="progressbar" aria-label="Fleet connectivity" aria-valuenow="94.9" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: 94.9%; background: var(--app-green)"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
        <div>
            <h2 class="h5 fw-semibold mb-1">Operations at a glance</h2>
            <p class="admin-muted small mb-0">Key delivery and service indicators <span class="badge rounded-pill admin-demo-badge ms-1">Demo data</span></p>
        </div>
        <span class="admin-muted small">{{ now()->format('F Y') }}</span>
    </div>

    <section class="row g-3 g-xl-4 mb-4" aria-label="Company operations">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="admin-card h-100 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-secondary small fw-medium">Active projects</span>
                    <span class="admin-icon-tile admin-icon-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6m-6 4h8"/></svg>
                    </span>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-3">
                    <span class="admin-metric-value">24</span>
                    <span class="admin-positive">+3 this month</span>
                </div>
                <p class="admin-subtle small mb-0">Across 18 active client accounts</p>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="admin-card h-100 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-secondary small fw-medium">In delivery</span>
                    <span class="admin-icon-tile admin-icon-orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 19V5m0 14h16M8 16V9m4 7V5m4 11v-4m4 4V7"/></svg>
                    </span>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-3">
                    <span class="admin-metric-value">7</span>
                    <span class="admin-positive">2 milestones due</span>
                </div>
                <p class="admin-subtle small mb-0">Projects in build, test, or deployment</p>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="admin-card h-100 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-secondary small fw-medium">IoT devices online</span>
                    <span class="admin-icon-tile admin-icon-purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="7" y="7" width="10" height="10" rx="2"/><path d="M9 2v3m6-3v3M9 19v3m6-3v3M2 9h3m-3 6h3m14-6h3m-3 6h3M10 10h4v4h-4z"/></svg>
                    </span>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-3">
                    <span class="admin-metric-value">486<span class="fs-6 fw-normal text-secondary">/512</span></span>
                    <span class="admin-positive">94.9%</span>
                </div>
                <p class="admin-subtle small mb-0">26 devices require a connection check</p>
            </article>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="admin-card h-100 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <span class="text-secondary small fw-medium">Production uptime</span>
                    <span class="admin-icon-tile admin-icon-pink">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 12h4l3-8 4 16 3-8h4"/><path d="M3 5v14h18"/></svg>
                    </span>
                </div>
                <div class="d-flex align-items-baseline gap-2 mt-3">
                    <span class="admin-metric-value">98.6%</span>
                    <span class="admin-positive">+0.4%</span>
                </div>
                <p class="admin-subtle small mb-0">Across monitored production systems</p>
            </article>
        </div>
    </section>

    <section class="row g-3 g-xl-4 mb-4" aria-label="Project portfolio and IoT fleet">
        <div class="col-12 col-xl-7">
            <article class="admin-card h-100 p-4">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h2 id="project-portfolio-title" class="admin-card-title mb-1">Project portfolio</h2>
                        <p class="admin-subtle small mb-0">Active work by engineering discipline</p>
                    </div>
                    <span class="badge rounded-pill admin-demo-badge px-3 py-2">24 total</span>
                </div>
                <div class="d-flex flex-column gap-4 mt-4">
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">Software &amp; production systems</span>
                            <span class="admin-subtle">9 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="Software and production system projects" aria-valuenow="37.5" aria-valuemin="0" aria-valuemax="100"><span style="width: 37.5%"></span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">IoT &amp; connected devices</span>
                            <span class="admin-subtle">6 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="IoT projects" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span style="width: 25%"></span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">Embedded systems</span>
                            <span class="admin-subtle">5 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="Embedded systems projects" aria-valuenow="20.8" aria-valuemin="0" aria-valuemax="100"><span style="width: 20.8%"></span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">Hardware design &amp; integration</span>
                            <span class="admin-subtle">4 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="Hardware projects" aria-valuenow="16.7" aria-valuemin="0" aria-valuemax="100"><span style="width: 16.7%"></span></div>
                    </div>
                </div>
                <div class="admin-subtle border-top mt-4 pt-3" style="font-size: .75rem">Project mix spans connected hardware through production-ready software.</div>
            </article>
        </div>

        <div class="col-12 col-xl-5">
            <article class="admin-card h-100 p-4">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h2 id="connected-fleet-title" class="admin-card-title mb-1">Connected fleet</h2>
                        <p class="admin-subtle small mb-0">Device status across client deployments</p>
                    </div>
                    <span class="admin-monitoring-pill small"><span class="admin-live-dot"></span> Monitoring</span>
                </div>
                <div class="mt-3">
                    <div class="admin-fleet-row d-flex align-items-center gap-3 py-3">
                        <span class="admin-fleet-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 21V8l9-5 9 5v13M3 10h18M8 21v-6h8v6m-4-12v2"/></svg>
                        </span>
                        <div class="admin-min-width-0 flex-grow-1">
                            <div class="small fw-medium">Northstar plant</div>
                            <div class="admin-subtle" style="font-size: .75rem">Production monitoring</div>
                        </div>
                        <div class="text-end">
                            <div class="small fw-semibold">218 / 220</div>
                            <div class="admin-positive">99.1% online</div>
                        </div>
                    </div>
                    <div class="admin-fleet-row d-flex align-items-center gap-3 py-3">
                        <span class="admin-fleet-icon" style="background: #eff1fb; color: #7479b4">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M5 18V8l7-4 7 4v10l-7 4-7-4Z"/><path d="m5 8 7 4 7-4m-7 4v10m-3-12 6-4"/></svg>
                        </span>
                        <div class="admin-min-width-0 flex-grow-1">
                            <div class="small fw-medium">FreshRoute cold chain</div>
                            <div class="admin-subtle" style="font-size: .75rem">Temperature sensors</div>
                        </div>
                        <div class="text-end">
                            <div class="small fw-semibold">164 / 176</div>
                            <div class="admin-positive">93.2% online</div>
                        </div>
                    </div>
                    <div class="admin-fleet-row d-flex align-items-center gap-3 py-3">
                        <span class="admin-fleet-icon" style="background: #fdf2e9; color: #ca8753">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="7" y="7" width="10" height="10" rx="2"/><path d="M9 2v3m6-3v3M9 19v3m6-3v3M2 9h3m-3 6h3m14-6h3m-3 6h3"/></svg>
                        </span>
                        <div class="admin-min-width-0 flex-grow-1">
                            <div class="small fw-medium">Apex machine network</div>
                            <div class="admin-subtle" style="font-size: .75rem">Edge gateways &amp; controllers</div>
                        </div>
                        <div class="text-end">
                            <div class="small fw-semibold">104 / 116</div>
                            <div class="admin-positive">89.7% online</div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <section class="admin-card" aria-labelledby="active-projects-title">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-4">
            <div>
                <h2 id="delivery-tracker-title" class="admin-card-title mb-1">Delivery tracker</h2>
                <p class="admin-subtle small mb-0">Project milestones across software and hardware engagements</p>
            </div>
            <span class="badge rounded-pill admin-demo-badge px-3 py-2">2 milestones due soon</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="admin-table-heading text-uppercase">
                        <th class="px-4 py-3 fw-semibold ps-lg-4">Project</th>
                        <th class="px-3 py-3 fw-semibold">Client</th>
                        <th class="px-3 py-3 fw-semibold">Discipline</th>
                        <th class="px-3 py-3 fw-semibold">Delivery status</th>
                        <th class="px-4 py-3 fw-semibold text-end pe-lg-4">Next milestone</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <tr>
                        <td class="px-4 py-3 fw-medium ps-lg-4 text-nowrap">Production control system</td>
                        <td class="px-3 py-3 text-nowrap text-secondary">Northstar Manufacturing</td>
                        <td class="px-3 py-3 text-nowrap admin-subtle">Production software</td>
                        <td class="px-3 py-3"><span class="badge rounded-pill admin-status admin-status-processing">Integration</span></td>
                        <td class="px-4 py-3 text-end fw-medium pe-lg-4 text-nowrap">Nov 18, 2026</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 fw-medium ps-lg-4 text-nowrap">Cold-chain sensor network</td>
                        <td class="px-3 py-3 text-nowrap text-secondary">FreshRoute Logistics</td>
                        <td class="px-3 py-3 text-nowrap admin-subtle">IoT &amp; embedded</td>
                        <td class="px-3 py-3"><span class="badge rounded-pill admin-status admin-status-shipped">Field testing</span></td>
                        <td class="px-4 py-3 text-end fw-medium pe-lg-4 text-nowrap">Nov 24, 2026</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 fw-medium ps-lg-4 text-nowrap">Machine monitoring gateway</td>
                        <td class="px-3 py-3 text-nowrap text-secondary">Apex Components</td>
                        <td class="px-3 py-3 text-nowrap admin-subtle">Hardware &amp; firmware</td>
                        <td class="px-3 py-3"><span class="badge rounded-pill admin-status admin-status-processing">Prototype</span></td>
                        <td class="px-4 py-3 text-end fw-medium pe-lg-4 text-nowrap">Dec 02, 2026</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 fw-medium ps-lg-4 text-nowrap">Operations planning platform</td>
                        <td class="px-3 py-3 text-nowrap text-secondary">Meridian Works</td>
                        <td class="px-3 py-3 text-nowrap admin-subtle">Custom software</td>
                        <td class="px-3 py-3"><span class="badge rounded-pill admin-status admin-status-delivered">On track</span></td>
                        <td class="px-4 py-3 text-end fw-medium pe-lg-4 text-nowrap">Dec 12, 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
