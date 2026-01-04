<?php

namespace Beartropy\Charts\Components;

use Beartropy\Charts\Concerns\HasChartStyling;
use Illuminate\View\Component;

class DonutChart extends Component
{
    use HasChartStyling;

    public array $data = [];
    public string $height = 'h-96';
    public bool $showLabels = true;
    public string $legendPosition = 'right';
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';
    public string $labelColor = 'white';
    public float $innerRadius = 0.6; // 60% of outer radius
    public ?string $centerText = null;
    public ?string $centerSubtext = null;

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
        float $innerRadius = 0.6,
        ?string $centerText = null,
        ?string $centerSubtext = null
    ) {
        $this->data = $data;
        $this->height = $height;
        $this->showLabels = $showLabels;
        $this->legendPosition = $legendPosition;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
        $this->labelColor = $labelColor;
        $this->innerRadius = $innerRadius;
        $this->centerText = $centerText;
        $this->centerSubtext = $centerSubtext;
        
        $this->initializeChartStyling($title, $border, $borderColor, $backgroundColor, $chartColor);
    }

    public function render()
    {
        $slices = $this->prepareSlices();
        $total = array_sum(array_column($this->extractItems(), 'value'));

        return view('beartropy-charts::donut-chart', array_merge([
            'slices' => $slices,
            'total' => $total,
        ], $this->getStylingVariables()));
    }

    protected function prepareSlices(): array
    {
        if (empty($this->data)) {
            return [];
        }

        $items = $this->extractItems();
        $total = array_sum(array_column($items, 'value'));
        
        if ($total == 0) {
            return [];
        }

        $slices = [];
        $currentAngle = 0;
        $cx = 50;
        $cy = 50;
        $outerRadius = 40; // Outer radius
        $innerRadius = $outerRadius * $this->innerRadius; // Inner radius for donut hole

        foreach ($items as $item) {
            $val = $item['value'];
            $percentage = $val / $total;
            $angle = $percentage * 360;

            $startRad = deg2rad($currentAngle - 90);
            $endRad = deg2rad($currentAngle + $angle - 90);

            // Outer arc points
            $x1Outer = $cx + $outerRadius * cos($startRad);
            $y1Outer = $cy + $outerRadius * sin($startRad);
            $x2Outer = $cx + $outerRadius * cos($endRad);
            $y2Outer = $cy + $outerRadius * sin($endRad);

            // Inner arc points
            $x1Inner = $cx + $innerRadius * cos($startRad);
            $y1Inner = $cy + $innerRadius * sin($startRad);
            $x2Inner = $cx + $innerRadius * cos($endRad);
            $y2Inner = $cy + $innerRadius * sin($endRad);

            $largeArc = $angle > 180 ? 1 : 0;

            // Create donut slice path
            // M to outer start, A outer arc, L to inner end, A inner arc (reverse), Z
            if ($percentage >= 0.9999) {
                // Full donut (two circles)
                $d = sprintf(
                    "M %f %f A %f %f 0 1 1 %f %f A %f %f 0 1 1 %f %f Z M %f %f A %f %f 0 1 0 %f %f A %f %f 0 1 0 %f %f Z",
                    $cx - $outerRadius, $cy,
                    $outerRadius, $outerRadius,
                    $cx + $outerRadius, $cy,
                    $outerRadius, $outerRadius,
                    $cx - $outerRadius, $cy,
                    $cx - $innerRadius, $cy,
                    $innerRadius, $innerRadius,
                    $cx + $innerRadius, $cy,
                    $innerRadius, $innerRadius,
                    $cx - $innerRadius, $cy
                );
            } else {
                $d = sprintf(
                    "M %.4f %.4f A %.4f %.4f 0 %d 1 %.4f %.4f L %.4f %.4f A %.4f %.4f 0 %d 0 %.4f %.4f Z",
                    $x1Outer, $y1Outer,           // Move to outer start
                    $outerRadius, $outerRadius,    // Outer arc radii
                    $largeArc,                     // Large arc flag
                    $x2Outer, $y2Outer,           // Outer arc end
                    $x2Inner, $y2Inner,           // Line to inner end
                    $innerRadius, $innerRadius,    // Inner arc radii
                    $largeArc,                     // Large arc flag (reversed)
                    $x1Inner, $y1Inner            // Inner arc end (back to start)
                );
            }

            // Calculate label position (middle of the donut ring)
            $midAngleRad = deg2rad($currentAngle + ($angle / 2) - 90);
            $labelR = $innerRadius + (($outerRadius - $innerRadius) / 2);
            $lx = $cx + $labelR * cos($midAngleRad);
            $ly = $cy + $labelR * sin($midAngleRad);

            $slices[] = [
                'value' => $val,
                'label' => $item['label'],
                'color' => $item['color'],
                'color_is_css' => $item['color_is_css'],
                'color_is_tailwind_class' => $item['color_is_tailwind_class'],
                'percent' => round($percentage * 100, 1),
                'formatted_value' => sprintf($this->formatValues, $val),
                'path' => $d,
                'label_x' => $lx,
                'label_y' => $ly,
            ];

            $currentAngle += $angle;
        }

        return $slices;
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
