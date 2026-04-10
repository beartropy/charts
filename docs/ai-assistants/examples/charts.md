# Chart Examples - Beartropy Charts

Ready-to-use chart examples.

## Bar Chart with Grid

```blade
<x-bt-bar-chart
    :data="['Jan' => 1200, 'Feb' => 1800, 'Mar' => 1500, 'Apr' => 2200]"
    :showGrid="true"
    :showYAxis="true"
    formatValues="$%s"
    title="Monthly Revenue"
/>
```

## Multi-Line Chart

```blade
<x-bt-line-chart
    :data="[
        ['label' => 'Revenue', 'data' => ['Q1' => 5000, 'Q2' => 8000, 'Q3' => 6000, 'Q4' => 9500], 'color' => '#3B82F6'],
        ['label' => 'Expenses', 'data' => ['Q1' => 4000, 'Q2' => 6000, 'Q3' => 5500, 'Q4' => 7000], 'color' => '#EF4444'],
    ]"
    legendPosition="bottom"
    xAxisTitle="Quarter"
    yAxisTitle="Amount ($)"
    formatValues="$%s"
/>
```

## Donut Chart with Center Text

```blade
<x-bt-donut-chart
    :data="['Complete' => 75, 'In Progress' => 15, 'Pending' => 10]"
    centerText="75%"
    centerSubtext="Complete"
    :innerRadius="0.65"
/>
```

## Radar Comparison

```blade
<x-bt-radar-chart
    :data="[
        ['label' => 'Product A', 'Performance' => 85, 'Reliability' => 90, 'Cost' => 70, 'Support' => 80, 'UX' => 95],
        ['label' => 'Product B', 'Performance' => 75, 'Reliability' => 85, 'Cost' => 90, 'Support' => 70, 'UX' => 80],
    ]"
    :fillArea="true"
    :fillOpacity="0.2"
    legendPosition="bottom"
/>
```

## Livewire Integration

```php
<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class SalesDashboard extends Component
{
    public function getMonthlyRevenueProperty(): array
    {
        return Order::query()
            ->selectRaw("DATE_FORMAT(created_at, '%b') as month, SUM(total) as total")
            ->whereYear('created_at', now()->year)
            ->groupByRaw("MONTH(created_at), DATE_FORMAT(created_at, '%b')")
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.sales-dashboard');
    }
}
```

```blade
{{-- livewire/sales-dashboard.blade.php --}}
<div>
    <x-bt-chart-wrapper title="Monthly Revenue" :collapsible="true">
        <x-bt-bar-chart
            :data="$this->monthlyRevenue"
            :showGrid="true"
            :showYAxis="true"
            formatValues="$%s"
            :border="false"
        />
    </x-bt-chart-wrapper>
</div>
```

## Pie Chart with Custom Colors

```blade
<x-bt-pie-chart
    :data="[
        ['label' => 'Desktop', 'value' => 65, 'color' => '#3B82F6'],
        ['label' => 'Mobile', 'value' => 28, 'color' => '#10B981'],
        ['label' => 'Tablet', 'value' => 7, 'color' => '#F59E0B'],
    ]"
    legendPosition="bottom"
    title="Traffic Sources"
/>
```
