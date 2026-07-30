<x-admin-layout title="Detail Pengguna" subtitle="Informasi lengkap pengguna">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="flex items-center text-gray-500 hover:text-indigo-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Manajemen Users
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center text-3xl font-bold text-indigo-600">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-500">{{ $user->email }}</p>
                            <div class="mt-3 flex gap-2">
                                @foreach($user->roles as $role)
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-sm font-semibold uppercase tracking-wide border border-indigo-100">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Akun</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-gray-500">User ID</dt>
                                <dd class="text-base font-medium text-gray-900">#{{ $user->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Tanggal Bergabung</dt>
                                <dd class="text-base font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</dd>
                            </div>
                             <div>
                                <dt class="text-sm text-gray-500">Terakhir Diupdate</dt>
                                <dd class="text-base font-medium text-gray-900">{{ $user->updated_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <!-- Activity Log Placeholder -->
                    <div>
                         <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Riwayat Aktivitas</h3>
                         <div class="bg-gray-50 rounded-xl p-4 text-center">
                             <p class="text-gray-400 text-sm">Belum ada aktivitas tercatat.</p>
                             <!-- In future, link to AuditLogs -->
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
