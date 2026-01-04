# Changelog

All notable changes to `beartropy/charts` will be documented in this file.


## [1.1.4] - 2026-01-04

### Fixed
- Fixed label truncation in Radar Chart where long labels (e.g., "Marketing") were being cut off at the edges
- Fixed label truncation in Polar Chart where category labels were clipped at cardinal positions
- Expanded SVG viewBox from 100x100 to 120x120 to provide adequate spacing for labels in all positions

## [1.1.3] - 2026-01-04

### Added
- Added interactive hover functionality to Radar Chart to display dataset values directly on the chart
- Dataset values now appear on hover instead of being always visible, improving chart readability
- Enhanced visual feedback with opacity changes and stroke width adjustments when hovering over datasets

## [1.1.2] - 2026-01-04

### Fixed
- Fixed dynamic Tailwind class construction issue where internal palette colors (e.g., `blue`, `red`) were being built as dynamic classes like `'bg-' . $color . '-500'`, preventing Tailwind JIT compiler from detecting them
- All internal palette colors are now converted to their CSS hex equivalents (e.g., `blue` → `#3b82f6`) and applied as inline styles
- User-provided Tailwind classes (e.g., `bg-rose-300/60`) continue to work correctly as they're evaluated in the user's project
- Removed duplicate `isCssColor()` and `isTailwindClass()` methods across all chart components, now using shared trait methods

## [1.1.1] - 2026-01-04

### Fixed
- Fixed Donut Chart hover alignment issue where labels would not move with the slice on hover

## [1.1.0] - 2026-01-04

### Added
- Added Polar Chart component with radial/polar visualization and cone-shaped segments
- Added Radar Chart component with spider/web visualization for multi-variable data comparison
- Added Donut Chart component with hollow center and optional center text/subtext display
- Added `showValues` prop to Radar Chart to display numeric values at data points
- Added `showLegend` prop to Radar Chart for explicit legend control (default: true)
- Enhanced Radar Chart legend to show detailed axis values for each dataset

### Changed
- Improved Polar Chart default height from h-80 to h-96 for better visibility
- Improved Donut Chart default height from h-80 to h-96 for better visibility
- Changed hover effect on Pie Chart percentage labels from font-size increase to bold weight
- Changed hover effect on Donut Chart percentage labels from font-size increase to bold weight
- Reduced stroke width on Polar Chart segments from 0.3 to 0.1 for cleaner appearance

## [1.0.3] - 2026-01-03

### Added
- Added `showValuesAlways` prop to Bar Chart component to display values constantly instead of only on hover
- Added `collapsible` prop to Chart Wrapper component to allow collapsing/expanding chart content from the title
- Added hover effects to Pie Chart slices: scale animation, 3D shadow effect, and larger percentage text on hover

### Fixed
- Fixed Chart Wrapper margins to properly center title vertically when chart is collapsed
- Fixed overflow issue that was cutting off values in collapsible charts

## [1.0.2] - 2026-01-03

### Fixed
- Added horizontal padding to line chart to prevent initial and final data points from touching the edges

## [1.0.1] - Previous Release

### Added
- Initial features and functionality

## [1.0.0] - Initial Release

### Added
- Bar Chart component
- Line Chart component
- Pie Chart component
- Chart styling and customization options
