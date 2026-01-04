<?php

namespace Beartropy\Charts\Components;

use Beartropy\Charts\Concerns\HasChartStyling;
use Illuminate\View\Component;

class BarChart extends Component
{
    use HasChartStyling;

    public array $data = [];
    public ?float $max = null;
    public string $height = 'h-64';
    public bool $showValues = true;
    public bool $showValuesAlways = false;
    public string $color = 'beartropy';
    public string $gap = 'sm';
    public mixed $rounded = true;
    public bool $showGrid = false;
    public bool $showYAxis = false;
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';

    public function __construct(
        array $data = [],
        ?float $max = null,
        string $height = 'h-64',
        bool $showValues = true,
        bool $showValuesAlways = false,
        string $color = 'beartropy',
        mixed $chartColor = null,
        ?string $backgroundColor = null,
        string $gap = 'sm',
        mixed $rounded = true,
        bool $showGrid = false,
        bool $showYAxis = false,
        string $formatValues = '%s',
        string $label = 'label',
        string $value = 'value',
        ?string $title = null,
        bool $border = true,
        ?string $borderColor = null
    ) {
        $this->data = $data;
        $this->max = $max;
        $this->height = $height;
        $this->showValues = $showValues;
        $this->showValuesAlways = $showValuesAlways;
        $this->color = $color;
        $this->gap = $gap;
        $this->rounded = $rounded;
        $this->showGrid = $showGrid;
        $this->showYAxis = $showYAxis;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
        
        $this->initializeChartStyling($title, $border, $borderColor, $backgroundColor, $chartColor);
    }

    public function render()
    {
        $gaps = [
            'xs' => 'space-x-1',
            'sm' => 'space-x-2',
            'md' => 'space-x-4',
            'lg' => 'space-x-6',
            'xl' => 'space-x-8',
        ];

        $gapClass = $gaps[$this->gap] ?? $this->gap;

        $roundedClass = $this->rounded === true
            ? 'rounded-t'
            : ($this->rounded === false ? '' : $this->rounded);

        $yAxisTicks = [];
        $items = $this->prepareItems();

        // Calculate max from items if not set
        $calcMax = $this->max ?? (empty($items) ? 100 : max(array_column($items, 'value')));
        if ($calcMax == 0) $calcMax = 100;

        if ($this->showYAxis) {
            $steps = 4;
            for ($i = $steps; $i >= 0; $i--) {
                $val = ($calcMax / $steps) * $i;
                $yAxisTicks[] = sprintf($this->formatValues, $val);
            }
        }

        return view('beartropy-charts::bar-chart', array_merge([
            'items' => $items,
            'gapClass' => $gapClass,
            'roundedClass' => $roundedClass,
            'yAxisTicks' => $yAxisTicks,
        ], $this->getStylingVariables()));
    }

    protected function prepareItems(): array
    {
        if (empty($this->data)) {
            return [];
        }

        $palette = $this->getColorPalette();

        // Normalize data structure
        // Supports: [10, 20, 30] or [['label' => 'A', 'value' => 10], ...]
        $items = [];
        $maxValue = 0;
        $index = 0;

        foreach ($this->data as $key => $value) {
            $item = [];
            
            // Determine color from cycling palette if not provided
            $autoColor = $palette[$index % count($palette)];

            if (is_array($value)) {
                $item['value'] = $value[$this->value] ?? 0;
                $item['label'] = $value[$this->label] ?? (string)$key;
                $item['color'] = $value['color'] ?? $autoColor;
            } else {
                $item['value'] = $value;
                $item['label'] = (string)$key;
                $item['color'] = $autoColor;
            }
            
            // Convert internal palette colors to CSS to avoid dynamic class construction
            if (!$this->isCssColor($item['color']) && !$this->isTailwindClass($item['color'])) {
                $cssColor = $this->getTailwindColorValue($item['color']);
                if ($cssColor) {
                    $item['color'] = $cssColor;
                }
            }
            
            $item['color_is_css'] = $this->isCssColor($item['color']);
            $item['color_is_tailwind_class'] = $this->isTailwindClass($item['color']);

            $items[] = $item;
            $maxValue = max($maxValue, $item['value']);
            $index++;
        }

        $calcMax = $this->max ?? ($maxValue > 0 ? $maxValue : 100);

        foreach ($items as &$item) {
            $item['percentage'] = $calcMax > 0 ? ($item['value'] / $calcMax) * 100 : 0;
            $item['formatted_value'] = sprintf($this->formatValues, $item['value']);
        }

        return $items;
    }
}
