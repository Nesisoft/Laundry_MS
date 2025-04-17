document.addEventListener('DOMContentLoaded', function() {
    // Mock Data
    const mockCustomers = [
        { id: 1, name: "John Doe", phone: "08012345678", email: "john@example.com" },
        { id: 2, name: "Jane Smith", phone: "08087654321", email: "jane@example.com" },
        { id: 3, name: "Robert Johnson", phone: "08023456789", email: "robert@example.com" },
    ];

    const mockServices = [
        { id: 1, name: "Wash & Fold", price: 1500, category: "laundry" },
        { id: 2, name: "Dry Cleaning", price: 2500, category: "laundry" },
        { id: 3, name: "Ironing Only", price: 800, category: "laundry" },
        { id: 4, name: "Stain Removal", price: 1200, category: "cleaning" },
        { id: 5, name: "Bedding (Full Set)", price: 3500, category: "laundry" },
        { id: 6, name: "Curtains (Per Panel)", price: 2000, category: "cleaning" },
        { id: 7, name: "Suit (2-Piece)", price: 4000, category: "dry-cleaning" },
        { id: 8, name: "Dress", price: 3000, category: "dry-cleaning" },
        { id: 9, name: "Shirt", price: 1000, category: "dry-cleaning" },
        { id: 10, name: "Pants/Trousers", price: 1200, category: "dry-cleaning" },
        { id: 11, name: "Comforter/Duvet", price: 5000, category: "bedding" },
        { id: 12, name: "Carpet Cleaning (Small)", price: 8000, category: "cleaning" },
    ];

    const mockDiscounts = [
        { id: 1, name: "New Customer", type: "percentage", value: 10 },
        { id: 2, name: "Loyalty Discount", type: "percentage", value: 15 },
        { id: 3, name: "Holiday Special", type: "amount", value: 2000 },
        { id: 4, name: "Bulk Order", type: "percentage", value: 20 },
    ];

    // Elements
    const servicesGrid = document.getElementById('servicesGrid');
    const serviceSearch = document.getElementById('serviceSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const cartItems = document.getElementById('cartItems');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const subtotalAmount = document.getElementById('subtotalAmount');
    const discountRow = document.getElementById('discountRow');
    const discountAmount = document.getElementById('discountAmount');
    const totalAmount = document.getElementById('totalAmount');
    const paymentBtn = document.getElementById('paymentBtn');
    const customerRequiredMessage = document.getElementById('customerRequiredMessage');
    const cartCustomerSearch = document.getElementById('cartCustomerSearch');
    const customerDropdown = document.getElementById('customerDropdown');
    const customerDropdownContent = document.getElementById('customerDropdownContent');
    const selectCustomerBtn = document.getElementById('selectCustomerBtn');
    const selectedCustomerName = document.getElementById('selectedCustomerName');
    const selectedCustomerInfo = document.getElementById('selectedCustomerInfo');
    const customerModal = document.getElementById('customerModal');
    const customerSearch = document.getElementById('customerSearch');
    const customersList = document.getElementById('customersList');
    const clearCartBtn = document.getElementById('clearCartBtn');
    const discountSelect = document.getElementById('discountSelect');
    const discountSelectContainer = document.getElementById('discountSelectContainer');
    const appliedDiscountInfo = document.getElementById('appliedDiscountInfo');
    const removeDiscountBtn = document.getElementById('removeDiscountBtn');
    const paymentModal = document.getElementById('paymentModal');
    const paymentTabBtns = document.querySelectorAll('.payment-tab-btn');
    const paymentTabs = document.querySelectorAll('.payment-tab');
    const amountPaid = document.getElementById('amountPaid');
    const changeContainer = document.getElementById('changeContainer');
    const changeAmount = document.getElementById('changeAmount');
    const itemsCount = document.getElementById('itemsCount');
    const totalQuantity = document.getElementById('totalQuantity');
    const paymentSubtotal = document.getElementById('paymentSubtotal');
    const paymentDiscountRow = document.getElementById('paymentDiscountRow');
    const paymentDiscount = document.getElementById('paymentDiscount');
    const paymentTotal = document.getElementById('paymentTotal');
    const completePaymentBtn = document.getElementById('completePaymentBtn');
    const receiptModal = document.getElementById('receiptModal');
    const receiptNumber = document.getElementById('receiptNumber');
    const receiptDate = document.getElementById('receiptDate');
    const receiptCustomer = document.getElementById('receiptCustomer');
    const receiptPaymentMethod = document.getElementById('receiptPaymentMethod');
    const receiptItems = document.getElementById('receiptItems');
    const receiptSubtotal = document.getElementById('receiptSubtotal');
    const receiptDiscountRow = document.getElementById('receiptDiscountRow');
    const receiptDiscount = document.getElementById('receiptDiscount');
    const receiptTotal = document.getElementById('receiptTotal');
    const receiptAmountPaidRow = document.getElementById('receiptAmountPaidRow');
    const receiptAmountPaid = document.getElementById('receiptAmountPaid');
    const receiptChangeRow = document.getElementById('receiptChangeRow');
    const receiptChange = document.getElementById('receiptChange');
    const pickupDate = document.getElementById('pickupDate');
    const printReceiptBtn = document.getElementById('printReceiptBtn');
    const newOrderBtn = document.getElementById('newOrderBtn');
    const toastContainer = document.getElementById('toastContainer');

    // State
    let cart = [];
    let selectedCustomer = null;
    let selectedDiscount = null;
    let filteredServices = [...mockServices];
    let currentPaymentMethod = 'cash';
    let orderNumber = '';

    // Initialize
    renderServices();
    renderDiscounts();
    updateCartUI();

    // Event Listeners
    serviceSearch.addEventListener('input', filterServices);
    categoryFilter.addEventListener('change', filterServices);
    selectCustomerBtn.addEventListener('click', openCustomerModal);
    clearCartBtn.addEventListener('click', clearCart);
    discountSelect.addEventListener('change', handleDiscountChange);
    removeDiscountBtn.addEventListener('click', removeDiscount);
    paymentBtn.addEventListener('click', openPaymentModal);
    amountPaid.addEventListener('input', calculateChange);
    completePaymentBtn.addEventListener('click', processPayment);
    printReceiptBtn.addEventListener('click', printReceipt);
    newOrderBtn.addEventListener('click', startNewOrder);
    customerSearch.addEventListener('input', filterCustomers);

    // Close modals when clicking on close button or outside
    document.querySelectorAll('.close-btn, [data-dismiss="modal"]').forEach(element => {
        element.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });

    // Payment tab switching
    paymentTabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchPaymentTab(tabId);
        });
    });

    // Functions
    function renderServices() {
        servicesGrid.innerHTML = '';

        if (filteredServices.length === 0) {
            servicesGrid.innerHTML = `
                <div class="empty-services">
                    <p>No services found. Try adjusting your search or filter.</p>
                </div>
            `;
            return;
        }

        filteredServices.forEach(service => {
            const serviceCard = document.createElement('div');
            serviceCard.className = 'service-card';
            serviceCard.innerHTML = `
                <div class="service-header">
                    <div>
                        <h3 class="service-name">${service.name}</h3>
                        <span class="service-category category-${service.category}">${service.category}</span>
                    </div>
                    <span class="service-price">${formatCurrency(service.price)}</span>
                </div>
            `;
            serviceCard.addEventListener('click', () => addToCart(service));
            servicesGrid.appendChild(serviceCard);
        });
    }

    function renderDiscounts() {
        discountSelect.innerHTML = '<option value="">Select discount</option>';
        mockDiscounts.forEach(discount => {
            const option = document.createElement('option');
            option.value = discount.id;
            option.textContent = `${discount.name} (${discount.type === 'percentage' ? `${discount.value}%` : formatCurrency(discount.value)})`;
            discountSelect.appendChild(option);
        });
    }

    function renderCustomers(customers) {
        customersList.innerHTML = '';

        if (customers.length === 0) {
            customersList.innerHTML = `
                <div class="text-center py-4 text-gray-500">No customers found</div>
            `;
            return;
        }

        customers.forEach(customer => {
            const customerItem = document.createElement('div');
            customerItem.className = 'customer-item';
            customerItem.innerHTML = `
                <div class="customer-details">
                    <span class="customer-item-name">${customer.name}</span>
                    <span class="customer-item-phone">${customer.phone}</span>
                </div>
                <button class="select-btn">Select</button>
            `;
            customerItem.addEventListener('click', () => selectCustomer(customer));
            customersList.appendChild(customerItem);
        });
    }

    function filterServices() {
        const searchTerm = serviceSearch.value.toLowerCase();
        const category = categoryFilter.value;

        filteredServices = mockServices.filter(service => {
            const matchesSearch = service.name.toLowerCase().includes(searchTerm);
            const matchesCategory = category === 'all' || service.category === category;
            return matchesSearch && matchesCategory;
        });

        renderServices();
    }

    function filterCustomers() {
        const searchTerm = customerSearch.value.toLowerCase();

        const filteredCustomers = mockCustomers.filter(customer =>
            customer.name.toLowerCase().includes(searchTerm) ||
            customer.phone.includes(searchTerm) ||
            customer.email.toLowerCase().includes(searchTerm)
        );

        renderCustomers(filteredCustomers);
    }

    function addToCart(service) {
        const existingItemIndex = cart.findIndex(item => item.serviceId === service.id);

        if (existingItemIndex !== -1) {
            // Item already exists, increment quantity
            cart[existingItemIndex].quantity += 1;
            cart[existingItemIndex].total = cart[existingItemIndex].price * cart[existingItemIndex].quantity;
        } else {
            // Add new item
            const newItem = {
                id: Date.now(),
                serviceId: service.id,
                name: service.name,
                price: service.price,
                quantity: 1,
                total: service.price
            };
            cart.push(newItem);
        }

        updateCartUI();
        showToast(`${service.name} added to cart`, 'success');
    }

    function updateCartUI() {
        // Update cart items
        if (cart.length === 0) {
            emptyCartMessage.classList.remove('hidden');
            cartItems.classList.add('hidden');
            clearCartBtn.classList.add('hidden');
        } else {
            emptyCartMessage.classList.add('hidden');
            cartItems.classList.remove('hidden');
            clearCartBtn.classList.remove('hidden');

            cartItems.innerHTML = '';
            cart.forEach(item => {
                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${formatCurrency(item.price)} each</div>
                    </div>
                    <div class="cart-item-actions">
                        <button class="quantity-btn decrease-btn" data-id="${item.id}">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="quantity-value">${item.quantity}</span>
                        <button class="quantity-btn increase-btn" data-id="${item.id}">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="remove-item-btn" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                cartItems.appendChild(cartItem);
            });

            // Add event listeners to quantity buttons
            document.querySelectorAll('.decrease-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    updateQuantity(id, -1);
                });
            });

            document.querySelectorAll('.increase-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    updateQuantity(id, 1);
                });
            });

            document.querySelectorAll('.remove-item-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    removeItem(id);
                });
            });
        }

        // Calculate totals
        const subtotal = calculateSubtotal();
        const discount = calculateDiscount(subtotal);
        const total = subtotal - discount;

        // Update summary
        subtotalAmount.textContent = formatCurrency(subtotal);

        if (selectedDiscount) {
            discountRow.classList.remove('hidden');
            discountAmount.textContent = `-${formatCurrency(discount)}`;
        } else {
            discountRow.classList.add('hidden');
        }

        totalAmount.textContent = formatCurrency(total);

        // Update payment button state
        if (cart.length > 0 && selectedCustomer) {
            paymentBtn.disabled = false;
            customerRequiredMessage.classList.add('hidden');
        } else {
            paymentBtn.disabled = true;
            if (cart.length > 0 && !selectedCustomer) {
                customerRequiredMessage.classList.remove('hidden');
            } else {
                customerRequiredMessage.classList.add('hidden');
            }
        }
    }

    function updateQuantity(id, change) {
        const itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex === -1) return;

        const newQuantity = cart[itemIndex].quantity + change;

        if (newQuantity < 1) return;

        cart[itemIndex].quantity = newQuantity;
        cart[itemIndex].total = cart[itemIndex].price * newQuantity;

        updateCartUI();
    }

    function removeItem(id) {
        cart = cart.filter(item => item.id !== id);
        updateCartUI();
    }

    function clearCart() {
        cart = [];
        updateCartUI();
    }

    function calculateSubtotal() {
        return cart.reduce((sum, item) => sum + item.total, 0);
    }

    function calculateDiscount(subtotal) {
        if (!selectedDiscount) return 0;

        return selectedDiscount.type === 'percentage'
            ? (subtotal * selectedDiscount.value) / 100
            : selectedDiscount.value;
    }

    // Event listeners for customer selection
    cartCustomerSearch.addEventListener('focus', showCustomerDropdown);
    cartCustomerSearch.addEventListener('input', filterCustomersInDropdown);
    document.addEventListener('click', function(event) {
        if (!cartCustomerSearch.contains(event.target) && !customerDropdown.contains(event.target)) {
            hideCustomerDropdown();
        }
    });

    // Functions for customer selection
    function showCustomerDropdown() {
        renderCustomersInDropdown(mockCustomers);
        customerDropdown.classList.add('show');
    }

    function hideCustomerDropdown() {
        customerDropdown.classList.remove('show');
    }

    function filterCustomersInDropdown() {
        const searchTerm = cartCustomerSearch.value.toLowerCase();

        const filteredCustomers = mockCustomers.filter(customer =>
            customer.name.toLowerCase().includes(searchTerm) ||
            customer.phone.includes(searchTerm) ||
            customer.email.toLowerCase().includes(searchTerm)
        );

        renderCustomersInDropdown(filteredCustomers);
    }

    function renderCustomersInDropdown(customers) {
        customerDropdownContent.innerHTML = '';

        if (customers.length === 0) {
            customerDropdownContent.innerHTML = `
                <div class="no-customers-message">No customers found</div>
            `;
            return;
        }

        customers.forEach(customer => {
            const customerItem = document.createElement('div');
            customerItem.className = 'customer-item';
            customerItem.innerHTML = `
                <div class="customer-details">
                    <span class="customer-item-name">${customer.name}</span>
                    <span class="customer-item-phone">${customer.phone}</span>
                </div>
            `;
            customerItem.addEventListener('click', () => selectCustomer(customer));
            customerDropdownContent.appendChild(customerItem);
        });
    }

    function selectCustomer(customer) {
        selectedCustomer = customer;

        selectedCustomerInfo.innerHTML = `
            <div class="customer-info">
                <span class="customer-name">${customer.name}</span>
                <span class="customer-phone">${customer.phone}</span>
            </div>
            <button class="btn-change" id="changeCustomerBtn">Change</button>
        `;
        selectedCustomerInfo.classList.remove('hidden');

        document.getElementById('changeCustomerBtn').addEventListener('click', function() {
            selectedCustomerInfo.classList.add('hidden');
            cartCustomerSearch.value = '';
            cartCustomerSearch.focus();
        });

        cartCustomerSearch.value = '';
        hideCustomerDropdown();
        updateCartUI();
    }

    function handleDiscountChange() {
        const discountId = parseInt(discountSelect.value);

        if (!discountId) {
            removeDiscount();
            return;
        }

        selectedDiscount = mockDiscounts.find(d => d.id === discountId);

        if (selectedDiscount) {
            discountSelectContainer.classList.add('hidden');
            appliedDiscountInfo.innerHTML = `
                <div class="discount-info">
                    <i class="fas fa-percent discount-icon"></i>
                    <span>${selectedDiscount.name} (${selectedDiscount.type === 'percentage'
                        ? `${selectedDiscount.value}%`
                        : formatCurrency(selectedDiscount.value)})</span>
                </div>
                <span class="discount-value">-${formatCurrency(calculateDiscount(calculateSubtotal()))}</span>
            `;
            appliedDiscountInfo.classList.remove('hidden');
            removeDiscountBtn.classList.remove('hidden');
        }

        updateCartUI();
    }

    function removeDiscount() {
        selectedDiscount = null;
        discountSelect.value = '';
        discountSelectContainer.classList.remove('hidden');
        appliedDiscountInfo.classList.add('hidden');
        removeDiscountBtn.classList.add('hidden');
        updateCartUI();
    }

    function openCustomerModal() {
        renderCustomers(mockCustomers);
        openModal(customerModal);
    }

    function openPaymentModal() {
        // Update payment summary
        const subtotal = calculateSubtotal();
        const discount = calculateDiscount(subtotal);
        const total = subtotal - discount;

        itemsCount.textContent = cart.length;
        totalQuantity.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        paymentSubtotal.textContent = formatCurrency(subtotal);

        if (selectedDiscount) {
            paymentDiscountRow.classList.remove('hidden');
            paymentDiscount.textContent = `-${formatCurrency(discount)}`;
        } else {
            paymentDiscountRow.classList.add('hidden');
        }

        paymentTotal.textContent = formatCurrency(total);

        // Reset payment form
        amountPaid.value = '';
        changeContainer.classList.add('hidden');
        switchPaymentTab('cash');

        openModal(paymentModal);
    }

    function switchPaymentTab(tabId) {
        currentPaymentMethod = tabId;

        // Update tab buttons
        paymentTabBtns.forEach(btn => {
            if (btn.getAttribute('data-tab') === tabId) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update tab content
        paymentTabs.forEach(tab => {
            if (tab.id === `${tabId}Tab`) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
    }

    function calculateChange() {
        const subtotal = calculateSubtotal();
        const discount = calculateDiscount(subtotal);
        const total = subtotal - discount;

        const paid = parseFloat(amountPaid.value) || 0;
        const change = paid - total;

        if (paid >= total) {
            changeContainer.classList.remove('hidden');
            changeAmount.textContent = formatCurrency(change);
        } else {
            changeContainer.classList.add('hidden');
        }
    }

    function processPayment() {
        const subtotal = calculateSubtotal();
        const discount = calculateDiscount(subtotal);
        const total = subtotal - discount;

        // Validate payment
        if (currentPaymentMethod === 'cash') {
            const paid = parseFloat(amountPaid.value) || 0;
            if (paid < total) {
                showToast('Amount paid must be equal to or greater than the total amount', 'error');
                return;
            }
        }

        // Generate order number
        orderNumber = `ORD-${Date.now().toString().slice(-6)}`;

        // Close payment modal
        closeModal(paymentModal);

        // Show receipt
        showReceipt();

        showToast(`Order #${orderNumber} has been processed`, 'success');
    }

    function showReceipt() {
        const subtotal = calculateSubtotal();
        const discount = calculateDiscount(subtotal);
        const total = subtotal - discount;
        const paid = parseFloat(amountPaid.value) || 0;
        const change = paid - total;

        // Set receipt details
        receiptNumber.textContent = orderNumber;
        receiptDate.textContent = new Date().toLocaleString();
        receiptCustomer.textContent = selectedCustomer.name;
        receiptPaymentMethod.textContent = currentPaymentMethod.charAt(0).toUpperCase() + currentPaymentMethod.slice(1);

        // Set receipt items
        receiptItems.innerHTML = '';
        cart.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-right">${formatCurrency(item.price)}</td>
                <td class="text-right">${formatCurrency(item.total)}</td>
            `;
            receiptItems.appendChild(row);
        });

        // Set receipt summary
        receiptSubtotal.textContent = formatCurrency(subtotal);

        if (selectedDiscount) {
            receiptDiscountRow.classList.remove('hidden');
            receiptDiscount.textContent = `-${formatCurrency(discount)}`;
        } else {
            receiptDiscountRow.classList.add('hidden');
        }

        receiptTotal.textContent = formatCurrency(total);

        if (currentPaymentMethod === 'cash' && paid > total) {
            receiptAmountPaidRow.classList.remove('hidden');
            receiptChangeRow.classList.remove('hidden');
            receiptAmountPaid.textContent = formatCurrency(paid);
            receiptChange.textContent = formatCurrency(change);
        } else {
            receiptAmountPaidRow.classList.add('hidden');
            receiptChangeRow.classList.add('hidden');
        }

        // Set pickup date (2 days from now)
        const pickupDateObj = new Date();
        pickupDateObj.setDate(pickupDateObj.getDate() + 2);
        pickupDate.textContent = `Items will be ready for pickup on ${pickupDateObj.toLocaleDateString()}`;

        // Show receipt modal
        openModal(receiptModal);
    }

    function printReceipt() {
        window.print();
    }

    function startNewOrder() {
        cart = [];
        selectedCustomer = null;
        selectedDiscount = null;

        selectedCustomerName.textContent = 'Select Customer';
        selectedCustomerInfo.classList.add('hidden');

        removeDiscount();
        updateCartUI();

        closeModal(receiptModal);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-GH', {
            style: 'currency',
            currency: 'GHS',
            minimumFractionDigits: 2
        }).format(amount);
    }

    function openModal(modal) {
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }

    function closeModal(modal) {
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        if (type === 'warning') icon = 'exclamation-triangle';

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="toast-content">
                <p>${message}</p>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        toastContainer.appendChild(toast);

        // Add event listener to close button
        toast.querySelector('.toast-close').addEventListener('click', function() {
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });

        // Auto close after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('hiding');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
});
