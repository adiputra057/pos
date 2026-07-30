<x-admin-layout>
   <div x-data="userManagement()" class="space-y-6">
    
    <!-- Header with Title and Action Button -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <!-- Title Section -->
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-1.5">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500 font-medium">Kelola pengguna dan hak akses sistem POS Anda.</p>
        </div>
        
        <!-- Action Button - Aligned to Right -->
        <div class="flex-shrink-0 ml-auto">
            <button @click="openCreateModal()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium flex items-center gap-2 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah User
            </button>
        </div>
    </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-xs uppercase font-bold text-gray-900 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Tanggal Gabung</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <button @click='openShowModal(@json($user), @json($user->roles))' class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <button @click='openEditModal(@json($user), @json($user->roles->pluck("id")))' class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                                                @click="$dispatch('confirm', { 
                                                    title: 'Hapus User?', 
                                                    message: 'User \'{{ $user->name }}\' akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.', 
                                                    formId: 'delete-user-{{ $user->id }}' 
                                                })"
                                                title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-8 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-8">
                @if($users->total() > 0)
                <p class="text-xs text-gray-400 font-medium italic order-2 md:order-1">
                    Menampilkan <span class="text-gray-700 font-bold">{{ $users->firstItem() }}</span> sampai <span class="text-gray-700 font-bold">{{ $users->lastItem() }}</span> dari <span class="text-gray-700 font-bold">{{ $users->total() }}</span> pengguna
                </p>
                @endif
                <div class="order-1 md:order-2">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <!-- CREATE MODAL -->
        <div x-show="isCreateModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
             <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeCreateModal()"></div>
            
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isCreateModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                    
                    <form action="{{ route('users.store') }}" method="POST" hx-boost="false" x-data="{ showPassword: false, showPasswordConfirm: false }">
                        @csrf
                        <div class="px-6 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-xl font-bold text-gray-900">Tambah User Baru</h3>
                            <button type="button" @click="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-all" placeholder="Contoh: Budi Santoso">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-all" placeholder="budi@example.com">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                                    <div class="relative">
                                        <input :type="showPassword ? 'text' : 'password'" name="password" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 pr-10 transition-all">
                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi</label>
                                    <div class="relative">
                                        <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 pr-10 transition-all">
                                        <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg x-show="showPasswordConfirm" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Role / Hak Akses</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($roles as $role)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors relative group">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <span class="ml-3 text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">{{ ucfirst($role->name) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-indigo-600 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">Simpan User</button>
                            <button type="button" @click="closeCreateModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 px-5 py-2.5 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeEditModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isEditModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                    
                    <form id="editUserForm" @submit.prevent="submitEdit" hx-boost="false" x-data="{ showEditPassword: false, showEditPasswordConfirm: false }">
                        @csrf
                        @method('PUT')
                        <div class="px-6 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-xl font-bold text-gray-900">Edit User</h3>
                            <button type="button" @click="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" x-model="editForm.name" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" x-model="editForm.email" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                            </div>
                            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                                <p class="text-xs text-amber-800 font-semibold mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Ubah Password (Kosongkan jika tidak ingin mengubah)
                                </p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="relative">
                                        <input :type="showEditPassword ? 'text' : 'password'" name="password" autocomplete="new-password" placeholder="Password Baru" class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm pr-9">
                                        <button type="button" @click="showEditPassword = !showEditPassword" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showEditPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg x-show="showEditPassword" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <input :type="showEditPasswordConfirm ? 'text' : 'password'" name="password_confirmation" autocomplete="new-password" placeholder="Konfirmasi" class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm pr-9">
                                        <button type="button" @click="showEditPasswordConfirm = !showEditPasswordConfirm" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showEditPasswordConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <svg x-show="showEditPasswordConfirm" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Role / Hak Akses</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($roles as $role)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors relative group">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" :checked="editForm.roles.includes({{ $role->id }})" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                        <span class="ml-3 text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">{{ ucfirst($role->name) }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-transparent px-5 py-2.5 bg-indigo-600 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">Simpan Perubahan</button>
                            <button type="button" @click="closeEditModal()" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 px-5 py-2.5 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SHOW MODAL -->
        <div x-show="isShowModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeShowModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="isShowModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 scale-95" 
                     x-transition:enter-end="opacity-100 scale-100" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    
                    <div class="relative bg-indigo-600 h-32">
                        <button type="button" @click="closeShowModal()" class="absolute top-4 right-4 text-white/80 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2">
                             <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg">
                                <div class="w-full h-full rounded-full bg-indigo-50 flex items-center justify-center text-2xl font-bold text-indigo-600" x-text="showData.name ? showData.name.charAt(0) : ''"></div>
                             </div>
                        </div>
                    </div>
                    
                    <div class="pt-12 pb-8 px-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900" x-text="showData.name"></h3>
                        <p class="text-sm text-gray-500 mb-6" x-text="showData.email"></p>
                        
                        <div class="flex flex-wrap gap-2 justify-center mb-6">
                            <template x-for="role in showData.roles" :key="role.id">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100 shadow-sm" x-text="role.name"></span>
                            </template>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 rounded-xl bg-gray-50">
                                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Bergabung</p>
                                    <p class="text-sm font-semibold text-gray-900" x-text="new Date(showData.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></p>
                                </div>
                                <div class="text-center p-3 rounded-xl bg-gray-50">
                                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">ID User</p>
                                    <p class="text-sm font-semibold text-gray-900" x-text="'#' + showData.id"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
    </div>

  <!-- Di dalam x-admin-layout atau di file terpisah SEBELUM penutup </body> -->
</x-admin-layout>
