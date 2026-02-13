<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex items-center">
    <x-dropdown align="right" width="56" contentClasses="py-1 bg-white border-0 shadow-2xl rounded-2xl">
        <x-slot name="trigger">
            <button class="flex flex-col text-right leading-tight group focus:outline-none transition-all duration-200 hover:opacity-70 active:scale-[0.98]">
                <p class="text-[11px] font-black text-brand-dark uppercase tracking-tight">
                    {{ auth()->user()->organization->name }}
                </p>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mt-1">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </p>
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-5 py-3 border-b border-slate-50/60 mb-1">
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mb-0.5">Access Level</p>
                <p class="text-[11px] font-black text-brand-dark uppercase tracking-tighter">{{ auth()->user()->role }}</p>
            </div>

            <div class="px-1 space-y-0.5">
                <x-dropdown-link :href="route('profile')" wire:navigate class="text-[11px] font-bold text-slate-600 rounded-xl">
                    {{ __('Settings') }}
                </x-dropdown-link>

                <button wire:click="logout" class="w-full text-start px-1">
                    <x-dropdown-link class="text-[11px] font-black text-red-500 hover:bg-red-50/50 rounded-xl">
                        {{ __('Sign Out') }}
                    </x-dropdown-link>
                </button>
            </div>
        </x-slot>
    </x-dropdown>
</div>
