<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/megaphone/css/megaphone.css') }}">

    <style>
        html {
  height: 100%;
}
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <main>
        <div class="bg-indigo-900 relative overflow-hidden h-screen">
            <img src="https://external-preview.redd.it/4MddL-315mp40uH18BgGL2-5b6NIPHcDMBSWuN11ynM.jpg?width=960&crop=smart&auto=webp&s=b98d54a43b3dac555df398588a2c791e0f3076d9"
                class="absolute h-full w-full object-cover" />
            <div class="inset-0 bg-black opacity-25 absolute">
            </div>
            <div class="container mx-auto px-6 md:px-12 relative z-10 flex items-center py-32 xl:py-28">
                <div class="w-full font-mono flex flex-col items-center relative z-10">
                    <a href="/">
                        <img width="200" class="" src="{{asset('/logo/logoClair.svg')}}">
                        <p class="text-white ml-4">{{__('Retourner à l\'accueil')}}</p>
                    </a>
                    <h1 class="font-extrabold text-5xl text-center text-white leading-tight mt-4">
                        {{_('Vous êtes seul ici')}}
                        </h1>
                    <p class="font-extrabold text-8xl my-44 text-white animate-bounce">
                        404
                    </p>
                </div>
            </div>
        </div>
    </main>
    </main>
    </div>
</body>

</html>
