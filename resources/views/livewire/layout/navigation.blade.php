<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void {
        $logout(); $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex items-center space-x-4">
    <div class="text-right">
        <p class="text-xs font-black text-brand-dark uppercase">{{ auth()->user()->first_name }}</p>
        <p class="text-[9px] text-brand-secondary font-bold uppercase tracking-widest">{{ auth()->user()->role }}</p>
    </div>
    <button wire:click="logout" class="text-[10px] font-bold text-red-500 hover:underline uppercase">Logout</button>
</div>
