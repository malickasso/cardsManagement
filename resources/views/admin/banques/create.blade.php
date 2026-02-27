@extends('admin.layouts.app')

@section('title', 'Gestion des banques')

@section('content')
    <div class="bg-gray-50 min-h-screen p-6 md:p-8" x-data="banqueManager()">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des banques</h1>
                <p class="mt-1 text-gray-600">Créer une banque selon la table <span class="font-semibold">banque</span>.</p>
            </div>
            <button @click="showCreate = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-800 text-white font-semibold rounded-lg hover:bg-blue-900 transition-colors shadow-md hover:shadow-lg">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Ajouter une Banque
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
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total</p>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Actives</p>
                <p class="mt-1 text-3xl font-bold text-emerald-700">{{ number_format($stats['actif'] ?? 0) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Inactives</p>
                <p class="mt-1 text-3xl font-bold text-red-600">{{ number_format($stats['inactif'] ?? 0) }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Nom Banque</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Code Banque</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($banques as $banque)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-800">{{ $banque->id_banque }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $banque->nom_banque }}</td>
                                <td class="px-4 py-3 font-mono text-sm text-gray-700">{{ $banque->code_banque }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $banque->statut === 'ACTIF' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $banque->statut }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Aucune banque trouvée dans la table <span class="font-semibold">banque</span>.
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

                <div @click.away="showCreate = false" class="relative w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-xl">
                    <div class="bg-blue-800 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-white">Ajouter une Banque</h3>
                            <button type="button" @click="showCreate = false"
                                class="text-white hover:text-gray-300 hover:bg-blue-700 rounded-lg p-1 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('admin.banques.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Nom Banque <span class="text-red-500">*</span></label>
                            <input type="text" name="nom_banque" required maxlength="150" value="{{ old('nom_banque') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Code Banque <span class="text-red-500">*</span></label>
                            <input type="text" name="code_banque" required maxlength="50" value="{{ old('code_banque') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg uppercase focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Statut</label>
                            <select name="statut"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-800 focus:border-transparent">
                                <option value="ACTIF" {{ old('statut', 'ACTIF') === 'ACTIF' ? 'selected' : '' }}>ACTIF</option>
                                <option value="INACTIF" {{ old('statut') === 'INACTIF' ? 'selected' : '' }}>INACTIF</option>
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
    </div>
@endsection

@section('scripts')
    <script>
        function banqueManager() {
            return {
                showCreate: false,
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
@endsection
