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
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-5">{{ $title }}</h3>
    @endif

    <div @class([
        'flex items-center w-full gap-6',
        'flex-col' => $legendPosition === 'bottom',
        'flex-row' => $legendPosition === 'right',
    ])>
        <!-- Chart Area -->
        <div class="relative {{ $height }} w-auto flex-shrink-0 flex justify-center items-center">
             <svg class="h-full w-auto" viewBox="0 0 100 100">
                @foreach($slices as $slice)
                    <path 
                        d="{{ $slice['path'] }}" 
                        fill="{{ $slice['color_is_css'] ? $slice['color'] : 'currentColor' }}"
                        class="{{ $slice['color_is_tailwind_class'] ? $slice['color'] . ' hover:opacity-80' : (!$slice['color_is_css'] ? 'text-' . $slice['color'] . '-500 hover:text-' . $slice['color'] . '-400' : 'hover:opacity-80') }} transition-colors duration-200 cursor-pointer stroke-white dark:stroke-gray-800"
                        stroke-width="0.5"
                    >
                        <title>{{ $slice['label'] }}: {{ $slice['formatted_value'] }} ({{ $slice['percent'] }}%)</title>
                    </path>
                @endforeach
                
                @if($showLabels)
                    <!-- Labels on chart slices -->
                    @foreach($slices as $slice)
                        @if($slice['percent'] > 5) <!-- Only show if slice is big enough -->
                        <text 
                            x="{{ $slice['label_x'] }}" 
                            y="{{ $slice['label_y'] }}" 
                            text-anchor="middle" 
                            dominant-baseline="middle" 
                            class="fill-white text-[3px] font-bold pointer-events-none select-none"
                            style="text-shadow: 0px 0px 2px rgba(0,0,0,0.5);"
                        >
                            {{ $slice['percent'] }}%
                        </text>
                        @endif
                    @endforeach
                @endif
             </svg>
        </div>

        <!-- Legend -->
        @if($legendPosition !== 'none' && $legendPosition !== 'hidden')
            <div @class([
                'flex gap-4 text-xs overflow-y-auto max-h-64 pr-2',
                'flex-col w-64' => $legendPosition === 'right',
                'flex-row flex-wrap justify-center w-full' => $legendPosition === 'bottom',
            ])>
                @foreach($slices as $slice)
                    <div class="flex items-center gap-2 group cursor-default">
                        <div class="w-3 h-3 rounded-full {{ $slice['color_is_tailwind_class'] ? $slice['color'] : (!$slice['color_is_css'] ? 'bg-' . $slice['color'] . '-500' : '') }} group-hover:scale-110 transition-transform flex-shrink-0"
                             style="{{ $slice['color_is_css'] ? 'background-color: ' . $slice['color'] . ';' : '' }}"
                        ></div>
                        <div class="flex justify-between items-baseline gap-2">
                            <span class="text-gray-600 dark:text-gray-300 truncate">{{ $slice['label'] }}</span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-gray-900 dark:text-gray-100">{{ $slice['formatted_value'] }}</span>
                                <span class="text-gray-400 dark:text-gray-500 scale-90">({{ $slice['percent'] }}%)</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
