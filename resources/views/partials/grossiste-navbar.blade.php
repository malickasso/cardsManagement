<!-- Top Navbar for Grossiste Users -->
<header class="bg-white border-b border-gray-200 shadow-sm z-30">
    <div class="flex items-center justify-between px-4 lg:px-8 py-4">
        <!-- Left Section: Mobile Menu + Search -->
        <div class="flex items-center gap-4 flex-1">
            <!-- Mobile Menu Button -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
            >
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex items-center flex-1 max-w-2xl">
                <div class="relative w-full">
                    <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input
                        type="text"
                        placeholder="Rechercher un partenaire, une transaction..."
                        class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all"
                    >
                </div>
            </div>
        </div>

        <!-- Right Section: Quick Actions + Profile -->
        <div class="flex items-center gap-3">
            <!-- Mobile Search Icon -->
            <button class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg transition-colors"
                >
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                        @php
                            $initials = Auth::user()->nom_proprietaire ?? 'G';
                            $initials = strtoupper(substr($initials, 0, 1)) . (Auth::user()->prenom_proprietaire ? strtoupper(substr(Auth::user()->prenom_proprietaire, 0, 1)) : '');
                        @endphp
                        {{ $initials }}
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-600 hidden sm:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50"
                    style="display: none;"
                >
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="font-semibold text-gray-900">{{ Auth::user()->nom_proprietaire ?? 'N/A' }} {{ Auth::user()->prenom_proprietaire ?? '' }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>

                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                        <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                        <span class="text-sm text-gray-700">Mon Profil</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                        <i data-lucide="settings" class="w-4 h-4 text-gray-500"></i>
                        <span class="text-sm text-gray-700">Paramètres</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                        <i data-lucide="life-buoy" class="w-4 h-4 text-gray-500"></i>
                        <span class="text-sm text-gray-700">Aide & Support</span>
                    </a>

                    <div class="border-t border-gray-100 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors text-red-600 w-full text-left">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span class="text-sm font-medium">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
