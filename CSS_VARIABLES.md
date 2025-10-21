# AAPP Theme CSS Variables Documentation

This document describes all the CSS variables used in the AAPP application for consistent theming.

## Color Variables

### Background Colors
```css
--bg-primary: #0f172a      /* Main page background - darkest slate */
--bg-secondary: #1e293b    /* Cards, panels, secondary sections */
--bg-tertiary: #334155     /* Headers, form inputs, lighter sections */
--bg-hover: #2d3748        /* Hover state for interactive elements */
```

**Usage Examples:**
- `--bg-primary`: Body background, main container
- `--bg-secondary`: Cards, tables, modals, panels
- `--bg-tertiary`: Card headers, form inputs, navigation
- `--bg-hover`: Table row hover, card hover effects

### Border Colors
```css
--border-primary: #475569   /* Main border color */
--border-secondary: #64748b /* Secondary/lighter borders */
```

**Usage Examples:**
- `--border-primary`: Card borders, form input borders, table cell borders
- `--border-secondary`: Disabled elements, subtle dividers

### Text Colors
```css
--text-primary: #e2e8f0    /* Main text color - light slate */
--text-secondary: #f1f5f9  /* Emphasized text, headings */
--text-muted: #94a3b8      /* Secondary text, placeholders, labels */
```

**Usage Examples:**
- `--text-primary`: Body text, card content, form labels
- `--text-secondary`: Important headings, emphasized content
- `--text-muted`: Help text, timestamps, placeholders

### Accent Colors

#### Green (Primary Actions)
```css
--accent-green: #15803d         /* Primary action color */
--accent-green-hover: #166534   /* Green hover state */
--accent-green-light: #22c55e   /* Success indicators */
```

**Usage:** Primary buttons, badges, success messages, active states

#### Red (Danger/Delete)
```css
--accent-red: #dc2626          /* Danger/delete actions */
--accent-red-hover: #b91c1c    /* Red hover state */
```

**Usage:** Delete buttons, error messages, critical alerts

#### Other Accents
```css
--accent-blue: #3b82f6    /* Info, links */
--accent-yellow: #fbbf24  /* Warnings */
--accent-orange: #ca8a04  /* Caution */
```

### Shadows
```css
--shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3)     /* Small shadow */
--shadow-md: 0 4px 15px rgba(0, 0, 0, 0.3)    /* Medium shadow */
--shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.3)   /* Large shadow */
```

**Usage Examples:**
- `--shadow-sm`: Small elements, icons, images
- `--shadow-md`: Cards, panels, dropdowns
- `--shadow-lg`: Modals, major components

### Transitions
```css
--transition-default: all 0.3s ease
```

**Usage:** All hover effects, color transitions, animations

## Usage Examples

### Cards
```css
.card {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-primary);
    box-shadow: var(--shadow-md);
}

.card-header {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}
```

### Buttons
```css
.btn-primary {
    background-color: var(--accent-green);
    border-color: var(--accent-green);
}

.btn-primary:hover {
    background-color: var(--accent-green-hover);
}
```

### Forms
```css
.form-control {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
    border: 1px solid var(--border-primary);
}

.form-control:focus {
    border-color: var(--accent-green);
}
```

### Tables
```css
.table {
    background-color: var(--bg-secondary);
}

.table thead th {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.table tbody tr:hover {
    background-color: var(--bg-hover);
}
```

## Implementation

### In HTML/Blade Files
Simply use the CSS variables in inline styles:
```html
<div style="background-color: var(--bg-secondary); color: var(--text-primary);">
    Content
</div>
```

### In CSS Classes
```css
.custom-element {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-primary);
    color: var(--text-primary);
    transition: var(--transition-default);
}

.custom-element:hover {
    background-color: var(--bg-hover);
}
```

## Files Updated
1. `public/css/theme-variables.css` - Main CSS variables file
2. `resources/views/layouts/app.blade.php` - Includes the CSS file
3. `resources/views/questions/index.blade.php` - Updated to use variables
4. All exam and question view files

## Benefits
- ✅ **Consistency**: All pages use the same colors
- ✅ **Maintainability**: Change one variable, update entire theme
- ✅ **Readability**: Clear, descriptive variable names
- ✅ **Flexibility**: Easy to create light theme variant
- ✅ **Performance**: Browser caching of CSS file

## Future Enhancements
- Add light theme variables
- Theme switcher functionality
- User preference storage
- Additional color schemes (blue, purple, etc.)
