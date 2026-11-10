@extends('admin.master')

@section('title', 'Engineering overview')

@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item">
                <span aria-current="page"><i class="fa-solid fa-house" aria-hidden="true"></i><span>Dashboard</span></span>
            </li>
        </ol>
    </nav>
    <section class="admin-hero mb-4" aria-labelledby="dashboard-title">
        <div class="admin-hero-content row align-items-center g-4">
            <div class="col-12 col-xl-7">
                <div class="admin-hero-kicker mb-2">GoTee engineering console</div>
                <h1 id="dashboard-title" class="display-6 fw-semibold mb-2">Good morning, {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h1>
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
                    <div class="progress admin-connectivity-progress bg-white bg-opacity-10" role="progressbar" aria-label="Fleet connectivity" aria-valuenow="94.9" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar admin-connectivity-fill"></div>
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
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="Software and production system projects" aria-valuenow="37.5" aria-valuemin="0" aria-valuemax="100"><span class="admin-portfolio-progress-software"></span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">IoT &amp; connected devices</span>
                            <span class="admin-subtle">6 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="IoT projects" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="admin-portfolio-progress-iot"></span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">Embedded systems</span>
                            <span class="admin-subtle">5 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="Embedded systems projects" aria-valuenow="20.8" aria-valuemin="0" aria-valuemax="100"><span class="admin-portfolio-progress-embedded"></span></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="fw-medium">Hardware design &amp; integration</span>
                            <span class="admin-subtle">4 projects</span>
                        </div>
                        <div class="admin-portfolio-progress" role="progressbar" aria-label="Hardware projects" aria-valuenow="16.7" aria-valuemin="0" aria-valuemax="100"><span class="admin-portfolio-progress-hardware"></span></div>
                    </div>
                </div>
                <div class="admin-subtle admin-dashboard-note border-top mt-4 pt-3">Project mix spans connected hardware through production-ready software.</div>
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
                            <div class="admin-subtle admin-fleet-description">Production monitoring</div>
                        </div>
                        <div class="text-end">
                            <div class="small fw-semibold">218 / 220</div>
                            <div class="admin-positive">99.1% online</div>
                        </div>
                    </div>
                    <div class="admin-fleet-row d-flex align-items-center gap-3 py-3">
                        <span class="admin-fleet-icon admin-fleet-icon-cold-chain">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M5 18V8l7-4 7 4v10l-7 4-7-4Z"/><path d="m5 8 7 4 7-4m-7 4v10m-3-12 6-4"/></svg>
                        </span>
                        <div class="admin-min-width-0 flex-grow-1">
                            <div class="small fw-medium">FreshRoute cold chain</div>
                            <div class="admin-subtle admin-fleet-description">Temperature sensors</div>
                        </div>
                        <div class="text-end">
                            <div class="small fw-semibold">164 / 176</div>
                            <div class="admin-positive">93.2% online</div>
                        </div>
                    </div>
                    <div class="admin-fleet-row d-flex align-items-center gap-3 py-3">
                        <span class="admin-fleet-icon admin-fleet-icon-gateway">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="7" y="7" width="10" height="10" rx="2"/><path d="M9 2v3m6-3v3M9 19v3m6-3v3M2 9h3m-3 6h3m14-6h3m-3 6h3"/></svg>
                        </span>
                        <div class="admin-min-width-0 flex-grow-1">
                            <div class="small fw-medium">Apex machine network</div>
                            <div class="admin-subtle admin-fleet-description">Edge gateways &amp; controllers</div>
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
