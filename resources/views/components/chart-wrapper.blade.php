@props([
    'title' => null,
    'border' => true,
    'borderColor' => null,
    'backgroundColor' => null,
])

@php
    $bgColorIsCss = $backgroundColor && (str_starts_with($backgroundColor, '#') || str_starts_with($backgroundColor, 'rgb') || str_starts_with($backgroundColor, 'hsl'));
    $bgColorIsTailwindClass = $backgroundColor && (str_contains($backgroundColor, ' ') || str_starts_with($backgroundColor, 'bg-'));
    
    $borderColorIsCss = $borderColor && (str_starts_with($borderColor, '#') || str_starts_with($borderColor, 'rgb') || str_starts_with($borderColor, 'hsl'));
    $borderColorIsTailwindClass = $borderColor && (str_contains($borderColor, ' ') || str_starts_with($borderColor, 'border-'));
    $defaultBorderColor = 'border-gray-200 dark:border-gray-700';
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col p-4' . 
    ($bgColorIsTailwindClass ? ' ' . $backgroundColor : (!$bgColorIsCss && $backgroundColor ? ' bg-' . $backgroundColor : '')) .
    ($border ? ' border rounded-lg' : '') .
    ($border ? ($borderColorIsTailwindClass ? ' ' . $borderColor : (!$borderColorIsCss && $borderColor ? ' border-' . $borderColor : ' ' . $defaultBorderColor)) : '')
]) }}
     @if($bgColorIsCss) style="background-color: {{ $backgroundColor }}; {{ $border && $borderColorIsCss ? 'border-color: ' . $borderColor . ';' : '' }}" 
     @elseif($border && $borderColorIsCss) style="border-color: {{ $borderColor }};" 
     @endif>

    @if(isset($title) && $title instanceof \Illuminate\View\ComponentSlot)
        {{ $title }}
    @elseif($title)
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ $title }}</h3>
    @endif

    <div class="mt-5">
        {{ $slot }}
    </div>
</div>
