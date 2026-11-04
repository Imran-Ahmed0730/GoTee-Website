@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top' => '',
    default => 'dropdown-menu-end',
};

$widthClass = match ($width) {
    '48', '12rem' => 'dropdown-menu-width-48',
    '64', '16rem' => 'dropdown-menu-width-64',
    '72', '18rem' => 'dropdown-menu-width-72',
    '80', '20rem' => 'dropdown-menu-width-80',
    default => 'dropdown-menu-width-48',
};
@endphp

<div class="dropdown position-relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-cloak x-show="open" class="dropdown-menu {{ $alignmentClasses }} {{ $contentClasses }} {{ $widthClass }} shadow" @click="open = false">
            {{ $content }}
    </div>
</div>
