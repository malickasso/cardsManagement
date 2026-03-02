@extends('admin.layouts.app')

@section('title', 'Gestion des types de cartes')

@section('content')
    <div class="bg-gray-50 min-h-screen p-6 md:p-8" x-data="typeCarteManager()">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des types de cartes</h1>
                <p class="mt-1 text-gray-600">Création et gestion de vos types de cartes.</p>
            </div>
            <button @click="openCreateModal()"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-800 text-white font-semibold rounded-lg hover:bg-blue-900 transition-colors shadow-md hover:shadow-lg">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Ajouter un Type de Carte
            </button>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                <p class="font-semibold">Des erreurs ont été détectées :</p>
                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Types</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</p>
                        <p class="mt-2 text-xs text-gray-400">Types de cartes enregistrés</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Actifs</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['actif'] ?? 0) }}</p>
                        <div class="mt-2">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Disponibles
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Inactifs</p>
                        <p class="mt-2 text-3xl font-bold text-red-600">{{ number_format($stats['inactif'] ?? 0) }}</p>
                        <div class="mt-2">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Désactivés
                            </span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">#
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Nom
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Statut</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($typesCartes as $type)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $type->nom_type_carte }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $type->description ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $type->statut === 'ACTIF' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $type->statut }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            @click="openViewModal({ id: {{ $type->id_type_carte }}, nom_type_carte: @js($type->nom_type_carte), description: @js($type->description), statut: @js($type->statut) })"
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Voir détails">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>

                                        <button type="button"
                                            @click="openEditModal({ id: {{ $type->id_type_carte }}, nom_type_carte: @js($type->nom_type_carte), description: @js($type->description), statut: @js($type->statut) })"
                                            class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <form action="{{ route('admin.type-cartes.destroy', $type->id_type_carte) }}"
                                            method="POST" onsubmit="return confirm('Supprimer ce type de carte ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Aucun type de carte trouvé dans la table <span class="font-semibold">type_carte</span>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="showCreate" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div @click="showCreate = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div @click.away="showCreate = false"
                    class="relative w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Ajouter un Type de Carte</h3>
                            <button type="button" @click="showCreate = false"
                                class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <form action="{{ route('admin.type-cartes.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Nom <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nom_type_carte" required maxlength="100"
                                value="{{ old('nom_type_carte') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg uppercase focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Statut</label>
                            <select name="statut"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                <option value="ACTIF" {{ old('statut', 'ACTIF') === 'ACTIF' ? 'selected' : '' }}>ACTIF
                                </option>
                                <option value="INACTIF" {{ old('statut') === 'INACTIF' ? 'selected' : '' }}>INACTIF
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showCreate = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-blue-800 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-900 transition-colors">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showEdit" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div @click="showEdit = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div @click.away="showEdit = false"
                    class="relative w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Modifier le Type de Carte</h3>
                            <button type="button" @click="showEdit = false"
                                class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <form :action="editAction" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Nom <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nom_type_carte" required maxlength="100" x-model="editNom"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg uppercase focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Description</label>
                            <textarea name="description" rows="3" x-model="editDescription"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent"></textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Statut</label>
                            <select name="statut" x-model="editStatut"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                <option value="ACTIF">ACTIF</option>
                                <option value="INACTIF">INACTIF</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showEdit = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-blue-800 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-900 transition-colors">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showView" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div @click="showView = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div @click.away="showView = false"
                    class="relative w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Détail du Type de Carte</h3>
                            <button type="button" @click="showView = false"
                                class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-6 space-y-3">
                        <div>
                            <p class="text-xs font-semibold uppercase text-gray-500">ID</p>
                            <p class="text-sm text-gray-900" x-text="viewId"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase text-gray-500">Nom</p>
                            <p class="text-sm font-semibold text-gray-900" x-text="viewNom"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase text-gray-500">Description</p>
                            <p class="text-sm text-gray-700" x-text="viewDescription || '-'"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase text-gray-500">Statut</p>
                            <p class="text-sm text-gray-900" x-text="viewStatut"></p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end border-t border-gray-100 pt-4">
                        <button type="button" @click="showView = false"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function typeCarteManager() {
            return {
                showCreate: false,
                showEdit: false,
                showView: false,
                editAction: '',
                editNom: '',
                editDescription: '',
                editStatut: 'ACTIF',
                viewId: '',
                viewNom: '',
                viewDescription: '',
                viewStatut: '',

                openCreateModal() {
                    this.showCreate = true;
                },

                openViewModal(type) {
                    this.viewId = type.id ?? '';
                    this.viewNom = type.nom_type_carte ?? '';
                    this.viewDescription = type.description ?? '';
                    this.viewStatut = type.statut ?? '';
                    this.showView = true;
                },

                openEditModal(type) {
                    this.editAction = `{{ url('/admin/type-cartes') }}/${type.id}`;
                    this.editNom = type.nom_type_carte ?? '';
                    this.editDescription = type.description ?? '';
                    this.editStatut = type.statut ?? 'ACTIF';
                    this.showEdit = true;
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
@endsection
