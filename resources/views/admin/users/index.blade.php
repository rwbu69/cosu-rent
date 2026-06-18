<x-layout.admin>
    <x-slot name="title">Manajemen Pengguna (Users)</x-slot>

    <div class="space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Nama & Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">RFID UID</th>
                        <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Riwayat Sewa</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ '@' . $user->username }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($user->rfid_uid)
                                    <span class="px-2 py-1 rounded-sm text-xs font-semibold bg-green-100 text-green-800 uppercase tracking-wider">Terdaftar</span>
                                @else
                                    <span class="px-2 py-1 rounded-sm text-xs font-semibold bg-gray-100 text-gray-500 uppercase tracking-wider">Kosong</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-primary">
                                {{ $user->bookings_count }} Transaksi
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="inline-block bg-white text-gray-700 border border-gray-300 rounded-sm px-3 py-1 font-semibold hover:border-primary hover:text-primary transition-colors">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium italic">
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
</x-layout.admin>
