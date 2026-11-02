@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top' => '',
    default => 'dropdown-menu-end',
};

$minWidth = $width === '48' ? '12rem' : $width;
@endphp

<div class="dropdown position-relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open" class="dropdown-menu {{ $alignmentClasses }} {{ $contentClasses }} shadow"
        style="display: none; min-width: {{ $minWidth }};"
        @click="open = false">
            {{ $content }}
    </div>
</div>
