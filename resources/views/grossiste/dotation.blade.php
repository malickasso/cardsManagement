@extends('layouts.grossiste')

@section('title', 'Revue des Demandes')

@section('content')
    <div class="p-6 lg:p-8" x-data="{
        filterStatus: 'all',
        searchQuery: '',
        showRejectModal: false,
        selectedDemand: null,
        rejectReason: '',
        demands: {{ json_encode($demandes ?? []) }}
    }">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Revue des Demandes</h1>
            <p class="text-gray-600">Gérez les demandes de dotation et de rechargement de vos partenaires</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="inbox" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Total Demandes</p>
                <p class="text-3xl font-bold text-gray-900">{{ count($demandes ?? []) }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">En Attente</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ collect($demandes ?? [])->where('statut', 'En attente')->count() }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Approuvées</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ collect($demandes ?? [])->where('statut', 'Approuvé')->count() }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Rejetées</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ collect($demandes ?? [])->where('statut', 'Rejeté')->count() }}</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input x-model="searchQuery" type="text" placeholder="Rechercher par nom de partenaire..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="flex gap-2 overflow-x-auto">
                    <button @click="filterStatus = 'all'"
                        :class="filterStatus === 'all' ? 'bg-emerald-600 text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-5 py-3 rounded-xl font-medium whitespace-nowrap transition-all">
                        Toutes
                    </button>
                    <button @click="filterStatus = 'En attente'"
                        :class="filterStatus === 'En attente' ? 'bg-amber-600 text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-5 py-3 rounded-xl font-medium whitespace-nowrap transition-all">
                        En Attente
                    </button>
                    <button @click="filterStatus = 'Approuvé'"
                        :class="filterStatus === 'Approuvé' ? 'bg-emerald-600 text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-5 py-3 rounded-xl font-medium whitespace-nowrap transition-all">
                        Approuvées
                    </button>
                    <button @click="filterStatus = 'Rejeté'"
                        :class="filterStatus === 'Rejeté' ? 'bg-red-600 text-white' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-5 py-3 rounded-xl font-medium whitespace-nowrap transition-all">
                        Rejetées
                    </button>
                </div>
            </div>
        </div>

        <!-- Demands Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($demandes ?? [] as $demande)
                <div x-show="(filterStatus === 'all' || '{{ $demande['statut'] }}' === filterStatus) && ('{{ strtolower($demande['partenaire']) }}'.includes(searchQuery.toLowerCase()) || searchQuery === '')"
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300">
                    <!-- Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                        {{ strtoupper(substr($demande['partenaire'], 0, 2)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-900">{{ $demande['partenaire'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $demande['type'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <span
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap
                        @if ($demande['statut'] === 'En attente') bg-amber-50 text-amber-700 border border-amber-200
                        @elseif($demande['statut'] === 'Approuvé') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @else bg-red-50 text-red-700 border border-red-200 @endif">
                                @if ($demande['statut'] === 'En attente')
                                    <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>
                                @elseif($demande['statut'] === 'Approuvé')
                                    <i data-lucide="check-circle" class="w-3 h-3 inline mr-1"></i>
                                @else
                                    <i data-lucide="x-circle" class="w-3 h-3 inline mr-1"></i>
                                @endif
                                {{ $demande['statut'] }}
                            </span>
                        </div>

                        <!-- Amount -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 mb-4">
                            <p class="text-sm text-gray-600 mb-1">Montant Demandé</p>
                            <p class="text-2xl font-bold text-emerald-700">
                                {{ number_format($demande['montant'], 0, ',', ' ') }} FCFA</p>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Date de demande</p>
                                <p class="text-sm font-medium text-gray-900">{{ $demande['date'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Référence</p>
                                <p class="text-sm font-medium text-gray-900">#{{ $demande['reference'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="px-6 py-4 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">Documents joints
                            ({{ count($demande['documents']) }})</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($demande['documents'] as $doc)
                                <div
                                    class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors cursor-pointer group">
                                    <i data-lucide="file-text"
                                        class="w-4 h-4 text-gray-500 group-hover:text-emerald-600"></i>
                                    <span
                                        class="text-sm text-gray-700 group-hover:text-emerald-600">{{ $doc }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-6">
                        @if ($demande['statut'] === 'En attente')
                            <div class="flex gap-3">
                                <form action="{{ route('demandes.approve', $demande['id']) }}" method="POST"
                                    class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all shadow-md hover:shadow-lg">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                        <span>Approuver</span>
                                    </button>
                                </form>

                                <button
                                    @click="selectedDemand = {{ $demande['id'] }}; showRejectModal = true; rejectReason = ''"
                                    class="flex-1 flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl font-semibold hover:from-red-700 hover:to-rose-700 transition-all shadow-md hover:shadow-lg">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                    <span>Rejeter</span>
                                </button>
                            </div>
                        @elseif($demande['statut'] === 'Rejeté' && $demande['motif_rejet'])
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <p class="text-sm font-semibold text-red-800 mb-2">Motif du rejet :</p>
                                <p class="text-sm text-red-700">{{ $demande['motif_rejet'] }}</p>
                            </div>
                        @else
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                                <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-600 mx-auto mb-2"></i>
                                <p class="text-sm font-semibold text-emerald-800">Demande traitée avec succès</p>
                            </div>
                        @endif

                        <!-- View Details Button -->
                        <button
                            class="w-full mt-3 flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            <span>Voir les détails</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune demande</h3>
                    <p class="text-gray-600">Il n'y a aucune demande de dotation ou de rechargement pour le moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Reject Modal -->
        <div x-show="showRejectModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div @click.away="showRejectModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
                <form :action="``" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Rejeter la demande</h3>
                            <p class="text-sm text-gray-600 mt-1">Veuillez indiquer le motif du rejet</p>
                        </div>
                        <button type="button" @click="showRejectModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Motif du rejet <span class="text-red-500">*</span>
                        </label>
                        <textarea x-model="rejectReason" name="motif_rejet" rows="5" required
                            placeholder="Expliquez la raison du rejet de cette demande..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-2">Ce motif sera communiqué au partenaire</p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        <button type="button" @click="showRejectModal = false"
                            class="flex-1 px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" :disabled="!rejectReason"
                            :class="rejectReason ?
                                'bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700' :
                                'bg-gray-300 cursor-not-allowed'"
                            class="flex-1 px-6 py-3 text-white rounded-xl font-semibold transition-all shadow-md">
                            Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => lucide.createIcons(), 100);
        });
    </script>
@endsection
