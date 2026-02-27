@extends('admin.layouts.app')

@section('title', 'Gestion des cartes')

@section('content')
    <div class="bg-gray-50 min-h-screen p-6 md:p-8" x-data="carteManager({{ $errors->any() ? 'true' : 'false' }})">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des cartes</h1>
                <p class="mt-1 text-gray-600">Création et suivi des cartes selon la table <span
                        class="font-semibold">carte</span>.</p>
            </div>
            <button @click="showCreate = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-800 text-white font-semibold rounded-lg hover:bg-blue-900 transition-colors shadow-md hover:shadow-lg">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Ajouter une carte
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

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total cartes</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $cartes->count() }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Enregistrées</p>
                <p class="mt-1 text-3xl font-bold text-slate-700">
                    {{ $cartes->where('statut_carte', 'ENREGISTREE')->count() }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Actives</p>
                <p class="mt-1 text-3xl font-bold text-emerald-700">{{ $cartes->where('statut_carte', 'ACTIVE')->count() }}
                </p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Bloquées</p>
                <p class="mt-1 text-3xl font-bold text-amber-700">{{ $cartes->where('statut_carte', 'BLOQUEE')->count() }}
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Numéro</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Type
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Banque</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Grossiste</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Expiration</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Statut</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($cartes as $carte)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $carte->id_carte }}</td>
                                <td class="px-4 py-3 font-mono text-sm text-gray-900">{{ $carte->numero_carte }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $carte->typeCarte->nom_type_carte ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $carte->banque->nom_banque ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $carte->grossiste->raison_sociale ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($carte->date_expiration)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'ENREGISTREE' => 'bg-slate-100 text-slate-700',
                                            'ACTIVE' => 'bg-emerald-100 text-emerald-700',
                                            'BLOQUEE' => 'bg-amber-100 text-amber-700',
                                            'EXPIREE' => 'bg-red-100 text-red-700',
                                            'ANNULEE' => 'bg-gray-200 text-gray-700',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusColors[$carte->statut_carte] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $carte->statut_carte }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            @click="openViewModal({
                                                id: {{ $carte->id_carte }},
                                                numero: @js($carte->numero_carte),
                                                type: @js($carte->typeCarte->nom_type_carte ?? '-'),
                                                banque: @js($carte->banque->nom_banque ?? '-'),
                                                grossiste: @js($carte->grossiste->raison_sociale ?? '-'),
                                                expiration: @js(\Carbon\Carbon::parse($carte->date_expiration)->format('d/m/Y')),
                                                statut: @js($carte->statut_carte)
                                            })"
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
                                            @click="openEditModal({
                                                id: {{ $carte->id_carte }},
                                                numero_carte: @js($carte->numero_carte),
                                                id_type_carte: {{ $carte->id_type_carte }},
                                                id_banque: {{ $carte->id_banque }},
                                                id_grossiste: {{ $carte->id_grossiste }},
                                                date_expiration: @js(\Carbon\Carbon::parse($carte->date_expiration)->format('Y-m-d')),
                                                statut_carte: @js($carte->statut_carte)
                                            })"
                                            class="p-2 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <form action="{{ route('admin.cartes.destroy', $carte->id_carte) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cette carte ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Aucune carte trouvée.
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
                    class="relative w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Ajouter une carte</h3>
                            <button type="button" @click="showCreate = false"
                                class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('admin.cartes.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Numéro de carte <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="numero_carte" required maxlength="20"
                                        value="{{ old('numero_carte') }}"
                                        class="w-full px-4 py-2 pr-28 border border-gray-300 rounded-lg uppercase font-mono tracking-wide focus:ring-2 focus:ring-blue-800 focus:border-transparent"
                                        placeholder="Ex: CARD2026000000000001">
                                    <button type="button" @click="generateCardNumber($event)"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                                        Générer
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Maximum 20 caractères, unique.</p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Type de carte <span
                                        class="text-red-500">*</span></label>
                                <select name="id_type_carte" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    <option value="">Sélectionnez un type</option>
                                    @foreach ($typesCartes as $type)
                                        <option value="{{ $type->id_type_carte }}"
                                            {{ (string) old('id_type_carte') === (string) $type->id_type_carte ? 'selected' : '' }}>
                                            {{ $type->nom_type_carte }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Banque <span
                                        class="text-red-500">*</span></label>
                                <select name="id_banque" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    <option value="">Sélectionnez une banque</option>
                                    @foreach ($banques as $banque)
                                        <option value="{{ $banque->id_banque }}"
                                            {{ (string) old('id_banque') === (string) $banque->id_banque ? 'selected' : '' }}>
                                            {{ $banque->nom_banque }} ({{ $banque->code_banque }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Grossiste <span
                                        class="text-red-500">*</span></label>
                                <select name="id_grossiste" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    <option value="">Sélectionnez un grossiste</option>
                                    @foreach ($grossistes as $grossiste)
                                        <option value="{{ $grossiste->id_user_detail }}"
                                            {{ (string) old('id_grossiste') === (string) $grossiste->id_user_detail ? 'selected' : '' }}>
                                            {{ $grossiste->raison_sociale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Date d'expiration <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="date_expiration" required min="{{ now()->format('Y-m-d') }}"
                                    value="{{ old('date_expiration') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Statut carte</label>
                                <select name="statut_carte"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    @foreach (['ENREGISTREE', 'ACTIVE', 'BLOQUEE', 'EXPIREE', 'ANNULEE'] as $statut)
                                        <option value="{{ $statut }}"
                                            {{ old('statut_carte', 'ENREGISTREE') === $statut ? 'selected' : '' }}>
                                            {{ $statut }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
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

        <div x-show="showView" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div @click="showView = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div @click.away="showView = false" class="relative w-full max-w-lg overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">Détail de la carte</h3>
                        <button type="button" @click="showView = false" class="text-white hover:text-gray-300">✕</button>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <p><span class="font-semibold">ID:</span> <span x-text="viewCard.id"></span></p>
                        <p><span class="font-semibold">Numéro:</span> <span class="font-mono" x-text="viewCard.numero"></span></p>
                        <p><span class="font-semibold">Type:</span> <span x-text="viewCard.type"></span></p>
                        <p><span class="font-semibold">Banque:</span> <span x-text="viewCard.banque"></span></p>
                        <p><span class="font-semibold">Grossiste:</span> <span x-text="viewCard.grossiste"></span></p>
                        <p><span class="font-semibold">Expiration:</span> <span x-text="viewCard.expiration"></span></p>
                        <p><span class="font-semibold">Statut:</span> <span x-text="viewCard.statut"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showEdit" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div @click="showEdit = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div @click.away="showEdit = false" class="relative w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">Modifier la carte</h3>
                        <button type="button" @click="showEdit = false" class="text-white hover:text-gray-300">✕</button>
                    </div>

                    <form :action="editAction" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Numéro de carte</label>
                                <input type="text" name="numero_carte" x-model="editCard.numero_carte" required maxlength="20"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg uppercase font-mono tracking-wide focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Type de carte</label>
                                <select name="id_type_carte" x-model="editCard.id_type_carte" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    @foreach ($typesCartes as $type)
                                        <option value="{{ $type->id_type_carte }}">{{ $type->nom_type_carte }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Banque</label>
                                <select name="id_banque" x-model="editCard.id_banque" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    @foreach ($banques as $banque)
                                        <option value="{{ $banque->id_banque }}">{{ $banque->nom_banque }} ({{ $banque->code_banque }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Grossiste</label>
                                <select name="id_grossiste" x-model="editCard.id_grossiste" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    @foreach ($grossistes as $grossiste)
                                        <option value="{{ $grossiste->id_user_detail }}">{{ $grossiste->raison_sociale }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Date d'expiration</label>
                                <input type="date" name="date_expiration" x-model="editCard.date_expiration" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Statut</label>
                                <select name="statut_carte" x-model="editCard.statut_carte" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                    @foreach (['ENREGISTREE', 'ACTIVE', 'BLOQUEE', 'EXPIREE', 'ANNULEE'] as $statut)
                                        <option value="{{ $statut }}">{{ $statut }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
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
    </div>
@endsection

@section('scripts')
    <script>
        function carteManager(openModalOnLoad = false) {
            return {
                showCreate: openModalOnLoad,
                showView: false,
                showEdit: false,
                editAction: '',
                viewCard: {
                    id: '',
                    numero: '',
                    type: '',
                    banque: '',
                    grossiste: '',
                    expiration: '',
                    statut: '',
                },
                editCard: {
                    numero_carte: '',
                    id_type_carte: '',
                    id_banque: '',
                    id_grossiste: '',
                    date_expiration: '',
                    statut_carte: 'ENREGISTREE',
                },

                generateCardNumber(event) {
                    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    const prefix = 'CARD' + new Date().getFullYear();
                    let result = prefix;

                    while (result.length < 20) {
                        result += chars.charAt(Math.floor(Math.random() * chars.length));
                    }

                    const input = event.target.closest('div').querySelector('input[name="numero_carte"]');
                    if (input) {
                        input.value = result.substring(0, 20);
                    }
                },

                openViewModal(card) {
                    this.viewCard = card;
                    this.showView = true;
                },

                openEditModal(card) {
                    this.editAction = `{{ url('/admin/cartes') }}/${card.id}`;
                    this.editCard = {
                        numero_carte: card.numero_carte ?? '',
                        id_type_carte: String(card.id_type_carte ?? ''),
                        id_banque: String(card.id_banque ?? ''),
                        id_grossiste: String(card.id_grossiste ?? ''),
                        date_expiration: card.date_expiration ?? '',
                        statut_carte: card.statut_carte ?? 'ENREGISTREE',
                    };
                    this.showEdit = true;
                },
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
@endsection
