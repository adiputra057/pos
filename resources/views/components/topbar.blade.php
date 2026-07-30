<header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm shrink-0 h-16 flex items-center">
    <div class="max-w-full mx-auto px-6 w-full">
        <div class="flex items-center justify-between">
            <!-- Logo Section (Left) -->
            <div class="flex items-center gap-3">
            </div>

            <!-- Right Section: Notification & User -->
            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <button class="relative text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                <!-- User Avatar Dropdown -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 group focus:outline-none">
                        <!-- User Info - Left of Avatar -->
                        <div class="hidden md:flex flex-col items-end gap-0.5">
                            <span class="text-sm font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ strtoupper(Auth::user()->roles->first()?->name ?? 'User') }}</span>
                        </div>
                        
                        <!-- Avatar -->
                        <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center overflow-hidden border-2 border-indigo-100 shadow-sm group-hover:border-indigo-300 transition-all">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Dropdown Arrow -->
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-400 transition-all" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-48 rounded-2xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50 overflow-hidden border border-gray-100">
                        
                        <!-- User Info in Dropdown (Mobile Only) -->
                        <div class="md:hidden px-4 py-3 bg-gray-50">
                            <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ strtoupper(Auth::user()->role) }}</p>
                        </div>

                        <div class="py-1">
                            <button x-on:click="$dispatch('open-modal', 'profile-edit'); open = false" class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all group">
                                <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center mr-2 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                Profil Saya
                            </button>
                        </div>

                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}" hx-boost="false">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-50 transition-all group">
                                    <div class="w-7 h-7 rounded-lg bg-red-50/50 flex items-center justify-center mr-2 group-hover:bg-red-100 transition-all">
                                        <svg class="w-4 h-4 text-red-400 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-profile-edit-modal />
</header>