<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="bg-[#041D56] flex flex-col rounded-xl p-7">
        <h1 class="text-white font-black text-center text-3xl pt-2">DAFTAR</h1>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="register" class="flex flex-col gap-6 pt-10">
            <!-- Name -->
            <flux:input wire:model="name" :label="__('Nama')" type="text" required autofocus autocomplete="name"
                :placeholder="__('Nama Lengkap')" class="bg-[#D6F0FE] rounded-xl" class:input="text-black!"/>

            <!-- Email Address -->
            <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email"
                placeholder="email@example.com" class="bg-[#D6F0FE] rounded-xl" class:input="text-black!" />

            <!-- Password -->
            <flux:input wire:model="password" :label="__('Password')" type="password" required
                autocomplete="new-password" :placeholder="__('Password')" class="bg-[#D6F0FE] rounded-xl" class:input="text-black!" viewable />

            <!-- Confirm Password -->
            <flux:input wire:model="password_confirmation" :label="__('Konfirmasi password')" type="password" required
                autocomplete="new-password" :placeholder="__('Konfirmasi password')" class="bg-[#D6F0FE] rounded-xl" class:input="text-black!" viewable />

            <div class="flex items-center justify-end my-3">
                <flux:button type="submit" variant="primary" class="w-full bg-[#276DA9]! text-white">
                    {{ __('Buat Akun') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Sudah mempunyai akun?') }}
            <flux:link :href="route('login')" wire:navigate>{{ __('Masuk') }}</flux:link>
        </div>
    </div>

    <!-- Session Status -->
</div>