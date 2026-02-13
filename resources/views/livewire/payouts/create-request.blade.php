<?php

use App\Models\PayoutRequest;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public $amount;
    public $beneficiary_name;
    public $account_number;
    public $bank_name;
    public $bank_code;
    public $reason;
    public $proof_of_work;

    protected $rules = [
        'amount' => 'required|numeric|min:100',
        'beneficiary_name' => 'required|string|max:255',
        'account_number' => 'required|digits:10',
        'bank_name' => 'required|string',
        'reason' => 'required|string|min:5',
        'proof_of_work' => 'nullable|mimes:pdf,jpg,png|max:2048', // 2MB Max
    ];

    public function submit($status = 'pending')
    {
        $this->validate();

        $path = $this->proof_of_work
            ? $this->proof_of_work->store('proofs', 'public')
            : null;

        PayoutRequest::create([
            'organization_id' => auth()->user()->organization_id,
            'requester_id' => auth()->id(),
            'amount' => $this->amount,
            'beneficiary_name' => $this->beneficiary_name,
            'account_number' => $this->account_number,
            'bank_code' => '000', // We will automate this with a Bank API later
            'bank_name' => $this->bank_name,
            'reason' => $this->reason,
            'proof_of_work_path' => $path,
            'status' => $status, // Can be 'draft' or 'pending'
        ]);

        session()->flash('status', $status === 'draft' ? 'Draft saved.' : 'Request submitted for approval.');
        $this->redirect(route('dashboard'), navigate: true);
    }
}; ?>

<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50">
        <h2 class="text-xl font-black text-brand-dark uppercase tracking-tight mb-2">New Payout Request</h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-10">Provide beneficiary and transaction details</p>

        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Amount (NGN)</label>
                    <input wire:model="amount" type="number" class="w-full mt-2 bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="0.00">
                    @error('amount') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Beneficiary Name</label>
                    <input wire:model="beneficiary_name" type="text" class="w-full mt-2 bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="Full Legal Name">
                    @error('beneficiary_name') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Account Number</label>
                    <input wire:model="account_number" type="text" maxlength="10" class="w-full mt-2 bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="0123456789">
                    @error('account_number') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Bank Name</label>
                    <select wire:model="bank_name" class="w-full mt-2 bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20">
                        <option value="">Select Bank</option>
                        <option value="Access Bank">Access Bank</option>
                        <option value="GTBank">Guaranty Trust Bank</option>
                        <option value="Zenith Bank">Zenith Bank</option>
                        <option value="Kuda Bank">Kuda Microfinance Bank</option>
                    </select>
                    @error('bank_name') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Reason for Payout</label>
                <textarea wire:model="reason" rows="3" class="w-full mt-2 bg-slate-50 border-0 rounded-2xl text-xs font-bold py-4 focus:ring-2 focus:ring-brand-primary/20" placeholder="Description of service or item..."></textarea>
                @error('reason') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="p-6 border-2 border-dashed border-slate-100 rounded-3xl bg-slate-50/30">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Proof of Work (Optional)</label>
                <input type="file" wire:model="proof_of_work" class="mt-4 block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-brand-primary file:text-white hover:file:bg-brand-dark cursor-pointer">
                @error('proof_of_work') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4">
                <button type="button" wire:click="submit('draft')" class="py-4 bg-slate-100 text-slate-500 text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-slate-200 transition-all">
                    Save as Draft
                </button>
                <button type="submit" class="py-4 bg-brand-primary text-white text-[10px] font-black rounded-2xl shadow-xl shadow-indigo-100 uppercase tracking-widest hover:opacity-90 transition-all">
                    Submit for Approval
                </button>
            </div>
        </form>
    </div>
</div>
