<?php

namespace Beartropy\Charts;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class ChartsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/components', 'beartropy-charts');

        Blade::componentNamespace('Beartropy\\Charts\\Components', 'beartropy-charts');

        $components = [
            'LineChart',
            'BarChart',
            'PieChart'
        ];

        foreach ($components as $component) {
            $class = 'Beartropy\\Charts\\Components\\' . $component;
            $alias = 'bt-' . Str::kebab($component);
            
            Blade::component($class, $alias);
            
        }
    }
}
