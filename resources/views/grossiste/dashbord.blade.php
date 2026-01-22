@extends('layouts.grossiste')

@section('title', 'Gestion des Partenaires')

@section('content')
    <div class="p-6" x-data="partenaireManager()">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Gestion des Partenaires</h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Gérez et suivez tous vos partenaires commerciaux
                    </p>
                </div>
                <button @click="openModal('add')"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    Ajouter un Partenaire
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-1" x-text="partenaires.length"></p>
                <p class="text-sm text-gray-600">Total Partenaires</p>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-1"
                    x-text="partenaires.filter(p => p.statut === 'Actif').length"></p>
                <p class="text-sm text-gray-600">Partenaires Actifs</p>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-1" x-text="formatCurrency(soldeTotal)"></p>
                <p class="text-sm text-gray-600">Solde Total (FCFA)</p>
            </div>

            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">+8%</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-1">142</p>
                <p class="text-sm text-gray-600">Transactions ce mois</p>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-6 flex flex-col lg:flex-row gap-4 bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
            <!-- Search Bar -->
            <div class="relative flex-1">
                <i data-lucide="search"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input type="text" x-model="searchQuery" placeholder="Rechercher par raison sociale ou IFU..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div class="flex gap-2">
                <button @click="statusFilter = 'Tous'"
                    :class="statusFilter === 'Tous' ? 'bg-emerald-600 text-white shadow-md' :
                        'bg-white text-gray-700 hover:bg-gray-50'"
                    class="px-5 py-2.5 font-medium rounded-lg border border-gray-300 transition-all">
                    Tous
                </button>
                <button @click="statusFilter = 'Actif'"
                    :class="statusFilter === 'Actif' ? 'bg-green-600 text-white shadow-md' :
                        'bg-white text-gray-700 hover:bg-gray-50'"
                    class="px-5 py-2.5 font-medium rounded-lg border border-gray-300 transition-all">
                    Actif
                </button>
                <button @click="statusFilter = 'Inactif'"
                    :class="statusFilter === 'Inactif' ? 'bg-gray-600 text-white shadow-md' :
                        'bg-white text-gray-700 hover:bg-gray-50'"
                    class="px-5 py-2.5 font-medium rounded-lg border border-gray-300 transition-all">
                    Inactif
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <div x-show="successMessage" x-transition
            class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <p class="text-green-800 font-medium" x-text="successMessage"></p>
        </div>

        <!-- Error Message -->
        <div x-show="errorMessage" x-transition
            class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            <p class="text-red-800 font-medium" x-text="errorMessage"></p>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Raison Sociale
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Propriétaire</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">IFU</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">RCCM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Solde Actuel</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="partenaire in filteredPartenaires" :key="partenaire.id">
                            <tr class="hover:bg-emerald-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                                            <i data-lucide="building-2" class="w-5 h-5 text-emerald-700"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900"
                                                x-text="partenaire.raison_sociale"></p>
                                            <p class="text-xs text-gray-500" x-text="partenaire.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span x-text="partenaire.proprietaire"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 text-sm text-gray-700 font-mono bg-gray-50 px-2 py-1 rounded">
                                        <i data-lucide="hash" class="w-3 h-3 text-gray-400"></i>
                                        <span x-text="partenaire.ifu"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-mono" x-text="partenaire.rccm"></td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-bold text-emerald-700"
                                        x-text="formatCurrency(partenaire.solde)"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="partenaire.statut === 'Actif' ?
                                            'bg-green-100 text-green-800 ring-green-600/20' :
                                            'bg-gray-100 text-gray-800 ring-gray-600/20'"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold ring-1 ring-inset">
                                        <span :class="partenaire.statut === 'Actif' ? 'bg-green-600' : 'bg-gray-600'"
                                            class="w-1.5 h-1.5 rounded-full"></span>
                                        <span x-text="partenaire.statut"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button @click="viewPartenaire(partenaire)"
                                            class="p-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition-colors border border-blue-200"
                                            title="Voir détails">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="openModal('edit', partenaire)"
                                            class="p-2 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg transition-colors border border-amber-200"
                                            title="Modifier">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="openCreditModal(partenaire)"
                                            class="p-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg transition-colors border border-green-200"
                                            title="Créditer le compte">
                                            <i data-lucide="wallet" class="w-4 h-4"></i>
                                        </button>
                                        <button @click="deletePartenaire(partenaire)"
                                            class="p-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors border border-red-200"
                                            title="Supprimer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredPartenaires.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
                                <p class="text-lg font-medium">Aucun partenaire trouvé</p>
                                <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
            <template x-for="partenaire in filteredPartenaires" :key="partenaire.id">
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-emerald-600">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="building-2" class="w-6 h-6 text-emerald-700"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-base truncate"
                                    x-text="partenaire.raison_sociale"></h3>
                                <p class="text-sm text-gray-600" x-text="partenaire.proprietaire"></p>
                            </div>
                        </div>
                        <span
                            :class="partenaire.statut === 'Actif' ? 'bg-green-100 text-green-800' :
                                'bg-gray-100 text-gray-800'"
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0"
                            x-text="partenaire.statut"></span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="hash" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-600">IFU:</span>
                            <span class="text-gray-900 font-medium font-mono" x-text="partenaire.ifu"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-600">RCCM:</span>
                            <span class="text-gray-900 font-medium font-mono" x-text="partenaire.rccm"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-900" x-text="partenaire.email"></span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg mt-3">
                            <span class="text-sm font-medium text-gray-700">Solde Actuel:</span>
                            <span class="text-xl font-bold text-emerald-700"
                                x-text="formatCurrency(partenaire.solde)"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-gray-200">
                        <button @click="viewPartenaire(partenaire)"
                            class="flex items-center justify-center gap-2 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            Voir
                        </button>
                        <button @click="openModal('edit', partenaire)"
                            class="flex items-center justify-center gap-2 px-3 py-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors text-sm font-medium">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            Modifier
                        </button>
                        <button @click="openCreditModal(partenaire)"
                            class="flex items-center justify-center gap-2 px-3 py-2 text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                            Créditer
                        </button>
                        <button @click="deletePartenaire(partenaire)"
                            class="flex items-center justify-center gap-2 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Supprimer
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="filteredPartenaires.length === 0" class="text-center py-12 bg-white rounded-lg">
                <i data-lucide="inbox" class="w-16 h-16 mx-auto mb-3 text-gray-300"></i>
                <p class="text-lg font-medium text-gray-600">Aucun partenaire trouvé</p>
                <p class="text-sm text-gray-500">Essayez de modifier vos critères</p>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showModal = false"></div>

                <div x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                                <i :data-lucide="modalMode === 'add' ? 'user-plus' : 'edit'"
                                    class="w-5 h-5 text-emerald-700"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900"
                                x-text="modalMode === 'add' ? 'Ajouter un Partenaire' : 'Modifier le Partenaire'"></h3>
                        </div>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form @submit.prevent="submitForm" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Raison Sociale *</label>
                                <input type="text" x-model="formData.raison_sociale" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="Ex: Entreprise ABC SARL">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom du Propriétaire *</label>
                                <input type="text" x-model="formData.nom_proprietaire" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="Nom">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom du Propriétaire
                                    *</label>
                                <input type="text" x-model="formData.prenom_proprietaire" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="Prénom">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                <input type="email" x-model="formData.email" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="contact@entreprise.com">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                                <input type="tel" x-model="formData.telephone"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="+229 XX XX XX XX">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">IFU *</label>
                                <input type="text" x-model="formData.ifu" required maxlength="50"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono"
                                    placeholder="1234567890123">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">RCCM *</label>
                                <input type="text" x-model="formData.rccm" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono"
                                    placeholder="RB/COT/XX-X-XXXX">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Quartier</label>
                                <input type="text" x-model="formData.quartier"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="Quartier">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de Passe <span
                                        x-show="modalMode === 'add'" class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" x-model="formData.mot_de_passe"
                                        :required="modalMode === 'add'" minlength="8"
                                        class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        :placeholder="modalMode === 'add' ? 'Minimum 8 caractères' :
                                            'Laisser vide pour ne pas changer'">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="modalMode === 'edit'" class="text-gray-500 text-xs mt-1">Laissez vide pour
                                    conserver le mot de passe actuel</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le Mot de Passe
                                    <span x-show="modalMode === 'add'" class="text-red-500">*</span></label>
                                <input type="password" x-model="formData.mot_de_passe_confirmation"
                                    :required="modalMode === 'add'" minlength="8"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    :placeholder="modalMode === 'add' ? 'Confirmez votre mot de passe' :
                                        'Laisser vide pour ne pas changer'">
                                <p x-show="formData.mot_de_passe && formData.mot_de_passe_confirmation && formData.mot_de_passe !== formData.mot_de_passe_confirmation"
                                    class="text-red-500 text-xs mt-1">Les mots de passe ne correspondent pas</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Solde Initial</label>
                                <input type="number" x-model="formData.solde" min="0" step="0.01"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                    placeholder="0.00">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea x-model="formData.description" rows="2"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                                    placeholder="Description de l'entreprise partenaire"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                                <select x-model="formData.statut_general"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    <option value="ACTIF">Actif</option>
                                    <option value="INACTIF">Inactif</option>
                                </select>
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" @click="showModal = false"
                                class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold rounded-lg transition-colors">
                                Annuler
                            </button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl">
                                <span x-text="modalMode === 'add' ? 'Ajouter' : 'Enregistrer'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Credit Modal -->
        <div x-show="showCreditModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showCreditModal = false">
                </div>

                <div x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                                <i data-lucide="wallet" class="w-5 h-5 text-green-700"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Créditer le Compte</h3>
                        </div>
                        <button @click="showCreditModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Credit Info -->
                    <div class="mb-6 p-4 bg-emerald-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Partenaire</p>
                        <p class="font-semibold text-gray-900" x-text="creditData.partenaire_name"></p>
                        <p class="text-sm text-gray-600 mt-2">Solde Actuel</p>
                        <p class="text-2xl font-bold text-emerald-700" x-text="formatCurrency(creditData.current_solde)">
                        </p>
                    </div>

                    <!-- Credit Form -->
                    <form @submit.prevent="submitCredit" class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Montant à Créditer (FCFA)
                                *</label>
                            <input type="number" x-model="creditData.amount" required min="1" step="0.01"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-lg font-semibold"
                                placeholder="0.00">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Note (optionnel)</label>
                            <textarea x-model="creditData.note" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                                placeholder="Ajouter une note pour cette transaction..."></textarea>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" @click="showCreditModal = false"
                                class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold rounded-lg transition-colors">
                                Annuler
                            </button>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl">
                                Créditer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Details Modal -->
        <div x-show="showViewModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showViewModal = false">
                </div>

                <div
                    class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <i data-lucide="building-2" class="w-6 h-6 text-blue-700"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Détails du Partenaire</h3>
                        </div>
                        <button @click="showViewModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <div class="space-y-6" x-show="viewData">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Raison Sociale</p>
                                <p class="text-lg font-semibold text-gray-900" x-text="viewData?.raison_sociale"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Propriétaire</p>
                                <p class="text-lg font-semibold text-gray-900" x-text="viewData?.proprietaire"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <p class="text-gray-900" x-text="viewData?.email"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                                <p class="text-gray-900" x-text="viewData?.telephone || 'Non renseigné'"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">IFU</p>
                                <p class="text-gray-900 font-mono" x-text="viewData?.ifu"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">RCCM</p>
                                <p class="text-gray-900 font-mono" x-text="viewData?.rccm"></p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Quartier</p>
                                <p class="text-gray-900" x-text="viewData?.quartier || 'Non renseigné'"></p>
                            </div>
                            <div class="sm:col-span-2" x-show="viewData?.description">
                                <p class="text-sm text-gray-600 mb-1">Description</p>
                                <p class="text-gray-900" x-text="viewData?.description"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Statut</p>
                                <span
                                    :class="viewData?.statut === 'Actif' ? 'bg-green-100 text-green-800' :
                                        'bg-gray-100 text-gray-800'"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold"
                                    x-text="viewData?.statut"></span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Solde Actuel</p>
                                <p class="text-2xl font-bold text-emerald-700" x-text="formatCurrency(viewData?.solde)">
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button @click="showViewModal = false"
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showDeleteModal = false">
                </div>

                <div x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-orange-100 flex items-center justify-center">
                                <i data-lucide="alert-triangle" class="w-6 h-6 text-red-700"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Confirmer la suppression</h3>
                        </div>
                        <button @click="showDeleteModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="mb-6">
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200 mb-4">
                            <p class="text-sm text-red-800 mb-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 inline mr-1"></i>
                                Cette action est irréversible
                            </p>
                        </div>

                        <p class="text-gray-700 mb-2">Êtes-vous sûr de vouloir supprimer le partenaire :</p>
                        <p class="text-lg font-bold text-gray-900 mb-4" x-text="deleteTargetName"></p>
                        <p class="text-sm text-gray-600">Toutes les données associées à ce partenaire seront définitivement
                            supprimées.</p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" @click="showDeleteModal = false"
                            class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 font-semibold rounded-lg transition-colors">
                            Annuler
                        </button>
                        <button @click="confirmDelete" :disabled="loading"
                            class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold rounded-lg hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!loading">Supprimer définitivement</span>
                            <span x-show="loading">Suppression...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function partenaireManager() {
            return {
                partenaires: [],
                searchQuery: '',
                statusFilter: 'Tous',
                showModal: false,
                showCreditModal: false,
                showViewModal: false,
                showDeleteModal: false,
                modalMode: 'add',
                successMessage: '',
                errorMessage: '',
                loading: false,
                deleteTargetId: null,
                deleteTargetName: '',
                showPassword: false,
                showPasswordConfirm: false,
                formData: {
                    raison_sociale: '',
                    nom_proprietaire: '',
                    prenom_proprietaire: '',
                    email: '',
                    telephone: '',
                    ifu: '',
                    rccm: '',
                    quartier: '',
                    description: '',
                    mot_de_passe: '',
                    mot_de_passe_confirmation: '',
                    solde: 0,
                    statut_general: 'ACTIF'
                },
                creditData: {
                    partenaire_id: null,
                    partenaire_name: '',
                    current_solde: 0,
                    amount: 0,
                    note: ''
                },
                viewData: null,

                get filteredPartenaires() {
                    return this.partenaires.filter(p => {
                        const matchesSearch = p.raison_sociale.toLowerCase().includes(this.searchQuery
                                .toLowerCase()) ||
                            p.ifu.includes(this.searchQuery);
                        const matchesStatus = this.statusFilter === 'Tous' || p.statut === this.statusFilter;
                        return matchesSearch && matchesStatus;
                    });
                },

                get soldeTotal() {
                    return this.partenaires.reduce((total, p) => total + (parseFloat(p.solde) || 0), 0);
                },

                async loadPartenaires() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('grossiste.partenaires.data') }}', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Transformer les données du backend pour le frontend
                            this.partenaires = result.data.map(p => ({
                                id: p.id_user_detail || p.id_partenaire,
                                raison_sociale: p.raison_sociale,
                                proprietaire: `${p.nom_proprietaire} ${p.prenom_proprietaire}`,
                                nom_proprietaire: p.nom_proprietaire,
                                prenom_proprietaire: p.prenom_proprietaire,
                                email: p.email,
                                telephone: p.telephone || '',
                                ifu: p.ifu,
                                rccm: p.rccm,
                                adresse: p.quartier || '',
                                quartier: p.quartier || '',
                                description: p.description || '',
                                solde: parseFloat(p.solde),
                                statut: p.statut_general === 'ACTIF' ? 'Actif' : 'Inactif',
                                statut_general: p.statut_general
                            }));

                            // Recréer les icônes après le chargement des données
                            this.$nextTick(() => {
                                lucide.createIcons();
                                this.updateNavbarSolde();
                            });
                        }
                    } catch (error) {
                        console.error('Erreur lors du chargement des partenaires:', error);
                        this.showError('Erreur lors du chargement des partenaires');
                    } finally {
                        this.loading = false;
                    }
                },

                updateNavbarSolde() {
                    const soldeFormatted = this.formatCurrency(this.soldeTotal);
                    const navbarSolde = document.getElementById('navbar-solde-total');
                    const navbarSoldeMobile = document.getElementById('navbar-solde-total-mobile');

                    if (navbarSolde) {
                        navbarSolde.textContent = soldeFormatted;
                    }
                    if (navbarSoldeMobile) {
                        navbarSoldeMobile.textContent = soldeFormatted;
                    }
                },

                openModal(mode, partenaire = null) {
                    this.modalMode = mode;
                    this.showPassword = false;
                    this.showPasswordConfirm = false;
                    if (mode === 'edit' && partenaire) {
                        this.formData = {
                            id: partenaire.id,
                            raison_sociale: partenaire.raison_sociale,
                            nom_proprietaire: partenaire.nom_proprietaire,
                            prenom_proprietaire: partenaire.prenom_proprietaire,
                            email: partenaire.email,
                            telephone: partenaire.telephone,
                            ifu: partenaire.ifu,
                            rccm: partenaire.rccm,
                            quartier: partenaire.quartier,
                            description: partenaire.description,
                            solde: partenaire.solde,
                            statut_general: partenaire.statut_general,
                            mot_de_passe: '', // Ne pas pré-remplir le mot de passe
                            mot_de_passe_confirmation: ''
                        };
                    } else {
                        this.resetForm();
                    }
                    this.showModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                openCreditModal(partenaire) {
                    this.creditData = {
                        partenaire_id: partenaire.id,
                        partenaire_name: partenaire.raison_sociale,
                        current_solde: partenaire.solde,
                        amount: 0,
                        note: ''
                    };
                    this.showCreditModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                viewPartenaire(partenaire) {
                    this.viewData = {
                        ...partenaire
                    };
                    this.showViewModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                async submitForm() {
                    // Validation de la confirmation du mot de passe
                    if (this.modalMode === 'add' || this.formData.mot_de_passe) {
                        if (this.formData.mot_de_passe !== this.formData.mot_de_passe_confirmation) {
                            this.showError('Les mots de passe ne correspondent pas');
                            return;
                        }
                    }

                    this.loading = true;
                    try {
                        const url = this.modalMode === 'add' ?
                            '{{ route('grossiste.partenaires.store') }}' :
                            `{{ url('grossiste/partenaires') }}/${this.formData.id}`;
                        const method = this.modalMode === 'add' ? 'POST' : 'PUT';

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showSuccess(result.message);
                            await this.loadPartenaires();
                            this.showModal = false;
                            this.resetForm();
                        } else {
                            // Afficher les erreurs de validation détaillées
                            if (result.errors) {
                                console.error('Erreurs de validation:', result.errors);
                                const errorMessages = Object.values(result.errors).flat().join(', ');
                                this.showError(errorMessages || 'Erreurs de validation');
                            } else {
                                this.showError(result.message || 'Une erreur est survenue');
                            }
                        }
                    } catch (error) {
                        console.error('Erreur lors de la soumission:', error);
                        this.showError('Erreur lors de la soumission du formulaire');
                    } finally {
                        this.loading = false;
                    }
                },

                async submitCredit() {
                    this.loading = true;
                    try {
                        const response = await fetch(
                            `{{ url('grossiste/partenaires') }}/${this.creditData.partenaire_id}/credit`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    montant: this.creditData.amount
                                })
                            });

                        const result = await response.json();

                        if (result.success) {
                            this.showSuccess(result.message);
                            await this.loadPartenaires();
                            this.showCreditModal = false;
                            this.creditData = {
                                partenaire_id: null,
                                partenaire_name: '',
                                current_solde: 0,
                                amount: 0,
                                note: ''
                            };
                        } else {
                            this.showError(result.message || 'Erreur lors du crédit');
                        }
                    } catch (error) {
                        console.error('Erreur lors du crédit:', error);
                        this.showError('Erreur lors du crédit du compte');
                    } finally {
                        this.loading = false;
                    }
                },

                deletePartenaire(partenaire) {
                    this.deleteTargetId = partenaire.id;
                    this.deleteTargetName = partenaire.raison_sociale;
                    this.showDeleteModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },

                async confirmDelete() {
                    this.loading = true;
                    try {
                        const response = await fetch(`{{ url('grossiste/partenaires') }}/${this.deleteTargetId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showSuccess(result.message);
                            await this.loadPartenaires();
                            this.showDeleteModal = false;
                            this.deleteTargetId = null;
                            this.deleteTargetName = '';
                        } else {
                            this.showError(result.message || 'Erreur lors de la suppression');
                        }
                    } catch (error) {
                        console.error('Erreur lors de la suppression:', error);
                        this.showError('Erreur lors de la suppression du partenaire');
                    } finally {
                        this.loading = false;
                    }
                },

                resetForm() {
                    this.formData = {
                        raison_sociale: '',
                        nom_proprietaire: '',
                        prenom_proprietaire: '',
                        email: '',
                        telephone: '',
                        ifu: '',
                        rccm: '',
                        quartier: '',
                        description: '',
                        mot_de_passe: '',
                        mot_de_passe_confirmation: '',
                        solde: 0,
                        statut_general: 'ACTIF'
                    };
                    this.showPassword = false;
                    this.showPasswordConfirm = false;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'XOF',
                        minimumFractionDigits: 0
                    }).format(value);
                },

                showSuccess(message) {
                    this.successMessage = message;
                    setTimeout(() => {
                        this.successMessage = '';
                    }, 3000);
                },

                showError(message) {
                    this.errorMessage = message;
                    setTimeout(() => {
                        this.errorMessage = '';
                    }, 5000);
                },

                init() {
                    this.loadPartenaires();
                    this.$nextTick(() => lucide.createIcons());
                }
            }
        }
    </script>
@endsection
