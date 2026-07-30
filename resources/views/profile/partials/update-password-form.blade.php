<section x-data="{ saving: false }">
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

        <div x-data="{ show: false }" class="relative">
            <label for="update_password_current_password" class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1">{{ __('Current Password') }}</label>
            <div class="relative mt-1">
                <input id="update_password_current_password" name="current_password" :type="show ? 'text' : 'password'" class="w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all px-4 py-2.5 pr-10" autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#1e66f5] focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057-5.064-7-9.542-7 1.274 4.057 5.064 7 9.542 7-4.477 0-8.268-2.943-9.542-7z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div x-data="{ show: false }" class="relative">
            <label for="update_password_password" class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1">{{ __('New Password') }}</label>
            <div class="relative mt-1">
                <input id="update_password_password" name="password" :type="show ? 'text' : 'password'" class="w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all px-4 py-2.5 pr-10" autocomplete="new-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#1e66f5] focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057-5.064-7-9.542-7 1.274 4.057 5.064 7 9.542 7-4.477 0-8.268-2.943-9.542-7z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div x-data="{ show: false }" class="relative">
            <label for="update_password_password_confirmation" class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1">{{ __('Confirm Password') }}</label>
            <div class="relative mt-1">
                <input id="update_password_password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" class="w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all px-4 py-2.5 pr-10" autocomplete="new-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#1e66f5] focus:outline-none transition-colors">
                    <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057-5.064-7-9.542-7 1.274 4.057 5.064 7 9.542 7-4.477 0-8.268-2.943-9.542-7z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" /></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" :disabled="saving" class="px-6 py-2.5 bg-[#1e66f5] hover:bg-blue-600 text-white text-[11px] font-black rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-blue-100 dark:shadow-none disabled:opacity-50">
                <span x-show="!saving">Simpan</span>
                <span x-show="saving" x-cloak>Menyimpan...</span>
            </button>
        </div>
    </form>
</section>
