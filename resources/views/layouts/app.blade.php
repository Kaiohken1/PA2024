<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/megaphone/css/megaphone.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireScripts



<body class="font-sans antialiased min-h-screen flex flex-col">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.nav')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        @auth
        <main>
            <div class="drawer drawer-end">
                <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content relative">


                    <div class="flex-grow">
                        {{ $slot }}

                    </div>
                </div>
                <div class="drawer-side drawer-side-sticky z-30">
                    <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                    <div class="bg-base-200 text-base-content h-2/3 absolute right-0 bottom-0 w-1/4 sm:rounded-t-lg">
                        @livewire('chatbot')
                    </div>
                </div>
            </div>
            <div class="z-20">
                <div class="avatar online w-24 h-24 fixed bottom-10 right-10 hover:cursor-pointer">
                    <div class="rounded-full">
                        <label for="my-drawer" class="drawer-button hover:cursor-pointer"><img src="https://cdn.dribbble.com/userupload/13167768/file/original-08e29755d8f12fdb9ef53d5b88bfeef0.jpg?resize=1024x768" /></label>

                    </div>
                </div>
        </main>
        @else
        <main>
            {{ $slot }}
        </main>
        @endauth

    </div>
    </div>


</body>

</html>