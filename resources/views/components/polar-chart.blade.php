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
             <svg class="h-full w-auto" viewBox="0 0 120 120">
                <!-- Shadow filter definition -->
                <defs>
                    <filter id="polar-segment-shadow" x="-50%" y="-50%" width="200%" height="200%">
                        <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.3"/>
                    </filter>
                </defs>
                
                <!-- Grid circles -->
                @if($showGrid)
                    @for($i = 1; $i <= $gridLevels; $i++)
                        @php
                            $gridRadius = (40 / $gridLevels) * $i;
                        @endphp
                        <circle 
                            cx="60" 
                            cy="60" 
                            r="{{ $gridRadius }}" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="0.2" 
                            class="text-gray-200 dark:text-gray-700"
                        />
                    @endfor
                @endif
                
                <!-- Grid lines radiating from center -->
                @if($showGrid && !empty($segments))
                    @foreach($segments as $index => $segment)
                        @php
                            $anglePerSegment = 360 / count($segments);
                            $angle = $index * $anglePerSegment - 90;
                            $rad = deg2rad($angle);
                            $x = 60 + 40 * cos($rad);
                            $y = 60 + 40 * sin($rad);
                        @endphp
                        <line 
                            x1="60" 
                            y1="60" 
                            x2="{{ $x }}" 
                            y2="{{ $y }}" 
                            stroke="currentColor" 
                            stroke-width="0.2" 
                            class="text-gray-200 dark:text-gray-700"
                        />
                    @endforeach
                @endif
                
                <!-- Segments -->
                @foreach($segments as $segment)
                    <g class="polar-segment-group hover:scale-105 origin-center transition-all duration-200">
                        <path 
                            d="{{ $segment['path'] }}" 
                            fill="{{ !$segment['color_is_tailwind_class'] ? $segment['color'] : 'currentColor' }}"
                            class="polar-segment {{ $segment['color_is_tailwind_class'] ? $segment['color'] : '' }} hover:opacity-80 transition-all duration-200 cursor-pointer stroke-white dark:stroke-gray-800"
                            stroke-width="0.1"
                        >
                            <title>{{ $segment['label'] }}: {{ $segment['formatted_value'] }} ({{ $segment['percent'] }}%)</title>
                        </path>
                        
                        <!-- Value labels inside segments -->
                        @if($showLabels && $segment['percent'] > 10)
                            <text 
                                x="{{ $segment['value_x'] }}" 
                                y="{{ $segment['value_y'] }}" 
                                text-anchor="middle" 
                                dominant-baseline="middle" 
                                class="polar-value-label fill-{{ $labelColor }} text-[4px] pointer-events-none select-none transition-all duration-200"
                                style="text-shadow: 0px 0px 2px rgba(0,0,0,0.5);"
                            >
                                {{ $segment['formatted_value'] }}
                            </text>
                        @endif
                        
                        <!-- Category labels outside -->
                        <text 
                            x="{{ $segment['label_x'] }}" 
                            y="{{ $segment['label_y'] }}" 
                            text-anchor="middle" 
                            dominant-baseline="middle" 
                            class="polar-category-label fill-gray-600 dark:fill-gray-300 text-[3px] font-medium pointer-events-none select-none"
                        >
                            {{ $segment['label'] }}
                        </text>
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
                @foreach($segments as $segment)
                    <div class="flex items-center gap-2 group cursor-default">
                        <div class="w-3 h-3 rounded-full {{ $segment['color_is_tailwind_class'] ? $segment['color'] : '' }} group-hover:scale-110 transition-transform flex-shrink-0"
                             style="{{ !$segment['color_is_tailwind_class'] ? 'background-color: ' . $segment['color'] . ';' : '' }}"
                        ></div>
                        <div class="flex justify-between items-baseline gap-2">
                            <span class="text-gray-600 dark:text-gray-300 truncate">{{ $segment['label'] }}</span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-gray-900 dark:text-gray-100">{{ $segment['formatted_value'] }}</span>
                                <span class="text-gray-400 dark:text-gray-500 scale-90">({{ $segment['percent'] }}%)</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <style>
        .polar-segment {
            filter: none;
        }
        .polar-segment:hover {
            filter: url(#polar-segment-shadow);
        }
        .polar-segment-group:hover .polar-value-label {
            font-size: 4.3px;
            font-weight: 700;
        }
        .polar-segment-group:hover .polar-category-label {
            font-size: 4px;
            font-weight: 700;
        }
    </style>
</x-beartropy-charts::chart-wrapper>
