    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 text-white font-bold px-8 py-3 border border-gray-200 rounded-md hover:bg-red-700 transition-colors uppercase"
    >
        {{ __('Hapus Akun') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 border border-gray-200 rounded-md">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-medium text-gray-900 uppercase">
                Apakah Anda yakin ingin menghapus akun Anda?
            </h2>

            <p class="mt-2 text-sm font-medium text-gray-600">
                Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full border border-gray-200 rounded-md p-3 focus:ring-0 focus:border-red-600 font-medium"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600 font-bold" />
            </div>

            <div class="mt-6 flex justify-end gap-4 border-t-2 border-gray-900 pt-4">
                <button x-on:click="$dispatch('close')" class="bg-white text-gray-900 font-bold px-6 py-3 border border-gray-200 rounded-md hover:bg-gray-100 transition-colors uppercase">
                    Batal
                </button>

                <button class="bg-red-600 text-white font-bold px-6 py-3 border border-gray-200 rounded-md hover:bg-red-700 transition-colors uppercase">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
