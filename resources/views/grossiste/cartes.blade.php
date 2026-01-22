@extends('layouts.grossiste')

@section('title', 'Gestion des Cartes')

@section('content')
    <div class="p-6 lg:p-8" x-data="cartesManager()">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Gestion des Cartes</h1>
                    <p class="text-gray-500 mt-1">Creez et gerez les cartes de votre stock</p>
                </div>
                <button @click="showCreateModal = true"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Creer une Carte
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Cartes -->
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Cartes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total'] ?? 1250) }}</p>
                        <p class="text-xs text-emerald-600 font-medium mt-2 flex items-center gap-1">
                            <i data-lucide="trending-up" class="w-3 h-3"></i>
                            +12% ce mois
                        </p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-7 h-7 text-emerald-600"></i>
                    </div>
                </div>
            </div>

            <!-- Cartes HIGH -->
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cartes HIGH</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['high'] ?? 320) }}</p>
                        <p class="text-xs text-amber-600 font-medium mt-2 flex items-center gap-1">
                            <i data-lucide="crown" class="w-3 h-3"></i>
                            Premium
                        </p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center">
                        <i data-lucide="star" class="w-7 h-7 text-amber-600"></i>
                    </div>
                </div>
            </div>

            <!-- Cartes MID -->
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cartes MID</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['mid'] ?? 580) }}</p>
                        <p class="text-xs text-blue-600 font-medium mt-2 flex items-center gap-1">
                            <i data-lucide="layers" class="w-3 h-3"></i>
                            Standard
                        </p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-7 h-7 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Cartes LOW -->
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cartes LOW</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['low'] ?? 350) }}</p>
                        <p class="text-xs text-gray-600 font-medium mt-2 flex items-center gap-1">
                            <i data-lucide="zap" class="w-3 h-3"></i>
                            Basique
                        </p>
                    </div>
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-gray-100 to-slate-100 rounded-2xl flex items-center justify-center">
                        <i data-lucide="wallet" class="w-7 h-7 text-gray-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1 relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="text" x-model="searchQuery" placeholder="Rechercher par numero de carte..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>

                <!-- Type Filter -->
                <select x-model="filterType"
                    class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all min-w-[160px]">
                    <option value="">Tous les types</option>
                    <option value="HIGH">HIGH</option>
                    <option value="MID">MID</option>
                    <option value="LOW">LOW</option>
                    <option value="PREMIUM">PREMIUM</option>
                </select>

                <!-- Status Filter -->
                <select x-model="filterStatus"
                    class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all min-w-[160px]">
                    <option value="">Tous les statuts</option>
                    <option value="disponible">Disponible</option>
                    <option value="attribuee">Attribuee</option>
                    <option value="activee">Activee</option>
                    <option value="expiree">Expiree</option>
                </select>

                <!-- Bulk Create Button -->
                <button @click="showBulkModal = true"
                    class="inline-flex items-center gap-2 px-5 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                    Creation en masse
                </button>
            </div>
        </div>

        <!-- Cards Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Liste des Cartes</h3>
                    <span class="text-sm text-gray-500">{{ $cartes->count() ?? 0 }} cartes trouvees</span>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <input type="checkbox"
                                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Numero de Carte</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date d'Expiration</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Partenaire</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date Creation</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cartes ?? [] as $carte)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                            <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div>
                                            <p class="font-mono font-semibold text-gray-900">{{ $carte->numero }}</p>
                                            <p class="text-xs text-gray-500">ID: {{ $carte->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'HIGH' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'MID' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'LOW' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            'PREMIUM' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg border {{ $typeColors[$carte->type] ?? 'bg-gray-100 text-gray-700' }}">
                                        @if ($carte->type === 'HIGH')
                                            <i data-lucide="crown" class="w-3.5 h-3.5"></i>
                                        @elseif($carte->type === 'PREMIUM')
                                            <i data-lucide="diamond" class="w-3.5 h-3.5"></i>
                                        @else
                                            <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                        @endif
                                        {{ $carte->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                        <span
                                            class="text-gray-700">{{ \Carbon\Carbon::parse($carte->date_expiration)->format('d/m/Y') }}</span>
                                    </div>
                                    @if (\Carbon\Carbon::parse($carte->date_expiration)->isPast())
                                        <p class="text-xs text-red-500 mt-1">Expiree</p>
                                    @elseif(\Carbon\Carbon::parse($carte->date_expiration)->diffInDays(now()) <= 30)
                                        <p class="text-xs text-amber-500 mt-1">Expire bientot</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'disponible' => 'bg-emerald-100 text-emerald-700',
                                            'attribuee' => 'bg-blue-100 text-blue-700',
                                            'activee' => 'bg-green-100 text-green-700',
                                            'expiree' => 'bg-red-100 text-red-700',
                                        ];
                                        $statusIcons = [
                                            'disponible' => 'circle-dot',
                                            'attribuee' => 'user-check',
                                            'activee' => 'check-circle',
                                            'expiree' => 'x-circle',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full {{ $statusColors[$carte->statut] ?? 'bg-gray-100 text-gray-700' }}">
                                        <i data-lucide="{{ $statusIcons[$carte->statut] ?? 'circle' }}"
                                            class="w-3.5 h-3.5"></i>
                                        {{ ucfirst($carte->statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($carte->partenaire)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($carte->partenaire->raison_sociale ?? 'P', 0, 2)) }}
                                            </div>
                                            <span
                                                class="text-gray-700 text-sm">{{ $carte->partenaire->raison_sociale ?? '-' }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">Non attribuee</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($carte->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button @click="viewCarte({{ $carte->id }})"
                                            class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                            title="Voir details">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="editCarte({{ $carte->id }})"
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="Modifier">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        @if ($carte->statut === 'disponible')
                                            <button @click="assignCarte({{ $carte->id }})"
                                                class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all"
                                                title="Attribuer">
                                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                        <button @click="deleteCarte({{ $carte->id }})"
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            title="Supprimer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- Sample Data for Preview -->
                            @foreach ([['numero' => 'CARD-2024-ABCD-1234-5678', 'type' => 'HIGH', 'date_expiration' => '2026-12-31', 'statut' => 'disponible', 'partenaire' => null], ['numero' => 'CARD-2024-EFGH-9012-3456', 'type' => 'MID', 'date_expiration' => '2025-06-30', 'statut' => 'attribuee', 'partenaire' => 'Entreprise ABC'], ['numero' => 'CARD-2024-IJKL-7890-1234', 'type' => 'LOW', 'date_expiration' => '2025-03-15', 'statut' => 'activee', 'partenaire' => 'Commerce XYZ'], ['numero' => 'CARD-2024-MNOP-5678-9012', 'type' => 'PREMIUM', 'date_expiration' => '2027-01-01', 'statut' => 'disponible', 'partenaire' => null], ['numero' => 'CARD-2023-QRST-3456-7890', 'type' => 'HIGH', 'date_expiration' => '2024-01-15', 'statut' => 'expiree', 'partenaire' => 'Societe DEF']] as $index => $carte)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                                <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-mono font-semibold text-gray-900 text-sm">
                                                    {{ $carte['numero'] }}</p>
                                                <p class="text-xs text-gray-500">ID: {{ $index + 1 }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $typeColors = [
                                                'HIGH' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'MID' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'LOW' => 'bg-gray-100 text-gray-700 border-gray-200',
                                                'PREMIUM' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg border {{ $typeColors[$carte['type']] }}">
                                            @if ($carte['type'] === 'HIGH')
                                                <i data-lucide="crown" class="w-3.5 h-3.5"></i>
                                            @elseif($carte['type'] === 'PREMIUM')
                                                <i data-lucide="diamond" class="w-3.5 h-3.5"></i>
                                            @else
                                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                            @endif
                                            {{ $carte['type'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                            <span
                                                class="text-gray-700">{{ \Carbon\Carbon::parse($carte['date_expiration'])->format('d/m/Y') }}</span>
                                        </div>
                                        @if (\Carbon\Carbon::parse($carte['date_expiration'])->isPast())
                                            <p class="text-xs text-red-500 mt-1">Expiree</p>
                                        @elseif(\Carbon\Carbon::parse($carte['date_expiration'])->diffInDays(now()) <= 90)
                                            <p class="text-xs text-amber-500 mt-1">Expire bientot</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'disponible' => 'bg-emerald-100 text-emerald-700',
                                                'attribuee' => 'bg-blue-100 text-blue-700',
                                                'activee' => 'bg-green-100 text-green-700',
                                                'expiree' => 'bg-red-100 text-red-700',
                                            ];
                                            $statusIcons = [
                                                'disponible' => 'circle-dot',
                                                'attribuee' => 'user-check',
                                                'activee' => 'check-circle',
                                                'expiree' => 'x-circle',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full {{ $statusColors[$carte['statut']] }}">
                                            <i data-lucide="{{ $statusIcons[$carte['statut']] }}"
                                                class="w-3.5 h-3.5"></i>
                                            {{ ucfirst($carte['statut']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($carte['partenaire'])
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr($carte['partenaire'], 0, 2)) }}
                                                </div>
                                                <span class="text-gray-700 text-sm">{{ $carte['partenaire'] }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">Non attribuee</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ now()->subDays(rand(1, 30))->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-1">
                                            <button
                                                class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                title="Voir details">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                            <button
                                                class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                title="Modifier">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>
                                            @if ($carte['statut'] === 'disponible')
                                                <button
                                                    class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all"
                                                    title="Attribuer">
                                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                                </button>
                                            @endif
                                            <button
                                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                title="Supprimer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden divide-y divide-gray-100">
                @foreach ([['numero' => 'CARD-2024-ABCD-1234-5678', 'type' => 'HIGH', 'date_expiration' => '2026-12-31', 'statut' => 'disponible', 'partenaire' => null], ['numero' => 'CARD-2024-EFGH-9012-3456', 'type' => 'MID', 'date_expiration' => '2025-06-30', 'statut' => 'attribuee', 'partenaire' => 'Entreprise ABC'], ['numero' => 'CARD-2024-IJKL-7890-1234', 'type' => 'LOW', 'date_expiration' => '2025-03-15', 'statut' => 'activee', 'partenaire' => 'Commerce XYZ']] as $carte)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                                    <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-mono font-semibold text-gray-900 text-sm">
                                        {{ Str::limit($carte['numero'], 20) }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @php
                                            $typeColors = [
                                                'HIGH' => 'bg-amber-100 text-amber-700',
                                                'MID' => 'bg-blue-100 text-blue-700',
                                                'LOW' => 'bg-gray-100 text-gray-700',
                                            ];
                                        @endphp
                                        <span
                                            class="text-xs font-bold px-2 py-0.5 rounded {{ $typeColors[$carte['type']] }}">{{ $carte['type'] }}</span>
                                    </div>
                                </div>
                            </div>
                            @php
                                $statusColors = [
                                    'disponible' => 'bg-emerald-100 text-emerald-700',
                                    'attribuee' => 'bg-blue-100 text-blue-700',
                                    'activee' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span
                                class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColors[$carte['statut']] }}">
                                {{ ucfirst($carte['statut']) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div>
                                <p class="text-gray-500 text-xs">Expiration</p>
                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($carte['date_expiration'])->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs">Partenaire</p>
                                <p class="font-medium text-gray-900">{{ $carte['partenaire'] ?? 'Non attribuee' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                            <button
                                class="flex-1 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                                Voir
                            </button>
                            <button
                                class="flex-1 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                Modifier
                            </button>
                            @if ($carte['statut'] === 'disponible')
                                <button
                                    class="flex-1 py-2 text-sm font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                    Attribuer
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-600">
                        Affichage de <span class="font-semibold">1</span> a <span class="font-semibold">5</span> sur <span
                            class="font-semibold">1250</span> cartes
                    </p>
                    <div class="flex items-center gap-2">
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 disabled:opacity-50"
                            disabled>
                            Precedent
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg">1</button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">2</button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">3</button>
                        <span class="px-2 text-gray-400">...</span>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">250</button>
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                            Suivant
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Card Modal -->
        <div x-show="showCreateModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div @click.away="showCreateModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Creer une Carte</h3>
                                <p class="text-emerald-100 text-sm">Remplissez les informations</p>
                            </div>
                        </div>
                        <button @click="showCreateModal = false" class="text-white/80 hover:text-white transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('cartes.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Card Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type de Carte <span
                                class="text-red-500">*</span></label>
                        <div class="grid grid-cols-4 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="LOW" class="sr-only peer" required>
                                <div
                                    class="p-3 text-center border-2 border-gray-200 rounded-xl peer-checked:border-gray-600 peer-checked:bg-gray-50 transition-all">
                                    <i data-lucide="wallet" class="w-6 h-6 mx-auto text-gray-600 mb-1"></i>
                                    <p class="text-xs font-bold text-gray-700">LOW</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="MID" class="sr-only peer">
                                <div
                                    class="p-3 text-center border-2 border-gray-200 rounded-xl peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                                    <i data-lucide="credit-card" class="w-6 h-6 mx-auto text-blue-600 mb-1"></i>
                                    <p class="text-xs font-bold text-blue-700">MID</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="HIGH" class="sr-only peer">
                                <div
                                    class="p-3 text-center border-2 border-gray-200 rounded-xl peer-checked:border-amber-600 peer-checked:bg-amber-50 transition-all">
                                    <i data-lucide="crown" class="w-6 h-6 mx-auto text-amber-600 mb-1"></i>
                                    <p class="text-xs font-bold text-amber-700">HIGH</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="PREMIUM" class="sr-only peer">
                                <div
                                    class="p-3 text-center border-2 border-gray-200 rounded-xl peer-checked:border-purple-600 peer-checked:bg-purple-50 transition-all">
                                    <i data-lucide="diamond" class="w-6 h-6 mx-auto text-purple-600 mb-1"></i>
                                    <p class="text-xs font-bold text-purple-700">PREMIUM</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Card Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Numero de Carte <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="numero" x-model="cardNumber" maxlength="20"
                                placeholder="Ex: CARD2024ABCD12345678"
                                class="w-full pl-4 pr-24 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-mono uppercase tracking-wider"
                                required>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                                <span class="text-xs text-gray-400" x-text="cardNumber.length + '/20'"></span>
                                <button type="button" @click="generateCardNumber()"
                                    class="px-2 py-1 text-xs font-medium text-emerald-600 bg-emerald-100 rounded-lg hover:bg-emerald-200 transition-colors">
                                    Generer
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">20 caracteres alphanumeriques</p>
                    </div>

                    <!-- Expiration Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'Expiration <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="date_expiration" min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            required>
                    </div>

                    <!-- Quick Expiration Options -->
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-2">Duree de validite rapide</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="setExpiration(1)"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">1
                                an</button>
                            <button type="button" @click="setExpiration(2)"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">2
                                ans</button>
                            <button type="button" @click="setExpiration(3)"
                                class="px-3 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-100 rounded-lg hover:bg-emerald-200 transition-colors">3
                                ans</button>
                            <button type="button" @click="setExpiration(5)"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">5
                                ans</button>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="showCreateModal = false"
                            class="flex-1 px-4 py-3 text-gray-700 font-semibold bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 text-white font-semibold bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl hover:shadow-lg hover:shadow-emerald-500/30 transition-all">
                            Creer la Carte
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Create Modal -->
        <div x-show="showBulkModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div @click.away="showBulkModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i data-lucide="layers" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Creation en Masse</h3>
                                <p class="text-blue-100 text-sm">Creer plusieurs cartes</p>
                            </div>
                        </div>
                        <button @click="showBulkModal = false" class="text-white/80 hover:text-white transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('cartes.bulk-store') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Card Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type de Carte <span
                                class="text-red-500">*</span></label>
                        <select name="type"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Selectionnez un type</option>
                            <option value="LOW">LOW - Basique</option>
                            <option value="MID">MID - Standard</option>
                            <option value="HIGH">HIGH - Premium</option>
                            <option value="PREMIUM">PREMIUM - VIP</option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de Cartes <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="quantity" x-model="bulkQuantity" min="1" max="1000"
                            placeholder="Ex: 50"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 cartes par operation</p>
                    </div>

                    <!-- Prefix -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prefixe des Numeros</label>
                        <input type="text" name="prefix" maxlength="8" placeholder="Ex: CARD2024"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono uppercase">
                        <p class="text-xs text-gray-500 mt-1">Les numeros seront generes automatiquement</p>
                    </div>

                    <!-- Expiration Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'Expiration <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="date_expiration" min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <!-- Preview -->
                    <div x-show="bulkQuantity > 0" class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center gap-2 text-blue-700">
                            <i data-lucide="info" class="w-5 h-5"></i>
                            <p class="text-sm font-medium">
                                <span x-text="bulkQuantity"></span> cartes seront creees avec des numeros uniques generes
                                automatiquement.
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="showBulkModal = false"
                            class="flex-1 px-4 py-3 text-gray-700 font-semibold bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 text-white font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                            Creer les Cartes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assign Card Modal -->
        <div x-show="showAssignModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div @click.away="showAssignModal = false"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i data-lucide="user-plus" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Attribuer la Carte</h3>
                                <p class="text-purple-100 text-sm">Selectionnez un partenaire</p>
                            </div>
                        </div>
                        <button @click="showAssignModal = false" class="text-white/80 hover:text-white transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('cartes.assign', ['id' => ':id']) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- Partner Select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Partenaire <span
                                class="text-red-500">*</span></label>
                        <select name="partenaire_id"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required>
                            <option value="">Selectionnez un partenaire</option>
                            @foreach ($partenaires ?? [] as $partenaire)
                                <option value="{{ $partenaire->id }}">{{ $partenaire->raison_sociale }}</option>
                            @endforeach
                            <option value="1">Entreprise ABC</option>
                            <option value="2">Commerce XYZ</option>
                            <option value="3">Societe DEF</option>
                        </select>
                    </div>

                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Note (optionnel)</label>
                        <textarea name="note" rows="3" placeholder="Ajouter une note..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="showAssignModal = false"
                            class="flex-1 px-4 py-3 text-gray-700 font-semibold bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 text-white font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl hover:shadow-lg hover:shadow-purple-500/30 transition-all">
                            Attribuer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function cartesManager() {
            return {
                showCreateModal: false,
                showBulkModal: false,
                showAssignModal: false,
                searchQuery: '',
                filterType: '',
                filterStatus: '',
                cardNumber: '',
                bulkQuantity: 0,

                generateCardNumber() {
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    let result = 'CARD';
                    const year = new Date().getFullYear();
                    result += year;
                    for (let i = 0; i < 12; i++) {
                        result += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.cardNumber = result.substring(0, 20);
                },

                setExpiration(years) {
                    const date = new Date();
                    date.setFullYear(date.getFullYear() + years);
                    const formattedDate = date.toISOString().split('T')[0];
                    document.querySelector('input[name="date_expiration"]').value = formattedDate;
                },

                viewCarte(id) {
                    window.location.href = `/cartes/${id}`;
                },

                editCarte(id) {
                    // Open edit modal or redirect
                    console.log('Edit carte:', id);
                },

                assignCarte(id) {
                    this.showAssignModal = true;
                },

                deleteCarte(id) {
                    if (confirm('Etes-vous sur de vouloir supprimer cette carte ?')) {
                        // Submit delete request
                        console.log('Delete carte:', id);
                    }
                }
            }
        }
    </script>

@endsection
