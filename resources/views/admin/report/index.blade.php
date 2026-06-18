<x-layout.admin>
    <x-slot name="title">Laporan Keuangan</x-slot>

    <div class="space-y-6">

        <!-- Filter & Action Bar -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('admin.report.index') }}" method="GET" class="flex items-center gap-4">
                <label class="font-semibold text-gray-700">Pilih Bulan:</label>
                <input type="month" name="month" value="{{ $month }}" class="border-gray-300 rounded-sm p-2 font-medium focus:ring-0 focus:border-primary">
                <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-sm font-semibold hover:bg-slate-800 transition-colors">Tampilkan</button>
            </form>

            <a href="{{ route('admin.report.export', ['month' => $month]) }}" class="bg-primary text-gray-900 px-6 py-2 rounded-sm font-bold shadow-sm hover:bg-[#E5A5B0] transition-colors">
                Unduh CSV
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 border-l-4 border-l-primary">
                <p class="text-sm font-semibold text-gray-500 uppercase mb-2">Total Pendapatan Bersih</p>
                <p class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-xs font-medium text-gray-400 mt-2">Sewa + Denda Keterlambatan</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 border-l-4 border-l-red-500">
                <p class="text-sm font-semibold text-gray-500 uppercase mb-2">Pendapatan Denda</p>
                <p class="text-3xl font-extrabold text-red-600">Rp {{ number_format($totalPenalty, 0, ',', '.') }}</p>
                <p class="text-xs font-medium text-gray-400 mt-2">Dari Keterlambatan/Kerusakan</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 border-l-4 border-l-slate-800">
                <p class="text-sm font-semibold text-gray-500 uppercase mb-2">Pesanan Selesai</p>
                <p class="text-3xl font-extrabold text-slate-800">{{ $totalRentals }}</p>
                <p class="text-xs font-medium text-gray-400 mt-2">Booking Berstatus Dikembalikan</p>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">ID Booking</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Kostum & Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Sewa Murni</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Denda</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Total Bersih</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                #{{ $booking->id }}<br>
                                <span class="text-xs text-gray-500 font-normal">{{ $booking->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $booking->costume->name }}</div>
                                <div class="text-xs text-gray-600">by {{ $booking->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($booking->sewa_murni, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                Rp {{ number_format($booking->penalty_fee, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-primary">
                                Rp {{ number_format($booking->revenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium italic">
                                Tidak ada data pendapatan untuk bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layout.admin>
