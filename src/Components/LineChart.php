<?php

namespace Beartropy\Charts\Components;

use Beartropy\Charts\Concerns\HasChartStyling;
use Illuminate\View\Component;

class LineChart extends Component
{
    use HasChartStyling;

    public array $data = [];
    public ?float $max = null;
    public string $height = 'h-64';
    public bool $showGrid = true;
    public bool $showYAxis = true;
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';
    public bool $showPoints = true;
    public string $dataLabels = 'hover';
    public ?string $xAxisTitle = null;
    public ?string $yAxisTitle = null;

    public function __construct(
        array $data = [],
        ?float $max = null,
        string $height = 'h-64',
        mixed $chartColor = null,
        ?string $backgroundColor = null,
        ?string $title = null,
        bool $showGrid = true,
        bool $showYAxis = true,
        string $formatValues = '%s',
        string $label = 'label',
        string $value = 'value',
        bool $showPoints = true,
        string $dataLabels = 'hover',
        ?string $xAxisTitle = null,
        ?string $yAxisTitle = null,
        bool $border = true,
        ?string $borderColor = null
    ) {
        $this->data = $data;
        $this->max = $max;
        $this->height = $height;
        $this->showGrid = $showGrid;
        $this->showYAxis = $showYAxis;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
        $this->showPoints = $showPoints;
        $this->dataLabels = $dataLabels;
        $this->xAxisTitle = $xAxisTitle;
        $this->yAxisTitle = $yAxisTitle;
        
        $this->initializeChartStyling($title, $border, $borderColor, $backgroundColor, $chartColor);
    }

    public function render()
    {
        $datasets = $this->prepareDatasets();
        $xAxisLabels = $this->extractXAxisLabels(); // Extract labels

        $yAxisTicks = [];

        // Calculate max across all datasets
        $globalMax = 0;
        foreach ($datasets as $dataset) {
            foreach ($dataset['values'] as $val) {
                $globalMax = max($globalMax, $val);
            }
        }
        
        $calcMax = $this->max ?? ($globalMax > 0 ? $globalMax : 100);

        // Generate ticks
        if ($this->showYAxis) {
            $steps = 4;
            for ($i = $steps; $i >= 0; $i--) {
                $val = ($calcMax / $steps) * $i;
                $yAxisTicks[] = sprintf($this->formatValues, $val);
            }
        }

        // Calculate SVG points and data points for overlay
        foreach ($datasets as &$dataset) {
            $points = [];
            $dataPoints = [];
            $count = count($dataset['values']);
            
            foreach ($dataset['values'] as $index => $val) {
                // X coordinate (0 to 100)
                $x = $count > 1 ? ($index / ($count - 1)) * 100 : 50;
                
                // Y coordinate (0 at top, 100 at bottom)
                // We map value 0 -> 100 (bottom), max -> 0 (top)
                $percentageY = ($val / $calcMax) * 100;
                $y = 100 - $percentageY;
                
                $points[] = "$x,$y";

                $dataPoints[] = [
                    'x' => $x,
                    'y' => $y,
                    'value' => $val,
                    'formatted_value' => sprintf($this->formatValues, $val),
                    'color' => $dataset['color'],
                    'color_is_css' => $dataset['color_is_css'] ?? false,
                    'color_is_tailwind_class' => $dataset['color_is_tailwind_class'] ?? false,
                    // Attach label if available for this index
                    'label' => $xAxisLabels[$index] ?? '', 
                    'label_position' => 'top', // Default position
                ];
            }
            $dataset['points'] = implode(' ', $points);
            $dataset['dataPoints'] = $dataPoints;
        }

        $this->resolveLabelOverlaps($datasets);

        return view('beartropy-charts::line-chart', array_merge([
            'datasets' => $datasets,
            'yAxisTicks' => $yAxisTicks,
            'xAxisLabels' => $xAxisLabels,
        ], $this->getStylingVariables()));
    }

