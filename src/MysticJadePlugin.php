<?php

/**
 * Phlix Mystic Jade theme plugin.
 *
 * @copyright 2026 Joe Huss <detain@interserver.net>
 * @license   MIT
 */

declare(strict_types=1);

namespace Phlix\MysticJade;

use Phlix\Shared\Plugin\LifecycleInterface;
use Phlix\Theming\ThemeSourceInterface;
use Psr\Container\ContainerInterface;

/**
 * Mystic Jade — a near-black cool-green ui-theme for Phlix.
 *
 * A single-theme plugin that extends the built-in `midnight` base with
 * near-black cool-green grounds and a bright teal accent ramp (led by
 * `#2dd4bf`). The name is mood, not hue: where Jade's accent is jade-green
 * (`#50c878`), this theme runs teal on darker grounds, with quiet grain,
 * vignette and ambient atmosphere.
 *
 * @package Phlix\MysticJade
 * @since 1.0.0
 */
final class MysticJadePlugin implements LifecycleInterface, ThemeSourceInterface
{
    /**
     * Canonical provenance key for this source.
     */
    public const SOURCE_NAME = 'mystic-jade';

    /**
     * Nothing to do — the host registers the themes off the `instanceof`.
     *
     * @param ContainerInterface $container The host container (unused).
     */
    public function onEnable(ContainerInterface $container): void
    {
    }

    /**
     * Nothing to do — the host deregisters this source by name on disable.
     */
    public function onDisable(): void
    {
    }

    /**
     * A theme plugin subscribes to no events.
     *
     * @return array<class-string, string> Always empty.
     */
    public function subscribedEvents(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function themeSourceName(): string
    {
        return self::SOURCE_NAME;
    }

    /**
     * @inheritDoc
     *
     * @return list<array<array-key, mixed>>
     */
    public function providedThemes(): array
    {
        return [
            [
                'id' => 'mystic-jade',
                'name' => 'Mystic Jade',
                'dark' => true,
                'extends' => 'midnight',
                'tokens' => [
                    // Accent ramp — one teal hue family. `--accent-text` is the
                    // foreground that sits on the dark elevated surfaces (an
                    // accent-tinted text colour); text placed on top of a solid
                    // `--accent` fill uses `--text-on-accent` instead.
                    '--accent' => '#2dd4bf',
                    '--accent-hover' => '#5eead4',
                    '--accent-active' => '#14b8a6',
                    '--accent-soft' => 'rgba(45, 212, 191, 0.12)',
                    '--accent-ring' => 'rgba(45, 212, 191, 0.6)',
                    '--accent-text' => '#5eead4',

                    // Background + elevation stack
                    '--bg' => '#030a08',
                    '--surface' => '#071210',
                    '--surface-2' => '#0e1f18',
                    '--surface-3' => '#152b22',
                    '--surface-glass' => 'rgba(7, 18, 16, 0.65)',
                    '--surface-glass-strong' => 'rgba(3, 10, 8, 0.85)',

                    // Text ramp
                    '--text' => '#e8f5f0',
                    '--text-muted' => '#9cb8ac',
                    '--text-subtle' => '#779889',
                    '--text-faint' => '#668277',
                    '--text-on-accent' => '#02100d',

                    // Borders
                    '--border' => '#1a3028',
                    '--border-subtle' => '#101e19',
                    '--border-strong' => '#2a4a3c',

                    // Atmosphere
                    '--grain-opacity' => '0.035',
                    '--vignette' => 'rgba(0, 8, 5, 0.5)',
                    '--ambient' => 'rgba(45, 212, 191, 0.15)',

                    // Legacy `--color-*` aliases — only the ones the shipped SPA still reads
                    '--color-bg' => '#030a08',
                    '--color-surface' => '#071210',
                    '--color-text' => '#e8f5f0',
                    '--color-text-muted' => '#9cb8ac',
                    '--color-border' => '#1a3028',
                ],
            ],
        ];
    }
}
