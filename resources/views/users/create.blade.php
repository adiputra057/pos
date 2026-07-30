<x-admin-layout title="Tambah User" subtitle="Buat akun pengguna baru">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                     <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                     @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                     <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Role / Hak Akses</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($roles as $role)
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">{{ ucfirst($role->name) }}</span>
                            <span class="block text-xs text-gray-500">{{ $role->description }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('roles') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</x-admin-layout>
