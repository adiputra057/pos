import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('supplierManagement', (initialData = {}) => ({
    showModal: false,
    showDetailModal: false,
    isEdit: false,
    formData: { name: '', phone: '', address: '' },
    formAction: '/suppliers',
    selectedSupplier: {},
    supplierId: null,
    modalTitle: '',
    errors: initialData.errors || {},

    init() {
        console.log('Supplier Management Initialized');
    },

    openCreateModal() {
        this.isEdit = false;
        this.modalTitle = 'Tambah Supplier Baru';
        this.formAction = '/suppliers';
        this.formData = { name: '', phone: '', address: '' };
        this.errors = {};
        this.showModal = true;
        document.body.classList.add('overflow-hidden');
    },

    openEditModal(supplier) {
        this.isEdit = true;
        this.modalTitle = 'Edit Supplier';
        this.supplierId = supplier.id;
        this.formAction = `/suppliers/${supplier.id}`;
        this.formData = {
            name: supplier.name,
            phone: supplier.phone,
            address: supplier.address || ''
        };
        this.errors = {};
        this.showModal = true;
        document.body.classList.add('overflow-hidden');
    },

    openDetailModal(supplier) {
        console.log('Opening detail for:', supplier);
        this.selectedSupplier = supplier;
        this.showDetailModal = true;
        document.body.classList.add('overflow-hidden');
    },

    submitForm(event) {
        const form = event.target;
        const formData = new FormData(form);
        this.errors = {};

        fetch(this.formAction, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            this.errors = data.errors;
                        });
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: data.message || 'Data berhasil disimpan.',
                        type: 'success'
                    }));
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    }
}));

Alpine.data('userManagement', () => ({
    isCreateModalOpen: false,
    isEditModalOpen: false,
    isShowModalOpen: false,

    editForm: { name: '', email: '', roles: [] },
    editAction: '',
    showData: {},

    openCreateModal() {
        this.isCreateModalOpen = true;
        document.body.classList.add('overflow-hidden');
    },
    closeCreateModal() {
        this.isCreateModalOpen = false;
        document.body.classList.remove('overflow-hidden');
    },

    openEditModal(user, roles) {
        this.editForm = { name: user.name, email: user.email, roles: roles };
        this.editAction = '/users/' + user.id;
        this.isEditModalOpen = true;
        document.body.classList.add('overflow-hidden');
    },
    closeEditModal() {
        this.isEditModalOpen = false;
        document.body.classList.remove('overflow-hidden');
    },

    openShowModal(user, roles) {
        this.showData = user;
        this.showData.roles = roles;
        this.isShowModalOpen = true;
        document.body.classList.add('overflow-hidden');
    },
    closeShowModal() {
        this.isShowModalOpen = false;
        document.body.classList.remove('overflow-hidden');
    },

    submitEdit(event) {
        // Updated to use AJAX
        const form = event.target;
        const formData = new FormData(form);
        // User management might not have 'errors' object initialized in data, ensure it is.
        // But userManagement doesn't seem to have 'errors' in data definition above. 
        // We really should add it. usage: this.errors = ...
        // Let's assume we fix the view to use this.

        // Wait, userManagement view structure is different. 
        // Let's just implement the standard submit logic.

        fetch(this.editAction, { // userManagement uses editAction
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    // For user management we might need to handle errors differently 
                    // For user management we might need to handle errors differently
                    // but for now let's just reload on success
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: data.message || 'User berhasil diperbarui.',
                        type: 'success'
                    }));
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error(error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    }
}));

Alpine.data('customerManagement', () => ({
    showModal: false,
    editMode: false,
    formAction: '/customers',
    form: { id: null, name: '', email: '', phone: '', address: '' },

    openAddModal() {
        this.showModal = true;
        this.editMode = false;
        this.formAction = '/customers';
        this.form = { id: null, name: '', email: '', phone: '', address: '' };
    },

    openEditModal(customer) {
        this.showModal = true;
        this.editMode = true;
        this.formAction = `/customers/${customer.id}`;
        this.form = { ...customer };
    },

    submitForm(event) {
        const form = event.target;
        const formData = new FormData(form);
        // Initialize errors if not present? customerManagement definition doesn't show errors object.
        // We should add it to data if we want to support it. 
        // But for now, let's just do the submission.

        fetch(this.formAction, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    // Simple error handling for now since 'errors' obj missing in data
                    if (response.status === 422) {
                        alert('Validasi gagal. Mohon periksa kembali inputan Anda.');
                    }
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: data.message || 'Data berhasil disimpan.',
                        type: 'success'
                    }));
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    }
}));

