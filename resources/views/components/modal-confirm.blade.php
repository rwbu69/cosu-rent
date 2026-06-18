<div x-data="{ open: false, title: '', message: '', form: null }"
     @open-confirm.window="
        open = true;
        title = $event.detail.title || 'Konfirmasi';
        message = $event.detail.message || 'Apakah Anda yakin?';
        form = $event.detail.form;
     "
     x-show="open"
     class="fixed inset-0 z-[100] flex items-center justify-center"
     style="display: none;"
     x-cloak>
     
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" 
         @click="open = false"></div>

    <!-- Modal Panel -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative bg-white rounded-sm shadow-xl max-w-md w-full mx-4 p-6 overflow-hidden transform transition-all">
         
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <!-- Icon Warning -->
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-bold text-gray-900" x-text="title"></h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-600 font-medium" x-text="message"></p>
                </div>
            </div>
        </div>
        <div class="mt-6 sm:flex sm:flex-row-reverse gap-3">
            <button type="button" @click="if(form) form.submit(); open = false;" class="w-full inline-flex justify-center rounded-sm border border-transparent shadow-sm px-6 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                Lanjutkan
            </button>
            <button type="button" @click="open = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-sm border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                Batal
            </button>
        </div>
    </div>
</div>
