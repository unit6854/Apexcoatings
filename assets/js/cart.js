/**
 * Apex Cart — localStorage-based cart system
 * Exposes window.ApexCart API used by all templates
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'apex_cart_v1';

    /* ============================================================
       STORAGE HELPERS
       ============================================================ */
    function loadCart() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(items) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        } catch (e) {}
        dispatchUpdate();
    }

    function dispatchUpdate() {
        window.dispatchEvent(new CustomEvent('apex-cart-updated'));
    }

    /* ============================================================
       PUBLIC API
       ============================================================ */
    var ApexCart = {

        getItems: function () {
            return loadCart();
        },

        getTotalItems: function () {
            return loadCart().reduce(function (s, i) { return s + i.quantity; }, 0);
        },

        getTotalPrice: function () {
            return loadCart().reduce(function (s, i) { return s + parseFloat(i.price) * i.quantity; }, 0);
        },

        addItem: function (product) {
            var items = loadCart();
            var id    = String(product.id);
            var idx   = items.findIndex(function (i) { return String(i.id) === id; });

            if (idx > -1) {
                items[idx].quantity += (product.quantity || 1);
            } else {
                items.push({
                    id:       id,
                    name:     product.name     || '',
                    desc:     product.desc     || '',
                    price:    parseFloat(product.price) || 0,
                    image:    product.image    || '',
                    quantity: product.quantity || 1,
                });
            }

            saveCart(items);
            showToast(product.name + ' added to cart');
        },

        remove: function (id) {
            var items = loadCart().filter(function (i) { return String(i.id) !== String(id); });
            saveCart(items);
        },

        updateQty: function (id, qty) {
            qty = parseInt(qty);
            if (isNaN(qty) || qty <= 0) {
                ApexCart.remove(id);
                return;
            }
            var items = loadCart().map(function (i) {
                return String(i.id) === String(id) ? Object.assign({}, i, { quantity: qty }) : i;
            });
            saveCart(items);
        },

        getQty: function (id) {
            var item = loadCart().find(function (i) { return String(i.id) === String(id); });
            return item ? item.quantity : 0;
        },

        isInCart: function (id) {
            return loadCart().some(function (i) { return String(i.id) === String(id); });
        },

        clear: function () {
            localStorage.removeItem(STORAGE_KEY);
            dispatchUpdate();
        },
    };

    window.ApexCart = ApexCart;

    /* ============================================================
       NAV CART BADGE
       ============================================================ */
    function updateNavBadge() {
        var badge = document.getElementById('nav-cart-count');
        if (!badge) return;

        var count = ApexCart.getTotalItems();
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    window.addEventListener('apex-cart-updated', updateNavBadge);
    document.addEventListener('DOMContentLoaded', updateNavBadge);

    /* ============================================================
       ADD TO CART BUTTONS
       Wire up all [data-id][data-name] buttons.
       MutationObserver scoped to the products container (not body)
       to avoid broad DOM surveillance overhead.
       ============================================================ */
    document.addEventListener('DOMContentLoaded', function () {

        function bindAddToCartButtons(root) {
            var scope = root || document;
            var buttons = scope.querySelectorAll('.add-to-cart-btn[data-id]');
            buttons.forEach(function (btn) {
                if (btn.dataset.bound) return;
                btn.dataset.bound = '1';

                btn.addEventListener('click', function () {
                    var id    = this.dataset.id;
                    var name  = this.dataset.name  || 'Item';
                    var price = parseFloat(this.dataset.price) || 0;
                    var desc  = this.dataset.desc  || '';
                    var image = this.dataset.image || '';

                    ApexCart.addItem({ id: id, name: name, price: price, desc: desc, image: image, quantity: 1 });

                    // Visual feedback on button
                    var orig = this.innerHTML;
                    this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg> Added!';
                    this.style.background = 'linear-gradient(135deg,#38a169,#276749)';
                    this.style.borderColor = 'transparent';

                    var self = this;
                    setTimeout(function () {
                        self.innerHTML = orig;
                        self.style.background = '';
                        self.style.borderColor = '';
                    }, 1800);
                });
            });
        }

        // Initial bind for all existing buttons
        bindAddToCartButtons();

        // Scoped MutationObserver — watches only the products grid container,
        // not the entire document.body. Falls back to no observer if grid absent.
        var productsGrid = document.querySelector('.services-grid, #products-grid, .cart-page-wrap');
        if (productsGrid) {
            var observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (m) {
                    if (m.addedNodes.length) {
                        bindAddToCartButtons(productsGrid);
                    }
                });
            });
            observer.observe(productsGrid, { childList: true, subtree: true });
        }
    });

    /* ============================================================
       TOAST NOTIFICATIONS
       ============================================================ */
    function showToast(message) {
        var container = document.getElementById('apex-toasts');
        if (!container) return;

        var toast = document.createElement('div');
        toast.className = 'apex-toast';
        toast.innerHTML = [
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
            '<span>' + escHtml(message) + '</span>',
        ].join('');

        container.appendChild(toast);

        setTimeout(function () {
            toast.classList.add('removing');
            setTimeout(function () { toast.remove(); }, 350);
        }, 2800);
    }

    window.apexShowToast = showToast;

    function escHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

})();
