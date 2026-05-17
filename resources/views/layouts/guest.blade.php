<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Thesis Atlas - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #FFFCF2;
                background-image:
                    radial-gradient(circle at 20% 20%, rgba(235, 94, 40, 0.06), transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(64, 61, 57, 0.04), transparent 50%);
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-xl shadow-[#252422]/5 overflow-hidden sm:rounded-3xl border border-[#CCC5B9]/20">
                <div class="mb-8">
                    <a href="/" class="flex items-center gap-2 font-bold text-xl text-[#252422]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#EB5E28]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        Thesis<span class="text-[#EB5E28]">Atlas</span>
                    </a>
                    <p class="mt-2 text-sm text-[#CCC5B9]">Academic Research Repository</p>
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
