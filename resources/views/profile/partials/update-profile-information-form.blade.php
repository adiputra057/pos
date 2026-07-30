<section x-data="{ saving: false }">
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

        <div>
            <label for="name" class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all px-4 py-2.5" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest block mb-1.5 ml-1">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="w-full bg-white dark:bg-[#11141D] border border-gray-200 dark:border-gray-800 rounded-xl text-sm font-medium text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-[#1e66f5]/20 focus:border-[#1e66f5] transition-all px-4 py-2.5" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs font-medium mt-2 text-gray-600 dark:text-gray-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-xs font-bold text-[#1e66f5] hover:text-blue-700 rounded-md focus:outline-none">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-bold text-green-500">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" :disabled="saving" class="px-6 py-2.5 bg-[#1e66f5] hover:bg-blue-600 text-white text-[11px] font-black rounded-xl uppercase tracking-widest transition-all shadow-lg shadow-blue-100 dark:shadow-none disabled:opacity-50">
                <span x-show="!saving">Simpan</span>
                <span x-show="saving" x-cloak>Menyimpan...</span>
            </button>
        </div>
    </form>
</section>
