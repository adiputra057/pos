<x-modal name="profile-edit" :show="false" focusable>
    <div class="bg-white dark:bg-[#11141D] transition-colors duration-300 rounded-2xl overflow-hidden"
         x-data="{ activeTab: 'info' }">

        {{-- Header --}}
        <div class="relative px-7 pt-7 pb-5 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#1e66f5]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#1e66f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight leading-none">
                            Edit Profil Saya
                        </h2>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5">Kelola informasi akun dan keamanan Anda</p>
                    </div>
                </div>
                <button x-on:click="$dispatch('close-modal', 'profile-edit')"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex gap-0 mt-5 border-b border-gray-100 dark:border-gray-800 -mb-5">
                <button @click="activeTab = 'info'"
                        :class="activeTab === 'info'
                            ? 'text-[#1e66f5] border-b-2 border-[#1e66f5] bg-transparent font-black'
                            : 'text-gray-400 dark:text-gray-500 border-b-2 border-transparent hover:text-gray-600 dark:hover:text-gray-300 font-bold'"
                        class="flex items-center gap-2 px-5 py-3 text-xs uppercase tracking-widest transition-all duration-200 relative">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Info Profil
                </button>
                <button @click="activeTab = 'password'"
                        :class="activeTab === 'password'
                            ? 'text-[#1e66f5] border-b-2 border-[#1e66f5] bg-transparent font-black'
                            : 'text-gray-400 dark:text-gray-500 border-b-2 border-transparent hover:text-gray-600 dark:hover:text-gray-300 font-bold'"
                        class="flex items-center gap-2 px-5 py-3 text-xs uppercase tracking-widest transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Ganti Password
                </button>
            </div>
        </div>

        {{-- Tab Content --}}
        <div class="px-7 py-6">

            {{-- ── INFO PROFIL TAB ── --}}
            <div x-show="activeTab === 'info'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-data="{ saving: false }">

                <div class="mb-5">
                    <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Profile Information</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Update your account's profile information and email address.</p>
                </div>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form @submit.prevent="async (e) => {
                    saving = true;
                    const formData = new FormData(e.target);
                    try {
                        const response = await fetch('{{ route('profile.update') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (response.ok) {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Profil berhasil diperbarui!', type: 'success' } }));
                            window.dispatchEvent(new CustomEvent('profile-updated', { detail: { name: formData.get('name') } }));
                        } else {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message || 'Gagal memperbarui profil', type: 'error' } }));
                        }
                    } catch (error) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjadi kesalahan', type: 'error' } }));
                    } finally {
                        saving = false;
                    }
                }" class="space-y-4">
                    <input type="hidden" name="_method" value="PATCH">

                    {{-- Name --}}
                    <div>
                        <label for="profile_name" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">Name</label>
                        <input id="profile_name" name="name" type="text"
                               value="{{ old('name', Auth::user()->name) }}"
                               required autofocus autocomplete="name"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] text-sm font-medium text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/30 focus:border-[#1e66f5] transition-all">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="profile_email" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">Email</label>
                        <input id="profile_email" name="email" type="email"
                               value="{{ old('email', Auth::user()->email) }}"
                               required autocomplete="username"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] text-sm font-medium text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/30 focus:border-[#1e66f5] transition-all">
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-1">
                        <button type="submit" :disabled="saving"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#1e66f5] hover:bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-blue-100 dark:shadow-none disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="saving">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="saving ? 'Menyimpan...' : 'Simpan'">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ── GANTI PASSWORD TAB ── --}}
            <div x-show="activeTab === 'password'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-data="{ saving: false, showCurrent: false, showNew: false, showConfirm: false }">

                <div class="mb-5">
                    <h3 class="text-sm font-black text-gray-800 dark:text-gray-200">Update Password</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Ensure your account is using a long, random password to stay secure.</p>
                </div>

                <form @submit.prevent="async (e) => {
                    saving = true;
                    const formData = new FormData(e.target);
                    try {
                        const response = await fetch('{{ route('password.update') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (response.ok) {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Password berhasil diperbarui!', type: 'success' } }));
                            e.target.reset();
                        } else if (response.status === 422) {
                            const errors = data.errors || {};
                            let errorMsg = 'Gagal memperbarui password';
                            if (errors.current_password) errorMsg = errors.current_password[0];
                            else if (errors.password) errorMsg = errors.password[0];
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: errorMsg, type: 'error' } }));
                        } else {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message || 'Gagal memperbarui password', type: 'error' } }));
                        }
                    } catch (error) {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Terjadi kesalahan', type: 'error' } }));
                    } finally {
                        saving = false;
                    }
                }" class="space-y-4">
                    <input type="hidden" name="_method" value="PUT">

                    {{-- Current Password --}}
                    <div>
                        <label for="pwd_current" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">Password Saat Ini</label>
                        <div class="relative">
                            <input id="pwd_current" name="current_password"
                                   :type="showCurrent ? 'text' : 'password'"
                                   autocomplete="current-password"
                                   class="w-full pl-4 pr-11 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] text-sm font-medium text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/30 focus:border-[#1e66f5] transition-all">
                            <button type="button" @click="showCurrent = !showCurrent"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#1e66f5] transition-colors">
                                <svg x-show="!showCurrent" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showCurrent" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-1.667 2.825M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="pwd_new" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input id="pwd_new" name="password"
                                   :type="showNew ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   class="w-full pl-4 pr-11 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] text-sm font-medium text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/30 focus:border-[#1e66f5] transition-all">
                            <button type="button" @click="showNew = !showNew"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#1e66f5] transition-colors">
                                <svg x-show="!showNew" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showNew" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-1.667 2.825M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="pwd_confirm" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1.5">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="pwd_confirm" name="password_confirmation"
                                   :type="showConfirm ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   class="w-full pl-4 pr-11 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0B0E14] text-sm font-medium text-gray-800 dark:text-gray-200 placeholder-gray-300 dark:placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-[#1e66f5]/30 focus:border-[#1e66f5] transition-all">
                            <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#1e66f5] transition-colors">
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-1.667 2.825M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-1">
                        <button type="submit" :disabled="saving"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#1e66f5] hover:bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-blue-100 dark:shadow-none disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="saving">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <span x-text="saving ? 'Menyimpan...' : 'Simpan'">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-modal>
