<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class KycModal extends Component
{
    public $phone_number;
    public $bvn;
    public $nin;
    // app/Livewire/KycModal.php

public $showModal = false;

// This allows other components to "call" this modal
protected $listeners = ['openKycModal' => 'show'];

public function mount()
    {
        // Fail-safe: If a non-admin somehow triggers this,
        // we just exit the method immediately.
        if (! Auth::user()->isAdmin()) {
            return;
        }
    }

public function show()
{
    $this->showModal = true;
}

    public function saveKyc()
    {
        $validated = $this->validate([
            'phone_number' => ['required', 'string', 'max:15'],
            'bvn' => ['required', 'string', 'size:11'],
            'nin' => ['required', 'string', 'size:11'],
        ]);

        $user = Auth::user();
        $user->update($validated);

        $this->showModal = false;

        // Refresh the page to unlock full dashboard features
        return redirect()->to('/dashboard');
    }

    public function render()
    {
        return view('livewire.kyc-modal');
    }
}
