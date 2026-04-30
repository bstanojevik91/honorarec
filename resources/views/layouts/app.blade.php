<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Honorarec.mk' }}</title>
    <meta name="description" content="{{ $description ?? 'Хонорарец.мк - најди работа на дневница.' }}">
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-331CW9F4XL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag()
        {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'G-331CW9F4XL');
    </script>
</head>
<body class="overflow-x-hidden bg-stone-50 font-sans text-slate-900 antialiased">
    @yield('content')
</body>
</html>
