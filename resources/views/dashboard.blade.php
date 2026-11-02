<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 fw-semibold mb-0">{{ __('Dashboard') }}</h1>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            {{ __("You're logged in!") }}
        </div>
    </div>
</x-app-layout>
