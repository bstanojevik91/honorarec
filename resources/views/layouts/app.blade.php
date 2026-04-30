<!DOCTYPE html>
<html lang="mk">
<head>
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-M7V2JM3Z');
    </script>
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
</head>
<body class="overflow-x-hidden bg-stone-50 font-sans text-slate-900 antialiased">
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M7V2JM3Z" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @yield('content')
</body>
</html>
