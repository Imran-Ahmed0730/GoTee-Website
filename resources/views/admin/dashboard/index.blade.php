@extends('admin.master')

@section('title', 'Overview')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-2xl font-semibold tracking-tight text-[#18231f] sm:text-[30px]">Good morning, {{ auth()->user()->name }}</h1>
                <span class="rounded-full bg-[#f0f3e9] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-[#647b4f]">Demo data</span>
            </div>
            <p class="mt-2 text-sm text-[#808a83]">Here’s what’s happening with your store today.</p>
        </div>
        <div class="flex w-fit items-center gap-2 rounded-xl border border-[#e6eae3] bg-white px-3.5 py-2.5 text-sm font-medium text-[#515d55]">
            <svg class="h-4 w-4 text-[#89928c]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3.5" y="5" width="17" height="16" rx="2"/><path d="M7.5 3v4m9-4v4M3.5 10h17"/></svg>
            {{ now()->format('F Y') }}
            <svg class="ml-1 h-4 w-4 text-[#89928c]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </div>
    </div>

    <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Store performance">
        <article class="rounded-2xl border border-[#e9ece6] bg-white p-5 sm:p-6">
            <div class="flex items-start justify-between">
                <span class="text-sm font-medium text-[#737e76]">Gross sales</span>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#f0f5e9] text-[#6b8c4c]">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M12 2v20m5-15H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-[28px] font-semibold tracking-tight">$48,290</span>
                <span class="text-xs font-semibold text-[#65864b]">+12.8%</span>
            </div>
            <p class="mt-1 text-xs text-[#9aa19c]">vs. previous month</p>
        </article>
        <article class="rounded-2xl border border-[#e9ece6] bg-white p-5 sm:p-6">
            <div class="flex items-start justify-between">
                <span class="text-sm font-medium text-[#737e76]">Orders</span>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#fdf2e9] text-[#ca8753]">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 7.5h16v13H4z"/><path d="M8 7.5V5h8v2.5M4 12h16"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-[28px] font-semibold tracking-tight">1,284</span>
                <span class="text-xs font-semibold text-[#65864b]">+8.2%</span>
            </div>
            <p class="mt-1 text-xs text-[#9aa19c]">vs. previous month</p>
        </article>
        <article class="rounded-2xl border border-[#e9ece6] bg-white p-5 sm:p-6">
            <div class="flex items-start justify-between">
                <span class="text-sm font-medium text-[#737e76]">Average order value</span>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#eff1fb] text-[#7479b4]">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 18V6m0 12h16M8 14l3-3 3 2 5-6"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-[28px] font-semibold tracking-tight">$37.61</span>
                <span class="text-xs font-semibold text-[#65864b]">+4.1%</span>
            </div>
            <p class="mt-1 text-xs text-[#9aa19c]">vs. previous month</p>
        </article>
        <article class="rounded-2xl border border-[#e9ece6] bg-white p-5 sm:p-6">
            <div class="flex items-start justify-between">
                <span class="text-sm font-medium text-[#737e76]">Returning customers</span>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#faedf1] text-[#bd728a]">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M3 20v-1.5A4.5 4.5 0 0 1 7.5 14h3a4.5 4.5 0 0 1 4.5 4.5V20m.2-12a3.5 3.5 0 0 1 2.8 5.5"/></svg>
                </span>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-[28px] font-semibold tracking-tight">32.8%</span>
                <span class="text-xs font-semibold text-[#65864b]">+2.6%</span>
            </div>
            <p class="mt-1 text-xs text-[#9aa19c]">vs. previous month</p>
        </article>
    </section>

    <section class="mt-5 grid gap-5 xl:grid-cols-[minmax(0,1.7fr)_minmax(280px,0.9fr)]" aria-label="Sales and products">
        <article class="rounded-2xl border border-[#e9ece6] bg-white p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold">Sales overview</h2>
                    <p class="mt-1 text-xs text-[#929a94]">Your revenue performance this week</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-[#737e76]">
                    <span class="h-2 w-2 rounded-full bg-[#97bd61]"></span>
                    Revenue
                </div>
            </div>
            <div class="mt-7 grid grid-cols-[32px_1fr] gap-3">
                <div class="flex h-[196px] flex-col justify-between pb-6 text-[10px] text-[#a2aaa4]" aria-hidden="true">
                    <span>$8k</span><span>$6k</span><span>$4k</span><span>$2k</span><span>$0</span>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 mb-6 flex flex-col justify-between" aria-hidden="true">
                        <span class="border-t border-dashed border-[#edf0eb]"></span>
                        <span class="border-t border-dashed border-[#edf0eb]"></span>
                        <span class="border-t border-dashed border-[#edf0eb]"></span>
                        <span class="border-t border-dashed border-[#edf0eb]"></span>
                        <span class="border-t border-dashed border-[#edf0eb]"></span>
                    </div>
                    <div class="relative flex h-[196px] items-end justify-around gap-3 px-2">
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#e6efd9]" style="height: 48%"></span></div>
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#e6efd9]" style="height: 67%"></span></div>
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#e6efd9]" style="height: 57%"></span></div>
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#e6efd9]" style="height: 82%"></span></div>
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#e6efd9]" style="height: 63%"></span></div>
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#e6efd9]" style="height: 94%"></span></div>
                        <div class="flex h-full flex-1 items-end"><span class="w-full rounded-t-md bg-[#9abd69]" style="height: 76%"></span></div>
                    </div>
                    <div class="mt-3 flex justify-around text-[10px] text-[#9aa19c]">
                        <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                    </div>
                </div>
            </div>
        </article>

        <article class="rounded-2xl border border-[#e9ece6] bg-white p-5 sm:p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-base font-semibold">Top products</h2>
                    <p class="mt-1 text-xs text-[#929a94]">Best sellers this month</p>
                </div>
                <span class="rounded-lg bg-[#f5f7f2] px-2.5 py-1.5 text-[11px] font-medium text-[#75806f]">Top 3</span>
            </div>
            <div class="mt-5 flex flex-col divide-y divide-[#eff1ed]">
                <div class="flex items-center gap-3 py-3 first:pt-0">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-[#f2f0eb] text-[#64665a]">
                        <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none" aria-hidden="true"><path d="m13 8 7 3 7-3 7 5-4 6-4-2v15H14V17l-4 2-4-6 7-5Z" fill="currentColor" opacity=".8"/><path d="m13 8 7 3 7-3" stroke="white" stroke-width="1.5"/></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">Everyday Cotton Tee</p>
                        <p class="mt-1 text-xs text-[#929a94]">142 sold</p>
                    </div>
                    <span class="text-sm font-semibold">$2,840</span>
                </div>
                <div class="flex items-center gap-3 py-3">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-[#e9edf0] text-[#57636c]">
                        <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none" aria-hidden="true"><path d="m13 8 7 3 7-3 7 5-4 6-4-2v15H14V17l-4 2-4-6 7-5Z" fill="currentColor" opacity=".8"/><path d="m13 8 7 3 7-3" stroke="white" stroke-width="1.5"/></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">Heavyweight Crewneck</p>
                        <p class="mt-1 text-xs text-[#929a94]">86 sold</p>
                    </div>
                    <span class="text-sm font-semibold">$2,408</span>
                </div>
                <div class="flex items-center gap-3 py-3 last:pb-0">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-[#f4ece6] text-[#936e59]">
                        <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none" aria-hidden="true"><path d="m13 8 7 3 7-3 7 5-4 6-4-2v15H14V17l-4 2-4-6 7-5Z" fill="currentColor" opacity=".8"/><path d="m13 8 7 3 7-3" stroke="white" stroke-width="1.5"/></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">Weekend Tote</p>
                        <p class="mt-1 text-xs text-[#929a94]">64 sold</p>
                    </div>
                    <span class="text-sm font-semibold">$1,216</span>
                </div>
            </div>
        </article>
    </section>

    <section class="mt-5 rounded-2xl border border-[#e9ece6] bg-white" aria-labelledby="recent-orders-title">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-5 sm:px-6">
            <div>
                <h2 id="recent-orders-title" class="text-base font-semibold">Recent orders</h2>
                <p class="mt-1 text-xs text-[#929a94]">A quick look at your latest sales</p>
            </div>
            <span class="rounded-full bg-[#f0f3e9] px-3 py-1.5 text-xs font-medium text-[#647b4f]">8 need fulfillment</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left">
                <thead>
                    <tr class="border-y border-[#eff1ed] bg-[#fafbf9] text-[10px] font-semibold uppercase tracking-[0.12em] text-[#929a94]">
                        <th class="px-6 py-3 font-semibold">Order</th>
                        <th class="px-4 py-3 font-semibold">Customer</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#eff1ed] text-sm">
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 font-medium">#GT-1048</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#657168]">Olivia Martin</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#929a94]">Today, 10:42 am</td>
                        <td class="whitespace-nowrap px-4 py-4"><span class="rounded-full bg-[#fdf3e3] px-2.5 py-1 text-xs font-medium text-[#a87a31]">Processing</span></td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-medium">$84.00</td>
                    </tr>
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 font-medium">#GT-1047</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#657168]">Noah Williams</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#929a94]">Today, 9:18 am</td>
                        <td class="whitespace-nowrap px-4 py-4"><span class="rounded-full bg-[#eaf4e8] px-2.5 py-1 text-xs font-medium text-[#628251]">Shipped</span></td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-medium">$42.50</td>
                    </tr>
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 font-medium">#GT-1046</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#657168]">Amara Johnson</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#929a94]">Yesterday, 4:32 pm</td>
                        <td class="whitespace-nowrap px-4 py-4"><span class="rounded-full bg-[#fdf3e3] px-2.5 py-1 text-xs font-medium text-[#a87a31]">Processing</span></td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-medium">$116.00</td>
                    </tr>
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 font-medium">#GT-1045</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#657168]">Ethan Chen</td>
                        <td class="whitespace-nowrap px-4 py-4 text-[#929a94]">Yesterday, 1:07 pm</td>
                        <td class="whitespace-nowrap px-4 py-4"><span class="rounded-full bg-[#eaf4e8] px-2.5 py-1 text-xs font-medium text-[#628251]">Delivered</span></td>
                        <td class="whitespace-nowrap px-6 py-4 text-right font-medium">$38.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
