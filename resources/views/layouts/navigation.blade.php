<nav x-data="{ open: false, masterOpen: false }" class="app-nav relative z-50 bg-white/80 border-b border-cyan-300/20 backdrop-blur-md">
    <div class="max-w-[96rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 grid grid-cols-[auto,1fr,auto] items-center gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-application-logo class="block h-9 w-auto fill-current text-cyan-300" />
                <span class="hidden md:block text-sm font-bold tracking-[0.16em] uppercase text-slate-700">UMKM Sembako</span>
            </a>

            <div class="hidden sm:flex items-center justify-center gap-2 min-w-0">
                <a href="{{ route('home') }}" class="app-menu-link {{ request()->routeIs('home') ? 'app-menu-link-active' : '' }}">Home</a>
                <a href="{{ route('sales.index') }}" class="app-menu-link {{ request()->routeIs('sales.*') ? 'app-menu-link-active' : '' }}">Marketplace</a>
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('orders.my') }}" class="app-menu-link {{ request()->routeIs('orders.my') ? 'app-menu-link-active' : '' }}">Pesanan Saya</a>
                @endif

                @if(!auth()->user()->isCustomer())
                    <a href="{{ route('products.index') }}" class="app-menu-link {{ request()->routeIs('products.*') ? 'app-menu-link-active' : '' }}">Produk</a>
                    <a href="{{ route('statistics.index') }}" class="app-menu-link {{ request()->routeIs('statistics.*') ? 'app-menu-link-active' : '' }}">Statistik</a>
                    <a href="{{ route('analytics.index') }}" class="app-menu-link {{ request()->routeIs('analytics.*') ? 'app-menu-link-active' : '' }}">Analitik</a>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="relative" @mouseenter="masterOpen = true" @mouseleave="masterOpen = false">
                        <button type="button" class="app-menu-link inline-flex items-center gap-1">
                            Master Data
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 011.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-cloak x-show="masterOpen" x-transition class="dropdown-panel absolute top-full left-0 mt-2 w-48 rounded-xl p-2">
                            <a href="{{ route('categories.index') }}" class="dropdown-item block rounded-lg px-3 py-2 text-sm">Kategori</a>
                            <a href="{{ route('suppliers.index') }}" class="dropdown-item block rounded-lg px-3 py-2 text-sm">Supplier</a>
                            <a href="{{ route('users.index') }}" class="dropdown-item block rounded-lg px-3 py-2 text-sm">Users</a>
                        </div>
                    </div>

                    <a href="{{ route('reports.stock') }}" class="app-menu-link {{ request()->routeIs('reports.*') ? 'app-menu-link-active' : '' }}">Laporan</a>
                @endif
            </div>

            <div class="hidden sm:flex items-center gap-3">
                @if(!auth()->user()->isCustomer())
                    <a href="{{ route('products.index') }}" class="app-cta-button">Mulai Kelola</a>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="app-nav-trigger inline-flex items-center px-3 py-2 text-sm font-medium rounded-md focus:outline-none transition border">
                            <div>{{ Auth::user()->name }} ({{ auth()->user()->role ?? 'admin' }})</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('settings.index')">
                            Settings
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center justify-self-end sm:hidden">
                <button @click="open = ! open"
                    class="app-nav-mobile-trigger inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Home
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
                Marketplace
            </x-responsive-nav-link>
            @if(auth()->user()->isCustomer())
                <x-responsive-nav-link :href="route('orders.my')" :active="request()->routeIs('orders.my')">
                    Pesanan Saya
                </x-responsive-nav-link>
            @endif

            @if(!auth()->user()->isCustomer())
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    Produk
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.*')">
                    Statistik
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('analytics.index')" :active="request()->routeIs('analytics.*')">
                    Analitik
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    Kategori
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                    Supplier
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    Users
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('reports.stock')" :active="request()->routeIs('reports.*')">
                    Laporan
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-cyan-300/20">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('settings.index')">
                    Settings
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
