<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::check() && Auth::user()->hasRole('bailleur',  Auth::user()->id))
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif
                    
                    <x-nav-link :href="route('espace-client')" :active="request()->routeIs('espace-client') || request()->routeIs('interventions.index') || request()->routeIs('reservation') || request()->routeIs('reservation.show')">
                        {{ __('Espace Client') }}
                    </x-nav-link>

                    <x-nav-link :href="route('property.create')" :active="request()->routeIs('property.create')">
                        {{ __('Louer mon logement') }}
                    </x-nav-link>

                    <x-nav-link :href="route('providers.create')" :active="request()->routeIs('providers.create')">
                        {{ __('Devenir prestataire') }}
                    </x-nav-link>

                    <x-nav-link :href="route('tickets.create')" :active="request()->routeIs('ticket.create')">
                        {{ __('Aide') }}
                    </x-nav-link>

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                @auth
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <livewire:megaphone></livewire:megaphone>
                        {{-- @livewire('notifications') --}}

                        {{-- @if (isset($notifications))
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div class="ms-1">
                                            <x-bell-logo></x-bell-logo>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    @foreach ($notifications as $notification)
                                        <a href="{{ route('providers.show', $notification->data['prestataire_id']) }}"
                                            class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                            {{ $notification->data['message'] }}
                                        </a>
                                        {{ $user->unreadNotifications->markAsRead() }}
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        @endif --}}

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div class="avatar mr-3">
                                        <div class="w-11 rounded-full ring ring-yellow-500 ring-offset-base-100 ring-offset-2">
                                            <img src={{ Auth::user()->getImageUrl() }} />
                                        </div>
                                    </div>
                                    <div>{{ Auth::user()->first_name }} {{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('users.show', ['user' => Auth::user()->id])">
                                    {{ __('Profil') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Modifier son profil') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Se déconnecter') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Connexion') }}
                        </x-nav-link>
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            {{ __('Inscription') }}
                        </x-nav-link>
                    </div>
                @endAuth

                <x-dropdown width="28">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="ms-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <ul>
                            <a href="{{ route('locale.setting', 'fr') }}">
                                <li class="text-center hover:bg-gray-50 {{ app()->getLocale() == 'fr' ? 'bg-gray-100' : '' }}">FR</li>
                            </a>
                            <a href="{{ route('locale.setting', 'en') }}">
                                <li class="text-center hover:bg-gray-50 {{ app()->getLocale() == 'en' ? 'bg-gray-100' : '' }}">EN</li>
                            </a>
                            <a href="{{ route('locale.setting', 'es') }}">
                                <li class="text-center hover:bg-gray-50 {{ app()->getLocale() == 'es' ? 'bg-gray-100' : '' }}">ES</li>
                            </a>
                        </ul>
                    </x-slot>
                </x-dropdown>
            </div>
            

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Se déconnecter') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endAuth
    </div>
</nav>
