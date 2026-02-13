<x-app-layout>
    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Main Balance</p>
                    <h3 class="text-xl font-bold text-gray-900">₦0.00</h3>
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center text-[9px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full border border-orange-100">
                        <span class="w-1 h-1 bg-orange-500 rounded-full mr-1.5 animate-pulse"></span>
                        KYC Required
                    </span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Virtual Account</p>
                <p class="text-xs font-mono text-gray-400 mt-2">NOT GENERATED</p>
                <button x-on:click="$dispatch('openKycModal')" class="text-[10px] text-indigo-600 font-bold hover:text-indigo-800 mt-2 block">
                    Initialize Setup →
                </button>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Pending Approvals</p>
                <div class="flex items-center justify-between mt-1">
                    <h3 class="text-xl font-bold text-gray-900">0</h3>
                    <svg class="w-4 h-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2" /></svg>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Organization Team</p>
                <div class="flex items-center mt-2">
                    <div class="h-6 w-6 rounded-full bg-indigo-600 flex items-center justify-center text-[10px] text-white font-bold border border-white">
                        {{ substr(auth()->user()->first_name, 0, 1) }}
                    </div>
                    <span class="ml-2 text-xs font-bold text-gray-700">1 Member</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <div class="lg:col-span-8 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Recent Activity</h4>
                    <button class="text-[10px] font-bold text-gray-400 hover:text-indigo-600 transition">Download Statements</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase">Description</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase">Date</th>
                                <th class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-8 h-8 text-gray-100 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                        <p class="text-xs text-gray-400 italic">No transactions found. Complete KYC to enable wallet inflows.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-4">
                <div class="bg-indigo-900 p-5 rounded-2xl shadow-lg shadow-indigo-100 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-xs font-bold mb-1">Onboarding Progress</h4>
                        <p class="text-[10px] text-indigo-300 mb-4 italic">Finish setup to unlock virtual accounts</p>

                        <div class="w-full bg-indigo-800 rounded-full h-1.5 mb-2">
                            <div class="bg-indigo-400 h-1.5 rounded-full transition-all duration-500" style="width: 30%"></div>
                        </div>
                        <div class="flex justify-between items-center mb-5">
                            <span class="text-[9px] font-bold uppercase text-indigo-300">Profile Strength</span>
                            <span class="text-[9px] font-bold text-white">30%</span>
                        </div>

                        <button x-on:click="$dispatch('openKycModal')" class="w-full py-2.5 bg-white text-indigo-900 text-xs font-black rounded-lg hover:bg-indigo-50 transition active:scale-95">
                            VERIFY IDENTITY NOW
                        </button>
                    </div>
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-800 rounded-full opacity-50"></div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-3">Support</h4>
                    <p class="text-[11px] text-gray-600 leading-relaxed">Need help with your BVN/NIN verification? Contact our compliance team.</p>
                    <a href="mailto:support@paygrid.ng" class="text-[11px] font-bold text-indigo-600 mt-2 block">support@paygrid.ng</a>
                </div>
            </div>

        </div>
    </div>

    <livewire:kyc-modal />
</x-app-layout>
