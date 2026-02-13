<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Paygrid') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <aside class="w-64 bg-white border-r border-gray-100 hidden md:flex flex-col fixed inset-y-0 z-20">
                <div class="p-6">
                    <x-application-logo class="h-8 w-auto fill-current text-indigo-600" />
                </div>

                <nav class="flex-1 px-4 space-y-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="w-full flex items-center px-3 py-2 text-xs font-semibold rounded-lg">
                        Dashboard
                    </x-nav-link>
                    </nav>

                @if(!auth()->user()->bvn)
                <div class="m-4 p-4 bg-orange-50 border border-orange-100 rounded-xl">
                    <p class="text-[10px] font-bold text-orange-800 uppercase tracking-tighter">KYC Required</p>
                    <button x-data @click="$dispatch('openKycModal')" class="mt-2 w-full bg-orange-600 text-white text-[10px] font-bold py-2 rounded-lg hover:bg-orange-700">
                        Complete Setup
                    </button>
                </div>
                @endif
            </aside>

            <div class="flex-1 md:ml-64 flex flex-col">
                <header class="h-14 bg-white border-b border-gray-100 sticky top-0 z-10">
                    <livewire:layout.navigation />
                </header>

                <main class="p-4 lg:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
