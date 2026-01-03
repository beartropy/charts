<?php

namespace Beartropy\Charts\Concerns;

trait HasChartStyling
{
    public ?string $title = null;
    public bool $border = true;
    public ?string $borderColor = null;
    public ?string $backgroundColor = null;
    public mixed $chartColor = null;

    protected function initializeChartStyling(
        ?string $title = null,
        bool $border = true,
        ?string $borderColor = null,
        ?string $backgroundColor = null,
        mixed $chartColor = null
    ): void {
        $this->title = $title;
        $this->border = $border;
        $this->borderColor = $borderColor;
        $this->backgroundColor = $backgroundColor;
        $this->chartColor = $chartColor;
    }

    protected function getStylingVariables(): array
    {
        return [
            'title' => $this->title,
            'border' => $this->border,
            'borderColor' => $this->borderColor,
            'backgroundColor' => $this->backgroundColor,
        ];
    }

    protected function isCssColor(string $color): bool
    {
        return str_starts_with($color, '#') || 
               str_starts_with($color, 'rgb') || 
               str_starts_with($color, 'hsl');
    }

    protected function isTailwindClass(string $color): bool
    {
        return str_contains($color, ' ') || 
               str_starts_with($color, 'bg-') || 
               str_starts_with($color, 'text-') ||
               str_starts_with($color, 'border-') ||
               str_starts_with($color, 'from-') ||
               str_starts_with($color, 'to-') ||
               str_starts_with($color, 'via-');
    }

    protected function getColorPalette(): array
    {
        $palette = [
            'yellow', 'slate', 'red', 'sky', 'indigo', 'lime', 'gray', 'blue', 
            'orange', 'teal', 'zinc', 'fuchsia', 'emerald', 'rose', 'violet', 
            'neutral', 'amber', 'cyan', 'purple', 'stone', 'green', 'pink',
        ];

        if ($this->chartColor) {
            if (is_array($this->chartColor)) {
                $palette = $this->chartColor;
            } else {
                $palette = [$this->chartColor];
            }
        }

        return $palette;
    }
}
