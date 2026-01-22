@extends('admin.layouts.app')

@section('title', 'Tableau de Bord - Gestion de Cartes')

@section('content')
<div class="flex h-screen bg-gray-50">

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Page Header -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord</h1>
                    <p class="text-gray-600 mt-2">Vue d'ensemble de votre système de gestion de cartes</p>
                </div>

                <!-- Stats Cards -->
                @include('admin.partials.stats-cards')

                <!-- Recharge Chart -->
                @include('admin.partials.recharge-chart')

                <!-- Transactions Table -->
                @include('admin.partials.transactions-table')
            </div>
        </main>
    </div>
</div>
@endsection
