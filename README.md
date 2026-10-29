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
| `--text-subtle` | `#779889` | Tertiary text |
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

The payload is the single source of truth in
[`MysticJadePlugin::providedThemes()`](src/MysticJadePlugin.php) — a theme
envelope (`id` / `name` / `dark` / `extends`) plus 28 CSS custom properties,
names keeping their `--` prefix on the wire. The colour tokens and their exact
values are tabled above; a trimmed sketch of the real shape:

```json
{
  "id": "mystic-jade",
  "name": "Mystic Jade",
  "dark": true,
  "extends": "midnight",
  "tokens": {
    "--accent": "#2dd4bf",
    "--accent-text": "#5eead4",
    "--bg": "#030a08",
    "--text-on-accent": "#02100d"
  }
}
```

The `--accent-*` ramp carries one teal hue. `--accent-text` is an
accent-tinted foreground for text sitting on the dark surfaces — the text
colour for chips filled with solid `--accent` is `--text-on-accent`.

## Requirements

- PHP >= 8.3
- Phlix >= 0.44.0

## License

MIT License - see [LICENSE](LICENSE) for details.
