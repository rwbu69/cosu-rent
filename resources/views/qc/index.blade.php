<x-layout.admin>
    <div x-data="qcDashboard()" class="space-y-6">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            {{ __('Quality Control Dashboard') }}
        </h2>

        <div class="bg-white overflow-hidden shadow-sm rounded-sm border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-4">Scan QR Code</h3>
                
                <form @submit.prevent="submitScan">
                    <div class="relative">
                        <input type="text" x-model="barcode" x-ref="barcodeInput" autofocus 
                            class="w-full border-gray-300 rounded-sm p-4 focus:ring-0 focus:border-primary shadow-sm text-lg pr-12" 
                            placeholder="Scan or type QR Code and press Enter...">
                    </div>
                </form>

                <div x-show="message" class="p-4 rounded-sm border font-semibold mb-4 text-sm" 
                     :class="isError ? 'border-red-200 bg-red-50 text-red-600' : 'border-green-200 bg-green-50 text-green-700'" x-text="message" style="display: none;"></div>

                <div x-show="component" class="p-5 border border-gray-200 rounded-sm bg-gray-50" style="display: none;">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <p><span class="text-gray-500 block mb-1">Name</span> <strong class="text-gray-900" x-text="component?.name"></strong></p>
                        <p><span class="text-gray-500 block mb-1">QR Code</span> <strong class="text-gray-900 font-mono bg-white px-2 py-0.5 rounded border border-gray-200" x-text="component?.barcode_string"></strong></p>
                        <p class="col-span-2"><span class="text-gray-500 block mb-1">Status</span> <strong x-text="component?.status" class="inline-block bg-primary bg-opacity-20 text-gray-900 px-3 py-1 rounded-sm uppercase tracking-wide text-xs"></strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-sm border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-5 border-b border-gray-200 pb-3">Register New Component</h3>
                <form @submit.prevent="registerComponent" class="space-y-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1.5">Data QR Code</label>
                        <input type="text" x-model="reg.barcode" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1.5">Component Name</label>
                        <input type="text" x-model="reg.name" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1.5">Costume ID (Demo)</label>
                        <input type="number" x-model="reg.costume_id" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary shadow-sm text-sm">
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-primary text-gray-900 font-bold py-2 px-6 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors">
                            Register Component
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function qcDashboard() {
            return {
                barcode: '',
                message: '',
                isError: false,
                component: null,
                reg: { barcode: '', name: '', costume_id: 1 },

                init() {
                    // Keep focus on input
                    document.addEventListener('click', () => {
                        this.$refs.barcodeInput.focus();
                    });
                },

                async submitScan() {
                    if (!this.barcode) return;
                    this.message = 'Scanning...';
                    this.isError = false;

                    try {
                        let response = await fetch('/admin/qc-barcode/scan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ barcode: this.barcode })
                        });
                        
                        let data = await response.json();
                        
                        if (!response.ok) {
                            this.isError = true;
                            this.message = data.message || 'Error scanning QR Code';
                            this.component = null;
                            // Pre-fill registration
                            this.reg.barcode = this.barcode;
                        } else {
                            this.message = data.message;
                            this.component = data.component;
                        }
                    } catch (e) {
                        this.isError = true;
                        this.message = 'Network error';
                    }
                    this.barcode = '';
                    this.$refs.barcodeInput.focus();
                },

                async registerComponent() {
                    // Note: Registration endpoint doesn't exist in given routes, but we keep UI for future
                    this.message = 'Registering... (Demo only, no endpoint in Phase 4)';
                    this.isError = true;
                }
            }
        }
    </script>
</x-layout.admin>
