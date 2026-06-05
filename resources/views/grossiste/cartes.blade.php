@extends('layouts.grossiste')

@section('title', 'Gestion des cartes')

@section('content')
    <div class="bg-gray-50 min-h-screen p-6 md:p-8" x-data="carteManager({{ $errors->any() ? 'true' : 'false' }})">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des cartes</h1>
                <p class="mt-1 text-gray-600">Créez vos cartes et consultez uniquement votre stock.</p>
            </div>
            <button @click="showCreate = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-700 text-white font-semibold rounded-lg hover:bg-emerald-800 transition-colors shadow-md hover:shadow-lg">
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
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Total cartes</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Enregistrées</p>
                <p class="mt-2 text-3xl font-bold text-slate-700">{{ $stats['enregistrees'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Actives</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $stats['actives'] }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Bloquées</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ $stats['bloquees'] }}</p>
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
                                Expiration</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Statut</th>

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

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
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
                    <div class="bg-emerald-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Ajouter une carte</h3>
                            <button type="button" @click="showCreate = false"
                                class="text-white hover:text-gray-300 hover:bg-emerald-600 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('grossiste.cartes.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Numéro de carte <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="numero_carte" required maxlength="20"
                                        value="{{ old('numero_carte') }}"
                                        class="w-full px-4 py-2 pr-28 border border-gray-300 rounded-lg uppercase font-mono tracking-wide focus:ring-2 focus:ring-emerald-700 focus:border-transparent"
                                        placeholder="Ex: CARD2026000000000001">
                                    <button type="button" @click="generateCardNumber($event)"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200">
                                        Générer
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Maximum 20 caractères, unique.</p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Type de carte <span
                                        class="text-red-500">*</span></label>
                                <select name="id_type_carte" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-700 focus:border-transparent">
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-700 focus:border-transparent">
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
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Date d'expiration <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="date_expiration" required min="{{ now()->format('Y-m-d') }}"
                                    value="{{ old('date_expiration') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-700 focus:border-transparent">
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">Statut carte</label>
                                <select name="statut_carte"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-700 focus:border-transparent">
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
                                class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800 transition-colors">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function carteManager(openModalOnLoad = false) {
            return {
                showCreate: openModalOnLoad,

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
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
@endsection
