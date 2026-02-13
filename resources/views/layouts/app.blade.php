<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Paygrid | Dashboard</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-surface text-slate-900">
        <div class="flex h-screen overflow-hidden">

            <aside class="w-64 bg-brand-dark flex-shrink-0 hidden md:flex flex-col border-r border-slate-800 z-50">
                <div class="h-16 flex items-center px-6 border-b border-slate-800/50">
                    <span class="text-white font-extrabold text-xl tracking-tighter uppercase italic">Paygrid</span>
                </div>

                <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] px-3 mb-3">Main Menu</div>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-xs font-bold rounded-xl {{ request()->routeIs('dashboard') ? 'bg-brand-primary text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }} transition-all duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        DASHBOARD
                    </a>
                </nav>

                @if(!auth()->user()->bvn)
                <div class="p-5 m-4 bg-slate-800/40 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                    <p class="text-[10px] font-bold text-brand-accent uppercase tracking-widest mb-1">KYC Required</p>
                    <p class="text-[11px] text-slate-400 leading-relaxed mb-4">Complete verification to enable payouts.</p>
                    <button x-data @click="$dispatch('openKycModal')" class="w-full py-2.5 bg-brand-primary hover:bg-indigo-500 text-white text-[11px] font-black rounded-lg transition-all shadow-md active:scale-95 uppercase tracking-wider">
                        Verify Now
                    </button>
                </div>
                @endif
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
                <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
                    <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <span>Workspace</span>
                        <span class="text-slate-200">/</span>
                        <span class="text-brand-dark">{{ request()->route()->getName() }}</span>
                    </div>
                    <livewire:layout.navigation />
                </header>

                <main class="flex-1 overflow-y-auto p-8 lg:p-10">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
