<x-layout.admin>
    <div x-data="kioskDashboard()" class="space-y-6">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            {{ __('Kiosk RFID Mandiri') }}
        </h2>

        <div class="bg-white overflow-hidden shadow-sm rounded-sm border border-gray-200 mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-6">Pilih Tindakan Terlebih Dahulu, Lalu Tap RFID</h3>
                
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mb-6">
                    <button @click="setAction('checkout')" 
                            :class="action === 'checkout' ? 'bg-light-primary text-gray-900 border-primary ring-2 ring-primary ring-opacity-30' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'" 
                            class="flex-1 font-bold py-4 rounded-sm border shadow-sm transition-all duration-200">
                        Ambil Kostum (Check-Out)
                    </button>
                    <button @click="setAction('return')" 
                            :class="action === 'return' ? 'bg-light-primary text-gray-900 border-primary ring-2 ring-primary ring-opacity-30' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'" 
                            class="flex-1 font-bold py-4 rounded-sm border shadow-sm transition-all duration-200">
                        Kembalikan Kostum (Return)
                    </button>
                </div>

                <form @submit.prevent="submitScan">
                    <input type="text" x-model="rfid" x-ref="rfidInput" 
                        class="w-full border border-gray-300 rounded-sm bg-gray-50 p-4 focus:ring-0 focus:border-primary focus:outline-none mb-4 shadow-inner text-gray-800 font-medium" 
                        placeholder="Tempelkan Kartu RFID..." :disabled="!action"
                        :class="!action ? 'opacity-50 cursor-not-allowed' : ''">
                </form>

                <div x-show="message" class="p-4 rounded-sm border font-semibold mb-4 text-sm transition-all" 
                     :class="isError ? 'border-red-200 bg-red-50 text-red-600' : 'border-green-200 bg-green-50 text-green-700'" x-text="message" style="display: none;"></div>

                <div x-show="user" class="p-6 border border-gray-200 rounded-sm bg-gray-50 mt-4" style="display: none;">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200">
                        <div class="h-12 w-12 bg-primary rounded-full flex items-center justify-center text-gray-900 font-bold text-xl shrink-0">
                            <span x-text="user?.name?.charAt(0)"></span>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-gray-900" x-text="user?.name"></p>
                            <p class="text-sm text-gray-500 font-medium" x-text="user?.email"></p>
                        </div>
                    </div>
                    
                    <div x-show="bookings && bookings.length > 0">
                        <div x-show="bookings.filter(b => b.status !== 'Returned').length > 0" class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wider">Pesanan Aktif</h4>
                            <div class="space-y-2">
                                <template x-for="booking in bookings.filter(b => b.status !== 'Returned')" :key="booking.id">
                                    <div class="p-3 bg-white border border-gray-200 rounded-sm shadow-sm flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">ID Kostum: <span x-text="booking.costume_id"></span></p>
                                            <p class="text-xs text-gray-500"><span x-text="booking.start_date.substring(0, 10)"></span> to <span x-text="booking.end_date.substring(0, 10)"></span></p>
                                        </div>
                                        <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider" x-text="booking.status"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="bookings.filter(b => b.status === 'Returned').length > 0">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wider">Riwayat Pengembalian</h4>
                            <div class="space-y-2">
                                <template x-for="booking in bookings.filter(b => b.status === 'Returned')" :key="'completed-'+booking.id">
                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm shadow-sm flex justify-between items-center opacity-80">
                                        <div>
                                            <p class="font-semibold text-gray-600 text-sm">ID Kostum: <span x-text="booking.costume_id"></span></p>
                                            <p class="text-xs text-gray-500"><span x-text="booking.start_date.substring(0, 10)"></span> to <span x-text="booking.end_date.substring(0, 10)"></span></p>
                                        </div>
                                        <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider" x-text="booking.status"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div x-show="!bookings || bookings.length === 0" class="text-sm text-gray-500 italic mt-2">
                        Tidak ada pesanan aktif untuk pelanggan ini.
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-sm border border-gray-200">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-5 border-b border-gray-200 pb-3">Pendaftaran Kartu RFID</h3>
                <form @submit.prevent="registerRfid" class="space-y-4 max-w-md">
                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1.5">Username Pelanggan</label>
                        <input type="text" x-model="reg.username" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1.5">Kode Kartu RFID</label>
                        <input type="text" x-model="reg.rfid" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary shadow-sm text-sm">
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-6 rounded-sm shadow-sm hover:bg-gray-900 transition-colors">
                            Daftarkan RFID
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function kioskDashboard() {
            return {
                action: null,
                rfid: '',
                message: '',
                isError: false,
                user: null,
                bookings: [],
                reg: { username: '', rfid: '' },

                setAction(act) {
                    this.action = act;
                    this.message = 'Siap untuk ' + (act === 'checkout' ? 'Check-out' : 'Return');
                    this.isError = false;
                    this.user = null;
                    this.bookings = [];
                    this.$nextTick(() => {
                        this.$refs.rfidInput.focus();
                    });
                },

                async submitScan() {
                    if (!this.rfid || !this.action) return;
                    this.message = 'Memproses...';
                    this.isError = false;

                    try {
                        let response = await fetch('/admin/rfid-kiosk/scan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ rfid: this.rfid, action: this.action })
                        });
                        
                        let data = await response.json();
                        
                        if (!response.ok) {
                            this.isError = true;
                            this.message = data.message || 'Gagal memproses RFID';
                            this.user = null;
                            this.reg.rfid = this.rfid;
                        } else {
                            this.message = data.message;
                            this.user = data.user;
                            this.bookings = data.bookings || [];
                        }
                    } catch (e) {
                        this.isError = true;
                        this.message = 'Terjadi kesalahan jaringan';
                    }
                    this.rfid = '';
                    if(this.action) {
                         this.$refs.rfidInput.focus();
                    }
                },

                async registerRfid() {
                    if (!this.reg.username || !this.reg.rfid) return;
                    this.message = 'Mendaftarkan...';
                    this.isError = false;

                    try {
                        let response = await fetch('/admin/rfid-kiosk/register', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ username: this.reg.username, rfid: this.reg.rfid })
                        });
                        
                        let data = await response.json();
                        
                        if (!response.ok) {
                            this.isError = true;
                            this.message = data.message || 'Gagal mendaftarkan RFID';
                        } else {
                            this.isError = false;
                            this.message = data.message;
                            this.reg.username = '';
                            this.reg.rfid = '';
                        }
                    } catch (e) {
                        this.isError = true;
                        this.message = 'Terjadi kesalahan jaringan';
                    }
                }
            }
        }
    </script>
</x-layout.admin>
