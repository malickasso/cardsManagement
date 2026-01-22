@section('title', 'Dashboard Partenaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Espace Partenaire</h1>
                    <p class="text-gray-600 mt-2">Bienvenue,</p>
                    <p class="text-sm text-gray-500"></p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full font-semibold">
                        PARTENAIRE
                    </span>
                </div>
            </div>
        </div>@extends('layouts.partenaire')

@section('title', 'Mes Cartes - Espace Partenaire')

@section('content')
<div class="p-4 lg:p-8" x-data="{
    activationModal: false,
    selectedCard: null,
    filterStatus: 'all',
    searchQuery: ''
}">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900">Mes Cartes</h1>
                <p class="text-slate-500 mt-1">Gerez vos cartes et demandez des activations pour vos clients</p>
            </div>
            <button
                @click="activationModal = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all shadow-lg shadow-indigo-500/25 font-semibold"
            >
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Demander une Activation</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-100 rounded-xl">
                    <i data-lucide="credit-card" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">24</p>
                    <p class="text-sm text-slate-500">Total Cartes</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-xl">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">18</p>
                    <p class="text-sm text-slate-500">Activees</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-100 rounded-xl">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">3</p>
                    <p class="text-sm text-slate-500">En Attente</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-slate-100 rounded-xl">
                    <i data-lucide="pause-circle" class="w-6 h-6 text-slate-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-900">3</p>
                    <p class="text-sm text-slate-500">Disponibles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 lg:p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                    <input
                        type="text"
                        x-model="searchQuery"
                        placeholder="Rechercher par numero de carte ou client..."
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                </div>
            </div>

            <!-- Status Filter -->
            <div class="flex items-center gap-2 flex-wrap">
                <button
                    @click="filterStatus = 'all'"
                    :class="filterStatus === 'all' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-4 py-2.5 rounded-lg font-medium transition-all text-sm"
                >
                    Toutes
                </button>
                <button
                    @click="filterStatus = 'active'"
                    :class="filterStatus === 'active' ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-4 py-2.5 rounded-lg font-medium transition-all text-sm"
                >
                    Activees
                </button>
                <button
                    @click="filterStatus = 'pending'"
                    :class="filterStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-4 py-2.5 rounded-lg font-medium transition-all text-sm"
                >
                    En Attente
                </button>
                <button
                    @click="filterStatus = 'available'"
                    :class="filterStatus === 'available' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-4 py-2.5 rounded-lg font-medium transition-all text-sm"
                >
                    Disponibles
                </button>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-6">

    </div>

    <!-- Activation Modal -->
    <div
        x-show="activationModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div
            x-show="activationModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            @click.away="activationModal = false"
            class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden"
        >
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold">Demande d'Activation de Carte</h2>
                        <p class="text-indigo-200 text-sm mt-1">Remplissez les informations du client</p>
                    </div>
                    <button
                        @click="activationModal = false"
                        class="p-2 hover:bg-white/20 rounded-xl transition-colors"
                    >
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Selected Card Display -->
                    <div x-show="selectedCard" class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i data-lucide="credit-card" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-indigo-600">Carte selectionnee</p>
                                <p class="font-bold text-indigo-900" x-text="selectedCard"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Selection (if no card selected) -->
                    <div x-show="!selectedCard">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Selectionnez une carte <span class="text-red-500">*</span>
                        </label>
                        <select name="carte_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            <option value="">Choisir une carte disponible...</option>

                        </select>
                    </div>

                    <!-- Client Information -->
                    <div class="bg-slate-50 rounded-xl p-5 space-y-5">
                        <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                            <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i>
                            Informations du Client
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nom"
                                    required
                                    placeholder="Nom du client"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Prenom(s) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="prenom"
                                    required
                                    placeholder="Prenom(s) du client"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Telephone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="phone" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input
                                        type="tel"
                                        name="telephone"
                                        required
                                        placeholder="+229 00 00 00 00"
                                        class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                    >
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input
                                        type="email"
                                        name="email"
                                        required
                                        placeholder="client@email.com"
                                        class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Upload -->
                    <div class="bg-slate-50 rounded-xl p-5 space-y-5">
                        <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-indigo-600"></i>
                            Documents Requis
                        </h3>

                        <!-- Piece d'identite -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Piece d'identite scannee <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="file"
                                    name="piece_identite"
                                    required
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    x-ref="pieceIdentite"
                                    @change="$refs.pieceIdentiteLabel.textContent = $event.target.files[0]?.name || 'Aucun fichier choisi'"
                                >
                                <div class="flex items-center gap-4 px-4 py-4 bg-white border-2 border-dashed border-slate-300 rounded-xl hover:border-indigo-400 transition-colors">
                                    <div class="p-3 bg-indigo-100 rounded-xl">
                                        <i data-lucide="id-card" class="w-6 h-6 text-indigo-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-slate-700" x-ref="pieceIdentiteLabel">Cliquez pour telecharger</p>
                                        <p class="text-sm text-slate-500">PDF, JPG ou PNG (max. 5MB)</p>
                                    </div>
                                    <div class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium">
                                        Parcourir
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fiche UBA -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Fiche UBA remplie et scannee <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="file"
                                    name="fiche_uba"
                                    required
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    x-ref="ficheUba"
                                    @change="$refs.ficheUbaLabel.textContent = $event.target.files[0]?.name || 'Aucun fichier choisi'"
                                >
                                <div class="flex items-center gap-4 px-4 py-4 bg-white border-2 border-dashed border-slate-300 rounded-xl hover:border-indigo-400 transition-colors">
                                    <div class="p-3 bg-violet-100 rounded-xl">
                                        <i data-lucide="file-check" class="w-6 h-6 text-violet-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-slate-700" x-ref="ficheUbaLabel">Cliquez pour telecharger</p>
                                        <p class="text-sm text-slate-500">PDF, JPG ou PNG (max. 5MB)</p>
                                    </div>
                                    <div class="px-4 py-2 bg-violet-600 text-white rounded-lg text-sm font-medium">
                                        Parcourir
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Notice -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Information importante</p>
                                <p class="text-sm text-blue-700 mt-1">Votre demande sera traitee dans un delai de 24 a 48 heures ouvrables. Vous recevrez une notification une fois la carte activee.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <button
                    @click="activationModal = false"
                    class="px-6 py-3 text-slate-700 hover:bg-slate-200 rounded-xl transition-colors font-medium"
                >
                    Annuler
                </button>
                <button
                    type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all shadow-lg font-semibold flex items-center gap-2"
                >
                    <i data-lucide="send" class="w-5 h-5"></i>
                    Soumettre la Demande
                </button>
            </div>
        </div>
    </div>
</div>
@endsection


        <!-- Solde -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg shadow-lg p-6 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm">Solde disponible</p>
                    <p class="text-4xl font-bold mt-2"></p>
                </div>
                <div class="p-4 bg-white bg-opacity-20 rounded-full">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-emerald-100 text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Mes Cartes</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Demandes en cours</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Validées</p>
                        <p class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Actions Rapides</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('partenaire.cartes.index') }}" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-emerald-500 hover:shadow-lg transition-all">
                    <div class="text-emerald-600 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Mes Cartes</h3>
                    <p class="text-sm text-gray-600 mt-1">Consulter vos cartes disponibles</p>
                </a>

                <a href="" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-teal-500 hover:shadow-lg transition-all">
                    <div class="text-teal-600 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Demande de Dotation</h3>
                    <p class="text-sm text-gray-600 mt-1">Faire une demande de crédit</p>
                </a>

                <a href="" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-lg transition-all">
                    <div class="text-blue-600 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Recharger une Carte</h3>
                    <p class="text-sm text-gray-600 mt-1">Demander un rechargement</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
