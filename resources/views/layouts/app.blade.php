<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    @include('partials.meta', [
        'title' => $metaTitle ?? null,
        'metaDescription' => $metaDescription ?? null,
        'canonical' => $canonical ?? null,
        'ogTitle' => $ogTitle ?? null,
        'ogDescription' => $ogDescription ?? null,
        'ogImage' => $ogImage ?? null,
        'ogUrl' => $ogUrl ?? null,
        'robots' => $robots ?? null,
    ])

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts: Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Nunito:wght@200..1000&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    {{-- Font fallback styles --}}
    <style>
        body {
            font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .font-quicksand {
            font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .font-inter {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .font-nunito {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>

    {{-- Flowbite: Defer for performance --}}
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js" defer></script>
</head>

<body class="antialiased bg-[#FAFAFA] font-quicksand">
    @isset($banner)
        @include('layouts.banner')
    @endisset

    @include('layouts.header')
    <main>
        @yield('content')
    </main>

    @include('layouts.footer')
</body>

</html>
