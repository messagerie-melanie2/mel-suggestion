<?php

return [
    'synonyms_url' => env('SYNONYMS_URL'),
    'list_operators_openidconnect' => env('LIST_OPERATORS_OPENIDCONNECT'),
    'application_url' => env('APPLICATION_URL'),
    'notification' => env('NOTIFICATION', false),
    'orm_path' => env('ORM_PATH'),
    'instance' => env('INSTANCE'),
    'use_roundcube_session' => env('USE_ROUNDCUBE_SESSION', false),
];