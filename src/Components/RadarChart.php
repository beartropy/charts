<?php

namespace Beartropy\Charts\Components;

use Beartropy\Charts\Concerns\HasChartStyling;
use Illuminate\View\Component;

class RadarChart extends Component
{
    use HasChartStyling;

    public array $data = [];
    public string $height = 'h-96';
    public bool $showLabels = true;
    public bool $showValues = false;
    public string $legendPosition = 'bottom';
    public string $formatValues = '%s';
    public string $label = 'label';
    public string $value = 'value';
    public bool $showGrid = true;
    public int $gridLevels = 5;
    public bool $showAxes = true;
    public bool $fillArea = true;
    public float $fillOpacity = 0.2;
    public bool $showLegend = true;

    public function __construct(
        array $data = [],
        string $height = 'h-96',
        mixed $chartColor = null,
        ?string $backgroundColor = null,
        ?string $title = null,
        bool $showLabels = true,
        bool $showValues = false,
        string $legendPosition = 'bottom',
        string $formatValues = '%s',
        string $label = 'label',
        string $value = 'value',
        bool $border = true,
        ?string $borderColor = null,
        bool $showGrid = true,
        int $gridLevels = 5,
        bool $showAxes = true,
        bool $fillArea = true,
        float $fillOpacity = 0.2,
        bool $showLegend = true
    ) {
        $this->data = $data;
        $this->height = $height;
        $this->showLabels = $showLabels;
        $this->showValues = $showValues;
        $this->legendPosition = $legendPosition;
        $this->formatValues = $formatValues;
        $this->label = $label;
        $this->value = $value;
        $this->showGrid = $showGrid;
        $this->gridLevels = $gridLevels;
        $this->showAxes = $showAxes;
        $this->fillArea = $fillArea;
        $this->fillOpacity = $fillOpacity;
        $this->showLegend = $showLegend;
        
        $this->initializeChartStyling($title, $border, $borderColor, $backgroundColor, $chartColor);
    }

    public function render()
    {
        $chartData = $this->prepareChartData();

        return view('beartropy-charts::radar-chart', array_merge([
            'datasets' => $chartData['datasets'],
            'axes' => $chartData['axes'],
            'maxValue' => $chartData['maxValue'],
        ], $this->getStylingVariables()));
    }

    protected function prepareChartData(): array
    {
        if (empty($this->data)) {
            return [
                'datasets' => [],
                'axes' => [],
                'maxValue' => 0,
            ];
        }

        // Extract axes labels from the first dataset
        $firstDataset = reset($this->data);
        $axes = [];
        
        if (is_array($firstDataset)) {
            foreach ($firstDataset as $key => $value) {
                if ($key !== 'label' && $key !== 'color' && $key !== $this->label && $key !== 'data') {
                    $axes[] = $key;
                }
            }
            
            // If using nested 'data' structure
            if (isset($firstDataset['data']) && is_array($firstDataset['data'])) {
                $axes = array_keys($firstDataset['data']);
            }
        }

        // Find max value across all datasets
        $allValues = [];
        foreach ($this->data as $item) {
            if (is_array($item)) {
                if (isset($item['data']) && is_array($item['data'])) {
                    $allValues = array_merge($allValues, array_values($item['data']));
                } else {
                    foreach ($item as $key => $value) {
                        if ($key !== 'label' && $key !== 'color' && $key !== $this->label && is_numeric($value)) {
                            $allValues[] = $value;
                        }
                    }
                }
            }
        }

        $maxValue = !empty($allValues) ? max($allValues) : 100;
        if ($maxValue == 0) $maxValue = 100;

        // Prepare datasets
        $datasets = [];
        $palette = $this->getColorPalette();
        $index = 0;

        foreach ($this->data as $key => $dataset) {
            $autoColor = $palette[$index % count($palette)];
            
            if (is_array($dataset)) {
                $color = $dataset['color'] ?? $autoColor;
                
                // Convert internal palette colors to CSS to avoid dynamic class construction
                if (!$this->isCssColor($color) && !$this->isTailwindClass($color)) {
                    $cssColor = $this->getTailwindColorValue($color);
                    if ($cssColor) {
                        $color = $cssColor;
                    }
                }
                
                $datasetLabel = $dataset[$this->label] ?? $dataset['label'] ?? "Dataset " . ($index + 1);
                
                // Extract data points
                $points = [];
                if (isset($dataset['data']) && is_array($dataset['data'])) {
                    foreach ($dataset['data'] as $axis => $value) {
                        $points[] = [
                            'axis' => $axis,
                            'value' => $value,
                        ];
                    }
                } else {
                    foreach ($axes as $axis) {
                        $points[] = [
                            'axis' => $axis,
                            'value' => $dataset[$axis] ?? 0,
                        ];
                    }
                }
                
                // Calculate polygon points
                $polygonPoints = $this->calculatePolygonPoints($points, $axes, $maxValue);
                
                $datasets[] = [
                    'label' => $datasetLabel,
                    'color' => $color,
                    'color_is_css' => $this->isCssColor($color),
                    'color_is_tailwind_class' => $this->isTailwindClass($color),
                    'points' => $points,
                    'polygon_points' => $polygonPoints,
                ];
            }
            
            $index++;
        }

        // Prepare axes data
        $axesData = [];
        $cx = 60;
        $cy = 60;
        $maxRadius = 40;
        $numAxes = count($axes);

        foreach ($axes as $index => $axis) {
            $angle = ($index * 360 / $numAxes) - 90; // Start from top
            $rad = deg2rad($angle);
            
            $x = $cx + $maxRadius * cos($rad);
            $y = $cy + $maxRadius * sin($rad);
            
            // Label position (slightly outside)
            $labelRadius = $maxRadius + 8;
            $labelX = $cx + $labelRadius * cos($rad);
            $labelY = $cy + $labelRadius * sin($rad);
            
            $axesData[] = [
                'label' => $axis,
                'x' => $x,
                'y' => $y,
                'label_x' => $labelX,
                'label_y' => $labelY,
            ];
        }

        return [
            'datasets' => $datasets,
            'axes' => $axesData,
            'maxValue' => $maxValue,
        ];
    }

    protected function calculatePolygonPoints(array $points, array $axes, float $maxValue): string
    {
        $cx = 60;
        $cy = 60;
        $maxRadius = 40;
        $numAxes = count($axes);
        
        $polygonPoints = [];
        
        foreach ($axes as $index => $axis) {
            // Find the value for this axis
            $value = 0;
            foreach ($points as $point) {
                if ($point['axis'] === $axis) {
                    $value = $point['value'];
                    break;
                }
            }
            
            $normalizedValue = $value / $maxValue;
            $radius = $maxRadius * $normalizedValue;
            
            $angle = ($index * 360 / $numAxes) - 90;
            $rad = deg2rad($angle);
            
            $x = $cx + $radius * cos($rad);
            $y = $cy + $radius * sin($rad);
            
            $polygonPoints[] = sprintf("%.4f,%.4f", $x, $y);
        }
        
        return implode(' ', $polygonPoints);
    }


}
