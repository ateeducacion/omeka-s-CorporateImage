<?php
declare(strict_types=1);

namespace PersonalizedHeaderFooter;

//use ThreeDViewer\Media\FileRenderer\Viewer3DRenderer;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            // Form\SettingsFieldset::class => Form\SettingsFieldset::class, // Ensure these are needed or remove
            // Form\SiteSettingsFieldset::class => Form\SiteSettingsFieldset::class, // Ensure these are needed or remove
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null, // Should be 'PersonalizedHeaderFooter' if you add translations
            ],
        ],
    ],
    'PersonalizedHeaderFooter' => [
        'settings' => [
            // Default settings are now handled in Module::install()
            // 'personalized_header_html' => '', // Example if needed here, but better in install()
            // 'personalized_footer_html' => '', // Example if needed here, but better in install()
        ]
    ],
];
