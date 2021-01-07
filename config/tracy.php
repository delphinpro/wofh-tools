<?php

return [
    'enabled'       => env('APP_DEBUG') === true && env('TRACY_ENABLED', false),
    'showBar'       => env('APP_ENV') !== 'production' && env('TRACY_SHOW_BAR', false),
    'showException' => true,
    'route'         => [
        'prefix' => 'tracy',
        'as'     => 'tracy.',
    ],
    'accepts'       => [
        'text/html',
    ],
    'appendTo'      => 'body',
    'editor'        => env('TRACY_EDITOR', 'phpstorm://open?file=%file&line=%line'),
    'maxDepth'      => env('TRACY_MAX_DEPTH', 4),
    'maxLength'     => 1000,
    'scream'        => true,
    'showLocation'  => true,
    'strictMode'    => true,
    'editorMapping' => [],
    'panels'        => [
        'routing'        => true,
        'database'       => true,
        'view'           => true,
        'event'          => false,
        'session'        => true,
        'request'        => true,
        'auth'           => true,
        'html-validator' => false,
        'terminal'       => false,
    ],
];
