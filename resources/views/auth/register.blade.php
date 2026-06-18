<x-guest-layout>
    <div class="flex flex-col items-center mb-8 text-center">
        <img src="{{ asset('icon.svg') }}" alt="Logo" class="w-16 h-16 mb-4">
        <h2 class="text-2xl font-medium tracking-wide text-gray-900 uppercase">Daftar</h2>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="relative">
            <input id="name"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="text" name="name" value="{{ old('name') }}" placeholder="name" required autofocus
                autocomplete="name" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('name')" class="pl-4 mt-2" />
        </div>

        <!-- Username -->
        <div class="relative mt-4">
            <input id="username"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="text" name="username" value="{{ old('username') }}" placeholder="username" required
                autocomplete="username" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('username')" class="pl-4 mt-2" />
        </div>

        <!-- Email Address -->
        <div class="relative mt-4">
            <input id="email"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="email" name="email" value="{{ old('email') }}" placeholder="email" required
                autocomplete="username" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                    </path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('email')" class="pl-4 mt-2" />
        </div>

        <!-- Password -->
        <div class="relative mt-4">
            <input id="password"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="password" name="password" placeholder="password" required autocomplete="new-password" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('password')" class="pl-4 mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="relative mt-4">
            <input id="password_confirmation"
                class="block w-full bg-[#f0f0f0] shadow-inner border-0 rounded-full px-5 py-3 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:outline-none transition-shadow"
                type="password" name="password_confirmation" placeholder="confirm password" required
                autocomplete="new-password" />
            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="pl-4 mt-2" />
        </div>

        <div class="mt-8 text-center">
            <button type="submit"
                class="w-3/4 mx-auto bg-light-primary hover:bg-[#E5A5B0] text-gray-900 rounded-full py-3 px-4 font-bold text-sm tracking-widest uppercase transition-colors shadow-sm">
                {{ __('Daftar') }}
            </button>
        </div>

        <div class="mt-4 text-center">
            <a class="text-xs text-gray-400 transition-colors hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Sudah punya akun? Masuk di sini') }}
            </a>
        </div>
    </form>
</x-guest-layout>
