<header class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <!-- Search Bar -->
        <div class="flex-1 max-w-2xl">
            <div class="relative">
                <input
                    type="text"
                    placeholder="Rechercher des transactions, cartes..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-4 ml-6">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User Avatar & Dropdown Menu -->
            <div class="relative group">
                <!-- Avatar Button -->
                <button class="w-10 h-10 rounded-full bg-navy-900 flex items-center justify-center text-white font-semibold hover:opacity-80 transition-opacity">
                    {{ substr(auth('admin')->user()->nom, 0, 1) }}
                </button>

                <!-- Dropdown Menu -->
                <div class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <!-- User Info -->
                    <div class="pt-4 pb-1 border-b border-gray-200">
                        <div class="px-4 py-1">
                            <div class="font-medium text-base text-gray-800">
                                {{ auth('admin')->user()->nom }} {{ auth('admin')->user()->prenom }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ auth('admin')->user()->email }}
                            </div>
                        </div>

                        <hr>

                        <div class="mt-3 space-y-1">
                            <!-- Profile Option -->
                            <a href="" class="block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                {{ __('Profile') }}
                            </a>

                            <!-- Settings Option -->
                            <a href="" class="block px-4 py-1 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                {{ __('Paramètres') }}
                            </a>

                            <hr>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-1 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    {{ __('Se déconnecter') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