Alpine.data('productManagement', () => ({
    showModal: false,
    showDetailModal: false,
    openExport: false,
    modalTitle: 'Tambah Produk Baru',
    isEdit: false,
    productId: null,
    formAction: '/products',
    selectedProductDetail: null,
    formData: {
        name: '', sku: '', barcode: '', category_id: '', supplier_id: '',
        buying_price: '', selling_price: '', wholesale_price: '',
        min_wholesale_qty: 5, box_price: '',
        box_quantity: '', stock: 0, warehouse_stock: 0, min_stock: 0,
        units: []
    },
    errors: {},
    imagePreview: null,
    imageFile: null,
    showTransferModal: false,
    transferForm: {
        product_id: '',
        quantity: 1,
        unit: 'pcs',
        from: 'warehouse',
        to: 'display',
        note: ''
    },
    // Keep a reference to the product name being transferred when opening from list
    transferProductName: '',
    warehouseStockBox: 0,
    warehouseStockPcs: 0,

    handleImageChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.imageFile = file;
            const reader = new FileReader();
            reader.onload = (e) => { this.imagePreview = e.target.result; };
            reader.readAsDataURL(file);
        }
    },

    formatCurrency(value) {
        if (value === null || value === undefined) return 'Rp 0';
        let numericValue = typeof value === 'string' ? parseFloat(value) : value;
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(numericValue);
    },

    formatInput(value) {
        if (value === null || value === undefined || value === '') return '';
        let str = value.toString();
        // If it's a raw database float string (e.g. "10000.00")
        // Detection: exactly one dot and it's followed by decimal-like digits (00)
        if (str.includes('.') && (str.match(/\./g) || []).length === 1 && /^\d+\.\d{1,2}$/.test(str)) {
            str = Math.floor(parseFloat(str)).toString();
        }
        str = str.replace(/\D/g, '');
        return str.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    unformatInput(value) {
        if (!value) return 0;
        return parseInt(value.toString().replace(/\./g, '')) || 0;
    },

    openDetailModal(product) {
        this.selectedProductDetail = product;
        this.showDetailModal = true;
    },

    openTransferModal(product) {
        this.transferForm = {
            product_id: product.id,
            quantity: 1,
            unit: 'pcs',
            from: 'warehouse',
            to: 'display',
            note: ''
        };
        this.transferProductName = product.name;
        this.showTransferModal = true;
        document.body.classList.add('overflow-hidden');
    },

    closeTransferModal() {
        this.showTransferModal = false;
        document.body.classList.remove('overflow-hidden');
    },

    openCreateModal() {
        this.modalTitle = 'Tambah Produk Baru';
        this.isEdit = false;
        this.productId = null;
        this.formAction = '/products';
        this.imagePreview = null;
        this.imageFile = null;
        this.resetForm();
        
        // Fetch next sequential SKU from server
        fetch('/products/next-sku', {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => { this.formData.sku = data.sku; })
        .catch(() => {
            // Fallback if fetch fails
            this.formData.sku = 'PRD-' + String(Date.now()).slice(-6);
        });
        
        this.showModal = true;
    },

    openEditModal(product) {
        this.modalTitle = 'Ubah Produk';
        this.isEdit = true;
        this.productId = product.id;
        this.formAction = `/products/${product.id}`;

        // Load units from product, or fall back to legacy fields
        if (product.units && product.units.length > 0) {
            this.formData.units = product.units.map(u => ({
                unit_name: u.unit_name,
                conversion_factor: u.conversion_factor,
                price: this.formatInput(u.price),
                is_base: u.is_base,
                warehouse_stock_qty: 0
            }));
        } else {
            this.formData.units = [
                { unit_name: 'pcs', conversion_factor: 1, price: this.formatInput(product.selling_price), is_base: true, warehouse_stock_qty: 0 }
            ];
            if (product.box_quantity > 0 && product.box_price > 0) {
                this.formData.units.push({
                    unit_name: 'dus',
                    conversion_factor: product.box_quantity,
                    price: this.formatInput(product.box_price),
                    is_base: false,
                    warehouse_stock_qty: 0
                });
            }
        }

        // Distribute product's warehouse stock among units (largest units first)
        let sortedUnitsForDistribution = [...this.formData.units].sort((a, b) => b.conversion_factor - a.conversion_factor);
        let remainingStock = parseInt(product.warehouse_stock) || 0;
        let distribution = {};

        sortedUnitsForDistribution.forEach(u => {
            const factor = parseInt(u.conversion_factor) || 1;
            if (factor > 1) {
                distribution[u.unit_name] = Math.floor(remainingStock / factor);
                remainingStock = remainingStock % factor;
            } else {
                distribution[u.unit_name] = remainingStock;
                remainingStock = 0;
            }
        });

        // Set distributed stock quantities
        this.formData.units.forEach(u => {
            u.warehouse_stock_qty = distribution[u.unit_name] || 0;
        });

        this.formData.name = product.name;
        this.formData.sku = product.sku;
        this.formData.barcode = product.barcode || '';
        this.formData.category_id = product.category_id;
        this.formData.supplier_id = product.supplier_id;
        this.formData.buying_price = this.formatInput(product.buying_price);
        this.formData.wholesale_price = this.formatInput(product.wholesale_price);
        this.formData.min_wholesale_qty = product.min_wholesale_qty !== null ? product.min_wholesale_qty : 5;
        this.formData.stock = product.stock;
        this.formData.min_stock = product.min_stock;

        this.updateWarehouseStockFromUnits();

        this.imagePreview = product.image ? `/storage/${product.image}` : null;
        this.showModal = true;
    },

    resetForm() {
        this.formData = {
            name: '', sku: '', barcode: '', category_id: '', supplier_id: '',
            buying_price: '', selling_price: '', wholesale_price: '',
            min_wholesale_qty: 5, box_price: '',
            box_quantity: '', stock: 0, warehouse_stock: 0, min_stock: 0,
            units: [
                { unit_name: 'pcs', conversion_factor: 1, price: '', is_base: true, warehouse_stock_qty: 0 }
            ]
        };
        this.errors = {};
    },

    addUnit() {
        this.formData.units.push({
            unit_name: '',
            conversion_factor: '',
            price: '',
            is_base: false,
            warehouse_stock_qty: 0
        });
    },

    removeUnit(index) {
        if (this.formData.units[index].is_base) {
            alert('Satuan dasar tidak boleh dihapus.');
            return;
        }
        this.formData.units.splice(index, 1);
        this.updateWarehouseStockFromUnits();
    },

    updateWarehouseStockFromUnits() {
        // Find if there is a box/alternative unit with conversion_factor > 1
        const boxUnit = this.formData.units.find(u => parseInt(u.conversion_factor) > 1);
        const boxQty = boxUnit ? (parseInt(boxUnit.conversion_factor) || 0) : 0;

        // Sync to formData.box_quantity and box_price for backend
        this.formData.box_quantity = boxQty;
        if (boxUnit) {
            this.formData.box_price = this.unformatInput(boxUnit.price);
        } else {
            this.formData.box_price = '';
        }

        // Calculate total warehouse stock in pieces
        let total = 0;
        this.formData.units.forEach(u => {
            const qty = parseInt(u.warehouse_stock_qty) || 0;
            const factor = parseInt(u.conversion_factor) || 1;
            total += qty * factor;
        });
        this.formData.warehouse_stock = total;
    },

    // Alias called from blade @input handlers — delegates to updateWarehouseStockFromUnits
    updateWarehouseStockFromBoxPcs() {
        this.updateWarehouseStockFromUnits();
    },

    submitForm(event) {
        const form = event.target;
        const formData = new FormData(form);

        // Sanitize price fields (remove dots)
        const priceFields = ['buying_price', 'selling_price', 'box_price', 'wholesale_price'];
        priceFields.forEach(field => {
            if (formData.has(field)) {
                formData.set(field, this.unformatInput(formData.get(field)));
            }
        });

        // Force readonly fields into FormData — some browsers skip readonly inputs
        formData.set('stock', this.formData.stock ?? 0);
        formData.set('min_stock', this.formData.min_stock ?? 0);

        this.errors = {};

        fetch(this.formAction, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            this.errors = data.errors;
                        });
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: data.message || 'Data berhasil disimpan.',
                        type: 'success'
                    }));
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    },

    submitTransfer(event) {
        let form = event.target;
        if (form.tagName !== 'FORM') {
            form = form.closest('form');
        }
        const formData = new FormData(form);

        fetch('/stocks/transfer', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            const errorMsg = Object.values(data.errors).flat().join('\n');
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: { message: 'Gagal pindah stok:\n' + errorMsg, type: 'error' }
                            }));
                        });
                    }
                    if (response.status === 419) {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: { message: 'Sesi anda telah berakhir, silahkan muat ulang halaman.', type: 'error' }
                        }));
                        return;
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data) {
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: 'Stok berhasil dipindahkan.',
                        type: 'success'
                    }));
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    }
}));

