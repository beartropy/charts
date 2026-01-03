<?php

namespace Beartropy\Charts\Components;

use Illuminate\View\Component;

class ChartWrapper extends Component
{
    public function __construct(
        public mixed $title = null,
        public bool $border = true,
        public ?string $borderColor = null,
        public ?string $backgroundColor = null,
    ) {}

    public function render()
    {
        return view('beartropy-charts::chart-wrapper');
    }
}
