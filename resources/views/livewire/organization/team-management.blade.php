<?php

use App\Models\User;
use App\Models\Invitation;
use App\Mail\TeamInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Volt\Component;

new class extends Component {
    public string $email = '';
    public string $role = 'requester';

    public function sendInvite()
    {
        $this->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:requester,approver,disburser'],
        ]);

        $invitation = Invitation::create([
            'organization_id' => auth()->user()->organization_id,
            'email' => $this->email,
            'role' => $this->role,
            'token' => Str::random(40),
            'expires_at' => now()->addHours(72),
        ]);

        Mail::to($this->email)->send(new TeamInvitationMail($invitation));

        $this->reset(['email', 'role']);
        session()->flash('status', 'Invitation sent. Link expires in 72 hours.');
    }

    public function with()
    {
        return [
            'members' => User::where('organization_id', auth()->user()->organization_id)->get(),
            'pending' => Invitation::where('organization_id', auth()->user()->organization_id)
                ->where('expires_at', '>', now())
                ->get(),
        ];
    }

    public function mount()
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col">
        <h2 class="text-xl font-black text-brand-dark uppercase tracking-tight">Team Management</h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Control access levels for your organization</p>
    </div>

    @if (session('status'))
        <div class="p-4 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-xl border border-emerald-100 uppercase tracking-tight">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-4">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-[11px] font-black text-brand-dark uppercase tracking-widest mb-6">Invite Member</h3>
                <form wire:submit="sendInvite" class="space-y-4">
                    <div>
                        <input wire:model="email" type="email" placeholder="Email Address" class="w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20">
                        @error('email') <span class="text-[9px] text-red-500 font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <select wire:model="role" class="w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20">
                            <option value="requester">Requester (Create Only)</option>
                            <option value="approver">Approver (Request + Approve)</option>
                            <option value="disburser">Disburser (Final Payment)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-brand-primary text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-indigo-100 hover:opacity-90 transition-all active:scale-95">
                        Send Invitation
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">User Details</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Permission</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($members as $member)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black text-brand-dark uppercase">{{ $member->first_name }} {{ $member->last_name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $member->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-tighter">{{ $member->role }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter">Active</span>
                            </td>
                        </tr>
                        @endforeach

                        @foreach($pending as $p)
                        <tr class="bg-slate-50/20">
                            <td class="px-6 py-4 opacity-50">
                                <p class="text-xs font-bold text-slate-500 italic">{{ $p->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-slate-300 uppercase italic">{{ $p->role }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[9px] font-black text-brand-accent uppercase tracking-tighter">Pending ({{ $p->expires_at->diffForHumans() }})</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
