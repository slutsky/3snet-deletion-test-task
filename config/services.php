<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return App::config([
    'services' => [
        '_defaults' => [
            'autowire' => true,      // automatically injects dependencies in your services.
            'autoconfigure' => true, // automatically registers your services as commands, event subscribers, etc.
        ],

        // makes classes in src/ available to be used as services
        // this creates a service per class whose id is the fully-qualified class name
        'Shared\\Deletion\\' => [
            'resource' => '../src/',
            'exclude' => '../src/{Attribute,Dto,Enum}',
        ],
    ],
]);
