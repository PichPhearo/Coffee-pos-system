@props([
'modalId' => 'receipt-modal',
])

<div id="{{ $modalId }}" class="receipt-modal-root fixed inset-0 z-50 hidden">
    <div class="receipt-backdrop absolute inset-0 bg-black/40"></div>
    <div class="relative mx-auto mt-8 w-[92%] max-w-md rounded-2xl border border-brown-200 bg-cream shadow-xl">
        <div class="border-b border-brown-100 px-5 py-4">
            <p class="text-xs uppercase tracking-widest text-brown-500">Receipt</p>
            <h3 class="font-serif text-xl text-espresso">Order <span class="receipt-order-id">#-</span></h3>
            <p class="receipt-date mt-1 text-xs text-brown-500"></p>
        </div>

        <div class="px-5 py-4 space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-brown-600">Payment</span>
                <span class="receipt-payment font-medium text-espresso uppercase">-</span>
            </div>

            <div class="rounded-xl border border-brown-100 bg-white overflow-hidden">
                <div class="max-h-64 overflow-y-auto">
                    <table class="w-full text-sm">
                        <tbody class="receipt-lines divide-y divide-brown-100"></tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-brown-100 bg-white p-3 space-y-1.5 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-brown-600">Subtotal</span>
                    <span class="receipt-subtotal text-espresso">$0.00</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-brown-600">Tax (10%)</span>
                    <span class="receipt-tax text-espresso">$0.00</span>
                </div>
                <div class="flex items-center justify-between border-t border-brown-100 pt-2">
                    <span class="font-semibold text-espresso">Total</span>
                    <span class="receipt-total text-lg font-semibold text-crema">$0.00</span>
                </div>
            </div>
        </div>

        <div class="receipt-actions border-t border-brown-100 px-5 py-4 flex items-center justify-end gap-2">
            <button type="button" class="receipt-close-btn rounded-xl border border-brown-200 bg-white px-4 py-2 text-sm text-brown-700 hover:bg-brown-50">
                Close
            </button>
            <button type="button" class="receipt-print-btn rounded-xl bg-espresso px-4 py-2 text-sm font-medium text-cream hover:bg-brown-800">
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
    (() => {
        const modal = document.getElementById('{{ $modalId }}');
        if (!modal) return;

        const orderIdEl = modal.querySelector('.receipt-order-id');
        const dateEl = modal.querySelector('.receipt-date');
        const paymentEl = modal.querySelector('.receipt-payment');
        const linesEl = modal.querySelector('.receipt-lines');
        const subtotalEl = modal.querySelector('.receipt-subtotal');
        const taxEl = modal.querySelector('.receipt-tax');
        const totalEl = modal.querySelector('.receipt-total');

        function formatCurrency(value) {
            return `$${Number(value || 0).toFixed(2)}`;
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;');
        }

        function closeReceipt() {
            modal.classList.add('hidden');
        }

        function showReceipt(order, dateField, datePrefix) {
            const items = Array.isArray(order?.items) ? order.items : [];
            const subtotal = items.reduce((sum, item) => {
                return sum + (Number(item.price || 0) * Number(item.quantity || 1));
            }, 0);
            const tax = subtotal * 0.1;
            const total = subtotal + tax;

            orderIdEl.textContent = `#${order?.id ?? '-'}`;

            const rawDate = order?.[dateField];
            dateEl.textContent = rawDate ? `${datePrefix} ${new Date(rawDate).toLocaleString()}` : '';

            paymentEl.textContent = order?.payment_method || '-';
            subtotalEl.textContent = formatCurrency(subtotal);
            taxEl.textContent = formatCurrency(tax);
            totalEl.textContent = formatCurrency(total);

            linesEl.innerHTML = items.map((item) => {
                const name = item?.product?.name || 'Item';
                const qty = Number(item?.quantity || 1);
                const price = Number(item?.price || 0);
                const lineTotal = qty * price;
                const size = item?.size || '-';
                const sugar = item?.sugar_level || '-';
                const note = item?.note || '';

                return `
                    <tr>
                        <td class="px-3 py-2.5 align-top">
                            <p class="text-sm font-medium text-espresso">${escapeHtml(name)}</p>
                            <p class="text-[11px] text-brown-500">${qty} x ${formatCurrency(price)}</p>
                            <p class="text-[11px] text-brown-500">${escapeHtml(size)} • ${escapeHtml(sugar)}</p>
                            ${note ? `<p class="text-[11px] italic text-brown-500">Note: ${escapeHtml(note)}</p>` : ''}
                        </td>
                        <td class="px-3 py-2.5 text-right font-semibold text-espresso align-top">${formatCurrency(lineTotal)}</td>
                    </tr>
                `;
            }).join('');

            modal.classList.remove('hidden');
        }

        modal.querySelector('.receipt-close-btn')?.addEventListener('click', closeReceipt);
        modal.querySelector('.receipt-backdrop')?.addEventListener('click', closeReceipt);
        modal.querySelector('.receipt-print-btn')?.addEventListener('click', () => window.print());

        document.querySelectorAll('.view-receipt-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const order = JSON.parse(button.dataset.order || '{}');
                const dateField = button.dataset.dateField || 'created_at';
                const datePrefix = button.dataset.datePrefix || 'Placed';
                showReceipt(order, dateField, datePrefix);
            });
        });
    })();
</script>