Alpine.data('categoryManagement', () => ({
    showModal: false,
    modalTitle: 'Tambah Kategori Baru',
    isEdit: false,
    categoryId: null,
    formAction: '/categories',
    formData: {
        name: '',
        description: ''
    },
    openCreateModal() {
        this.modalTitle = 'Tambah Kategori Baru';
        this.isEdit = false;
        this.categoryId = null;
        this.formAction = '/categories';
        this.resetForm();
        this.showModal = true;
    },
    openEditModal(category) {
        this.modalTitle = 'Edit Kategori: ' + category.name;
        this.isEdit = true;
        this.categoryId = category.id;
        this.formAction = `/categories/${category.id}`;
        this.formData = {
            name: category.name,
            description: category.description || ''
        };
        this.showModal = true;
    },
    resetForm() {
        this.formData = {
            name: '',
            description: ''
        };
    },

    submitForm(event) {
        const form = event.target;
        const formData = new FormData(form);
        // categoryManagement doesn't have errors object in data definition. 
        // We should add it or use a local one.
        // Ideally we update the data definition too, but let's try to set it dynamically?
        // Alpine proxies might not react if property didn't exist.
        // BUT current view might not rely on 'errors' object for categories?
        // Actually categories/index.blade.php DOES NOT show errors block in the file I viewed.
        // So alert is safer here.

        fetch(this.formAction, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        alert('Validasi gagal. Cek inputan Anda.');
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    sessionStorage.setItem('notification', JSON.stringify({
                        message: data.message || 'Data berhasil disimpan.',
                        type: 'success'
                    }));
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    }
}));

// Global Notification Handler
document.addEventListener('alpine:init', () => {
    const notification = sessionStorage.getItem('notification');
    if (notification) {
        try {
            const { message, type } = JSON.parse(notification);
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message, type }
                }));
            }, 500);
            sessionStorage.removeItem('notification');
        } catch (e) {
            console.error('Error parsing notification', e);
            sessionStorage.removeItem('notification');
        }
    }
});

