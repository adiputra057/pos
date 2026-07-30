<div x-data="{ 
        show: false, 
        title: 'Konfirmasi Hapus',
        message: 'Apakah Anda yakin ingin menghapus data ini?',
        formId: null,
        confirmCallback: null,
        
        open(options) {
            this.title = options.title || 'Konfirmasi Hapus';
            this.message = options.message || 'Apakah Anda yakin ingin menghapus data ini?';
            this.formId = options.formId || null;
            this.confirmCallback = options.callback || null;
            this.show = true;
        },
        
        confirm() {
            if (this.formId) {
                document.getElementById(this.formId).requestSubmit();
            } else if (this.confirmCallback) {
                this.confirmCallback();
            }
            this.show = false;
        }
    }"
    @confirm.window="open($event.detail)"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[10000] overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true">
    
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="show = false"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" 
             aria-hidden="true"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Panel -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
            
            <div class="bg-white p-8">
                <div class="flex items-center justify-center w-20 h-20 bg-red-50 rounded-full mx-auto mb-6 border border-red-100 ring-8 ring-red-50/50">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                
                <div class="text-center">
                    <h3 class="text-2xl font-black text-gray-900 mb-2" x-text="title"></h3>
                    <p class="text-sm text-gray-500 leading-relaxed font-medium px-4" x-text="message"></p>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                <button type="button" 
                        @click="confirm()"
                        class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-black rounded-2xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-lg shadow-red-200 active:scale-95">
                    Hapus Sekarang
                </button>
                <button type="button" 
                        @click="show = false"
                        class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-200 text-sm font-bold rounded-2xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95">
                    Batalkan
                </button>
            </div>
        </div>
    </div>
</div>
