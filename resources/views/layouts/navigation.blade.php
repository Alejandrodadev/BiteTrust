<nav x-data="{ open: false }"
     class="sticky top-0 inset-x-0 z-50
            bg-white/80 backdrop-blur-sm">

<!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo + Nombre --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('landing') }}" class="mr-0.15">
                    <x-application-logo class="block h-[6rem] w-auto fill-current" />
                </a>
                <a href="{{ route('landing') }}"
                   class="hidden sm:inline-block text-xl text-secondary hover:text-primary transition-colors duration-150">
                    {{ config('app.name', 'BiteTrust') }}
                </a>
            </div>

            {{-- Auth Links (solo en sm+) --}}
            <div class="hidden sm:flex ml-auto items-center space-x-4">
                @guest
                    <a href="{{ route('access') }}"
                       class="text-gray-700 hover:text-primary px-4 py-2 rounded-md">
                        {{ __('Iniciar sesión') }}
                    </a>
                @else
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md
                                     text-gray-700 dark:text-form bg-white dark:bg-white hover:text-gray-800 dark:hover:text-gray-600
                                     focus:outline-none transition ease-in-out duration-150">
                                <div>{{ auth()->user()->name }}</div>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10
                                             10.586l3.293-3.293a1 1 0
                                             011.414 1.414l-4 4a1 1 0
                                             01-1.414 0l-4-4a1 1 0
                                             010-1.414z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Mi perfil') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('reviews.user')">
                                {{ __('Mis reseñas') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-gray-300 text-sm font-medium hover:bg-gray-800/90 transition">
                                        {{ __('Cerrar sesión') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endguest
            </div>

            <!-- Mobile Hamburger (solo en xs) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-600
                               hover:text-gray-200 dark:hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-200
                               focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden absolute top-full right-4 mt-2
         w-44 bg-gray-800/95 backdrop-blur-sm
         rounded-md shadow-lg z-50">
        @guest

            {{-- Invitados ven solo “Iniciar sesión” --}}
            <div class="flex justify-end px-4 py-2">
                <x-responsive-nav-link
                    :href="route('access')"
                    class="!inline-flex !items-center !w-auto !px-3 !py-1 !bg-gray-800 !text-white !rounded-md !text-sm !font-medium hover:!bg-gray-800/90 transition">
                    {{ __('Iniciar sesión') }}
                </x-responsive-nav-link>
            </div>
        @else
            {{-- Usuarios autenticados ven su menú --}}
            <div class="px-4 py-4 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="!rounded-md">
                    {{ __('Mi perfil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reviews.user')" class="!rounded-md">
                    {{ __('Mis reseñas') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700/75 rounded-md transition"
                    >
                        {{ __('Cerrar sesión') }}
                    </button>
                </form>

            </div>
        @endguest
    </div>

</nav>
