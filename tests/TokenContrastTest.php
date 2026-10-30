<?php

declare(strict_types=1);

namespace Phlix\MysticJade\Tests;

use Phlix\MysticJade\MysticJadePlugin;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Contrast contract law for the shipped token palette.
 *
 * The token contract (phlix-tokens src/css/colors.css, consumed by the shipped
 * SPA through phlix-ui applyThemeTokens) assigns each role a WCAG floor,
 * measured on the grounds it is actually painted on:
 *
 *  - `--accent-text` is the accent color AS foreground text/icon — it must
 *    reach AA (>= 4.5:1) on every background and surface. The ui sites
 *    consuming it (`color: var(--accent-text)`) sit on the elevation stack.
 *    The ink-on-fill role is `--text-on-accent`, pinned against `--accent`.
 *    Review theme-mystic-jade__webux M1 measured the shipped #042f2e at
 *    1.04–1.38 on the stack; the cure #5eead4 reaches 10.14–13.51.
 *  - `--accent-ring` is the sole desktop focus cue (the ui paints it as
 *    `box-shadow: 0 0 0 Npx var(--accent-ring)` after `outline: none`).
 *    Every ground it can be painted on, composited, must reach the 3:1
 *    WCAG 1.4.11 non-text minimum against every ground it can sit adjacent
 *    to — the full adjacency matrix, not just the same-ground state change.
 *    Review M3 measured the old α=0.45 at 2.79–2.92 same-ground (failing);
 *    α=0.6 passes the strict full matrix, worst pair 3.25.
 *  - `--text-subtle` carries form placeholders and hints (ui Input.vue
 *    `::placeholder`, Combobox, CommandPalette, labels/metadata) — body-text
 *    tier, so it must reach AA (>= 4.5:1) on every ground too. Review M2
 *    measured the old #5c7a6e at 4.25/4.05/3.64/3.19 on
 *    bg/surface/surface-2/surface-3; the cure #779889 reaches 6.32/6.02/
 *    5.41/4.74, plus the translucent input ground below.
 *
 * The `--text-faint` tier is deliberately NOT floor-lawed here: at #334d43
 * it measures 2.07–2.17 on these grounds — below every WCAG floor, the same
 * dim-faint family situation the estate review registered as a separate,
 * owner-gated cohort finding (see R-THEME-OBSIDIAN-FLAME-01 lineage). Curing
 * its value is that finding's job; this file only pins that the ramp order
 * the M2 cure must preserve stays intact (faint < subtle < muted).
 *
 * Values are parsed from providedThemes() so the law follows the palette;
 * it is not a duplicated constant table. The WCAG math is recomputed here
 * from the hexes themselves — no external fixture to drift.
 *
 * Ported from the family reference phlix-plugin-arctic-jade-theme /
 * phlix-plugin-volcanic-ember-theme shape (reviewed SHIP twins: the same
 * three webux cures, the same four laws) with the jade canonical file's
 * INPUT_SURFACE_ALPHA ground, per the r2 repair brief naming jade's
 * providedThemes()-parsed, strict-parseHex, mutation-toothed form as the
 * cohort standard.
 */
#[CoversClass(MysticJadePlugin::class)]
final class TokenContrastTest extends TestCase
{
    /** WCAG 1.4.3 AA floor for normal-size text. */
    private const AA_TEXT_RATIO = 4.5;

    /** WCAG 1.4.11 floor for non-text UI components (the focus ring). */
    private const NON_TEXT_RATIO = 3.0;

    /**
     * Alpha the SPA paints `--surface` at for an auth input field.
     *
     * Input placeholders are not painted on the opaque surface token: the
     * field is a translucent fill over the page background, so the effective
     * ground is `--surface` composited onto `--bg` at this alpha. The value
     * is read from the shipped ui source (phlix-ui
     * src/components/auth/AuthField.vue: `.authfield__input { background:
     * color-mix(in srgb, var(--surface) 80%, transparent) }`), not assumed.
     */
    private const INPUT_SURFACE_ALPHA = 0.8;

