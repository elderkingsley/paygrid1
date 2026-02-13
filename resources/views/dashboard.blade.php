<x-app-layout>
    <div class="max-w-[1400px] mx-auto space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @php
                $role = auth()->user()->role;
                $isAdminOrApprover = in_array($role, ['admin', 'approver', 'disburser']);

                if ($isAdminOrApprover) {
                    // Oversight Metrics
                    $metrics = [
                        ['label' => 'Total Balance', 'val' => '₦ 0.00', 'sub' => 'Main Wallet', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Total Outflow', 'val' => '₦ 0.00', 'sub' => 'This Month', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                        ['label' => 'Virtual Account', 'val' => 'NOT ACTIVE', 'sub' => 'KYC Required', 'icon' => 'M8 14v20m0 0l-8-8m8 8l8-8'],
                        ['label' => 'Team Active', 'val' => auth()->user()->organization->users()->count(), 'sub' => 'Total Members', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                } else {
                    // Requester Specific Metrics
                    $metrics = [
                        ['label' => 'My Requests', 'val' => auth()->user()->payoutRequests()->count(), 'sub' => 'Total Created', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['label' => 'Pending Approval', 'val' => auth()->user()->payoutRequests()->where('status', 'pending')->count(), 'sub' => 'Awaiting Review', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Disbursed', 'val' => '₦ ' . number_format(auth()->user()->payoutRequests()->where('status', 'disbursed')->sum('amount'), 2), 'sub' => 'Paid Out', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['label' => 'Rejected', 'val' => auth()->user()->payoutRequests()->where('status', 'rejected')->count(), 'sub' => 'Needs Attention', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                }
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
                    <h4 class="text-[11px] font-black text-brand-dark uppercase tracking-widest">
                        {{ $isAdminOrApprover ? 'Recent Transactions' : 'My Payout Requests' }}
                    </h4>
                    <button class="text-[10px] font-black text-brand-primary hover:underline uppercase tracking-tighter">View All History</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Beneficiary</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            {{-- This will be populated by your PayoutRequest model later --}}
                            <tr>
                                <td colspan="4" class="px-6 py-24 text-center">
                                    <p class="text-xs font-black text-slate-300 uppercase tracking-widest italic">No active requests found</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">

                @if($role === 'requester')
                <div class="bg-brand-primary rounded-[2rem] p-8 shadow-2xl shadow-indigo-100 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-xl font-black mb-2 tracking-tight">New Request</h4>
                        <p class="text-xs text-indigo-100 leading-relaxed mb-8 opacity-80 font-medium">Create a new payout for approval. Drafts can be edited later.</p>

                        <a href="{{ route('payouts.create') }}" wire:navigate class="block w-full py-4 bg-white text-brand-primary text-center text-[10px] font-black rounded-2xl shadow-xl uppercase tracking-widest hover:opacity-90 transition-all active:scale-95">
                            Create Payout
                        </a>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500 rounded-full opacity-30"></div>
                </div>
                @endif

                @if($role === 'admin')
                <div class="bg-brand-primary rounded-[2rem] p-8 shadow-2xl shadow-indigo-100 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-xl font-black mb-2 tracking-tight">Activate Wallet</h4>
                        <p class="text-xs text-indigo-100 leading-relaxed mb-8 opacity-80 font-medium">Provide BVN and NIN to enable virtual account generation.</p>

                        <div class="mb-8">
                            <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-[0.2em]">
                                <span>Verification</span>
                                <span>30%</span>
                            </div>
                            <div class="w-full bg-indigo-900/50 h-2 rounded-full overflow-hidden">
                                <div class="bg-white h-full" style="width: 30%"></div>
                            </div>
                        </div>

                        <button x-on:click="$dispatch('openKycModal')" class="w-full py-4 bg-white text-brand-primary text-xs font-black rounded-2xl hover:bg-slate-50 transition-all shadow-xl active:scale-95 uppercase tracking-widest">
                            Verify Identity
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500 rounded-full opacity-30"></div>
                </div>
                @endif

                @if(!auth()->user()->isAdmin() && !auth()->user()->bvn && $role !== 'requester')
                <div class="bg-slate-100 rounded-[2rem] p-8 text-slate-500 border border-slate-200">
                    <h4 class="text-base font-black mb-2 tracking-tight uppercase">Account Locked</h4>
                    <p class="text-[11px] leading-relaxed font-bold opacity-80 italic">Organization KYC is incomplete. Only admins can activate payout features.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <livewire:kyc-modal />
    @endif
</x-app-layout>
