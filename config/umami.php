<?php

return [
    'website_id' => env('UMAMI_WEBSITE_ID'),
    'host_analytics' => env('UMAMI_HOST_ANALYTICS', 'https://analytics.staging.nhsla.net/umami/'),
    // By default, Umami will send data to wherever the script is located. You can override this to send data to another location.
    'host_url' => env('UMAMI_HOST_URL'),
    // By default, Umami tracks all pageviews and events for you automatically. You can disable this behavior and track events yourself using the tracker functions. https://umami.is/docs/tracker-functions
    'auto_track' => env('UMAMI_AUTO_TRACK', true),
    // If you want the tracker to only run on specific domains, you can add them to your tracker script. This is a comma delimited list of domain names. Helps if you are working in a staging/development environment.
    'domains' => env('UMAMI_DOMAINS'),
    // If you want the tracker to collect events under a specific tag. Events can be filtered in the dashboard by a specific tag.
    'tag' => env('UMAMI_TAG'),
    // If you don't want to collect search parameters from the URL.
    'exclude_search' => env('UMAMI_EXCLUDE_SEARCH', false),
    // If you don't want to collect the hash value from the URL.
    'exclude_hash' => env('UMAMI_EXCLUDE_HASH', false),
];
