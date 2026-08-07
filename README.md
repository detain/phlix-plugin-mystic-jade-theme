# Mystic Jade Theme for Phlix

A deep emerald UI-theme for Phlix with luminous teal accents.

## Overview

Mystic Jade extends the built-in `midnight` base with a rich emerald and luminous teal palette. The theme evokes an enchanted forest atmosphere with deep greens, glowing jade accents, and subtle atmospheric effects.

## Installation

```bash
composer require detain/phlix-plugin-mystic-jade-theme
```

## Color Palette

### Core Colors

| Token | Hex | Usage |
|-------|-----|-------|
| `--bg` | `#030a08` | Page background |
| `--surface` | `#071210` | Card/panel backgrounds |
| `--surface-2` | `#0e1f18` | Elevated surfaces |
| `--surface-3` | `#152b22` | Highest elevation |
| `--accent` | `#2dd4bf` | Primary accent (jade/teal) |

### Text Colors

| Token | Hex | Usage |
|-------|-----|-------|
| `--text` | `#e8f5f0` | Primary text |
| `--text-muted` | `#9cb8ac` | Secondary text |
| `--text-subtle` | `#5c7a6e` | Tertiary text |
| `--text-faint` | `#334d43` | Disabled/hint text |

### Border Colors

| Token | Hex | Usage |
|-------|-----|-------|
| `--border` | `#1a3028` | Default borders |
| `--border-subtle` | `#101e19` | Subtle borders |
| `--border-strong` | `#2a4a3c` | Emphasized borders |

### Atmosphere

| Token | Value | Usage |
|-------|-------|-------|
| `--ambient` | `rgba(45, 212, 191, 0.15)` | Ambient glow |
| `--vignette` | `rgba(0, 8, 5, 0.5)` | Edge darkening |
| `--grain-opacity` | `0.035` | Film grain effect |

## Theme Tokens

```json
{
  "id": "mystic-jade",
  "name": "Mystic Jade",
  "dark": true,
  "extends": "midnight",
  "tokens": {
    "accent": "#2dd4bf",
    "bg": "#030a08",
    "surface": "#071210",
    "surface-2": "#0e1f18",
    "surface-3": "#152b22",
    "text": "#e8f5f0",
    "border": "#1a3028"
  }
}
```

## Requirements

- PHP >= 8.3
- Phlix >= 0.44.0

## License

MIT License - see [LICENSE](LICENSE) for details.
