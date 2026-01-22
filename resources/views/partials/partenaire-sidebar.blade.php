<!-- Sidebar for Partner Users -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-indigo-950 via-indigo-900 to-violet-950 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col shadow-2xl"
>
    <!-- Logo Section -->
    <div class="flex items-center justify-between p-6 border-b border-indigo-800/50">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-gradient-to-br from-indigo-400 via-violet-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">CardPartner</h1>
                <p class="text-xs text-indigo-300">Espace Partenaire</p>
            </div>
        </div>
        <button
            @click="sidebarOpen = false"
            class="lg:hidden text-indigo-200 hover:text-white transition-colors"
        >
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-indigo-100 hover:bg-indigo-800/50 hover:text-white font-medium transition-all group">
            <div class="p-2 rounded-lg bg-indigo-800/50 group-hover:bg-indigo-700/50 transition-colors">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            </div>
            <span>Tableau de Bord</span>
        </a>

        <a href="{{ route('partenaire.cartes.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600/50 to-violet-600/50 text-white font-medium transition-all group shadow-lg shadow-indigo-900/20">
            <div class="p-2 rounded-lg bg-white/10 transition-colors">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
            </div>
            <span>Mes Cartes</span>
            <span class="ml-auto bg-white/20 text-white text-xs px-2.5 py-1 rounded-full font-bold">24</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-indigo-100 hover:bg-indigo-800/50 hover:text-white font-medium transition-all group">
            <div class="p-2 rounded-lg bg-indigo-800/50 group-hover:bg-indigo-700/50 transition-colors">
                <i data-lucide="send" class="w-5 h-5"></i>
            </div>
            <span>Demandes d'Activation</span>
            <span class="ml-auto bg-amber-500/90 text-white text-xs px-2.5 py-1 rounded-full font-bold">3</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-indigo-100 hover:bg-indigo-800/50 hover:text-white font-medium transition-all group">
            <div class="p-2 rounded-lg bg-indigo-800/50 group-hover:bg-indigo-700/50 transition-colors">
                <i data-lucide="history" class="w-5 h-5"></i>
            </div>
            <span>Historique</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-indigo-100 hover:bg-indigo-800/50 hover:text-white font-medium transition-all group">
            <div class="p-2 rounded-lg bg-indigo-800/50 group-hover:bg-indigo-700/50 transition-colors">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </div>
            <span>Mon Solde</span>
        </a>
    </nav>

    <!-- User Profile Section -->
    <div class="p-4 border-t border-indigo-800/50 bg-indigo-950/50">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-indigo-800/50 to-violet-800/50">
            <div class="relative">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    @php
                        $initials = Auth::user()->nom_proprietaire ?? 'P';
                        $initials = strtoupper(substr($initials, 0, 1)) . (Auth::user()->prenom_proprietaire ? strtoupper(substr(Auth::user()->prenom_proprietaire, 0, 1)) : '');
                    @endphp
                    {{ $initials }}
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 border-2 border-indigo-900 rounded-full"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-white text-sm truncate">{{ (Auth::user()->nom_proprietaire ?? '') . ' ' . (Auth::user()->prenom_proprietaire ?? '') }}</p>
                <p class="text-xs text-indigo-300 truncate">{{ Auth::user()->email ?? 'partenaire@example.com' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-indigo-300 hover:text-white hover:bg-indigo-700/50 rounded-lg transition-all">
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
    class="fixed inset-0 bg-slate-900/80 lg:hidden z-40"
    style="display: none;"
></div>