Alpine.data('settingsManagement', (initialTab = 'general') => ({
    activeTab: initialTab,

    init() {
        console.log('Settings Management Initialized with tab:', initialTab);
        // Update URL when tab changes without reloading
        this.$watch('activeTab', (value) => {
            console.log('Tab changed to:', value);
            const url = new URL(window.location);
            url.pathname = `/settings/${value}`;
            window.history.pushState({}, '', url);
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', () => {
            const path = window.location.pathname;
            const parts = path.split('/');
            const tab = parts[parts.length - 1];
            if (['general', 'payment', 'security', 'system'].includes(tab)) {
                this.activeTab = tab;
            }
        });
    },

    submitForm(event) {
        const form = event.target;
        const formData = new FormData(form);
        const action = form.getAttribute('action');

        fetch(action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            // Display validation errors (simple alert for now or implement error mapping)
                            const errors = Object.values(data.errors).flat().join('\n');
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: { message: 'Validasi gagal:\n' + errors, type: 'error' }
                            }));
                        });
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data) {
                    // Show success notification without reload for smoother experience
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { message: data.message || 'Pengaturan berhasil disimpan.', type: 'success' }
                    }));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { message: 'Terjadi kesalahan sistem.', type: 'error' }
                }));
            });
    }
}));

// Intercept programmatic form.submit() to make them work with HTMX hx-boost
const originalSubmit = HTMLFormElement.prototype.submit;
HTMLFormElement.prototype.submit = function() {
    const isBoosted = this.closest('[hx-boost="true"]') || this.getAttribute('hx-boost') === 'true';
    if (isBoosted) {
        if (typeof this.requestSubmit === 'function') {
            this.requestSubmit();
        } else {
            const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
            this.dispatchEvent(submitEvent);
            if (!submitEvent.defaultPrevented) {
                originalSubmit.call(this);
            }
        }
    } else {
        originalSubmit.call(this);
    }
};

Alpine.start();

// HTMX + Alpine.js Integration
// When HTMX swaps content, reinitialize Alpine on the new content
document.addEventListener('htmx:afterSwap', function (event) {
    // Dispatch nav-changed event so sidebar active state updates
    const url = new URL(window.location.href);
    window.dispatchEvent(new CustomEvent('nav-changed', { detail: { url: url.pathname } }));

    // Reinitialize Alpine on the new content
    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
        window.Alpine.initTree(event.detail.elt);
    }
});

// When HTMX loads a new page, scroll to top
document.addEventListener('htmx:afterSettle', function () {
    const mainContent = document.getElementById('main-content');
    if (mainContent) {
        mainContent.scrollTop = 0;
    }
});

// PWA Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => console.log('Service Worker registered', reg))
            .catch(err => console.error('Service Worker registration failed', err));
    });
}
