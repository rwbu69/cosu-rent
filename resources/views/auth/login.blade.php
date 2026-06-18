<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex flex-col items-center mb-8 text-center">
        <img src="{{ asset('icon.svg') }}" alt="Logo" class="w-16 h-16 mb-4">
        <h2 class="text-2xl font-black tracking-wide text-gray-900 uppercase">Login</h2>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="relative">
            <input id="username"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3.5 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="text" name="username" :value="old('username')" placeholder="username" required autofocus
                autocomplete="username" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('username')" class="pl-4 mt-2" />
        </div>

        <!-- Password -->
        <div class="relative mt-5">
            <input id="password"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3.5 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="password" name="password" placeholder="password" required autocomplete="current-password" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('password')" class="pl-4 mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block pl-3 mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="border-gray-300 rounded-full shadow-sm text-primary focus:ring-primary" name="remember">
                <span class="text-xs font-medium text-gray-400 ms-2">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-8 text-center">
            <button type="submit"
                class="w-3/4 mx-auto bg-primary hover:bg-[#E5A5B0] text-gray-900 rounded-full py-3 px-4 font-bold text-sm tracking-widest uppercase transition-colors shadow-sm">
                {{ __('Log in') }}
            </button>
        </div>

        <div class="mt-4 text-center">
            @if (Route::has('password.request'))
                <a class="text-xs text-gray-400 transition-colors hover:text-gray-600"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <!-- Link to Register if they don't have an account -->
        <div class="mt-6 text-center">
            <a class="text-xs text-[#E5A5B0] hover:text-gray-900 font-semibold transition-colors"
                href="{{ route('register') }}">
                Belum punya akun? Daftar di sini
            </a>
        </div>
    </form>
</x-guest-layout>
