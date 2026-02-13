<?php

use App\Models\PayoutRequest;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public bool $showModal = false;
    public $amount, $beneficiary_name, $account_number, $bank_name, $bank_code, $reason, $proof_of_work;

    protected $listeners = ['openPayoutModal' => 'show'];

    public function show() { $this->showModal = true; }
    public function close() { $this->reset(); $this->showModal = false; }

    public function submit($status = 'pending')
    {
        $this->validate([
            'amount' => 'required|numeric|min:100',
            'beneficiary_name' => 'required|string|max:255',
            'account_number' => 'required|digits:10',
            'bank_name' => 'required',
            'reason' => 'required|min:5',
        ]);

        $finalStatus = (auth()->user()->isAdmin() && $status === 'pending') ? 'approved' : $status;

        PayoutRequest::create([
            'id' => Str::uuid(),
            'organization_id' => auth()->user()->organization_id,
            'requester_id' => auth()->id(),
            'amount' => $this->amount,
            'beneficiary_name' => $this->beneficiary_name,
            'account_number' => $this->account_number,
            'bank_code' => '000',
            'bank_name' => $this->bank_name,
            'reason' => $this->reason,
            'status' => $finalStatus,
            'approver_id' => auth()->user()->isAdmin() ? auth()->id() : null,
            'approved_at' => auth()->user()->isAdmin() ? now() : null,
        ]);

        $this->dispatch('notify', message: 'Request processed successfully!');
        $this->close();
    }
}; ?>

<div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>

    <div class="relative w-full max-w-xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all border border-slate-100"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100">

        <div class="p-8 sm:p-10">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">New Payout</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Initiate a secure fund transfer</p>
                </div>
                <button @click="open = false" class="p-2 bg-slate-50 rounded-full hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="submit" class="space-y-6">
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Amount to Send (NGN)</label>
                    <div class="flex items-center">
                        <span class="text-2xl font-black text-slate-300 mr-2">₦</span>
                        <input wire:model="amount" type="number" class="w-full bg-transparent border-0 p-0 text-3xl font-black text-brand-dark focus:ring-0 placeholder-slate-200" placeholder="0.00">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Account Number</label>
                        <input wire:model="account_number" type="text" class="w-full bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="0123456789">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Select Bank</label>
                        <select wire:model="bank_name" class="w-full bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20">
                            <option value="">Choose...</option>
                            <option value="Access">Access Bank</option>
                            <option value="GTBank">GTBank</option>
                            <option value="Zenith">Zenith Bank</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Beneficiary Name</label>
                    <input wire:model="beneficiary_name" type="text" class="w-full bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="Receiver full name">
                </div>

                <div class="grid grid-cols-2 gap-4 pt-6">
                    <button type="button" wire:click="submit('draft')" class="py-4 bg-slate-100 text-slate-500 text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-200 transition-all">Save Draft</button>
                    <button type="submit" class="py-4 bg-brand-primary text-white text-[10px] font-black rounded-2xl shadow-xl shadow-indigo-100 uppercase tracking-widest hover:opacity-90 transition-all">
                        {{ auth()->user()->isAdmin() ? 'Direct Pay' : 'Submit for Approval' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
