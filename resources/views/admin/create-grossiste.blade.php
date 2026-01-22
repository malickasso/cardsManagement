@extends('admin.layouts.app')

@section('title', 'Gestion des Grossistes - Administration')

@section('content')
    <div class="bg-gray-50 min-h-screen p-6 md:p-8" x-data="grossisteManager()">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des Grossistes</h1>
                    <p class="text-gray-600 mt-1">Gérez et administrez tous vos grossistes en un seul endroit</p>
                </div>
                <button
                    @click="openModal('add')"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-800 text-white font-semibold rounded-lg hover:bg-blue-900 transition-colors shadow-md hover:shadow-lg"
                >
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Ajouter un Grossiste
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div x-show="loading" class="mb-6 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-800"></div>
            <p class="text-gray-600 mt-2">Chargement...</p>
        </div>

        <!-- Search Bar -->
        <div class="mb-6" x-show="!loading">
            <div class="relative max-w-md">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="Rechercher par nom ou raison sociale..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Success Message -->
        <div
            x-show="successMessage"
            x-transition
            class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3"
        >
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <p class="text-green-800 font-medium" x-text="successMessage"></p>
        </div>

        <!-- Error Message -->
        <div
            x-show="errorMessage"
            x-transition
            class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3"
        >
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            <p class="text-red-800 font-medium" x-text="errorMessage"></p>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-md overflow-hidden" x-show="!loading">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Raison Sociale</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Propriétaire</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IFU</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">RCCM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Téléphone</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="grossiste in filteredGrossistes" :key="grossiste.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900" x-text="grossiste.raison_sociale"></td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span x-text="(grossiste.nom_proprietaire || grossiste.nom) + ' ' + (grossiste.prenom_proprietaire || grossiste.prenom)"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="grossiste.ifu"></td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="grossiste.rccm"></td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="grossiste.email"></td>
                                <td class="px-6 py-4 text-sm text-gray-700" x-text="grossiste.telephone || 'N/A'"></td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="(grossiste.statut === 'Actif' || grossiste.statut === 'ACTIF' || grossiste.statut_general === 'ACTIF') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                        x-text="grossiste.statut_general || grossiste.statut"
                                    ></span>
                                </td>
                                <td class="px-1 py-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            @click="viewGrossiste(grossiste)"
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Voir détails"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="openModal('edit', grossiste)"
                                            class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                            title="Modifier"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="deleteGrossiste(grossiste)"
                                            class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Supprimer"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredGrossistes.length === 0">
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                Aucun grossiste trouvé
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4" x-show="!loading">
            <template x-for="grossiste in filteredGrossistes" :key="grossiste.id">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-lg" x-text="grossiste.raison_sociale"></h3>
                            <p class="text-sm text-gray-600" x-text="(grossiste.nom_proprietaire || grossiste.nom) + ' ' + (grossiste.prenom_proprietaire || grossiste.prenom)"></p>
                        </div>
                        <span
                            :class="(grossiste.statut === 'Actif' || grossiste.statut === 'ACTIF' || grossiste.statut_general === 'ACTIF') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                            x-text="grossiste.statut_general || grossiste.statut"
                        ></span>
                    </div>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-600">IFU:</span>
                            <span class="text-gray-900 font-medium" x-text="grossiste.ifu"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-600">RCCM:</span>
                            <span class="text-gray-900 font-medium" x-text="grossiste.rccm"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-900" x-text="grossiste.email"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-900" x-text="grossiste.telephone || 'N/A'"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-3 border-t border-gray-200">
                        <button
                            @click="viewGrossiste(grossiste)"
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                        >
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            Voir
                        </button>
                        <button
                            @click="openModal('edit', grossiste)"
                            class="flex-1 flex items-center justify-center gap-2 px-4 py-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors"
                        >
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            Modifier
                        </button>
                        <button
                            @click="deleteGrossiste(grossiste)"
                            class="px-4 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </template>
            <div x-show="filteredGrossistes.length === 0" class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                Aucun grossiste trouvé
            </div>
        </div>

        <!-- Modal (Add/Edit) -->
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                >
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white" x-text="modalMode === 'add' ? 'Ajouter un Grossiste' : 'Modifier le Grossiste'"></h3>
                            <button @click="closeModal" class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form @submit.prevent="submitForm" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Raison Sociale -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Raison Sociale <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    x-model="formData.raison_sociale"
                                    maxlength="150"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.raison_sociale ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.raison_sociale" class="text-red-500 text-xs mt-1" x-text="errors.raison_sociale"></p>
                            </div>

                            <!-- Nom -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom du Propriétaire <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    x-model="formData.nom"
                                    maxlength="100"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.nom ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.nom" class="text-red-500 text-xs mt-1" x-text="errors.nom"></p>
                            </div>

                            <!-- Prénom -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Prénom du Propriétaire <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    x-model="formData.prenom"
                                    maxlength="100"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.prenom ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.prenom" class="text-red-500 text-xs mt-1" x-text="errors.prenom"></p>
                            </div>

                            <!-- IFU -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    IFU <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    x-model="formData.ifu"
                                    maxlength="50"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.ifu ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.ifu" class="text-red-500 text-xs mt-1" x-text="errors.ifu ? errors.ifu[0] : ''"></p>
                            </div>

                            <!-- RCCM -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    RCCM <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    x-model="formData.rccm"
                                    maxlength="50"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.rccm ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.rccm" class="text-red-500 text-xs mt-1" x-text="errors.rccm ? errors.rccm[0] : ''"></p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    x-model="formData.email"
                                    maxlength="150"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.email ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.email" class="text-red-500 text-xs mt-1" x-text="errors.email ? errors.email[0] : ''"></p>
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input
                                    type="tel"
                                    x-model="formData.telephone"
                                    maxlength="30"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                >
                            </div>

                            <!-- Quartier -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Quartier
                                </label>
                                <input
                                    type="text"
                                    x-model="formData.quartier"
                                    maxlength="100"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                >
                            </div>

                            <!-- Mot de Passe -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mot de Passe <span x-show="modalMode === 'add'" class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        x-model="formData.password"
                                        minlength="8"
                                        :required="modalMode === 'add'"
                                        class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                        :class="errors.password ? 'border-red-500' : ''"
                                    >
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    >
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="errors.password" class="text-red-500 text-xs mt-1" x-text="errors.password ? errors.password[0] : ''"></p>
                                <p x-show="modalMode === 'edit'" class="text-gray-500 text-xs mt-1">Laissez vide pour conserver le mot de passe actuel</p>
                            </div>

                            <!-- Confirmation Mot de Passe -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirmer le Mot de Passe <span x-show="modalMode === 'add'" class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    x-model="formData.password_confirmation"
                                    minlength="8"
                                    :required="modalMode === 'add'"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                    :class="errors.password_confirmation ? 'border-red-500' : ''"
                                >
                                <p x-show="errors.password_confirmation" class="text-red-500 text-xs mt-1" x-text="errors.password_confirmation ? errors.password_confirmation[0] : ''"></p>
                                <p x-show="formData.password && formData.password_confirmation && formData.password !== formData.password_confirmation" class="text-red-500 text-xs mt-1">Les mots de passe ne correspondent pas</p>
                            </div>

                            <!-- Statut -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Statut <span class="text-red-500">*</span>
                                </label>
                                <select
                                    x-model="formData.statut"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                >
                                    <option value="Actif">Actif</option>
                                    <option value="Inactif">Inactif</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    x-model="formData.description"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent resize-none"
                                ></textarea>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                @click="closeModal"
                                :disabled="submitting"
                                class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors disabled:opacity-50"
                            >
                                Annuler
                            </button>
                            <button
                                type="submit"
                                :disabled="submitting"
                                :class="submitting ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-800 hover:bg-blue-900'"
                                class="flex-1 px-6 py-3 text-white font-semibold rounded-lg transition-colors shadow-md"
                            >
                                <span x-show="!submitting">Soumettre</span>
                                <span x-show="submitting">Enregistrement...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Details Modal -->
        <div
            x-show="showViewModal"
            x-transition
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="showViewModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Détails du Grossiste</h3>
                            <button @click="showViewModal = false" class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-6 space-y-4" x-show="viewingGrossiste">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Raison Sociale</p>
                            <p class="text-lg text-gray-900" x-text="viewingGrossiste?.raison_sociale"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-500">Nom</p>
                                <p class="text-gray-900" x-text="viewingGrossiste?.nom_proprietaire || viewingGrossiste?.nom"></p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500">Prénom</p>
                                <p class="text-gray-900" x-text="viewingGrossiste?.prenom_proprietaire || viewingGrossiste?.prenom"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-500">IFU</p>
                                <p class="text-gray-900" x-text="viewingGrossiste?.ifu"></p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500">RCCM</p>
                                <p class="text-gray-900" x-text="viewingGrossiste?.rccm"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Email</p>
                            <p class="text-gray-900" x-text="viewingGrossiste?.email"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Téléphone</p>
                            <p class="text-gray-900" x-text="viewingGrossiste?.telephone || 'N/A'"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Quartier</p>
                            <p class="text-gray-900" x-text="viewingGrossiste?.quartier || 'N/A'"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Description</p>
                            <p class="text-gray-900" x-text="viewingGrossiste?.description || 'Aucune description'"></p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Statut</p>
                            <span
                                :class="(viewingGrossiste?.statut === 'Actif' || viewingGrossiste?.statut === 'ACTIF' || viewingGrossiste?.statut_general === 'ACTIF') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold"
                                x-text="viewingGrossiste?.statut_general || viewingGrossiste?.statut"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            x-show="showDeleteModal"
            x-transition
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="showDeleteModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-4">
                        <div class="flex items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-0 ml-4 text-left flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Confirmer la suppression</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">
                                        Êtes-vous sûr de vouloir supprimer ce grossiste ? Cette action est irréversible.
                                    </p>
                                    <p class="text-sm text-gray-900 font-semibold mt-2" x-show="deletingGrossiste" x-text="deletingGrossiste?.raison_sociale"></p>
                                </div>
                            </div>
                            <button @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex gap-3">
                        <button
                            @click="showDeleteModal = false"
                            type="button"
                            :disabled="deleting"
                            class="flex-1 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50"
                        >
                            Annuler
                        </button>
                        <button
                            @click="confirmDelete"
                            type="button"
                            :disabled="deleting"
                            class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
                        >
                            <span x-show="!deleting">Supprimer</span>
                            <span x-show="deleting">Suppression...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function grossisteManager() {
            return {
                grossistes: [],
                searchQuery: '',
                showModal: false,
                showViewModal: false,
                showDeleteModal: false,
                modalMode: 'add',
                viewingGrossiste: null,
                deletingGrossiste: null,
                successMessage: '',
                errorMessage: '',
                loading: false,
                submitting: false,
                deleting: false,
                showPassword: false,
                formData: {
                    raison_sociale: '',
                    nom: '',
                    prenom: '',
                    ifu: '',
                    rccm: '',
                    email: '',
                    telephone: '',
                    quartier: '',
                    description: '',
                    password: '',
                    password_confirmation: '',
                    statut: 'Actif'
                },
                errors: {},

                get filteredGrossistes() {
                    console.log('filteredGrossistes appelé, grossistes:', this.grossistes);
                    if (!this.searchQuery) return this.grossistes;
                    const query = this.searchQuery.toLowerCase();
                    return this.grossistes.filter(g => {
                        const nom = g.nom_proprietaire || g.nom || '';
                        const prenom = g.prenom_proprietaire || g.prenom || '';
                        return g.raison_sociale.toLowerCase().includes(query) ||
                               nom.toLowerCase().includes(query) ||
                               prenom.toLowerCase().includes(query);
                    });
                },

                get isFormValid() {
                    const baseValid = this.formData.raison_sociale &&
                           this.formData.nom &&
                           this.formData.prenom &&
                           this.formData.ifu &&
                           this.formData.rccm &&
                           this.formData.email;

                    if (this.modalMode === 'edit') {
                        return baseValid;
                    }

                    return baseValid && this.formData.password && this.formData.password_confirmation && this.formData.password === this.formData.password_confirmation;
                },

                async loadGrossistes() {
                    this.loading = true;
                    try {
                        console.log('Chargement des grossistes...');
                        const response = await fetch('{{ route("admin.grossistes.data") }}', {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();
                        console.log('Résultat loadGrossistes:', result);

                        if (result.success) {
                            this.grossistes = result.data;
                            console.log('Grossistes chargés:', this.grossistes.length, this.grossistes);
                        } else {
                            console.error('Erreur serveur:', result.message);
                            this.showErrorMessage('Erreur lors du chargement des grossistes');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        this.showErrorMessage('Erreur de connexion au serveur');
                    } finally {
                        this.loading = false;
                    }
                },

                openModal(mode, grossiste = null) {
                    this.modalMode = mode;
                    this.errors = {};

                    if (mode === 'add') {
                        this.formData = {
                            raison_sociale: '',
                            nom: '',
                            prenom: '',
                            ifu: '',
                            rccm: '',
                            email: '',
                            telephone: '',
                            quartier: '',
                            description: '',
                            password: '',
                            statut: 'Actif'
                        };
                    } else {
                        // Adapter les noms de colonnes pour le formulaire
                        this.formData = {
                            id: grossiste.id_grossiste || grossiste.id_user_detail || grossiste.id,
                            raison_sociale: grossiste.raison_sociale,
                            nom: grossiste.nom_proprietaire || grossiste.nom,
                            prenom: grossiste.prenom_proprietaire || grossiste.prenom,
                            ifu: grossiste.ifu,
                            rccm: grossiste.rccm,
                            email: grossiste.email,
                            telephone: grossiste.telephone,
                            quartier: grossiste.quartier,
                            description: grossiste.description,
                            password: '',
                            statut: (grossiste.statut_general || grossiste.statut || 'ACTIF').charAt(0).toUpperCase() + (grossiste.statut_general || grossiste.statut || 'actif').slice(1).toLowerCase()
                        };
                    }

                    this.showModal = true;
                    setTimeout(() => lucide.createIcons(), 100);
                },

                closeModal() {
                    this.showModal = false;
                    this.showPassword = false;
                    this.formData = {
                        raison_sociale: '',
                        nom: '',
                        prenom: '',
                        ifu: '',
                        rccm: '',
                        email: '',
                        telephone: '',
                        quartier: '',
                        description: '',
                        password: '',
                        password_confirmation: '',
                        statut: 'Actif'
                    };
                    this.errors = {};
                },

                async submitForm() {
                    if (!this.isFormValid) return;

                    // Vérifier que les mots de passe correspondent
                    if (this.formData.password && this.formData.password !== this.formData.password_confirmation) {
                        this.errors.password_confirmation = ['Les mots de passe ne correspondent pas'];
                        return;
                    }

                    this.submitting = true;
                    this.errors = {};

                    try {
                        const url = this.modalMode === 'add'
                            ? '{{ route("admin.grossistes.store") }}'
                            : `{{ url('/admin/grossistes') }}/${this.formData.id}`;

                        const method = this.modalMode === 'add' ? 'POST' : 'PUT';

                        console.log('Envoi des données:', this.formData);
                        console.log('URL:', url);

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();
                        console.log('Réponse du serveur:', result);

                        if (result.success) {
                            this.closeModal();
                            await this.loadGrossistes();
                            this.showSuccessMessage(result.message);
                        } else {
                            if (result.errors) {
                                this.errors = result.errors;
                                console.error('Erreurs de validation:', result.errors);
                            } else {
                                this.showErrorMessage(result.message || 'Une erreur est survenue');
                            }
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        this.showErrorMessage('Erreur de connexion au serveur');
                    } finally {
                        this.submitting = false;
                    }
                },

                viewGrossiste(grossiste) {
                    this.viewingGrossiste = grossiste;
                    this.showViewModal = true;
                    setTimeout(() => lucide.createIcons(), 100);
                },

                deleteGrossiste(grossiste) {
                    this.deletingGrossiste = grossiste;
                    this.showDeleteModal = true;
                },

                async confirmDelete() {
                    if (!this.deletingGrossiste) return;

                    this.deleting = true;
                    const id = this.deletingGrossiste.id_grossiste || this.deletingGrossiste.id_user_detail || this.deletingGrossiste.id;

                    try {
                        const response = await fetch(`{{ url('/admin/grossistes') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showDeleteModal = false;
                            this.deletingGrossiste = null;
                            await this.loadGrossistes();
                            this.showSuccessMessage(result.message);
                        } else {
                            this.showErrorMessage(result.message || 'Erreur lors de la suppression');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        this.showErrorMessage('Erreur de connexion au serveur');
                    } finally {
                        this.deleting = false;
                    }
                },

                showSuccessMessage(message) {
                    this.successMessage = message;
                    this.errorMessage = '';
                    setTimeout(() => {
                        this.successMessage = '';
                    }, 5000);
                },

                showErrorMessage(message) {
                    this.errorMessage = message;
                    this.successMessage = '';
                    setTimeout(() => {
                        this.errorMessage = '';
                    }, 5000);
                },

                init() {
                    this.loadGrossistes();
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    });

                    // Observer pour les changements DOM
                    this.$watch('filteredGrossistes', () => {
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    });

                    this.$watch('showModal', () => {
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        });
                    });
                }
            }
        }
    </script>
@endsection
