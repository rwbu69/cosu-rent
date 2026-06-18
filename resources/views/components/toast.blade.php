<div x-data="{ show: false, message: '', type: 'success' }"
     x-on:toast.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
     class="fixed bottom-4 right-4 z-50 transition-transform duration-300 transform"
     x-show="show"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">

    <!-- Success Toast -->
    <template x-if="type === 'success'">
        <div class="bg-primary text-gray-900 px-6 py-4 border-2 border-gray-900 font-bold shadow-none flex items-center gap-3">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span x-text="message"></span>
        </div>
    </template>

    <!-- Error Toast -->
    <template x-if="type === 'error'">
        <div class="bg-red-200 text-red-900 px-6 py-4 border-2 border-red-900 font-bold shadow-none flex items-center gap-3">
            <svg class="w-6 h-6 text-red-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span x-text="message"></span>
        </div>
    </template>
</div>

<!-- Server-side Session Flash Messages Trigger -->
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: "{{ session('success') }}", type: 'success' }}));
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: "{{ session('error') }}", type: 'error' }}));
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: "{{ $errors->first() }}", type: 'error' }}));
        });
    </script>
@endif
