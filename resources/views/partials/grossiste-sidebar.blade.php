<!-- Sidebar for Grossiste Users -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-emerald-900 via-emerald-800 to-teal-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl"
>
    <!-- Logo Section -->
    <div class="flex items-center justify-between p-6 border-b border-emerald-700/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                <i data-lucide="layers" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white">GrossistePro</h1>
                <p class="text-xs text-emerald-300">Espace Grossiste</p>
            </div>
        </div>
        <button
            @click="sidebarOpen = false"
            class="lg:hidden text-emerald-200 hover:text-white transition-colors"
        >
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('grossiste.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('grossiste.dashboard') ? 'bg-emerald-700/50 text-white' : 'text-emerald-100 hover:bg-emerald-700/50 hover:text-white' }} rounded-xl font-medium transition-all group">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Tableau de Bord</span>
            <div class="ml-auto w-2 h-2 bg-emerald-400 rounded-full"></div>
        </a>

        <a href="{{ route('grossiste.dotation') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('grossiste.dotation') ? 'bg-emerald-700/50 text-white' : 'text-emerald-100 hover:bg-emerald-700/50 hover:text-white' }} rounded-xl font-medium transition-all group">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Dotations</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-emerald-100 hover:bg-emerald-700/50 hover:text-white font-medium transition-all group">
            <i data-lucide="credit-card" class="w-5 h-5"></i>
            <span>Cartes</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-emerald-100 hover:bg-emerald-700/50 hover:text-white font-medium transition-all group">
            <i data-lucide="settings" class="w-5 h-5"></i>
            <span>Paramètres</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-emerald-100 hover:bg-emerald-700/50 hover:text-white font-medium transition-all group">
            <i data-lucide="life-buoy" class="w-5 h-5"></i>
            <span>Support</span>
        </a>
    </nav>

    <!-- User Profile Section -->
    <div class="p-4 border-t border-emerald-700/50 bg-emerald-900/50">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-800/50">
            <div class="relative">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold shadow-lg">
                    @php
                        $initials = Auth::user()->nom_proprietaire ?? 'G';
                        $initials = strtoupper(substr($initials, 0, 1)) . (Auth::user()->prenom_proprietaire ? strtoupper(substr(Auth::user()->prenom_proprietaire, 0, 1)) : '');
                    @endphp
                    {{ $initials }}
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 border-2 border-emerald-900 rounded-full"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-white text-sm truncate">{{ Auth::user()->nom_proprietaire ?? 'N/A' }} {{ Auth::user()->prenom_proprietaire ?? '' }}</p>
                <p class="text-xs text-emerald-300 truncate">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-emerald-300 hover:text-white transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div
    x-show="sidebarOpen"
    @click="sidebarOpen = false"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-900/80 lg:hidden z-40"
    style="display: none;"
></div>
