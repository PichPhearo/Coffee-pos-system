<x-layouts.pos title="Menu" subtitle="Fast checkout for cashier">
    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-6">
        <section class="space-y-5">
            <div class="sticky top-[72px] z-20 bg-white/95 backdrop-blur-sm border border-brown-100 rounded-2xl p-5 shadow-sm">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brown-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.6-4.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        id="search-input"
                        type="text"
                        placeholder="Search menu..."
                        class="w-full rounded-xl border border-brown-200 pl-10 pr-4 py-2.5 text-sm text-espresso placeholder-brown-400 focus:outline-none focus:ring-2 focus:ring-crema/50 focus:border-crema">
                </div>

                <div class="mt-4 flex gap-2 overflow-x-auto pb-1" id="category-nav">
                    <button
                        type="button"
                        class="category-btn shrink-0 px-4 py-1.5 text-sm rounded-full border border-crema bg-crema/20 text-espresso"
                        data-category="all">
                        All
                    </button>
                    @foreach($categories as $category)
                    <button
                        type="button"
                        class="category-btn shrink-0 px-4 py-1.5 text-sm rounded-full border border-brown-200 bg-white text-brown-700 hover:border-crema/50"
                        data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($products as $product)
                <article
                    class="product-card bg-white border border-brown-100 rounded-2xl p-3 shadow-sm hover:shadow-md transition"
                    data-category="{{ $product->category_id }}"
                    data-name="{{ strtolower($product->name) }}">
                    <div class="h-36 rounded-xl overflow-hidden bg-brown-50 border border-brown-100">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-brown-300 text-xs">No image</div>
                        @endif
                    </div>

                    <div class="pt-3">
                        <h3 class="font-serif text-lg text-espresso truncate">{{ $product->name }}</h3>
                        <div class="mt-1 text-crema text-xl font-serif font-semibold">${{ number_format($product->price, 2) }}</div>

                        <button
                            type="button"
                            class="add-btn mt-3 w-full rounded-xl bg-espresso text-cream text-sm font-medium py-2.5 hover:bg-brown-800 transition"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $product->price }}">
                            Add to cart
                        </button>
                    </div>
                </article>
                @empty
                <div class="col-span-full rounded-2xl border border-dashed border-brown-200 bg-white p-10 text-center text-brown-500">
                    No products found.
                </div>
                @endforelse
            </div>
        </section>

        <aside class="bg-white border border-brown-100 rounded-2xl shadow-sm h-fit lg:sticky lg:top-24">
            <div class="p-5 border-b border-brown-100">
                <h2 class="font-serif text-xl text-espresso">Checkout</h2>
                <p class="text-xs text-brown-500 mt-1">{{ now()->format('d M Y, H:i') }}</p>
            </div>

            <div id="cart-items" class="p-4 space-y-2 max-h-[360px] overflow-y-auto">
                <div class="rounded-xl bg-brown-50 border border-brown-100 p-4 text-sm text-brown-500 text-center">Cart is empty</div>
            </div>

            <div class="p-5 border-t border-brown-100 space-y-2.5 bg-brown-50/60 rounded-b-2xl">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-brown-600">Items</span>
                    <span id="sum-items" class="text-espresso font-medium">0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-brown-600">Subtotal</span>
                    <span id="sum-subtotal" class="text-espresso font-medium">$0.00</span>
                </div>
                <div class="flex items-center justify-between text-sm border-b border-brown-200 pb-3">
                    <span class="text-brown-600">Tax (10%)</span>
                    <span id="sum-tax" class="text-espresso font-medium">$0.00</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-espresso font-serif text-lg">Total</span>
                    <span id="sum-total" class="text-crema font-serif text-2xl font-semibold">$0.00</span>
                </div>

                <div class="pt-1">
                    <span class="block text-xs text-brown-600 mb-1.5">Payment method</span>
                    <div id="payment-method-group" class="grid grid-cols-3 gap-2">
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment-method" value="cash" class="peer sr-only" checked>
                            <div class="flex flex-col items-center justify-center gap-1 rounded-xl border border-brown-200 bg-white px-2 py-2.5 text-xs font-medium text-brown-700 transition hover:bg-brown-50 peer-checked:border-crema peer-checked:bg-crema/20 peer-checked:text-espresso">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                                </svg>
                                Cash
                            </div>
                        </label>
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment-method" value="card" class="peer sr-only">
                            <div class="flex flex-col items-center justify-center gap-1 rounded-xl border border-brown-200 bg-white px-2 py-2.5 text-xs font-medium text-brown-700 transition hover:bg-brown-50 peer-checked:border-crema peer-checked:bg-crema/20 peer-checked:text-espresso">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2zM7 15h2" />
                                </svg>
                                Card
                            </div>
                        </label>
                        <label class="payment-option cursor-pointer">
                            <input type="radio" name="payment-method" value="ewallet" class="peer sr-only">
                            <div class="flex flex-col items-center justify-center gap-1 rounded-xl border border-brown-200 bg-white px-2 py-2.5 text-xs font-medium text-brown-700 transition hover:bg-brown-50 peer-checked:border-crema peer-checked:bg-crema/20 peer-checked:text-espresso">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                E-Wallet
                            </div>
                        </label>
                    </div>
                </div>

                <button id="place-order-btn" type="button" class="w-full mt-3 rounded-xl bg-crema text-espresso py-3 font-semibold hover:bg-brown-300 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Place Order
                </button>
                <button id="clear-cart-btn" type="button" class="w-full rounded-xl border border-brown-200 bg-white text-brown-700 py-2.5 text-sm hover:bg-brown-50 transition">
                    Clear
                </button>
            </div>
        </aside>
    </div>

    <div id="receipt-modal" class="receipt-modal-root fixed inset-0 z-50 hidden">
        <div id="receipt-backdrop" class="receipt-backdrop absolute inset-0 bg-black/40"></div>
        <div class="relative mx-auto mt-8 w-[92%] max-w-md rounded-2xl border border-brown-200 bg-cream shadow-xl">
            <div class="border-b border-brown-100 px-5 py-4">
                <p class="text-xs uppercase tracking-widest text-brown-500">Receipt</p>
                <h3 class="font-serif text-xl text-espresso">Order <span id="receipt-order-id">#-</span></h3>
                <p id="receipt-date" class="mt-1 text-xs text-brown-500"></p>
            </div>

            <div class="px-5 py-4 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-brown-600">Payment</span>
                    <span id="receipt-payment" class="font-medium text-espresso uppercase">-</span>
                </div>

                <div class="rounded-xl border border-brown-100 bg-white overflow-hidden">
                    <div class="max-h-64 overflow-y-auto">
                        <table class="w-full text-sm">
                            <tbody id="receipt-lines" class="divide-y divide-brown-100"></tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-xl border border-brown-100 bg-white p-3 space-y-1.5 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-brown-600">Subtotal</span>
                        <span id="receipt-subtotal" class="text-espresso">$0.00</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-brown-600">Tax (10%)</span>
                        <span id="receipt-tax" class="text-espresso">$0.00</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-brown-100 pt-2">
                        <span class="font-semibold text-espresso">Total</span>
                        <span id="receipt-total" class="text-lg font-semibold text-crema">$0.00</span>
                    </div>
                </div>
            </div>

            <div class="receipt-actions border-t border-brown-100 px-5 py-4 flex items-center justify-end gap-2">
                <button id="receipt-close-btn" type="button" class="rounded-xl border border-brown-200 bg-white px-4 py-2 text-sm text-brown-700 hover:bg-brown-50">
                    Close
                </button>
                <button id="receipt-print-btn" type="button" class="rounded-xl bg-espresso px-4 py-2 text-sm font-medium text-cream hover:bg-brown-800">
                    Print
                </button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }

            .receipt-modal-root,
            .receipt-modal-root * {
                visibility: visible !important;
            }

            .receipt-modal-root {
                position: absolute !important;
                inset: 0 !important;
                background: #fff !important;
            }

            .receipt-modal-root .receipt-backdrop,
            .receipt-modal-root .receipt-actions {
                display: none !important;
            }

            .receipt-modal-root>div:last-of-type {
                margin: 0 !important;
                width: 100% !important;
                max-width: 80mm !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                background: #fff !important;
            }

            @page {
                size: 80mm auto;
                margin: 4mm;
            }
        }
    </style>

    <script>
        const cart = {};
        let activeCategory = 'all';

        const searchInput = document.getElementById('search-input');
        const productCards = [...document.querySelectorAll('.product-card')];
        const categoryButtons = [...document.querySelectorAll('.category-btn')];
        const receiptModal = document.getElementById('receipt-modal');

        function formatCurrency(value) {
            return `$${Number(value).toFixed(2)}`;
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function showReceipt({
            orderId,
            paymentMethod,
            items
        }) {
            const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.1;
            const total = subtotal + tax;

            document.getElementById('receipt-order-id').textContent = `#${orderId}`;
            document.getElementById('receipt-date').textContent = new Date().toLocaleString();
            document.getElementById('receipt-payment').textContent = paymentMethod;
            document.getElementById('receipt-subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('receipt-tax').textContent = formatCurrency(tax);
            document.getElementById('receipt-total').textContent = formatCurrency(total);

            document.getElementById('receipt-lines').innerHTML = items.map((item) => `
                <tr>
                    <td class="px-3 py-2.5 align-top">
                        <p class="text-sm font-medium text-espresso">${escapeHtml(item.name)}</p>
                        <p class="text-[11px] text-brown-500">${item.quantity} x ${formatCurrency(item.price)}</p>
                        <p class="text-[11px] text-brown-500">${escapeHtml(item.size)} • ${escapeHtml(item.sugar_level)}</p>
                        ${item.note ? `<p class="text-[11px] italic text-brown-500">Note: ${escapeHtml(item.note)}</p>` : ''}
                    </td>
                    <td class="px-3 py-2.5 text-right font-semibold text-espresso align-top">${formatCurrency(item.price * item.quantity)}</td>
                </tr>
            `).join('');

            receiptModal.classList.remove('hidden');
        }

        function closeReceipt() {
            receiptModal.classList.add('hidden');
        }

        document.getElementById('receipt-close-btn').addEventListener('click', closeReceipt);
        document.getElementById('receipt-backdrop').addEventListener('click', closeReceipt);
        document.getElementById('receipt-print-btn').addEventListener('click', () => window.print());

        function filterProducts() {
            const term = searchInput.value.trim().toLowerCase();

            productCards.forEach((card) => {
                const matchesCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
                const matchesSearch = card.dataset.name.includes(term);
                card.style.display = matchesCategory && matchesSearch ? '' : 'none';
            });
        }

        categoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeCategory = button.dataset.category;

                categoryButtons.forEach((btn) => {
                    btn.classList.remove('border-crema', 'bg-crema/20', 'text-espresso');
                    btn.classList.add('border-brown-200', 'bg-white', 'text-brown-700');
                });

                button.classList.remove('border-brown-200', 'bg-white', 'text-brown-700');
                button.classList.add('border-crema', 'bg-crema/20', 'text-espresso');
                filterProducts();
            });
        });

        searchInput.addEventListener('input', filterProducts);

        document.querySelectorAll('.add-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const id = button.dataset.productId;
                const name = button.dataset.productName;
                const price = parseFloat(button.dataset.productPrice);

                if (!cart[id]) {
                    cart[id] = {
                        name,
                        price,
                        qty: 0,
                        size: 'medium',
                        sugar_level: 'normal',
                        note: '',
                    };
                }

                cart[id].qty += 1;
                renderCart();
            });
        });

        document.getElementById('clear-cart-btn').addEventListener('click', () => {
            Object.keys(cart).forEach((id) => delete cart[id]);
            renderCart();
        });

        document.getElementById('place-order-btn').addEventListener('click', async () => {
            const placeOrderButton = document.getElementById('place-order-btn');

            if (!Object.keys(cart).length || placeOrderButton.disabled) return;

            const items = Object.keys(cart).map((id) => ({
                product_id: Number(id),
                quantity: cart[id].qty,
                size: cart[id].size,
                sugar_level: cart[id].sugar_level,
                note: (cart[id].note || '').trim() || null,
            }));

            const receiptItems = Object.keys(cart).map((id) => ({
                name: cart[id].name,
                price: cart[id].price,
                quantity: cart[id].qty,
                size: cart[id].size,
                sugar_level: cart[id].sugar_level,
                note: (cart[id].note || '').trim(),
            }));

            const paymentMethod = document.querySelector('input[name="payment-method"]:checked')?.value || 'cash';

            placeOrderButton.disabled = true;
            placeOrderButton.textContent = 'Placing...';

            try {
                const response = await fetch("{{ route('pos.orders.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        payment_method: paymentMethod,
                        items,
                    }),
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Failed to place order.');
                }

                const data = await response.json();
                showReceipt({
                    orderId: data.order_id,
                    paymentMethod,
                    items: receiptItems,
                });
                Object.keys(cart).forEach((id) => delete cart[id]);
                renderCart();
            } catch (error) {
                alert(error.message || 'Failed to place order.');
                renderCart();
            } finally {
                placeOrderButton.textContent = 'Place Order';
            }
        });

        function renderCart() {
            const cartEl = document.getElementById('cart-items');
            const ids = Object.keys(cart);

            if (!ids.length) {
                cartEl.innerHTML = '<div class="rounded-xl bg-brown-50 border border-brown-100 p-4 text-sm text-brown-500 text-center">Cart is empty</div>';
                setSummary(0, 0);
                return;
            }

            let items = 0;
            let subtotal = 0;

            cartEl.innerHTML = ids.map((id) => {
                const item = cart[id];
                const lineTotal = item.price * item.qty;
                items += item.qty;
                subtotal += lineTotal;

                return `
                    <div class="rounded-xl border border-brown-100 bg-white px-2.5 py-2">
                        <div class="flex items-center gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="text-[13px] text-espresso font-medium truncate">${item.name}</div>
                                <div class="text-[11px] text-brown-500">$${item.price.toFixed(2)}</div>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" class="cart-dec w-6 h-6 rounded-full border border-brown-200 text-brown-700 text-sm leading-none flex items-center justify-center hover:bg-brown-50" data-id="${id}" aria-label="Decrease">−</button>
                                <span class="w-5 text-center text-xs font-semibold text-espresso">${item.qty}</span>
                                <button type="button" class="cart-inc w-6 h-6 rounded-full border border-brown-200 text-brown-700 text-sm leading-none flex items-center justify-center hover:bg-brown-50" data-id="${id}" aria-label="Increase">+</button>
                            </div>
                            <div class="w-14 text-right text-[13px] font-semibold text-crema shrink-0">$${lineTotal.toFixed(2)}</div>
                        </div>
                        <div class="mt-1.5 grid grid-cols-2 gap-1.5">
                            <select class="cart-size w-full rounded-md border border-brown-200 px-1.5 py-1 text-[11px] text-espresso" data-id="${id}">
                                <option value="small" ${item.size === 'small' ? 'selected' : ''}>Small</option>
                                <option value="medium" ${item.size === 'medium' ? 'selected' : ''}>Medium</option>
                                <option value="large" ${item.size === 'large' ? 'selected' : ''}>Large</option>
                            </select>
                            <select class="cart-sugar w-full rounded-md border border-brown-200 px-1.5 py-1 text-[11px] text-espresso" data-id="${id}">
                                <option value="sweet" ${item.sugar_level === 'sweet' ? 'selected' : ''}>Sweet</option>
                                <option value="normal" ${item.sugar_level === 'normal' ? 'selected' : ''}>Normal</option>
                                <option value="less" ${item.sugar_level === 'less' ? 'selected' : ''}>Less</option>
                                <option value="no sugar" ${item.sugar_level === 'no sugar' ? 'selected' : ''}>No Sugar</option>
                            </select>
                        </div>
                        <input
                            type="text"
                            class="cart-note mt-1.5 w-full rounded-md border border-brown-200 px-1.5 py-1 text-[11px] text-espresso placeholder-brown-300"
                            data-id="${id}"
                            placeholder="Note (optional)"
                            value="${(item.note || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')}"
                        >
                    </div>
                `;
            }).join('');

            setSummary(items, subtotal);

            cartEl.querySelectorAll('.cart-inc').forEach((button) => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    cart[id].qty += 1;
                    renderCart();
                });
            });

            cartEl.querySelectorAll('.cart-dec').forEach((button) => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    cart[id].qty -= 1;
                    if (cart[id].qty <= 0) delete cart[id];
                    renderCart();
                });
            });

            cartEl.querySelectorAll('.cart-size').forEach((select) => {
                select.addEventListener('change', () => {
                    const id = select.dataset.id;
                    if (!cart[id]) return;
                    cart[id].size = select.value;
                });
            });

            cartEl.querySelectorAll('.cart-sugar').forEach((select) => {
                select.addEventListener('change', () => {
                    const id = select.dataset.id;
                    if (!cart[id]) return;
                    cart[id].sugar_level = select.value;
                });
            });

            cartEl.querySelectorAll('.cart-note').forEach((input) => {
                input.addEventListener('input', () => {
                    const id = input.dataset.id;
                    if (!cart[id]) return;
                    cart[id].note = input.value;
                });
            });
        }

        function setSummary(items, subtotal) {
            const tax = subtotal * 0.1;
            const total = subtotal + tax;

            document.getElementById('sum-items').textContent = items;
            document.getElementById('sum-subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('sum-tax').textContent = `$${tax.toFixed(2)}`;
            document.getElementById('sum-total').textContent = `$${total.toFixed(2)}`;
            document.getElementById('place-order-btn').disabled = items === 0;
        }
    </script>
</x-layouts.pos>