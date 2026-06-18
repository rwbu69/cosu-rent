    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Nama</label>
            <input id="name" name="name" type="text" class="w-full border-2 border-gray-900 p-3 focus:ring-0 focus:border-primary font-medium" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-600 font-bold" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Email</label>
            <input id="email" name="email" type="email" class="w-full border-2 border-gray-900 p-3 focus:ring-0 focus:border-primary font-medium" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600 font-bold" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 border-2 border-yellow-400 bg-yellow-50 p-4">
                    <p class="text-sm font-bold text-gray-900">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-900 hover:text-primary focus:outline-none ml-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="phone_number" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Nomor Telepon / WhatsApp</label>
            <input id="phone_number" name="phone_number" type="text" class="w-full border-2 border-gray-900 p-3 focus:ring-0 focus:border-primary font-medium" value="{{ old('phone_number', $user->phone_number) }}" autocomplete="tel" />
            <x-input-error class="mt-2 text-red-600 font-bold" :messages="$errors->get('phone_number')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="bank_name" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Nama Bank (Untuk Refund)</label>
                <input id="bank_name" name="bank_name" type="text" class="w-full border-2 border-gray-900 p-3 focus:ring-0 focus:border-primary font-medium" value="{{ old('bank_name', $user->bank_name) }}" placeholder="Misal: BCA, Mandiri, BNI" />
                <x-input-error class="mt-2 text-red-600 font-bold" :messages="$errors->get('bank_name')" />
            </div>

            <div>
                <label for="bank_account_number" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Nomor Rekening Bank</label>
                <input id="bank_account_number" name="bank_account_number" type="text" class="w-full border-2 border-gray-900 p-3 focus:ring-0 focus:border-primary font-medium" value="{{ old('bank_account_number', $user->bank_account_number) }}" placeholder="Nomor rekening Anda" />
                <x-input-error class="mt-2 text-red-600 font-bold" :messages="$errors->get('bank_account_number')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t-2 border-gray-900">
            <button class="bg-gray-900 text-white font-bold px-8 py-3 border-2 border-gray-900 hover:bg-primary hover:text-gray-900 transition-colors uppercase">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-green-600"
                >Berhasil Disimpan.</p>
            @endif
        </div>
    </form>
