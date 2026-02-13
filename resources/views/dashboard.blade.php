<x-app-layout>
    <div class="max-w-[1400px] mx-auto space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @php
                $metrics = [
                    ['label' => 'Total Balance', 'val' => '₦ 0.00', 'sub' => 'Main Wallet', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Virtual Account', 'val' => 'NOT ACTIVE', 'sub' => 'Verification Required', 'icon' => 'M8 14v20m0 0l-8-8m8 8l8-8'],
                    ['label' => 'Total Outflow', 'val' => '₦ 0.00', 'sub' => 'This Month', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                    ['label' => 'Team Active', 'val' => '1', 'sub' => 'Admin Role', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ];
            @endphp

            @foreach($metrics as $m)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center group hover:border-brand-primary transition-colors duration-300">
                <div class="flex-1">
                    <p class="text-[10px] font-black text-brand-secondary uppercase tracking-[0.15em] mb-1.5">{{ $m['label'] }}</p>
                    <h3 class="text-2xl font-black text-brand-dark tracking-tighter">{{ $m['val'] }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold mt-1.5">{{ $m['sub'] }}</p>
                </div>
                <div class="p-3 bg-brand-surface text-slate-300 rounded-xl group-hover:text-brand-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $m['icon'] }}" /></svg>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h4 class="text-[11px] font-black text-brand-dark uppercase tracking-widest">Recent Transactions</h4>
                    <button class="text-[10px] font-black text-brand-primary hover:underline">Download CSV</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reference</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td colspan="3" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center opacity-40">
                                        <svg class="w-12 h-12 text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Transaction stream empty</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-brand-primary rounded-[2rem] p-8 shadow-2xl shadow-indigo-100 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-xl font-black mb-2 tracking-tight">Activate Wallet</h4>
                        <p class="text-xs text-indigo-100 leading-relaxed mb-8 opacity-80 font-medium">Please provide your BVN and NIN to generate your Monnify virtual accounts.</p>

                        <div class="mb-8">
                            <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-[0.2em]">
                                <span>Verification</span>
                                <span>30%</span>
                            </div>
                            <div class="w-full bg-indigo-900/50 h-2 rounded-full overflow-hidden">
                                <div class="bg-white h-full shadow-[0_0_10px_rgba(255,255,255,0.5)]" style="width: 30%"></div>
                            </div>
                        </div>

                        <button x-on:click="$dispatch('openKycModal')" class="w-full py-4 bg-white text-brand-primary text-xs font-black rounded-2xl hover:bg-slate-50 transition-all shadow-xl active:scale-95 uppercase tracking-widest">
                            Verify Identity
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500 rounded-full opacity-30"></div>
                </div>
            </div>
        </div>
    </div>

    <livewire:kyc-modal />
</x-app-layout>
