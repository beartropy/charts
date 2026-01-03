<x-beartropy-charts::chart-wrapper 
    :title="$title"
    :border="$border"
    :borderColor="$borderColor"
    :backgroundColor="$backgroundColor"
    {{ $attributes }}
>
    <div class="flex w-full">
        <!-- Y-Axis -->
        <div class="flex flex-col items-end pr-2 gap-1">
             @if($yAxisTitle)
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">{{ $yAxisTitle }}</span>
            @endif
            @if ($showYAxis && !empty($yAxisTicks))
                <div class="flex flex-col justify-between items-end text-xs text-gray-400 {{ $height }} pb-6">
                    @foreach ($yAxisTicks as $tick)
                        <span>{{ $tick }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex-1 flex flex-col space-y-2 relative">
            <!-- Chart Area -->
            <div class="relative {{ $height }} w-full border-b border-gray-200 dark:border-white/10 pb-2 px-4">
                
                <!-- Grid Lines -->
                @if ($showGrid)
                    <div class="absolute inset-x-4 bottom-2 top-0 flex flex-col justify-between pointer-events-none z-0">
                        <div class="border-t border-gray-200 dark:border-gray-700 w-full h-0"></div>
                        <div class="border-t border-gray-200 dark:border-gray-700 w-full h-0"></div>
                        <div class="border-t border-gray-200 dark:border-gray-700 w-full h-0"></div>
                        <div class="border-t border-gray-200 dark:border-gray-700 w-full h-0"></div>
                        <div class="border-t border-gray-200 dark:border-gray-700 w-full h-0"></div>
                    </div>
                @endif

                <!-- SVG Lines -->
                <div class="absolute inset-x-4 bottom-2 top-0 z-10 pointer-events-none">
                    <svg class="w-full h-full text-transparent" viewBox="0 0 100 100" preserveAspectRatio="none">
                        @foreach($datasets as $dataset)
                            <polyline 
                                points="{{ $dataset['points'] }}" 
                                fill="none" 
                                stroke="{{ $dataset['color_is_css'] ? $dataset['color'] : 'currentColor' }}"
                                stroke-width="2" 
                                vector-effect="non-scaling-stroke"
                                class="{{ $dataset['color_is_tailwind_class'] ? $dataset['color'] : (!$dataset['color_is_css'] ? 'text-' . $dataset['color'] . '-500' : '') }} transition-all duration-500 ease-out"
                            />
                        @endforeach
                    </svg>
                </div>
                
                <!-- Points and Labels Overlay -->
                @if($showPoints)
                    <div class="absolute inset-x-4 bottom-2 top-0 z-20 pointer-events-none">
                        @foreach($datasets as $dataset)
                            @foreach($dataset['dataPoints'] as $point)
                                <div 
                                    class="absolute group flex items-center justify-center"
                                    style="left: {{ $point['x'] }}%; top: {{ $point['y'] }}%; transform: translate(-50%, -50%);"
                                >
                                    <!-- Dot -->
                                    <div class="w-2 h-2 rounded-full {{ $point['color_is_tailwind_class'] ? $point['color'] : (!$point['color_is_css'] ? 'bg-' . $point['color'] . '-500' : '') }} border border-white dark:border-gray-800 shadow-sm pointer-events-auto hover:scale-125 transition-transform"
                                         style="{{ $point['color_is_css'] ? 'background-color: ' . $point['color'] . ';' : '' }}"
                                    ></div>
                                    
                                    <!-- Label -->
                                    @if($dataLabels !== 'none')
                                        <div @class([
                                            'absolute px-1.5 py-0.5 rounded text-xs font-medium text-gray-900 dark:text-gray-100 pointer-events-none whitespace-nowrap z-30',
                                            '-top-6' => ($point['label_position'] ?? 'top') === 'top',
                                            'top-4' => ($point['label_position'] ?? 'top') === 'bottom',
                                            'opacity-0 group-hover:opacity-100 transition-opacity' => $dataLabels === 'hover',
                                            'opacity-100' => $dataLabels === 'always',
                                        ])>
                                            {{ $point['formatted_value'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif

            </div>

             <!-- X-Axis Labels -->
             @if(!empty($xAxisLabels))
                <div class="flex justify-between w-full text-xs text-gray-500 dark:text-gray-400 px-1">
                    @foreach($xAxisLabels as $index => $label)
                        <div class="flex justify-center" style="width: 0;">
                             <span class="whitespace-nowrap">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
             @endif
             
             <!-- X-Axis Title -->
             @if($xAxisTitle)
                <div class="text-center text-xs font-bold text-gray-500 dark:text-gray-400 mt-1">
                    {{ $xAxisTitle }}
                </div>
             @endif
        </div>
    </div>
</x-beartropy-charts::chart-wrapper>
