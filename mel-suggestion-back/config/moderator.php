<?php

/**
 * Configuration file for moderators.
 *
 * This file contains configuration settings for moderators.
 * It retrieves a list of moderator emails from the environment variables and returns it as an array.
 *
 * @return array
 */
return [

    'moderator' => explode(',', env('MODERATOR'))

];
