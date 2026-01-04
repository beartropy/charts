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
                    <filter id="radar-shadow" x="-50%" y="-50%" width="200%" height="200%">
                        <feDropShadow dx="0" dy="1" stdDeviation="2" flood-opacity="0.3"/>
                    </filter>
                </defs>
                
                <!-- Grid circles -->
                @if($showGrid)
                    @for($i = 1; $i <= $gridLevels; $i++)
                        @php
                            $gridRadius = (40 / $gridLevels) * $i;
                        @endphp
                        <circle 
                            cx="50" 
                            cy="50" 
                            r="{{ $gridRadius }}" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="0.15" 
                            class="text-gray-200 dark:text-gray-700"
                        />
                    @endfor
                @endif
                
                <!-- Axes lines -->
                @if($showAxes && !empty($axes))
                    @foreach($axes as $axis)
                        <line 
                            x1="50" 
                            y1="50" 
                            x2="{{ $axis['x'] }}" 
                            y2="{{ $axis['y'] }}" 
                            stroke="currentColor" 
                            stroke-width="0.2" 
                            class="text-gray-300 dark:text-gray-600"
                        />
                    @endforeach
                @endif
                
                <!-- Data polygons -->
                @foreach($datasets as $dataset)
                    <g class="radar-dataset-group">
                        <!-- Filled area -->
                        @if($fillArea)
                            <polygon 
                                points="{{ $dataset['polygon_points'] }}" 
                                fill="{{ $dataset['color_is_css'] ? $dataset['color'] : 'currentColor' }}"
                                class="{{ $dataset['color_is_tailwind_class'] ? $dataset['color'] : (!$dataset['color_is_css'] ? 'text-' . $dataset['color'] . '-500' : '') }} transition-all duration-200"
                                style="opacity: {{ $fillOpacity }};"
                            />
                        @endif
                        
                        <!-- Stroke outline -->
                        <polygon 
                            points="{{ $dataset['polygon_points'] }}" 
                            fill="none"
                            stroke="{{ $dataset['color_is_css'] ? $dataset['color'] : 'currentColor' }}"
                            class="radar-polygon {{ $dataset['color_is_tailwind_class'] ? $dataset['color'] : (!$dataset['color_is_css'] ? 'text-' . $dataset['color'] . '-500' : '') }} transition-all duration-200 cursor-pointer hover:stroke-[1.5]"
                            stroke-width="1"
                        >
                            <title>{{ $dataset['label'] }}</title>
                        </polygon>
                        
                        <!-- Data points -->
                        @foreach($dataset['points'] as $index => $point)
                            @php
                                $numAxes = count($axes);
                                $angle = ($index * 360 / $numAxes) - 90;
                                $rad = deg2rad($angle);
                                $normalizedValue = $point['value'] / $maxValue;
                                $radius = 40 * $normalizedValue;
                                $pointX = 50 + $radius * cos($rad);
                                $pointY = 50 + $radius * sin($rad);
                            @endphp
                            <circle 
                                cx="{{ $pointX }}" 
                                cy="{{ $pointY }}" 
                                r="1" 
                                fill="{{ $dataset['color_is_css'] ? $dataset['color'] : 'currentColor' }}"
                                class="radar-point {{ $dataset['color_is_tailwind_class'] ? $dataset['color'] : (!$dataset['color_is_css'] ? 'text-' . $dataset['color'] . '-600' : '') }} transition-all duration-200 hover:r-[1.5]"
                                stroke="white"
                                stroke-width="0.3"
                            >
                                <title>{{ $point['axis'] }}: {{ sprintf($formatValues, $point['value']) }}</title>
                            </circle>
                            
                            @if($showValues)
                                <text 
                                    x="{{ $pointX }}" 
                                    y="{{ $pointY - 3 }}" 
                                    text-anchor="middle" 
                                    dominant-baseline="middle" 
                                    class="radar-value-label fill-gray-700 dark:fill-gray-200 text-[3px] font-medium pointer-events-none select-none"
                                >
                                    {{ sprintf($formatValues, $point['value']) }}
                                </text>
                            @endif
                        @endforeach
                    </g>
                @endforeach
                
                <!-- Axis labels -->
                @if($showLabels && !empty($axes))
                    @foreach($axes as $axis)
                        <text 
                            x="{{ $axis['label_x'] }}" 
                            y="{{ $axis['label_y'] }}" 
                            text-anchor="middle" 
                            dominant-baseline="middle" 
                            class="fill-gray-700 dark:fill-gray-300 text-[3px] font-semibold pointer-events-none select-none"
                        >
                            {{ $axis['label'] }}
                        </text>
                    @endforeach
                @endif
             </svg>
        </div>

        <!-- Legend -->
        @if($showLegend && $legendPosition !== 'none' && $legendPosition !== 'hidden')
            <div @class([
                'flex gap-4 text-xs overflow-y-auto max-h-64 pr-2',
                'flex-col' => $legendPosition === 'right',
                'flex-row flex-wrap justify-center w-full gap-6' => $legendPosition === 'bottom',
            ])>
                @foreach($datasets as $dataset)
                    <div class="flex flex-col gap-2">
                        <!-- Dataset header -->
                        <div class="flex items-center gap-2 group cursor-default">
                            <div class="w-3 h-3 rounded-full {{ $dataset['color_is_tailwind_class'] ? $dataset['color'] : (!$dataset['color_is_css'] ? 'bg-' . $dataset['color'] . '-500' : '') }} group-hover:scale-110 transition-transform flex-shrink-0"
                                 style="{{ $dataset['color_is_css'] ? 'background-color: ' . $dataset['color'] . ';' : '' }}"
                            ></div>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $dataset['label'] }}</span>
                        </div>
                        
                        <!-- Dataset values -->
                        <div class="pl-5 flex flex-col gap-1">
                            @foreach($dataset['points'] as $point)
                                <div class="flex justify-between items-baseline gap-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-[11px]">{{ $point['axis'] }}</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ sprintf($formatValues, $point['value']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <style>
        .radar-polygon {
            filter: none;
        }
        .radar-polygon:hover {
            filter: url(#radar-shadow);
        }
        .radar-dataset-group:hover .radar-point {
            r: 1.5;
        }
    </style>
</x-beartropy-charts::chart-wrapper>
