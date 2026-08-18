<!DOCTYPE html>
<html lang="mk">
<head>
    @php
        $resolvedCanonical = \App\Support\PublicUrl::normalize($canonical ?? url()->current());
        $resolvedOgUrl = \App\Support\PublicUrl::normalize($ogUrl ?? $resolvedCanonical);
        $resolvedOgImage = ! empty($ogImage) ? \App\Support\PublicUrl::normalize($ogImage) : null;
    @endphp
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VDST4H1ZZ7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-VDST4H1ZZ7');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Honorarec.mk' }}</title>
    <meta name="description" content="{{ $description ?? 'Хонорарец.мк - најди работа на дневница.' }}">
    <link rel="icon" href="https://honorarec.mk/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" href="https://honorarec.mk/favicon.png" sizes="48x48">
    <link rel="apple-touch-icon" href="https://honorarec.mk/favicon.png">
    <link rel="canonical" href="{{ $resolvedCanonical }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'Honorarec.mk') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? 'Хонорарец.мк - најди работа на дневница.') }}">
    <meta property="og:url" content="{{ $resolvedOgUrl }}">
    @if ($resolvedOgImage)
        <meta property="og:image" content="{{ $resolvedOgImage }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    @stack('styles')
</head>
<body class="overflow-x-hidden bg-stone-50 font-sans text-slate-900 antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
