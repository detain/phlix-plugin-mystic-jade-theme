# Mystic Jade Theme for Phlix

A near-black cool-green UI theme for Phlix with bright teal accents.

## Overview

Mystic Jade extends the built-in `midnight` base with near-black cool-green
grounds (`#030a08` up through the elevation stack) and a bright teal accent
ramp led by `#2dd4bf`. The name is mood, not hue: where Jade's own accent is
jade-green (`#50c878`), Mystic Jade runs teal on darker grounds, with the
same quiet grain, vignette and ambient atmosphere.

## Installation

Install and enable are **two separate actions**: installing only stages the
plugin's code on disk — the theme appears in the picker only after it is
enabled.

**Admin UI** — *Admin → Plugins* (`/app/admin/plugins`), find **Mystic Jade**,
press **Install**, then flip its switch to enable it. The page posts this
repository's URL to `POST /api/v1/admin/plugins/install` and the server
supplies the catalog pin (exact `ref` + `artifactSha256`) itself; without a
catalog entry the install is refused outright — deliberate default-deny.

**CLI** — the same thing, from the server root. Pass the **repository URL**,
not the plugin name: the installer downloads from that string verbatim, and a
bare name is not downloadable.

```bash
php bin/phlix plugin:install https://github.com/detain/phlix-plugin-mystic-jade-theme
php bin/phlix plugin:enable  phlix-plugin-mystic-jade-theme
php bin/phlix plugin:list
```

`composer require` into the host checkout is not the supported install path —
the plugin loader manages its own per-plugin vendor directory.

## Color Palette

### Core Colors

| Token | Hex | Usage |
|-------|-----|-------|
| `--bg` | `#030a08` | Page background |
| `--surface` | `#071210` | Card/panel backgrounds |
| `--surface-2` | `#0e1f18` | Elevated surfaces |
| `--surface-3` | `#152b22` | Highest elevation |
| `--surface-glass` | `rgba(7, 18, 16, 0.65)` | Translucent frosted surfaces (overlays) |
| `--surface-glass-strong` | `rgba(3, 10, 8, 0.85)` | Opaque-tier glass (docks, auth card, player panels) |
| `--accent` | `#2dd4bf` | Primary accent (teal-400) |

### Accent Variants

| Token | Hex | Usage |
|-------|-----|-------|
| `--accent-hover` | `#5eead4` | Hover state accent |
| `--accent-active` | `#14b8a6` | Active/pressed state |
| `--accent-soft` | `rgba(45, 212, 191, 0.12)` | Soft accent-tinted fills |
| `--accent-ring` | `rgba(45, 212, 191, 0.6)` | Focus ring color |
| `--accent-text` | `#5eead4` | Accent-tinted foreground text on the dark surfaces |

### Text Colors

| Token | Hex | Usage |
|-------|-----|-------|
| `--text` | `#e8f5f0` | Primary text |
| `--text-muted` | `#9cb8ac` | Secondary text |
| `--text-subtle` | `#779889` | Tertiary text |
| `--text-faint` | `#668277` | Disabled/hint text (placeholder ink — AA-pinned, see `tests/TokenContrastTest.php`) |
| `--text-on-accent` | `#02100d` | Dark ink drawn on solid `--accent` fills (buttons, badges) |

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

### Legacy `--color-*` Aliases

| Token | Hex | Usage |
|-------|-----|-------|
| `--color-bg` | `#030a08` | Legacy alias — mirrors `--bg` |
| `--color-surface` | `#071210` | Legacy alias — mirrors `--surface` |
| `--color-text` | `#e8f5f0` | Legacy alias — mirrors `--text` |
| `--color-text-muted` | `#9cb8ac` | Legacy alias — mirrors `--text-muted` |
| `--color-border` | `#1a3028` | Legacy alias — mirrors `--border` |

`phlix-tokens/src/css/colors.css` declares every `--color-*` name as a
`var(--modern-token)` indirection inside each theme block, so these explicit
values are belt-and-braces for un-migrated `var(--color-*)` readers rather
than a second cascade layer.

## Theme Tokens

The payload is the single source of truth in
[`MysticJadePlugin::providedThemes()`](src/MysticJadePlugin.php) — a theme
envelope (`id` / `name` / `dark` / `extends`) plus 28 CSS custom properties,
names keeping their `--` prefix on the wire. All 28 tokens and their exact
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

## Development

Install dependencies:

```bash
composer install
```

Run the test suite (`phpunit.xml`, bootstrapped by `tests/bootstrap.php`):

```bash
composer test
```

Run static analysis (`phpstan.neon`, level 9) and coding standards (`phpcs.xml`, PSR-12):

```bash
composer phpstan
composer phpcs
```

Both static gates cover `scripts/security-audit-check.php` as well as the
PHP source: the S440 gate script is analyzed at level 9 with zero ignores
and checked under PSR-12 (two sniffs are scoped off for `scripts/` only —
the reasons are pinned in `phpcs.xml`; the script itself is a byte-frozen
family template).

`tests/bootstrap.php` loads `dev-stubs/LifecycleInterface.php` and `dev-stubs/ThemeSourceInterface.php` only when the real host contracts are absent, so the suite runs outside a Phlix server checkout. `.github/workflows/test.yml` runs the same three checks on PHP 8.3 and 8.4, plus the S440 dependency-audit leg (`composer-audit` job — `php scripts/security-audit-check.php`, locked down by `tests/SecurityAuditCheckTest.php`).

## License

MIT License - see [LICENSE](LICENSE) for details.
