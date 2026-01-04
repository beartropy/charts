<?php

namespace Beartropy\Charts\Components;

use Beartropy\Charts\Concerns\HasChartStyling;
use Illuminate\View\Component;

class PolarChart extends Component
{
    use HasChartStyling;

    public array $data = [];
    public string $height = 'h-80';
    public bool $showLabels = true;
    public string $legendPosition = 'right';
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';
    public string $labelColor = 'white';
    public bool $showGrid = true;
    public int $gridLevels = 4;

    public function __construct(
        array $data = [],
        string $height = 'h-96',
        mixed $chartColor = null,
        ?string $backgroundColor = null,
        ?string $title = null,
        bool $showLabels = true,
        string $legendPosition = 'right',
        string $formatValues = '%s',
        string $label = 'label',
        string $value = 'value',
        bool $border = true,
        ?string $borderColor = null,
        string $labelColor = 'white',
        bool $showGrid = true,
        int $gridLevels = 4
    ) {
        $this->data = $data;
        $this->height = $height;
        $this->showLabels = $showLabels;
        $this->legendPosition = $legendPosition;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
        $this->labelColor = $labelColor;
        $this->showGrid = $showGrid;
        $this->gridLevels = $gridLevels;
        
        $this->initializeChartStyling($title, $border, $borderColor, $backgroundColor, $chartColor);
    }

    public function render()
    {
        $segments = $this->prepareSegments();

        return view('beartropy-charts::polar-chart', array_merge([
            'segments' => $segments,
        ], $this->getStylingVariables()));
    }

    protected function prepareSegments(): array
    {
        if (empty($this->data)) {
            return [];
        }

        $items = $this->extractItems();
        $maxValue = max(array_column($items, 'value'));
        
        if ($maxValue == 0) {
            return [];
        }

        $segments = [];
        $count = count($items);
        $anglePerSegment = 360 / $count;
        
        $cx = 50;
        $cy = 50;
        $maxRadius = 40; // Maximum radius leaving some padding

        foreach ($items as $index => $item) {
            $val = $item['value'];
            $normalizedValue = $val / $maxValue;
            $radius = $maxRadius * $normalizedValue;
            
            // Calculate angles for this segment
            $startAngle = $index * $anglePerSegment - 90; // Start from top (12 o'clock)
            $endAngle = ($index + 1) * $anglePerSegment - 90;
            
            $startRad = deg2rad($startAngle);
            $endRad = deg2rad($endAngle);
            
            // Calculate points for the segment
            $x1 = $cx + $radius * cos($startRad);
            $y1 = $cy + $radius * sin($startRad);
            $x2 = $cx + $radius * cos($endRad);
            $y2 = $cy + $radius * sin($endRad);
            
            // Create path for the segment (cone shape with arc)
            // Determine if we need a large arc (more than 180 degrees)
            $largeArc = $anglePerSegment > 180 ? 1 : 0;
            
            // M to center, L to start point, A (arc) to end point, Z close path
            $path = sprintf(
                "M %.4f %.4f L %.4f %.4f A %.4f %.4f 0 %d 1 %.4f %.4f Z",
                $cx, $cy,      // Move to center
                $x1, $y1,      // Line to start point on arc
                $radius, $radius,  // Arc radii (x, y)
                $largeArc,     // Large arc flag
                $x2, $y2       // Arc end point
            );
            
            // Calculate label position (at the outer edge, middle of segment)
            $midAngle = $startAngle + ($anglePerSegment / 2);
            $midRad = deg2rad($midAngle);
            $labelRadius = $maxRadius + 8; // Position labels outside the chart
            $labelX = $cx + $labelRadius * cos($midRad);
            $labelY = $cy + $labelRadius * sin($midRad);
            
            // Calculate value label position (inside the segment)
            $valueRadius = $radius * 0.7;
            $valueX = $cx + $valueRadius * cos($midRad);
            $valueY = $cy + $valueRadius * sin($midRad);

            $segments[] = [
                'value' => $val,
                'label' => $item['label'],
                'color' => $item['color'],
                'color_is_css' => $item['color_is_css'],
                'color_is_tailwind_class' => $item['color_is_tailwind_class'],
                'percent' => round($normalizedValue * 100, 1),
                'formatted_value' => sprintf($this->formatValues, $val),
                'path' => $path,
                'label_x' => $labelX,
                'label_y' => $labelY,
                'value_x' => $valueX,
                'value_y' => $valueY,
                'radius' => $radius,
            ];
        }

        return $segments;
    }

    protected function extractItems(): array
    {
        $palette = $this->getColorPalette();

        $items = [];
        $index = 0;

        foreach ($this->data as $key => $value) {
            $autoColor = $palette[$index % count($palette)];

            if (is_array($value)) {
                $color = $value['color'] ?? $autoColor;
            } else {
                $color = $autoColor;
            }
            
            // Convert internal palette colors to CSS to avoid dynamic class construction
            if (!$this->isCssColor($color) && !$this->isTailwindClass($color)) {
                $cssColor = $this->getTailwindColorValue($color);
                if ($cssColor) {
                    $color = $cssColor;
                }
            }
            
            if (is_array($value)) {
                $items[] = [
                    'value' => $value[$this->value] ?? 0,
                    'label' => $value[$this->label] ?? (string)$key,
                    'color' => $color,
                    'color_is_css' => $this->isCssColor($color),
                    'color_is_tailwind_class' => $this->isTailwindClass($color),
                ];
            } else {
                $items[] = [
                    'value' => $value,
                    'label' => (string)$key,
                    'color' => $color,
                    'color_is_css' => $this->isCssColor($color),
                    'color_is_tailwind_class' => $this->isTailwindClass($color),
                ];
            }
            $index++;
        }

        return $items;
    }


}
