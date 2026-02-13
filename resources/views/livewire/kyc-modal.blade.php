<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-90 backdrop-blur-sm">
            <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Complete Your Profile</h2>
                <p class="text-sm text-gray-600 mb-6">
                    To enable virtual accounts and payments via Monnify, we need to verify your identity.
                </p>

                <form wire:submit.prevent="saveKyc" class="space-y-4">
                    <div>
                        <x-input-label for="phone_number" value="Phone Number" />
                        <x-text-input wire:model="phone_number" class="block mt-1 w-full" type="text" placeholder="080..." required />
                        <x-input-error :messages="$errors->get('phone_number')" />
                    </div>

                    <div>
                        <x-input-label for="bvn" value="Bank Verification Number (BVN)" />
                        <x-text-input wire:model="bvn" class="block mt-1 w-full" type="text" maxlength="11" required />
                        <x-input-error :messages="$errors->get('bvn')" />
                    </div>

                    <div>
                        <x-input-label for="nin" value="National Identity Number (NIN)" />
                        <x-text-input wire:model="nin" class="block mt-1 w-full" type="text" maxlength="11" required />
                        <x-input-error :messages="$errors->get('nin')" />
                    </div>

                    <div class="pt-4">
                        <x-primary-button class="w-full justify-center py-3">
                            Verify Identity & Unlock Dashboard
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