    protected function resolveLabelOverlaps(array &$datasets): void
    {
        // 1. Group points by their X coordinate (as string to be used as key)
        $groupedByX = [];

        foreach ($datasets as $dsIndex => $dataset) {
            foreach ($dataset['dataPoints'] as $pIndex => $point) {
                // Use string key for X to group precise overlaps
                $key = (string)$point['x'];
                $groupedByX[$key][] = [
                    'y' => $point['y'],
                    'dsIndex' => $dsIndex,
                    'pIndex' => $pIndex,
                ];
            }
        }

        // 2. Iterate through each X group -> sort by Y -> detect overlaps
        // Threshold in percentage: approx 8-10% of height for a label
        $threshold = 8; 

        foreach ($groupedByX as $xVal => $points) {
            if (count($points) < 2) {
                continue;
            }

            // Sort by Y ascending (0 is top, 100 is bottom)
            usort($points, fn($a, $b) => $a['y'] <=> $b['y']);

            // Simple greedy collision resolution
            for ($i = 0; $i < count($points) - 1; $i++) {
                $current = $points[$i];
                $next = $points[$i+1];

                // If distance is less than threshold, we have an overlap
                if (($next['y'] - $current['y']) < $threshold) {
                    // $current ('top') is physically above $next.
                    // If we have a collision, flip the lower point ($next) to 'bottom'
                    
                    // Helper to update the original dataset array
                    $this->setLabelPosition($datasets, $next['dsIndex'], $next['pIndex'], 'bottom');
                }
            }
        }
    }

    protected function setLabelPosition(array &$datasets, int $dsIndex, int $pIndex, string $position): void
    {
        if (isset($datasets[$dsIndex]['dataPoints'][$pIndex])) {
            $datasets[$dsIndex]['dataPoints'][$pIndex]['label_position'] = $position;
        }
    }

    protected function extractXAxisLabels(): array
    {
        if (empty($this->data)) {
            return [];
        }

        // Detect if multidimensional (multiple datasets)
        $isMultiple = isset($this->data[0]) 
            && is_array($this->data[0]) 
            && (isset($this->data[0]['data']) || isset($this->data[0]['values']));

        $sourceData = $isMultiple 
            ? ($this->data[0]['data'] ?? $this->data[0]['values'] ?? [])
            : $this->data;

        $labels = [];
        foreach ($sourceData as $key => $item) {
            if (is_array($item)) {
                $labels[] = $item[$this->label] ?? $item['label'] ?? (string)$key;
            } else {
                // If simple value, use key if string, else empty or generic? 
                // Using key might be useful for associative arrays ['Jan' => 10]
                $labels[] = is_string($key) ? $key : '';
            }
        }
        return $labels;
    }

    protected function prepareDatasets(): array
    {
        if (empty($this->data)) {
            return [];
        }

        $palette = $this->getColorPalette();
        $normalized = [];
        
        // Detect if multidimensional (multiple datasets) or simple array
        $isMultiple = isset($this->data[0]) 
            && is_array($this->data[0]) 
            && (isset($this->data[0]['data']) || isset($this->data[0]['values']));

        if (!$isMultiple) {
            // Treat as single dataset
            $color = $palette[0];
            
            // Convert internal palette colors to CSS to avoid dynamic class construction
            if (!$this->isCssColor($color) && !$this->isTailwindClass($color)) {
                $cssColor = $this->getTailwindColorValue($color);
                if ($cssColor) {
                    $color = $cssColor;
                }
            }
            
            $normalized[] = [
                'label' => 'Dataset 1',
                'color' => $color,
                'color_is_css' => $this->isCssColor($color),
                'color_is_tailwind_class' => $this->isTailwindClass($color),
                'values' => $this->extractValues($this->data),
            ];
        } else {
            foreach ($this->data as $index => $series) {
                $color = $series['color'] ?? $palette[$index % count($palette)];
                
                // Convert internal palette colors to CSS to avoid dynamic class construction
                if (!$this->isCssColor($color) && !$this->isTailwindClass($color)) {
                    $cssColor = $this->getTailwindColorValue($color);
                    if ($cssColor) {
                        $color = $cssColor;
                    }
                }
                
                $normalized[] = [
                    'label' => $series['label'] ?? "Series " . ($index + 1),
                    'color' => $color,
                    'color_is_css' => $this->isCssColor($color),
                    'color_is_tailwind_class' => $this->isTailwindClass($color),
                    'values' => $this->extractValues($series['data'] ?? $series['values'] ?? []),
                ];
            }
        }

        return $normalized;
    }

    protected function extractValues(array $data): array
    {
        $values = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $values[] = $item[$this->value] ?? $item['value'] ?? 0;
            } else {
                $values[] = $item;
            }
        }
        return $values;
    }


}
