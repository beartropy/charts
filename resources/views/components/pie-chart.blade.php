<div {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    
    @if($title)
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-5">{{ $title }}</h3>
    @endif

    <div @class([
        'flex items-center w-full mt-2 gap-6',
        'flex-col' => $legendPosition === 'bottom',
        'flex-row' => $legendPosition === 'right',
    ])>
        <!-- Chart Area -->
        <div class="relative {{ $height }} w-auto flex-shrink-0 flex justify-center items-center">
             <svg class="h-full w-auto" viewBox="0 0 100 100">
                @foreach($slices as $slice)
                    <path 
                        d="{{ $slice['path'] }}" 
                        fill="currentColor"
                        class="text-{{ $slice['color'] }}-500 hover:text-{{ $slice['color'] }}-400 transition-colors duration-200 cursor-pointer stroke-white dark:stroke-gray-800"
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
                        <div class="w-3 h-3 rounded-full bg-{{ $slice['color'] }}-500 group-hover:scale-110 transition-transform flex-shrink-0"></div>
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
