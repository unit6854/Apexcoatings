/**
 * Apex Checkout — handles order form on /checkout and /order pages.
 * Depends on: apex-cart (window.ApexCart)
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        /* ============================================================
           ORDER SUMMARY — render cart items into #checkout-summary
           ============================================================ */
        var summaryEl = document.getElementById('checkout-summary');
        if (summaryEl && window.ApexCart) {
            var items = ApexCart.getItems();

            if (items.length === 0) {
                summaryEl.innerHTML = '<p style="color:var(--apex-gray-mid);font-size:0.9rem;">Your cart is empty.</p>';
                return;
            }

            var rows = items.map(function (item) {
                var subtotal = (parseFloat(item.price) * item.quantity).toFixed(2);
                return '<div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--apex-gray-light);">'
                    + '<div><span style="font-weight:600;">' + escHtml(item.name) + '</span>'
                    + ' <span style="color:var(--apex-gray-mid);font-size:0.85rem;">× ' + item.quantity + '</span></div>'
                    + '<span style="color:var(--apex-orange);font-weight:700;">$' + subtotal + '</span>'
                    + '</div>';
            }).join('');

            var total = ApexCart.getTotalPrice().toFixed(2);
            summaryEl.innerHTML = rows
                + '<div style="display:flex;justify-content:space-between;padding:14px 0 0;font-weight:800;font-size:1.05rem;">'
                + '<span>Estimated Total</span><span style="color:var(--apex-orange);">$' + total + '</span></div>'
                + '<p style="font-size:0.78rem;color:var(--apex-gray-mid);margin-top:8px;">Final pricing confirmed before work begins.</p>';
        }

        /* ============================================================
           HIDDEN CART FIELD — populate #cart-data for form submission
           ============================================================ */
        var cartField = document.getElementById('cart-data');
        if (cartField && window.ApexCart) {
            try {
                cartField.value = JSON.stringify(ApexCart.getItems());
            } catch (e) {}
        }

    });

    function escHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

})();
