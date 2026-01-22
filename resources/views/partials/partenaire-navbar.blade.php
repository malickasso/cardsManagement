<!-- Top Navbar for Partner Users -->
<header class="bg-white/80 glass-effect border-b border-slate-200/50 shadow-sm z-30">
    <div class="flex items-center justify-between px-4 lg:px-8 py-4">
        <!-- Left Section: Mobile Menu + Breadcrumb -->
        <div class="flex items-center gap-4 flex-1">
            <!-- Mobile Menu Button -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors"
            >
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex items-center flex-1 max-w-xl">
                <div class="relative w-full">
                    <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                    <input
                        type="text"
                        placeholder="Rechercher une carte, un client..."
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-transparent transition-all text-sm"
                    >
                </div>
            </div>
        </div>

        <!-- Right Section: Actions + Profile -->
        <div class="flex items-center gap-3">
            <!-- Mobile Search -->
            <button class="md:hidden p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>

            <!-- Notifications -->
            <div class="relative" x-data="{ notifOpen: false }">
                <button
                    @click="notifOpen = !notifOpen"
                    class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors relative"
                >
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Notifications Dropdown -->
                <div
                    x-show="notifOpen"
                    @click.away="notifOpen = false"
                    x-transition
                    class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50"
                    style="display: none;"
                >
                    <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                        <p class="font-semibold">Notifications</p>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                        <a href="#" class="flex items-start gap-3 p-4 hover:bg-slate-50 transition-colors">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Carte activee</p>
                                <p class="text-xs text-slate-500">La carte #4532 a ete activee avec succes</p>
                                <p class="text-xs text-indigo-600 mt-1">Il y a 5 min</p>
                            </div>
                        </a>
                        <a href="#" class="flex items-start gap-3 p-4 hover:bg-slate-50 transition-colors">
                            <div class="p-2 bg-amber-100 rounded-lg">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Demande en attente</p>
                                <p class="text-xs text-slate-500">Votre demande d'activation est en cours</p>
                                <p class="text-xs text-indigo-600 mt-1">Il y a 1h</p>
                            </div>
                        </a>
                    </div>
                    <a href="#" class="block px-4 py-3 text-center text-sm text-indigo-600 hover:bg-indigo-50 font-medium border-t border-slate-100">
                        Voir toutes les notifications
                    </a>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex items-center gap-3 p-2 hover:bg-slate-100 rounded-xl transition-colors"
                >
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                        @php
                            $initials = Auth::user()->nom_proprietaire ?? 'P';
                            $initials = strtoupper(substr($initials, 0, 1)) . (Auth::user()->prenom_proprietaire ? strtoupper(substr(Auth::user()->prenom_proprietaire, 0, 1)) : '');
                        @endphp
                        {{ $initials }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-slate-900">{{ (Auth::user()->nom_proprietaire ?? '') . ' ' . (Auth::user()->prenom_proprietaire ?? '') }}</p>
                        <p class="text-xs text-slate-500">Partenaire</p>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden md:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50"
                    style="display: none;"
                >
                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="font-semibold text-slate-900">{{ (Auth::user()->nom_proprietaire ?? '') . ' ' . (Auth::user()->prenom_proprietaire ?? '') }}</p>
                        <p class="text-sm text-slate-500 truncate">{{ Auth::user()->email ?? 'partenaire@example.com' }}</p>
                    </div>

                    <div class="py-2">
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors">
                            <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                            <span class="text-sm text-slate-700">Mon Profil</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors">
                            <i data-lucide="settings" class="w-4 h-4 text-slate-500"></i>
                            <span class="text-sm text-slate-700">Parametres</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 transition-colors">
                            <i data-lucide="headphones" class="w-4 h-4 text-slate-500"></i>
                            <span class="text-sm text-slate-700">Support</span>
                        </a>
                    </div>

                    <div class="border-t border-slate-100 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 transition-colors text-red-600 w-full text-left">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span class="text-sm font-medium">Deconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
