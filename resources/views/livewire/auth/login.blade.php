<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6 ">
    <div class="bg-[#041D56] flex flex-col rounded-xl p-7">
        <h1 class="text-white font-black text-center text-3xl">LOGIN</h1>
        <form wire:submit="login" class="flex flex-col pt-16">
            <flux:field>
                <flux:label class="text-white!">Email</flux:label>
                <flux:input wire:model="email" class="bg-[#D6F0FE] rounded-xl text-black!" class:input="text-black!" type="email" required />
                <flux:error name="username" />
            </flux:field>
            <flux:field class="pt-3">
                <flux:label class="text-white!">Password</flux:label>
                <flux:input wire:model="password" class="bg-[#D6F0FE] rounded-xl" class:input="text-black!" type="password" required />
                <flux:error name="password" />
            </flux:field>

            <div class="flex justify-between py-8">
                <flux:field variant="inline">
                    <flux:checkbox wire:model="terms" />
    
                    <flux:label>Ingat Saya</flux:label>
    
                    <flux:error name="terms" />
                </flux:field>

                <flux:link class="text-sm" href="#">Lupa Password</flux:link>
            </div>

            <flux:button  type="submit" class="w-full bg-[#276DA9]! mb-4">Login</flux:button>

        </form>
        @if (Route::has('register'))
            <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Tidak punya akun?') }}
                <flux:link :href="route('register')" wire:navigate>{{ __('Daftar') }}</flux:link>
            </div>
        @endif
    </div>

</div>