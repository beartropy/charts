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

    /**
     * Convert internal palette color names to CSS hex values.
     * This prevents dynamic Tailwind class construction which doesn't work with JIT compilation.
     * 
     * @param string $colorName The color name (e.g., 'blue', 'red')
     * @return string|null The hex color value or null if not found
     */
    protected function getTailwindColorValue(string $colorName): ?string
    {
        // Map of Tailwind color names to their 500 shade hex values
        // Source: https://tailwindcss.com/docs/customizing-colors
        $colorMap = [
            'slate' => '#64748b',
            'gray' => '#6b7280',
            'zinc' => '#71717a',
            'neutral' => '#737373',
            'stone' => '#78716c',
            'red' => '#ef4444',
            'orange' => '#f97316',
            'amber' => '#f59e0b',
            'yellow' => '#eab308',
            'lime' => '#84cc16',
            'green' => '#22c55e',
            'emerald' => '#10b981',
            'teal' => '#14b8a6',
            'cyan' => '#06b6d4',
            'sky' => '#0ea5e9',
            'blue' => '#3b82f6',
            'indigo' => '#6366f1',
            'violet' => '#8b5cf6',
            'purple' => '#a855f7',
            'fuchsia' => '#d946ef',
            'pink' => '#ec4899',
            'rose' => '#f43f5e',
        ];

        return $colorMap[$colorName] ?? null;
    }
}
