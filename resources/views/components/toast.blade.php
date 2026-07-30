<div x-data="{ 
        notifications: [],
        add(n) {
            if (this.notifications.some(existing => existing.message === n.message)) {
                return;
            }
            const id = Date.now();
            this.notifications = [{ id, ...n }];
            setTimeout(() => {
                this.remove(id);
            }, 5000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @notify.window="add($event.detail)"
    class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"
    style="min-width: 300px;">
    
    <template x-for="n in notifications" :key="n.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-[-20px] opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="translate-y-[-20px] opacity-0 scale-95"
             class="pointer-events-auto flex items-center p-4 rounded-2xl shadow-xl border backdrop-blur-md transition-all"
             :class="{
                'bg-emerald-50/90 border-emerald-100 text-emerald-800': n.type === 'success',
                'bg-red-50/90 border-red-100 text-red-800': n.type === 'error',
                'bg-amber-50/90 border-amber-100 text-amber-800': n.type === 'warning',
                'bg-indigo-50/90 border-indigo-100 text-indigo-800': n.type === 'info'
             }">
            
            <div class="flex-shrink-0 mr-3">
                <template x-if="n.type === 'success'">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <template x-if="n.type === 'error'">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <template x-if="n.type === 'warning'">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </template>
                <template x-if="n.type === 'info'">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
            </div>

            <div class="flex-grow mr-4">
                <p class="text-sm font-bold" x-text="n.message"></p>
            </div>

            <button @click="remove(n.id)" class="text-current opacity-50 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
    </template>

    {{-- Handle notifications on initial full page load --}}
    @if(session('success'))
        <div x-init="add({ message: '{{ session('success') }}', type: 'success' })"></div>
    @endif
    @if(session('error'))
        <div x-init="add({ message: '{{ session('error') }}', type: 'error' })"></div>
    @endif
    @if(session('warning'))
        <div x-init="add({ message: '{{ session('warning') }}', type: 'warning' })"></div>
    @endif
    @if(session('info'))
        <div x-init="add({ message: '{{ session('info') }}', type: 'info' })"></div>
    @endif
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div x-init="add({ message: '{{ $error }}', type: 'error' })"></div>
        @endforeach
    @endif
</div>
