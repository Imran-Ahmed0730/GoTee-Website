@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'modal-sm',
    'lg' => 'modal-lg',
    'xl', '2xl' => 'modal-xl',
    default => '',
};
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            const selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)].filter(element => ! element.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0]; },
        lastFocusable() { return this.focusables().slice(-1)[0]; },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; },
    }"
    x-init="$watch('show', value => {
        document.body.classList.toggle('modal-open', value);
        if (value && {{ $attributes->has('focusable') ? 'true' : 'false' }}) {
            setTimeout(() => firstFocusable()?.focus(), 100);
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable()?.focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable()?.focus()"
>
    <div x-show="show" class="modal-backdrop fade show" @click="show = false"></div>
    <div x-show="show" class="modal fade" :class="{ 'show d-block': show }" role="dialog" aria-modal="true" aria-labelledby="{{ $name }}-title">
        <div class="modal-dialog modal-dialog-centered {{ $maxWidthClass }}">
            <div class="modal-content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
