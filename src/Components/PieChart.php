<?php

namespace Beartropy\Charts\Components;

use Beartropy\Charts\Concerns\HasChartStyling;
use Illuminate\View\Component;

class PieChart extends Component
{
    use HasChartStyling;

    public array $data = [];
    public string $height = 'h-64';
    public bool $showLabels = true;
    public string $legendPosition = 'right';
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';
    public string $labelColor = 'white';

    public function __construct(
        array $data = [],
        string $height = 'h-64',
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
        string $labelColor = 'white'
    ) {
        $this->data = $data;
        $this->height = $height;
        $this->showLabels = $showLabels;
        $this->legendPosition = $legendPosition;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
        $this->labelColor = $labelColor;
        
        $this->initializeChartStyling($title, $border, $borderColor, $backgroundColor, $chartColor);
    }

    public function render()
    {
        $slices = $this->prepareSlices();

        return view('beartropy-charts::pie-chart', array_merge([
            'slices' => $slices,
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
        $r = 40; // Radius leaving some padding

        foreach ($items as $item) {
            $val = $item['value'];
            $percentage = $val / $total;
            $angle = $percentage * 360; // 360 degrees

            // Calculate SVG path
            // Start P:
            // x = cx + r * cos(a)
            // y = cy + r * sin(a)
            // Angles in standard SVG: 0 is 3 o'clock. We usually want 12 o'clock (-90 deg).
            // Let's shift everything by -90 deg (-PI/2).
            
            $startRad = deg2rad($currentAngle - 90);
            $endRad = deg2rad($currentAngle + $angle - 90);

            $x1 = $cx + $r * cos($startRad);
            $y1 = $cy + $r * sin($startRad);
            $x2 = $cx + $r * cos($endRad);
            $y2 = $cy + $r * sin($endRad);

            // Large arc flag
            $largeArc = $angle > 180 ? 1 : 0;

            // M cx cy L x1 y1 A r r 0 large_arc 1 x2 y2 Z
            // Note: If percentage is 100%, we need a circle, not a path with an arc.
            if ($percentage >= 0.9999) {
                // Full circle
                $d = "M $cx, $cy m -$r, 0 a $r,$r 0 1,0 " . ($r * 2) . ",0 a $r,$r 0 1,0 -" . ($r * 2) . ",0";
                // Or just <circle> but we want to be consistent with path structure if possible.
            } else {
                $d = sprintf(
                    // Move to center
                    // Line to start
                    // Arc to end
                    // Close path (Line to center)
                    "M %.4f %.4f L %.4f %.4f A %.4f %.4f 0 %d 1 %.4f %.4f Z",
                    $cx, $cy,
                    $x1, $y1,
                    $r, $r,
                    $largeArc,
                    $x2, $y2
                );
            }

            // Calculate label position (centroidish - mid angle)
            $midAngleRad = deg2rad($currentAngle + ($angle / 2) - 90);
            // Label radius (slightly inside? or outside?)
            // If inside:
            $labelR = $r * 0.7; 
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
        // Palette from other components
        $palette = [
            'yellow', 'slate', 'red', 'sky', 'indigo', 'lime', 'gray', 'blue', 'orange', 'teal',
            'zinc', 'fuchsia', 'emerald', 'rose', 'violet', 'neutral', 'amber', 'cyan', 'purple',
            'stone', 'green', 'pink',
        ];

        if ($this->chartColor) {
             if (is_array($this->chartColor)) {
                 $palette = $this->chartColor;
             } else {
                 $palette = [$this->chartColor];
             }
        }


        $items = [];
        $index = 0;

        foreach ($this->data as $key => $value) {
            $autoColor = $palette[$index % count($palette)];

            if (is_array($value)) {
                $color = $value['color'] ?? $autoColor;
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
                    'color' => $autoColor,
                    'color_is_css' => $this->isCssColor($autoColor),
                    'color_is_tailwind_class' => $this->isTailwindClass($autoColor),
                ];
            }
            $index++;
        }

        return $items;
    }

    protected function isCssColor(string $color): bool
    {
        return str_starts_with($color, '#') || str_starts_with($color, 'rgb') || str_starts_with($color, 'hsl');
    }

    protected function isTailwindClass(string $color): bool
    {
        // If it contains spaces or starts with common Tailwind prefixes, it's a full class string
        return str_contains($color, ' ') || 
               str_starts_with($color, 'bg-') || 
               str_starts_with($color, 'text-') ||
               str_starts_with($color, 'border-') ||
               str_starts_with($color, 'from-') ||
               str_starts_with($color, 'to-') ||
               str_starts_with($color, 'via-');
    }
}
