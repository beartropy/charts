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

        foreach (glob(__DIR__ . '/Components/*.php') as $file) {
            $component = basename($file, '.php');
            $class = 'Beartropy\\Charts\\Components\\' . $component;
            $alias = 'bt-' . Str::kebab($component);

            Blade::component($class, $alias);
        }
    }
}
