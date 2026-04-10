# AI Assistant Support for Beartropy Charts

Beartropy Charts includes AI assistant integration to help you build charts faster.

## Supported AI Assistants

### Claude Code (Full Support)
- Context-aware component suggestions
- Complete code examples
- LLM reference docs

### Cursor (Full Support)
- Custom rules integration
- Component autocomplete context

### Other AI Tools (Content Support)
- Universal guide for any AI assistant
- Complete examples and patterns
- Copy-paste ready code

## Directory Structure

```
beartropy/charts/
└── docs/
    ├── llms/                      # LLM reference docs per component
    ├── components/                # User reference docs per component
    └── ai-assistants/
        ├── README.md              # This file
        ├── BEARTROPY_GUIDE.md     # Universal AI guide
        ├── cursor/
        │   └── .cursorrules       # Cursor configuration
        └── examples/
            └── charts.md          # Chart examples
```

## Quick Start

### Using with Cursor

Copy `.cursorrules` to your project root:

```bash
cp vendor/beartropy/charts/docs/ai-assistants/cursor/.cursorrules .cursorrules
```

### Using with Other AI Tools

Point your AI assistant to:
```
vendor/beartropy/charts/docs/ai-assistants/BEARTROPY_GUIDE.md
```

## Available Resources

### Universal Guide
- **BEARTROPY_GUIDE.md** — Complete chart component reference with all props and data formats

### Cursor Rules
- **.cursorrules** — Cursor-specific configuration for chart components

### Code Examples
- **examples/charts.md** — Ready-to-use chart examples for all chart types

## Tips for Best Results

1. **Specify the chart type**: "Create a bar chart" not just "create a chart"
2. **Describe the data format**: "Data comes from an Eloquent query" or "Simple associative array"
3. **Mention styling needs**: "With grid lines and Y-axis labels"
4. **Reference props by name**: Use `showGrid`, `legendPosition`, `formatValues`, etc.

## License

These AI assistant resources are part of Beartropy Charts and are provided under the MIT License.
