<?php

/**
 * Configuration file for suggestion-related settings.
 *
 * This file contains configuration settings related to suggestions, such as application name, title,
 * comment source, mail subject, and mail body. These settings are retrieved from environment variables.
 *
 * @return array
 */
return [
    'application_name' => env('SUGGESTION_APPLICATION_NAME'),
    'title' => env('SUGGESTION_TITLE'),
    'comment_from' => env('SUGGESTION_COMMENT_FROM'),
    'mail_subject' => env('SUGGESTION_MAIL_SUBJECT'),
    'mail_body' => env('SUGGESTION_MAIL_BODY'),
    'application_version' => env('SUGGESTION_APPLICATION_VERSION')
];
