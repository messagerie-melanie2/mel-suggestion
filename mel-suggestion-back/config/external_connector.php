<?php

/**
 * Configuration file for external authentication connectors.
 *
 * This file contains configuration settings for external authentication connectors such as Google and Microsoft.
 * Each connector has its own configuration parameters including client URL, client ID, client secret, and client fields.
 *
 * @return array
 */
return [
  'external_connector' => explode(',', env('EXTERNAL_CONNECTOR')),
  'external_proxy' => env('EXTERNAL_PROXY'),

  'google' => [
    'client_url' => env('GOOGLE_URL'),
    'client_id' => env('GOOGLE_ID'),
    'client_secret' => env('GOOGLE_SECRET'),
    'client_fields' => explode(',', env('GOOGLE_FIELDS'))
  ],

  'microsoft' => [
    'client_url' => env('MICROSOFT_URL'),
    'client_id' => env('MICROSOFT_ID'),
    'client_secret' => env('MICROSOFT_SECRET'),
    'client_fields' => explode(',', env('MICROSOFT_FIELDS'))
  ]
];
