<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#003770" />

    @php($seo = \App\Support\Seo::forRequest(request()))
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}" />
    <link rel="canonical" href="{{ $seo['canonical'] }}" />
    @foreach ($seo['alternates'] as $code => $href)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ $href }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $seo['alternates'][config('mrt.default_locale', 'id')] }}" />
    <meta property="og:site_name" content="M.R.T — Marine Raider Tadulako" />
    <meta property="og:type" content="{{ $seo['type'] }}" />
    <meta property="og:title" content="{{ $seo['title'] }}" />
    <meta property="og:description" content="{{ $seo['description'] }}" />
    <meta property="og:url" content="{{ $seo['canonical'] }}" />
    <meta property="og:image" content="{{ $seo['image'] }}" />
    <meta property="og:locale" content="{{ $seo['locale'] === 'id' ? 'id_ID' : 'en_US' }}" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="icon" href="/favicon.svg" type="image/svg+xml" />

    @vite(['resources/js/app.ts'])
    @inertiaHead
</head>

<body class="antialiased">
    @inertia
</body>

</html>
