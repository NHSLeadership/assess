<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Leadership and management assessment'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    */

    'version' => '2.1.8',

    /*
    |--------------------------------------------------------------------------
    | Additional settings
    |--------------------------------------------------------------------------
    */

    'description' => env('APP_DESCRIPTION', 'NHS leadership and management assessment'),
    'page_title_prefix' => env('PAGE_TITLE_PREFIX', 'NHS leadership and management assessment | '),
    'welcome' => env('APP_WELCOME', "<p>Use this tool to assess yourself against the standards within the <a href='https://lmframework.leadershipacademy.nhs.uk/'>NHS Leadership and Management Framework</a></p><hr>"),
    'organisation' => env('APP_ORGANISATION', 'NHS Leadership Academy'),
    'org_address' => env('APP_ORG_ADDRESS', '7 & 8 Wellington Place, Leeds, West Yorkshire, LS1 4AP, England.'),
    'org_domain' => env('APP_ORG_DOMAIN', 'leadershipacademy.nhs.uk'),
    'copyright_org' => env('APP_COPYRIGHT_ORG', 'NHS England'),
    'profile_url' => env('APP_PROFILE_URL', 'https://profile.leadershipacademy.nhs.uk'),
    'support_url' => env('APP_SUPPORT_URL', 'https://support.leadershipacademy.nhs.uk/'),
    'show_node_type_prefix' => env('SHOW_NODE_TYPE_PREFIX', false),
    'auth0_admin_permission_cache_ttl' => env('AUTH0_ADMIN_PERMISSION_CACHE_TTL', 300),
    'assessment_min_interval_months' => env('ASSESSMENT_MIN_INTERVAL_MONTHS', 3),
    'pdf_engine' => env('PDF_ENGINE', 'dompdf'),
    'gotenberg_url' => env('GOTENBERG_URL', 'https://gotenberg.staging.nhsla.net'),
    'gotenberg_basic_auth_enabled' => env('GOTENBERG_BASIC_AUTH_ENABLED', false),
    'gotenberg_basic_auth_username' => env('GOTENBERG_BASIC_AUTH_USERNAME', false),
    'gotenberg_basic_auth_password' => env('GOTENBERG_BASIC_AUTH_PASSWORD', false),
    'alert_banner_on' => env('ALERT_BANNER_ON', false),
];
