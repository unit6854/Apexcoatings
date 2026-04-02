<?php
/**
 * Template Name: Cart Page
 * Cart page — shows items stored in localStorage, handles qty changes and removal.
 */
get_header();
?>

<div class="cart-page-wrap">
    <div class="container">

        <!-- Header -->
        <div class="cart-page-header">
            <a href="<?php echo home_url('/products'); ?>" class="page-back-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Continue Shopping
            </a>
            <h1 class="page-title">Shopping Cart</h1>
            <p class="page-subtitle" id="cart-item-count">Loading cart...</p>
        </div>

        <!-- Cart Layout: populated by JS -->
        <div id="cart-content">
            <!-- Loading state -->
            <div id="cart-loading" style="text-align:center;padding:60px;color:var(--apex-gray-mid);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto 16px;display:block;color:var(--apex-gray-light);animation:spin 1s linear infinite;" aria-hidden="true"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                <style>@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}</style>
                Loading your cart...
            </div>
        </div>

    </div>
</div>

<!-- Cart Page HTML Templates (rendered by JS) -->
<template id="tpl-empty-cart">
    <div class="empty-cart">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <h2>Your Cart is Empty</h2>
        <p>Add services or products to get started with your order.</p>
        <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-primary">
            Browse Products
        </a>
    </div>
</template>

<template id="tpl-cart-layout">
    <div class="cart-layout">
        <!-- Items -->
        <div>
            <div class="cart-table-wrap">
                <div class="cart-table-header">
                    <span>Item</span>
                    <span>Quantity</span>
                    <span>Price</span>
                    <span></span>
                </div>
                <div id="cart-items-list"></div>
            </div>
        </div>

        <!-- Summary -->
        <div>
            <div class="order-summary">
                <div class="summary-header">Order Summary</div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span class="label">Items (<span id="sum-item-count">0</span>)</span>
                        <span class="value" id="sum-subtotal">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Shipping</span>
                        <span class="value">TBD at confirmation</span>
                    </div>
                    <div class="summary-row total">
                        <span class="label">Estimated Total</span>
                        <span class="value" id="sum-total">$0.00</span>
                    </div>
                </div>
                <div class="summary-actions">
                    <a href="<?php echo esc_url(home_url('/checkout')); ?>" class="btn btn-primary" id="checkout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        Proceed to Order Form
                    </a>
                    <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-outline-dark">
                        Continue Shopping
                    </a>
                    <button id="clear-cart-btn" style="background:none;border:none;cursor:pointer;font-size:0.82rem;color:var(--apex-gray-mid);text-decoration:underline;margin-top:4px;">
                        Clear Cart
                    </button>
                </div>
                <div class="trust-badges">
                    <div class="trust-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>No payment collected online — order confirmed by phone/email</span>
                    </div>
                    <div class="trust-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Pricing confirmed before work begins</span>
                    </div>
                    <div class="trust-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        <span>Fast turnaround — 3-5 business days standard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// Cart page rendering — runs after cart.js loads
