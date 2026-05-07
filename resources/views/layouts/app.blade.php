<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Honorarec.mk' }}</title>
    <meta name="description" content="{{ $description ?? 'Хонорарец.мк - најди работа на дневница.' }}">
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="apple-touch-icon" href="/favicon.png">
    @if (!empty($canonical))
        <link rel="canonical" href="{{ $canonical }}">
    @endif
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'Honorarec.mk') }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($description ?? 'Хонорарец.мк - најди работа на дневница.') }}">
    <meta property="og:url" content="{{ $ogUrl ?? ($canonical ?? request()->url()) }}">
    @if (!empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
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
</head>
<body class="overflow-x-hidden bg-stone-50 font-sans text-slate-900 antialiased">
    @yield('content')
</body>
</html>
