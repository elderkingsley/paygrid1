<?php

use App\Models\User;
use App\Models\Invitation;
use App\Models\Organization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $organization_name = '';
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?string $token = null;
    public ?Invitation $invitation = null;

    public function mount()
    {
        $this->token = request()->query('token');

        if ($this->token) {
            $this->invitation = Invitation::where('token', $this->token)
                ->where('expires_at', '>', now())
                ->first();

            if ($this->invitation) {
                $this->email = $this->invitation->email;
                $this->organization_name = $this->invitation->organization->name;
            } else {
                session()->flash('error', 'This invitation link is invalid or has expired.');
                $this->redirectRoute('login');
            }
        }
    }

    public function register(): void
    {
        $this->validate([
            'organization_name' => $this->invitation ? ['nullable'] : ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $orgId = $this->invitation ? $this->invitation->organization_id : null;
        $role = $this->invitation ? $this->invitation->role : 'admin';

        // Create Organization if this is a fresh registration
        if (!$this->invitation) {
            $organization = Organization::create([
                'name' => $this->organization_name,
                'slug' => Str::slug($this->organization_name) . '-' . Str::random(5),
            ]);
            $orgId = $organization->id;
        }

        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'organization_id' => $orgId,
            'role' => $role,
        ]);

        event(new Registered($user));

        if ($this->invitation) {
            $this->invitation->delete();
        }

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="p-6">
    <form wire:submit="register">
        <div>
            <x-input-label for="organization_name" :value="__('Organization / Company Name')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
            <x-text-input
                wire:model="organization_name"
                id="organization_name"
                class="block mt-1 w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20 {{ $invitation ? 'opacity-50 cursor-not-allowed' : '' }}"
                type="text"
                required
                :readonly="$invitation !== null"
            />
            <x-input-error :messages="$errors->get('organization_name')" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20" type="text" required autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20" type="text" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
            <x-text-input
                wire:model="email"
                id="email"
                class="block mt-1 w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20 {{ $invitation ? 'opacity-50 cursor-not-allowed' : '' }}"
                type="email"
                required
                :readonly="$invitation !== null"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20" type="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full bg-slate-50 border-0 rounded-xl text-xs font-bold py-3 focus:ring-2 focus:ring-brand-primary/20" type="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-brand-dark transition-colors" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="px-8 py-3 bg-brand-primary text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-indigo-100 hover:opacity-90 transition-all active:scale-95">
                {{ __('Complete Registration') }}
            </button>
        </div>
    </form>
</div>
