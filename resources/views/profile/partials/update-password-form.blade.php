    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full border border-gray-200 rounded-md p-3 focus:ring-0 focus:border-primary font-medium" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600 font-bold" />
        </div>

        <div>
            <label for="update_password_password" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full border border-gray-200 rounded-md p-3 focus:ring-0 focus:border-primary font-medium" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600 font-bold" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full border border-gray-200 rounded-md p-3 focus:ring-0 focus:border-primary font-medium" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600 font-bold" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t-2 border-gray-900">
            <button class="bg-gray-900 text-white font-bold px-8 py-3 border border-gray-200 rounded-md hover:bg-light-primary hover:text-gray-900 transition-colors uppercase">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
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