    private MysticJadePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->plugin = new MysticJadePlugin();
    }

    /**
     * @return array<string, string>
     */
    private function tokens(): array
    {
        $themes = $this->plugin->providedThemes();
        /** @var array<string, string> $tokens */
        $tokens = $themes[0]['tokens'];

        return $tokens;
    }

    /**
     * The opaque elevation stack every foreground role is painted on.
     *
     * @return array<string, string>
     */
    private function grounds(): array
    {
        $tokens = $this->tokens();
        $grounds = [];
        foreach (['--bg', '--surface', '--surface-2', '--surface-3'] as $key) {
            $grounds[$key] = $tokens[$key];
        }

        return $grounds;
    }

    #[Test]
    public function testAccentTextMeetsAaAsForegroundOnEveryGround(): void
    {
        $ink = $this->tokens()['--accent-text'];

        foreach ($this->grounds() as $name => $ground) {
            $ratio = $this->contrastRatio($ink, $ground);
            $this->assertGreaterThanOrEqual(
                self::AA_TEXT_RATIO,
                $ratio,
                sprintf(
                    '--accent-text %s on %s (%s) is %.2f:1 — below the 4.5:1 AA foreground floor',
                    $ink,
                    $name,
                    $ground,
                    $ratio
                )
            );
        }
    }

    #[Test]
    public function testTextOnAccentMeetsAaAsInkOnTheAccentFill(): void
    {
        $tokens = $this->tokens();
        $ratio = $this->contrastRatio($tokens['--text-on-accent'], $tokens['--accent']);

        $this->assertGreaterThanOrEqual(
            self::AA_TEXT_RATIO,
            $ratio,
            sprintf(
                '--text-on-accent %s on --accent %s is %.2f:1 — below the 4.5:1 AA floor',
                $tokens['--text-on-accent'],
                $tokens['--accent'],
                $ratio
            )
        );
    }

    #[Test]
    public function testTextSubtleMeetsAaAsBodyTierOnEveryGround(): void
    {
        $tokens = $this->tokens();
        $ink = $tokens['--text-subtle'];
        $graded = $this->grounds();
        $graded['input composite'] = $this->composite(
            $tokens['--surface'],
            self::INPUT_SURFACE_ALPHA,
            $tokens['--bg']
        );

        foreach ($graded as $name => $ground) {
            $ratio = $this->contrastRatio($ink, $ground);
            $this->assertGreaterThanOrEqual(
                self::AA_TEXT_RATIO,
                $ratio,
                sprintf(
                    '--text-subtle %s on %s (%s) is %.2f:1 — below the 4.5:1 AA floor',
                    $ink,
                    $name,
                    $ground,
                    $ratio
                )
            );
        }
    }

    #[Test]
    public function testAccentRingMeetsNonTextMinimumUnderEveryAdjacency(): void
    {
        $ring = $this->parseRgba($this->tokens()['--accent-ring']);

        $grounds = [];
        foreach ($this->grounds() as $name => $hex) {
            $grounds[$name] = $this->parseHex($hex);
        }

        foreach ($grounds as $onName => $paintedOn) {
            $painted = $this->compositeOver($ring, $paintedOn);
            foreach ($grounds as $adjName => $adjacent) {
                $ratio = $this->contrastRatioRgb($painted, $adjacent);
                $this->assertGreaterThanOrEqual(
                    self::NON_TEXT_RATIO,
                    $ratio,
                    sprintf(
                        '--accent-ring painted on %s is %s vs adjacent %s: '
                        . '%.2f:1 — below the 3:1 non-text minimum',
                        $onName,
                        $this->toHex($painted),
                        $adjName,
                        $ratio
                    )
                );
            }
        }
    }

    #[Test]
    public function testRampKeepsFaintBelowSubtleBelowMutedInLuminance(): void
    {
        $tokens = $this->tokens();
        $faint = $this->relativeLuminance($this->parseHex($tokens['--text-faint']));
        $subtle = $this->relativeLuminance($this->parseHex($tokens['--text-subtle']));
        $muted = $this->relativeLuminance($this->parseHex($tokens['--text-muted']));

        // The AA cures must lift a tier, never flatten it: if the subtle
        // cure had collided with muted, or faint had caught subtle, the
        // three text tiers would collapse into two and the palette's
        // hierarchy would be gone behind a passing 4.5:1 bar.
        $this->assertLessThan(
            $subtle,
            $faint,
            '--text-faint must stay strictly darker than --text-subtle (ramp order)'
        );
        $this->assertLessThan(
            $muted,
            $subtle,
            '--text-subtle must stay strictly darker than --text-muted (ramp order)'
        );
    }

    /**
     * Alpha-composite a hex over a hex, the way the browser would.
     */
    private function composite(string $topHex, float $alpha, string $bottomHex): string
    {
        $top = $this->parseHex($topHex);
        $bottom = $this->parseHex($bottomHex);
        $mixed = [];
        foreach ($top as $index => $channel) {
            $mixed[$index] = (int) round($alpha * $channel + (1 - $alpha) * $bottom[$index]);
        }

        return sprintf('#%02x%02x%02x', $mixed[0], $mixed[1], $mixed[2]);
    }

    /**
     * WCAG 2.x relative luminance of an sRGB channel triplet.
     *
     * @param array{int, int, int} $rgb
     */
    private function relativeLuminance(array $rgb): float
    {
        $channels = [];
        foreach ($rgb as $channel) {
            $srgb = $channel / 255;
            $channels[] = $srgb <= 0.04045
                ? $srgb / 12.92
                : ((($srgb + 0.055) / 1.055) ** 2.4);
        }

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }

    private function contrastRatio(string $hexA, string $hexB): float
    {
        return $this->contrastRatioRgb($this->parseHex($hexA), $this->parseHex($hexB));
    }

    /**
     * @param array{int, int, int} $rgbA
     * @param array{int, int, int} $rgbB
     */
    private function contrastRatioRgb(array $rgbA, array $rgbB): float
    {
        $luminanceA = $this->relativeLuminance($rgbA);
        $luminanceB = $this->relativeLuminance($rgbB);
        $brighter = max($luminanceA, $luminanceB);
        $darker = min($luminanceA, $luminanceB);

        return ($brighter + 0.05) / ($darker + 0.05);
    }

    /**
     * @return array{int, int, int}
     */
    private function parseHex(string $hex): array
    {
        $matches = [];
        if (preg_match('/^#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/', $hex, $matches) !== 1) {
            throw new RuntimeException("Contrast law expects literal #rrggbb, got '$hex'");
        }

        return [
            (int) hexdec($matches[1]),
            (int) hexdec($matches[2]),
            (int) hexdec($matches[3]),
        ];
    }

    /**
     * @param array{int, int, int, float} $rgba
     * @param array{int, int, int} $ground
     *
     * @return array{int, int, int}
     */
    private function compositeOver(array $rgba, array $ground): array
    {
        [$r, $g, $b, $alpha] = $rgba;

        return [
            (int) round($alpha * $r + (1 - $alpha) * $ground[0]),
            (int) round($alpha * $g + (1 - $alpha) * $ground[1]),
            (int) round($alpha * $b + (1 - $alpha) * $ground[2]),
        ];
    }

    /**
     * @return array{int, int, int, float}
     */
    private function parseRgba(string $value): array
    {
        $matches = [];
        if (
            preg_match('/^rgba\((\d{1,3}), (\d{1,3}), (\d{1,3}), (0?\.\d+|1|0)\)$/', $value, $matches)
                !== 1
        ) {
            throw new RuntimeException("Contrast law expects literal rgba(r, g, b, a), got '$value'");
        }

        return [
            (int) $matches[1],
            (int) $matches[2],
            (int) $matches[3],
            (float) $matches[4],
        ];
    }

    /**
     * @param array{int, int, int} $rgb
     */
    private function toHex(array $rgb): string
    {
        return sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
    }
}
