<?php

use App\Models\PayoutRequest;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public bool $showModal = false;
    public bool $directAuthorize = true; // Default to direct for Admin
    public $amount, $beneficiary_name, $account_number, $bank_name, $bank_code, $reason;

    protected $listeners = ['openPayoutModal' => 'show'];

    public function show() { $this->showModal = true; }

    public function close() {
        $this->reset(['amount', 'beneficiary_name', 'account_number', 'bank_name', 'reason', 'directAuthorize']);
        $this->showModal = false;
    }

    public function submit($status = 'pending')
    {
        $this->validate([
            'amount' => 'required|numeric|min:100',
            'beneficiary_name' => 'required|string|max:255',
            'account_number' => 'required|digits:10',
            'bank_name' => 'required',
            'reason' => 'nullable|min:5',
        ]);

        // Internal Control: If Admin chooses Direct Authorize, status is 'approved'
        $finalStatus = (auth()->user()->isAdmin() && $this->directAuthorize && $status === 'pending')
            ? 'approved'
            : $status;

        PayoutRequest::create([
            'id' => Str::uuid(),
            'organization_id' => auth()->user()->organization_id,
            'requester_id' => auth()->id(),
            'amount' => $this->amount,
            'beneficiary_name' => $this->beneficiary_name,
            'account_number' => $this->account_number,
            'bank_code' => '000',
            'bank_name' => $this->bank_name,
            'reason' => $this->reason ?? 'Business Expense',
            'status' => $finalStatus,
            'approver_id' => ($finalStatus === 'approved') ? auth()->id() : null,
            'approved_at' => ($finalStatus === 'approved') ? now() : null,
        ]);

        $this->dispatch('notify', message: $finalStatus === 'approved' ? 'Expense authorized!' : 'Request submitted.');
        $this->close();
        $this->redirect(route('dashboard'), navigate: true);
    }
}; ?>

<div x-data="{ open: @entangle('showModal') }" x-on:open-payout-modal.window="open = true" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <style>
        /* Force remove spinners */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>

    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="open = false"></div>

    <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="relative w-full max-w-xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100">

        <div class="p-8 sm:p-10">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">New Expense</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Initiate a new expense request</p>
                </div>
                <button @click="open = false" class="p-2 bg-slate-50 rounded-full hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="submit" class="space-y-6">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Amount (NGN)</label>
                    <div class="flex items-center">
                        <span class="text-2xl font-black text-slate-300 mr-2">₦</span>
                        <input wire:model="amount" type="number" class="w-full bg-transparent border-0 p-0 text-3xl font-black text-brand-dark focus:ring-0 placeholder-slate-200" placeholder="0.00" required>
                    </div>
                </div>

                @if(auth()->user()->isAdmin())
                <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <svg class="w-4 h-4 text-brand-primary" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-brand-primary uppercase tracking-widest">Admin Authorization</p>
                            <p class="text-[9px] text-indigo-400 font-bold">Skip approval queue & authorize now</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="directAuthorize" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-primary"></div>
                    </label>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Account Number</label>
                        <input wire:model="account_number" type="text" class="w-full bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="0123456789" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Select Bank</label>
                        <select wire:model="bank_name" class="w-full bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" required>
                            <option value="">Choose...</option>
                            <option value="Access">Access Bank</option>
                            <option value="GTBank">GTBank</option>
                            <option value="Zenith">Zenith Bank</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-6">
                    <button type="button" wire:click="submit('draft')" class="py-4 bg-slate-100 text-slate-500 text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-200 transition-all">Save Draft</button>
                    <button type="submit" class="py-4 bg-brand-primary text-white text-[10px] font-black rounded-2xl shadow-xl shadow-indigo-100 uppercase tracking-widest hover:opacity-90 transition-all">
                        {{ auth()->user()->isAdmin() && $directAuthorize ? 'Direct Authorize' : 'Submit for Approval' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
