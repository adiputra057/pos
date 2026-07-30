<x-admin-layout title="Manajemen Inventaris">
    <div class="flex justify-center items-start min-h-[80vh] pt-10">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden relative">
            <div class="bg-white px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Edit Produk: {{ $product->name }}</h2>
                <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            </div>
            
            <div class="p-8">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('products.form')
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
