@extends('layouts.app')
@section('title', 'New Order')

@push('styles')
    <style>
        .pos-layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 20px;
            height: calc(100vh - 120px);
        }

        /* Product panel */
        .products-panel {
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow-y: auto;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
        }

        .pos-product {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            cursor: pointer;
            transition: border-color .15s, transform .1s;
            user-select: none;
        }

        .pos-product:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .pos-product.oos {
            opacity: .45;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pos-product img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            display: block;
            background: var(--surface2);
        }

        .pos-product .pp-body {
            padding: 8px;
        }

        .pos-product .pp-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pos-product .pp-price {
            color: var(--accent);
            font-family: var(--mono);
            font-size: 13px;
            font-weight: 700;
        }

        .pos-product .pp-qty {
            color: var(--muted);
            font-size: 11px;
        }

        /* Cart panel */
        .cart-panel {
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .cart-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            border-radius: 7px;
            margin-bottom: 4px;
            background: var(--surface2);
        }

        .ci-name {
            flex: 1;
            font-size: 13px;
            font-weight: 500;
        }

        .ci-controls {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .qty-btn {
            width: 24px;
            height: 24px;
            border-radius: 5px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .ci-qty {
            font-family: var(--mono);
            font-size: 13px;
            width: 28px;
            text-align: center;
        }

        .ci-price {
            font-family: var(--mono);
            font-size: 13px;
            color: var(--muted);
            min-width: 56px;
            text-align: right;
        }

        .ci-remove {
            cursor: pointer;
            color: var(--muted);
            font-size: 16px;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .ci-remove:hover {
            color: var(--red);
            background: rgba(239, 68, 68, .1);
        }

        .cart-empty {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            color: var(--muted);
            padding: 32px;
            text-align: center;
        }

        .cart-summary {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 3px 0;
            color: var(--muted);
        }

        .summary-row.total {
            color: var(--text);
            font-weight: 700;
            font-size: 16px;
            margin-top: 6px;
            padding-top: 8px;
            border-top: 1px solid var(--border);
        }

        .summary-row .mono {
            font-family: var(--mono);
        }

        .cart-form {
            padding: 12px 16px;
            border-top: 1px solid var(--border);
        }

        .cart-form .form-group {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header" style="margin-bottom:16px;">
        <h1 class="page-title">🛒 New Order</h1>
        <div class="flex gap-2">
            <input type="text" id="search" placeholder="Search products…" class="form-control" style="width:220px;"
                oninput="filterProducts(this.value)">
        </div>
    </div>

    <div class="pos-layout">
        {{-- PRODUCTS --}}
        <div class="products-panel">
            <div class="product-grid" id="productGrid">
                @foreach ($products as $product)
                    <div class="pos-product {{ $product->qty === 0 ? 'oos' : '' }}" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->qty }}"
                        data-search="{{ strtolower($product->name . ' ' . $product->category) }}" onclick="addToCart(this)">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div
                                style="height:100px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:30px;">
                                🛍️</div>
                        @endif
                        <div class="pp-body">
                            <div class="pp-name">{{ $product->name }}</div>
                            <div class="pp-price">${{ number_format($product->price, 2) }}</div>
                            <div class="pp-qty">{{ $product->qty > 0 ? 'Stock: ' . $product->qty : 'Out of stock' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CART --}}
        <div class="cart-panel">
            <div class="cart-header">
                <span>Cart</span>
                <button onclick="clearCart()" class="btn btn-secondary btn-sm">Clear</button>
            </div>

            <div id="cartItems" class="cart-items">
                <div class="cart-empty" id="emptyMsg">
                    <div style="font-size:36px;">🛒</div>
                    <p>Tap a product to add it</p>
                </div>
            </div>

            <div class="cart-summary">
                <div class="summary-row"><span>Subtotal</span><span class="mono" id="subtotal">$0.00</span></div>
                <div class="summary-row">
                    <span>Discount</span>
                    <span><input type="number" id="discountInput" min="0" step="0.01" placeholder="0.00"
                            style="width:70px;background:var(--surface2);border:1px solid var(--border);border-radius:5px;color:var(--text);padding:3px 6px;font-family:var(--mono);font-size:12px;text-align:right;"
                            oninput="recalc()"></span>
                </div>
                <div class="summary-row total"><span>Total</span><span class="mono" id="totalDisplay">$0.00</span></div>
            </div>

            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf
                <div class="cart-form">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Walk-in customer">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Payment</label>
                        <select name="payment_method" class="form-control">
                            <option value="cash">💵 Cash</option>
                            <option value="card">💳 Card</option>
                            <option value="transfer">🏦 Transfer</option>
                        </select>
                    </div>
                    <input type="hidden" name="discount" id="discountHidden" value="0">
                    <div id="cartInputs"></div>
                    <button type="submit" class="btn btn-primary w-100" id="placeOrderBtn" disabled
                        onclick="return validateCart()">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let cart = {}; // { productId: { name, price, qty, stock } }

        function addToCart(el) {
            const id = el.dataset.id;
            const name = el.dataset.name;
            const price = parseFloat(el.dataset.price);
            const stock = parseInt(el.dataset.stock);

            if (cart[id]) {
                if (cart[id].qty >= stock) {
                    alert('Maximum stock reached!');
                    return;
                }
                cart[id].qty++;
            } else {
                cart[id] = {
                    name,
                    price,
                    qty: 1,
                    stock
                };
            }
            renderCart();
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];
            else if (cart[id].qty > cart[id].stock) cart[id].qty = cart[id].stock;
            renderCart();
        }

        function removeItem(id) {
            delete cart[id];
            renderCart();
        }

        function clearCart() {
            cart = {};
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItems');
            const ids = Object.keys(cart);

            if (!ids.length) {
                container.innerHTML = `
            <div class="cart-empty" id="emptyMsg">
                <div style="font-size:36px;">🛒</div>
                <p>Tap a product to add it</p>
            </div>`;
                document.getElementById('cartInputs').innerHTML = '';
                document.getElementById('placeOrderBtn').disabled = true;
                recalc();
                return;
            }

            container.innerHTML = ids.map(id => {
                const item = cart[id];
                const lineTotal = (item.price * item.qty).toFixed(2);
                return `
        <div class="cart-item">
            <div class="ci-name">${item.name}</div>
            <div class="ci-controls">
                <button type="button" class="qty-btn" onclick="changeQty('${id}',-1)">−</button>
                <div class="ci-qty">${item.qty}</div>
                <button type="button" class="qty-btn" onclick="changeQty('${id}',1)">+</button>
            </div>
            <div class="ci-price">$${lineTotal}</div>
            <div class="ci-remove" onclick="removeItem('${id}')">✕</div>
        </div>`;
            }).join('');

            // Hidden inputs
            document.getElementById('cartInputs').innerHTML = ids.map((id, i) =>
                `<input type="hidden" name="items[${i}][product_id]" value="${id}">
         <input type="hidden" name="items[${i}][quantity]"   value="${cart[id].qty}">`
            ).join('');

            document.getElementById('placeOrderBtn').disabled = false;
            recalc();
        }

        function recalc() {
            let subtotal = 0;
            for (const id in cart) subtotal += cart[id].price * cart[id].qty;
            const discount = parseFloat(document.getElementById('discountInput').value) || 0;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('totalDisplay').textContent = '$' + Math.max(0, total).toFixed(2);
            document.getElementById('discountHidden').value = discount;
        }

        function validateCart() {
            if (!Object.keys(cart).length) {
                alert('Add at least one product!');
                return false;
            }
            return true;
        }

        function filterProducts(q) {
            q = q.toLowerCase();
            document.querySelectorAll('.pos-product').forEach(el => {
                el.style.display = el.dataset.search.includes(q) ? '' : 'none';
            });
        }
    </script>
@endpush
