@php
    use JeffersonGoncalves\Umami\Settings\UmamiSettings;

    /** @var UmamiSettings $umami */
    $umami = app(UmamiSettings::class);
@endphp

@if(!empty($umami->website_id))
    <script
            async
            defer
            data-website-id="{{ $umami->website_id }}"
            src="{{ $umami->host_analytics }}"
            @if($umami->host_url) data-host-url="{{ $umami->host_url }}" @endif
            @if($umami->domains) data-domains="{{ $umami->domains }}" @endif
            @if($umami->tag) data-tag="{{ $umami->tag }}" @endif
            data-auto-track="{{ $umami->auto_track ? 'true' : 'false' }}"
            data-exclude-search="{{ $umami->exclude_search ? 'true' : 'false' }}"
            data-exclude-hash="{{ $umami->exclude_hash ? 'true' : 'false' }}"
    ></script>
@endif
