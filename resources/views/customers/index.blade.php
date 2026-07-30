<x-admin-layout>
    <div x-data="customerManagement" class="space-y-6">
        
        <!-- Header with Title and Action Button -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <!-- Title Section -->
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Manajemen Pelanggan</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola database pelanggan dan riwayat belanja toko Anda.</p>
            </div>
            
            <!-- Action Button - Aligned to Right -->
            <div class="flex-shrink-0 ml-auto">
                <button @click="openAddModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 font-medium shadow-sm whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pelanggan
                </button>
            </div>
        </div>

        <!-- Search Bar Below Title -->
        <div class="relative w-full md:w-64">
            <form method="GET" action="{{ route('customers.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-lg max-w-lg w-full shadow-xl transform transition-all">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900" x-text="editMode ? 'Ubah Data Pelanggan' : 'Tambah Pelanggan Baru'"></h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form id="customerForm" @submit.prevent="submitForm" class="p-6" hx-boost="false">
                        @csrf
                        <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">

                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                                <input type="text" name="name" x-model="form.name" required 
                                       class="w-full rounded-lg border-gray-300 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                       placeholder="Contoh: Budi Santoso">
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP (Opsional)</label>
                                <input type="text" name="phone" x-model="form.phone" 
                                       class="w-full rounded-lg border-gray-300 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                       placeholder="Contoh: 08123456789">
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat (Opsional)</label>
                                <textarea name="address" x-model="form.address" rows="3" 
                                          class="w-full rounded-lg border-gray-300 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                          placeholder="Masukkan alamat lengkap..."></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-sm transition-colors">
                                <span x-text="editMode ? 'Perbarui Data' : 'Simpan Pelanggan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-900 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider">No. HP</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider">Alamat</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-900 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-3">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900">{{ $customer->phone ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm text-gray-500 truncate max-w-xs mx-auto" title="{{ $customer->address }}">
                                {{ $customer->address ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center items-center gap-2">
                                <button @click='openEditModal(@json($customer))' class="p-2 text-gray-400 hover:text-indigo-600 transition-all hover:bg-indigo-50 rounded-lg" title="Ubah">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form id="delete-customer-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="p-2 text-gray-400 hover:text-red-600 transition-all hover:bg-red-50 rounded-lg"
                                            @click="$dispatch('confirm', { 
                                                title: 'Hapus Pelanggan?', 
                                                message: 'Data pelanggan \'{{ $customer->name }}\' akan dihapus. Tindakan ini tidak dapat dibatalkan.', 
                                                formId: 'delete-customer-{{ $customer->id }}' 
                                            })"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data pelanggan. Klik "Tambah Pelanggan" untuk membuat baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Table Footer: Pagination & Entry Info -->
            <div class="px-6 py-8 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-8">
                @if($customers->total() > 0)
                <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                    Menampilkan <span class="text-gray-700 font-bold">{{ $customers->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $customers->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $customers->total() }}</span> pelanggan
                </p>
                @endif
                <div class="order-1 md:order-2">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
