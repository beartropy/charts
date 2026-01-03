@props([
    'title' => null,
    'border' => true,
    'borderColor' => null,
    'backgroundColor' => null,
    'collapsible' => false,
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
     @endif
     @if($collapsible && ($title || isset($title))) x-data="{ collapsed: false }" @endif>

    @if(isset($title) && $title instanceof \Illuminate\View\ComponentSlot)
        @if($collapsible)
            <div @click="collapsed = !collapsed" class="cursor-pointer select-none">
                <div class="flex items-center justify-between">
                    {{ $title }}
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" 
                         :class="{ 'rotate-180': collapsed }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        @else
            {{ $title }}
        @endif
    @elseif($title)
        @if($collapsible)
            <div @click="collapsed = !collapsed" class="flex items-center justify-between cursor-pointer select-none" :class="{ 'mb-3': !collapsed }">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" 
                     :class="{ 'rotate-180': collapsed }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        @else
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ $title }}</h3>
        @endif
    @endif

    <div @if($collapsible && ($title || isset($title))) 
            :class="{ 'mt-5': !collapsed }"
            x-show="!collapsed"
            x-collapse
         @else
            class="mt-5"
         @endif
         class="transition-all duration-300">
        {{ $slot }}
    </div>
</div>
