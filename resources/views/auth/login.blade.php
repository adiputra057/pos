<x-guest-layout>
    <div class="flex flex-col justify-center items-center px-6 py-12 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-[400px] p-8 sm:p-10 border border-gray-100 relative overflow-hidden">
             <!-- Decorative background blur -->
             <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-indigo-50 blur-2xl opacity-60"></div>
             <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-blue-50 blur-2xl opacity-60"></div>
            
            <!-- Logo & Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="inline-flex justify-center items-center w-14 h-14 bg-indigo-50 rounded-xl mb-4 text-indigo-600 shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">MiniMart POS</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your store with ease</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5 relative z-10" x-data="{ showPassword: false }">
                @csrf
                
                <!-- Role Selector (Visual mostly, but could be functional if needed) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 ml-0.5">Access Role</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <select name="role" class="block w-full rounded-xl border-gray-200 pl-10 pr-10 py-2.5 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50/50">
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner (Full Access)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500 ml-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username/Email -->
                <div>
                     <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5 ml-0.5">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="username" required 
                            class="block w-full rounded-xl border-gray-200 pl-10 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 bg-white" 
                            placeholder="Enter your username" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 ml-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5 ml-0.5">
                        <label for="password" class="block text-xs font-semibold text-gray-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password"
                            class="block w-full rounded-xl border-gray-200 pl-10 pr-10 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 bg-white" 
                            placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 ml-0.5">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-indigo-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all items-center gap-2">
                        Sign In
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col items-center gap-2">
                <a href="#" class="flex items-center gap-1 text-xs text-gray-400 hover:text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Help Center
                </a>
                <p class="text-[10px] text-gray-300">© 2024 MINIMART POS V1.0.4</p>
            </div>
        </div>
    </div>
</x-guest-layout>
