<x-app-layout>
    <div class="max-w-[1400px] mx-auto space-y-8">
        @php
            $user = auth()->user();
            $role = $user->role;
            // Define the variable clearly for the whole page
            $isAdminOrApprover = in_array($role, ['admin', 'approver', 'disburser']);

            // Safeguard for organization count
            $teamCount = $user->organization ? $user->organization->users()->count() : 1;

            if ($isAdminOrApprover) {
                $metrics = [
                    ['label' => 'Total Balance', 'val' => '₦ 0.00', 'sub' => 'Main Wallet', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2'],
                    ['label' => 'Total Outflow', 'val' => '₦ 0.00', 'sub' => 'This Month', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                    ['label' => 'Virtual Account', 'val' => 'NOT ACTIVE', 'sub' => 'KYC Required', 'icon' => 'M8 14v20m0 0l-8-8m8 8l8-8'],
                    ['label' => 'Team Active', 'val' => $teamCount, 'sub' => 'Total Members', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ];
            } else {
                $metrics = [
                    ['label' => 'My Requests', 'val' => $user->payoutRequests()->count(), 'sub' => 'Total Created', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2'],
                    ['label' => 'Pending Approval', 'val' => $user->payoutRequests()->where('status', 'pending')->count(), 'sub' => 'Awaiting Review', 'icon' => 'M12 8v4l3 3'],
                    ['label' => 'Disbursed', 'val' => '₦ ' . number_format($user->payoutRequests()->where('status', 'disbursed')->sum('amount'), 2), 'sub' => 'Paid Out', 'icon' => 'M9 12l2 2 4-4'],
                    ['label' => 'Rejected', 'val' => $user->payoutRequests()->where('status', 'rejected')->count(), 'sub' => 'Needs Attention', 'icon' => 'M10 14l2-2'],
                ];
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @foreach($metrics as $m)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center group hover:border-brand-primary transition-colors">
                <div class="flex-1">
                    <p class="text-[10px] font-black text-brand-secondary uppercase tracking-[0.15em] mb-1.5">{{ $m['label'] }}</p>
                    <h3 class="text-2xl font-black text-brand-dark tracking-tighter">{{ $m['val'] }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold mt-1.5">{{ $m['sub'] }}</p>
                </div>
                <div class="p-3 bg-brand-surface text-slate-300 rounded-xl group-hover:text-brand-primary">
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
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Beneficiary</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td colspan="2" class="px-6 py-24 text-center text-slate-300 italic text-xs uppercase tracking-widest">No active records</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                @if($user->isAdmin())
                <div class="bg-brand-primary rounded-[2rem] p-8 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-xl font-black mb-2 tracking-tight uppercase">Activate Wallet</h4>
                        <p class="text-xs text-indigo-100 leading-relaxed mb-8 opacity-80 font-medium italic">Complete KYC to unlock organization-wide disbursements.</p>
                        <button x-on:click="$dispatch('openKycModal')" class="w-full py-4 bg-white text-brand-primary text-[10px] font-black rounded-2xl shadow-xl uppercase tracking-widest">
                            Verify Identity
                        </button>
                    </div>
                </div>
                @else
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden">
                    <h4 class="text-lg font-black mb-1 tracking-tight uppercase">Quick Info</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-6">Internal Controls Active</p>
                    <p class="text-[11px] leading-relaxed text-slate-300 italic">
                        Use the sidebar to create requests. Note that you cannot approve or disburse your own initiated payments.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($user->isAdmin())
        <livewire:kyc-modal />
    @endif
</x-app-layout>
