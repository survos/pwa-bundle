<?php

declare(strict_types=1);

namespace SpomkyLabs\PwaBundle\Tests\Functional;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use SpomkyLabs\PwaBundle\ImageProcessor\ImagickImageProcessor;
use SpomkyLabs\PwaBundle\SpomkyLabsPwaBundle;
use SpomkyLabs\PwaBundle\Tests\DummyImageProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Definition\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class ConfigurationTest extends KernelTestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @param array<string, mixed> $configuration
     */
    #[Test]
    #[DataProvider('dataConfigurationIsValid')]
    public function configurationIsValid(array $configuration): void
    {
        $this->assertConfigurationIsValid($configuration);
    }

    /**
     * @return array<array-key, mixed>[]
     */
    public static function dataConfigurationIsValid(): iterable
    {
        yield 'No configuration values' => [[]];
        yield 'Empty configuration' => [[
            'pwa' => null,
        ]];
        yield 'Image processor is defined' => [[
            'pwa' => [
                'image_processor' => ImagickImageProcessor::class,
                'web_client' => 'id_web_client',
                'user_agent' => 'user-agent/1.0',
            ],
        ]];
        yield 'Favicons only' => [[
            'pwa' => [
                'favicons' => [
                    'enabled' => true,
                    'src' => 'pwa/1920x1920.svg',
                ],
            ],
        ]];
        yield 'Manifest only' => [[
            'pwa' => [
                'manifest' => [
                    'enabled' => true,
                ],
            ],
        ]];
        yield 'Service Worker only' => [[
            'pwa' => [
                'serviceworker' => [
                    'enabled' => true,
                    'src' => __DIR__ . '/sw.js',
                ],
            ],
        ]];
        yield 'Service Worker without source' => [
            [
                'pwa' => [
                    'serviceworker' => null,
                ],
            ],
        ];
        yield 'Complete configuration' => [[
            'pwa' => [
                'image_processor' => DummyImageProcessor::class,
                'favicons' => [
                    'enabled' => true,
                    'src' => 'pwa/1920x1920.svg',
                ],
                'manifest' => [
                    'enabled' => true,
                    'background_color' => 'red',
                    'categories' => ['pwa.categories.0', 'pwa.categories.1', 'pwa.categories.2'],
                    'description' => 'pwa.description',
                    'display' => 'standalone',
                    'display_override' => ['fullscreen', 'minimal-ui'],
                    'file_handlers' => [
                        [
                            'action' => [
                                'path' => 'audio_file_handler',
                                'params' => [
                                    'param1' => 'audio',
                                ],
                            ],
                            'accept' => [
                                'audio/wav' => ['.wav'],
                                'audio/x-wav' => ['.wav'],
                                'audio/mpeg' => ['.mp3'],
                                'audio/mp4' => ['.mp4'],
                                'audio/aac' => ['.adts'],
                                'audio/ogg' => ['.ogg'],
                                'application/ogg' => ['.ogg'],
                                'audio/webm' => ['.webm'],
                                'audio/flac' => ['.flac'],
                                'audio/mid' => ['.rmi', '.mid'],
                            ],
                        ],
                    ],
                    'icons' => [
                        [
                            'src' => 'pwa/1920x1920.svg',
                            'sizes' => [48, 72, 96, 128, 256],
                            'type' => 'webp',
                        ],
                        [
                            'src' => 'pwa/1920x1920.svg',
                            'sizes' => [48, 72, 96, 128, 256],
                            'type' => 'png',
                            'purpose' => 'maskable',
                        ],
                        [
                            'src' => 'pwa/1920x1920.svg',
                            'sizes' => [0],
                        ],
                    ],
                    'id' => '/?homescreen=1',
                    'launch_handler' => [
                        'client_mode' => ['focus-existing', 'auto'],
                    ],
                    'orientation' => 'portrait-primary',
                    'prefer_related_applications' => true,
                    'dir' => 'rtl',
                    'lang' => 'ar',
                    'name' => 'pwa.name',
                    'short_name' => 'pwa.short_name',
                    'protocol_handlers' => [
                        [
                            'protocol' => 'web+jngl',
                            'url' => '/lookup?type=%s',
                        ],
                        [
                            'protocol' => 'web+jnglstore',
                            'url' => '/shop?for=%s',
                        ],
                    ],
                    'related_applications' => [
                        [
                            'platform' => 'play',
                            'url' => 'https://play.google.com/store/apps/details?id=com.example.app1',
                            'id' => 'com.example.app1',
                        ],
                        [
                            'platform' => 'itunes',
                            'url' => 'https://itunes.apple.com/app/example-app1/id123456789',
                        ],
                        [
                            'platform' => 'windows',
                            'url' => 'https://apps.microsoft.com/store/detail/example-app1/id123456789',
                        ],
                    ],
                    'scope' => '/',
                    'start_url' => 'pwa.start_url',
                    'theme_color' => 'red',
                    'screenshots' => [
                        [
                            'src' => 'pwa/screenshots/360x800.svg',
                            'label' => 'pwa.screenshots.0',
                        ],
                    ],
                    'share_target' => [
                        'action' => [
                            'path' => 'shared_content_receiver',
                            'params' => [
                                'param1' => 'value1',
                                'param2' => 'value2',
                            ],
                        ],
                        'method' => 'GET',
                        'params' => [
                            'title' => 'name',
                            'text' => 'description',
                            'url' => 'link',
                        ],
                    ],
                    'shortcuts' => [
                        [
                            'name' => "Today's agenda",
                            'url' => [
                                'path' => 'agenda',
                                'params' => [
                                    'date' => 'today',
                                ],
                            ],
                            'description' => 'List of events planned for today',
                        ],
                        [
                            'name' => 'New event',
                            'url' => '/create/event',
                        ],
                        [
                            'name' => 'New reminder',
                            'url' => '/create/reminder',
                            'icons' => [
                                'pwa/1920x1920.svg',
                                [
                                    'src' => 'pwa/1920x1920.svg',
                                    'purpose' => 'maskable',
                                ],
                            ],
                        ],
                    ],
                    'edge_side_panel' => [
                        'preferred_width' => 480,
                    ],
                    'iarc_rating_id' => '123456',
                    'scope_extensions' => [
                        [
                            'origin' => '*.foo.com',
                        ],
                        [
                            'origin' => 'https://*.bar.com',
                        ],
                        [
                            'origin' => 'https://*.baz.com',
                        ],
                    ],
                    'widgets' => [
                        [
                            'name' => 'PWAmp mini player',
                            'description' => 'widget to control the PWAmp music player',
                            'tag' => 'pwamp',
                            'template' => 'pwamp-template',
                            'ms_ac_template' => 'app_widget_template',
                            'data' => 'app_widget_data',
                            'type' => 'application/json',
                            'screenshots' => [
                                [
                                    'src' => 'pwa/1920x1920.svg',
                                    'label' => 'The PWAmp mini-player widget',
                                ],
                            ],
                            'icons' => [
                                [
                                    'src' => 'pwa/1920x1920.svg',
                                    'sizes' => [16, 48],
                                    'type' => 'webp',
                                ],
                            ],
                            'auth' => false,
                            'update' => 86400,
                        ],
                    ],
                    'handle_links' => 'auto',
                ],
                'serviceworker' => [
                    'enabled' => true,
                    'src' => __DIR__ . '/sw.js',
                    'scope' => '/',
                    'use_cache' => true,
                    'workbox' => [
                        'resource_caches' => [
                            [
                                'match_callback' => 'regex:.*',
                                'strategy' => 'StaleWhileRevalidate',
                                'cache_name' => 'page-cache',
                                'broadcast' => true,
                                'preload_urls' => ['privacy_policy', 'terms_of_service', '@static-pages', '@widgets'],
                            ],
                        ],
                        'offline_fallback' => [
                            'page' => '/offline.html',
                        ],
                    ],
                ],
            ],
        ]];
    }

    /**
     * @param array<string, mixed> $configuration
     */
    #[Test]
    #[DataProvider('dataConfigurationIsInvalid')]
    public function configurationIsInvalid(array $configuration, string $message): void
    {
        $this->assertConfigurationIsInvalid($configuration, $message);
    }

    /**
     * @return array<array-key, mixed>[]
     */
    public static function dataConfigurationIsInvalid(): iterable
    {
        yield 'No configuration values' => [
            [
                'pwa' => [
                    'favicons' => 10,
                ],
            ],
            'Invalid type for path "pwa.favicons". Expected "array", but got "int"',
        ];
        yield 'No favicon source' => [
            [
                'pwa' => [
                    'favicons' => [
                        'enabled' => true,
                    ],
                ],
            ],
            'The child config "src" under "pwa.favicons" must be configured: The source of the favicon. Shall be a SVG or large PNG.',
        ];
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration(new SpomkyLabsPwaBundle(), null, 'pwa');
    }
}
