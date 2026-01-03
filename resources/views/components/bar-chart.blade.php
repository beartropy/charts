<x-beartropy-charts::chart-wrapper
    :title="$title"
    :border="$border"
    :borderColor="$borderColor"
    :backgroundColor="$backgroundColor"
    {{ $attributes }}
>
    <div class="flex w-full">
        <!-- Y-Axis -->
        @if ($showYAxis && !empty($yAxisTicks))
            <div class="flex flex-col justify-between items-end pr-2 text-xs text-gray-400 {{ $height }} pb-6">
                @foreach ($yAxisTicks as $tick)
                    <span>{{ $tick }}</span>
                @endforeach
            </div>
        @endif

        <div class="flex-1 flex flex-col space-y-2">
            <!-- Chart Area -->
            <div
                class="relative flex items-end {{ $gapClass }} {{ $height }} w-full border-b border-gray-200 dark:border-white/10 pb-2">

                <!-- Grid Lines -->
                @if ($showGrid)
                    <div class="absolute inset-x-0 bottom-2 top-0 flex flex-col justify-between pointer-events-none z-0">
                        <div class="border-t border-gray-100 dark:border-white/5 w-full h-0"></div>
                        <div class="border-t border-gray-100 dark:border-white/5 w-full h-0"></div>
                        <div class="border-t border-gray-100 dark:border-white/5 w-full h-0"></div>
                        <div class="border-t border-gray-100 dark:border-white/5 w-full h-0"></div>
                        <div class="border-t border-gray-100 dark:border-white/5 w-full h-0"></div>
                    </div>
                @endif

                @foreach ($items as $item)
                    <div
                        class="group relative flex flex-col items-center justify-end flex-1 h-full hover:opacity-80 transition-opacity z-10">
                        <!-- Value Tooltip/Label -->
                        @if ($showValues)
                            <span
                                class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs text-gray-500 dark:text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-20">
                                {{ $item['formatted_value'] }}
                            </span>
                        @endif

                        <!-- Bar -->
                        <div style="height: {{ $item['percentage'] }}%; {{ $item['color_is_css'] ? 'background-color: ' . $item['color'] . ';' : '' }}"
                            class="w-full {{ $roundedClass }} {{ $item['color_is_tailwind_class'] ? $item['color'] : (!$item['color_is_css'] ? 'bg-' . $item['color'] . '-500' : '') }} transition-all duration-500 ease-out">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- X-Axis Labels -->
            <div class="flex items-start {{ $gapClass }} w-full text-xs text-center text-gray-500 dark:text-gray-400">
                @foreach ($items as $item)
                    <div class="flex-1 truncate px-1" title="{{ $item['label'] }}">
                        {{ $item['label'] }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-beartropy-charts::chart-wrapper>
