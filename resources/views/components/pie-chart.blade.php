<x-beartropy-charts::chart-wrapper 
    :title="$title"
    :border="$border"
    :borderColor="$borderColor"
    :backgroundColor="$backgroundColor"
    {{ $attributes }}
>
    <div @class([
        'flex items-center w-full gap-6',
        'flex-col' => $legendPosition === 'bottom',
        'flex-row' => $legendPosition === 'right',
    ])>
        <!-- Chart Area -->
        <div class="relative {{ $height }} w-auto flex-shrink-0 flex justify-center items-center">
             <svg class="h-full w-auto" viewBox="0 0 100 100">
                <!-- Shadow filter definition -->
                <defs>
                    <filter id="pie-slice-shadow" x="-50%" y="-50%" width="200%" height="200%">
                        <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.3"/>
                    </filter>
                </defs>
                
                @foreach($slices as $slice)
                    <g class="pie-slice-group">
                        <path 
                            d="{{ $slice['path'] }}" 
                            fill="{{ !$slice['color_is_tailwind_class'] ? $slice['color'] : 'currentColor' }}"
                            class="pie-slice {{ $slice['color_is_tailwind_class'] ? $slice['color'] : '' }} hover:opacity-80 transition-all duration-200 cursor-pointer stroke-white dark:stroke-gray-800 hover:scale-105 origin-center"
                            stroke-width="0"
                        >
                            <title>{{ $slice['label'] }}: {{ $slice['formatted_value'] }} ({{ $slice['percent'] }}%)</title>
                        </path>
                        
                        @if($showLabels && $slice['percent'] > 5)
                            <text 
                                x="{{ $slice['label_x'] }}" 
                                y="{{ $slice['label_y'] }}" 
                                text-anchor="middle" 
                                dominant-baseline="middle" 
                                class="pie-label fill-{{ $labelColor }} text-[5px] pointer-events-none select-none transition-all duration-200"
                                style="text-shadow: 0px 0px 2px rgba(0,0,0,0.5);"
                            >
                                {{ $slice['percent'] }}%
                            </text>
                        @endif
                    </g>
                @endforeach
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
                        <div class="w-3 h-3 rounded-full {{ $slice['color_is_tailwind_class'] ? $slice['color'] : '' }} group-hover:scale-110 transition-transform flex-shrink-0"
                             style="{{ !$slice['color_is_tailwind_class'] ? 'background-color: ' . $slice['color'] . ';' : '' }}"
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
    
    <style>
        .pie-slice {
            filter: none;
        }
        .pie-slice:hover {
            filter: url(#pie-slice-shadow);
        }
        .pie-slice-group:hover .pie-label {
            font-weight: 700;
        }
    </style>
</x-beartropy-charts::chart-wrapper>