document.addEventListener('DOMContentLoaded', function () {
    function renderCartPage() {
        var cart    = window.ApexCart ? window.ApexCart.getItems() : [];
        var content = document.getElementById('cart-content');
        var loading = document.getElementById('cart-loading');
        var countEl = document.getElementById('cart-item-count');

        if (loading) loading.remove();

        var totalItems = cart.reduce(function(s,i){return s+i.quantity;},0);

        if (countEl) {
            countEl.textContent = totalItems + ' ' + (totalItems === 1 ? 'item' : 'items') + ' in cart';
        }

        if (cart.length === 0) {
            var tplEmpty = document.getElementById('tpl-empty-cart');
            content.innerHTML = '';
            content.appendChild(tplEmpty.content.cloneNode(true));
            return;
        }

        var tplLayout = document.getElementById('tpl-cart-layout');
        content.innerHTML = '';
        content.appendChild(tplLayout.content.cloneNode(true));

        renderCartItems(cart);
        updateSummary(cart);

        // Clear cart button
        var clearBtn = document.getElementById('clear-cart-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (confirm('Remove all items from your cart?')) {
                    window.ApexCart.clear();
                    renderCartPage();
                }
            });
        }
    }

    function renderCartItems(cart) {
        var list = document.getElementById('cart-items-list');
        if (!list) return;
        list.innerHTML = '';

        cart.forEach(function(item) {
            var row = document.createElement('div');
            row.className = 'cart-item';
            row.dataset.id = item.id;

            var subtotal = (parseFloat(item.price) * item.quantity).toFixed(2);

            row.innerHTML = [
                '<div class="cart-item-info">',
                    item.image ? '<img src="'+normalizeImgUrl(item.image)+'" alt="'+escHtml(item.name)+'" style="width:64px;height:48px;object-fit:cover;border-radius:4px;margin-bottom:10px;" onerror="this.style.display=\'none\'">' : '',
                    '<div class="cart-item-name">'+escHtml(item.name)+'</div>',
                    item.desc ? '<div class="cart-item-desc">'+escHtml(item.desc)+'</div>' : '',
                '</div>',
                '<div>',
                    '<div class="qty-control">',
                        '<button class="qty-btn qty-minus" data-id="'+item.id+'" aria-label="Decrease quantity">&#8722;</button>',
                        '<input class="qty-value" type="number" value="'+item.quantity+'" min="1" data-id="'+item.id+'" aria-label="Quantity">',
                        '<button class="qty-btn qty-plus" data-id="'+item.id+'" aria-label="Increase quantity">&#43;</button>',
                    '</div>',
                '</div>',
                '<div class="cart-item-price">'+formatPrice(subtotal)+'</div>',
                '<div>',
                    '<button class="remove-btn" data-id="'+item.id+'" aria-label="Remove '+escHtml(item.name)+' from cart">',
                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>',
                    '</button>',
                '</div>',
            ].join('');

            list.appendChild(row);
        });

        // Bind events
        list.querySelectorAll('.qty-minus').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                window.ApexCart.updateQty(id, window.ApexCart.getQty(id) - 1);
                renderCartPage();
            });
        });

        list.querySelectorAll('.qty-plus').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                window.ApexCart.updateQty(id, window.ApexCart.getQty(id) + 1);
                renderCartPage();
            });
        });

        list.querySelectorAll('.qty-value').forEach(function(input) {
            input.addEventListener('change', function() {
                var id  = this.dataset.id;
                var qty = parseInt(this.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                window.ApexCart.updateQty(id, qty);
                renderCartPage();
            });
        });

        list.querySelectorAll('.remove-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                window.ApexCart.remove(this.dataset.id);
                renderCartPage();
            });
        });
    }

    function updateSummary(cart) {
        var total = cart.reduce(function(s,i){ return s + parseFloat(i.price)*i.quantity; }, 0);
        var items = cart.reduce(function(s,i){ return s + i.quantity; }, 0);

        var el = function(id){ return document.getElementById(id); };
        if (el('sum-item-count')) el('sum-item-count').textContent = items;
        if (el('sum-subtotal'))   el('sum-subtotal').textContent   = formatPrice(total.toFixed(2));
        if (el('sum-total'))      el('sum-total').textContent      = formatPrice(total.toFixed(2));
    }

    function formatPrice(val) {
        return '$' + parseFloat(val).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Fix image URLs saved under a different domain (e.g. .local vs tunnel vs live)
    function normalizeImgUrl(url) {
        if (!url) return '';
        try {
            var p = new URL(url);
            p.protocol = window.location.protocol;
            p.host     = window.location.host;
            return p.toString();
        } catch(e) { return url; }
    }

    // Listen for cart changes
    window.addEventListener('apex-cart-updated', renderCartPage);
    renderCartPage();
});
</script>

<?php get_footer(); ?>
