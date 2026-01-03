<?php

namespace Beartropy\Charts\Components;

use Illuminate\View\Component;

class PieChart extends Component
{
    public array $data = [];
    public string $height = 'h-64';
    public ?string $title = null;
    public bool $showLabels = true;
    public string $legendPosition = 'right';
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';

    public function __construct(
        array $data = [],
        string $height = 'h-64',
        ?string $title = null,
        bool $showLabels = true,
        string $legendPosition = 'right',
        string $formatValues = '%s',
        string $label = 'label',
        string $value = 'value'
    ) {
        $this->data = $data;
        $this->height = $height;
        $this->title = $title;
        $this->showLabels = $showLabels;
        $this->legendPosition = $legendPosition;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
    }

    public function render()
    {
        $slices = $this->prepareSlices();

        return view('beartropy-charts::pie-chart', [
            'slices' => $slices,
        ]);
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

        $items = [];
        $index = 0;

        foreach ($this->data as $key => $value) {
            $autoColor = $palette[$index % count($palette)];

            if (is_array($value)) {
                $items[] = [
                    'value' => $value[$this->value] ?? 0,
                    'label' => $value[$this->label] ?? (string)$key,
                    'color' => $value['color'] ?? $autoColor,
                ];
            } else {
                $items[] = [
                    'value' => $value,
                    'label' => (string)$key,
                    'color' => $autoColor,
                ];
            }
            $index++;
        }

        return $items;
    }
}
