<?php
/**
 * Template Name: Checkout / Order Form
 * Collects customer info and sends order email — no payment processing.
 */
get_header();
?>

<div class="checkout-page-wrap">
    <div class="container">

        <!-- Header -->
        <div class="cart-page-header">
            <a href="<?php echo esc_url( home_url('/cart') ); ?>" class="page-back-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Cart
            </a>
            <h1 class="page-title">Order Request</h1>
            <p class="page-subtitle">Fill out the form below — no payment required. We'll confirm your order before work begins.</p>
        </div>

        <div class="checkout-layout" id="checkout-layout">

            <!-- Form Card -->
            <div>
                <div class="checkout-form-card">

                    <!-- Progress Indicator -->
                    <div class="form-progress" id="form-progress">
                        <div class="progress-step active" data-step="1" id="prog-1">
                            <div class="step-num">1</div>
                            <div class="step-label">Your Info</div>
                        </div>
                        <div class="progress-step" data-step="2" id="prog-2">
                            <div class="step-num">2</div>
                            <div class="step-label">Review</div>
                        </div>
                        <div class="progress-step" data-step="3" id="prog-3">
                            <div class="step-num" style="font-size:0.75rem;">&#10003;</div>
                            <div class="step-label">Done</div>
                        </div>
                    </div>

                    <!-- Step 1: Customer Info -->
                    <div class="form-step active" id="step-1">
                        <div class="form-card-header">
                            <h2>Contact &amp; Shipping Information</h2>
                            <p>We'll use this to confirm your order and ship or arrange pickup.</p>
                        </div>
                        <div class="form-card-body">
                            <div id="step1-alert" class="form-alert" role="alert"></div>

                            <h3 class="form-section-title">Contact Details</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name <span class="required">*</span></label>
                                    <input class="form-input" type="text" id="first_name" name="first_name" autocomplete="given-name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name <span class="required">*</span></label>
                                    <input class="form-input" type="text" id="last_name" name="last_name" autocomplete="family-name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email Address <span class="required">*</span></label>
                                    <input class="form-input" type="email" id="email" name="email" autocomplete="email" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone Number <span class="required">*</span></label>
                                    <input class="form-input" type="tel" id="phone" name="phone" autocomplete="tel" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="company">Company / Organization</label>
                                <input class="form-input" type="text" id="company" name="company" autocomplete="organization">
                            </div>

                            <h3 class="form-section-title" style="margin-top:32px;">Shipping / Pickup Address</h3>
                            <div class="form-group">
                                <label class="form-label" for="address">Street Address <span class="required">*</span></label>
                                <input class="form-input" type="text" id="address" name="address" autocomplete="street-address" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="city">City <span class="required">*</span></label>
                                    <input class="form-input" type="text" id="city" name="city" autocomplete="address-level2" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="state">State <span class="required">*</span></label>
                                    <select class="form-select" id="state" name="state" autocomplete="address-level1" required>
                                        <option value="">— Select State —</option>
                                        <?php
                                        $states = ['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DC'=>'District of Columbia','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','PR'=>'Puerto Rico','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'];
                                        foreach ($states as $abbr => $name): ?>
                                        <option value="<?php echo esc_attr($abbr); ?>"><?php echo esc_html($name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="zip">ZIP Code <span class="required">*</span></label>
                                    <input class="form-input" type="text" id="zip" name="zip" autocomplete="postal-code" maxlength="10" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="country">Country</label>
                                    <select class="form-select" id="country" name="country" autocomplete="country-name">
                                        <option value="United States">United States</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <h3 class="form-section-title" style="margin-top:32px;">Order Notes</h3>
                            <div class="form-group">
                                <label class="form-label" for="notes">Special Requests / Details</label>
                                <textarea class="form-textarea" id="notes" name="notes" placeholder="Describe your custom engraving design, preferred colors, specific requirements, artwork details, etc." rows="5"></textarea>
                            </div>

                            <div style="margin-top:8px;">
                                <button type="button" id="step1-next" class="btn btn-primary" style="width:100%;justify-content:center;">
                                    Review Order
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Review -->
                    <div class="form-step" id="step-2">
                        <div class="form-card-header">
                            <h2>Review Your Order</h2>
                            <p>Please confirm everything looks correct before submitting.</p>
                        </div>
                        <div class="form-card-body">
                            <div id="step2-alert" class="form-alert" role="alert"></div>

                            <h3 class="form-section-title">Customer Information</h3>
                            <div id="review-customer" style="background:var(--apex-off-white);border-radius:6px;padding:20px;margin-bottom:24px;font-size:0.9rem;color:var(--apex-gray-dark);line-height:1.9;"></div>

                            <h3 class="form-section-title">Order Items</h3>
                            <div class="order-review-items" id="review-items"></div>

                            <div id="review-notes-wrap" style="display:none;">
                                <h3 class="form-section-title">Special Notes</h3>
                                <div style="background:var(--apex-off-white);border-left:3px solid var(--apex-orange);padding:14px 18px;font-size:0.9rem;color:var(--apex-gray-dark);border-radius:0 4px 4px 0;margin-bottom:24px;" id="review-notes"></div>
                            </div>

                            <!-- Disclaimer -->
                            <div style="background:rgba(245,131,31,0.06);border:1px solid rgba(245,131,31,0.25);border-radius:6px;padding:18px 20px;margin-bottom:24px;">
                                <p style="font-size:0.85rem;color:var(--apex-gray-dark);line-height:1.7;margin:0;">
                                    <strong style="color:var(--apex-black);">Important:</strong> Submitting this form is a <em>quote request only</em>. No payment is collected online. We will review your order and contact you within 1-2 business days to confirm pricing, timeline, and next steps before any work begins.
                                </p>
                            </div>

                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                <button type="button" id="step2-back" class="btn btn-outline-dark" style="flex:1;min-width:140px;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                    Edit Info
                                </button>
                                <button type="button" id="submit-order-btn" class="btn btn-primary" style="flex:2;min-width:200px;justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                    Submit Order Request
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Confirmation -->
                    <div class="form-step" id="step-3">
                        <div class="confirmation-wrap">
                            <div class="confirm-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <h2>Order Submitted!</h2>
                            <p>Thank you — your order request has been received.</p>
                            <p>We've sent a confirmation to <strong id="confirm-email"></strong></p>
                            <div class="order-ref" id="confirm-ref">APEX-XXXXXXXX</div>
                            <p style="color:var(--apex-gray-mid);font-size:0.9rem;margin-bottom:32px;">We'll review your order and contact you within <strong>1-2 business days</strong> to confirm pricing and next steps.</p>
                            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                                <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-primary">Shop More</a>
                                <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn btn-outline-dark">Go Home</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div>
                <div class="checkout-summary">
                    <div class="summary-header">Your Cart</div>
                    <div class="checkout-summary-items" id="checkout-summary-items">
                        <p style="color:var(--apex-gray-mid);font-size:0.9rem;padding:8px 0;">Loading...</p>
                    </div>
                    <div class="summary-body" style="padding-top:0;">
                        <div class="summary-row total">
                            <span class="label">Estimated Total</span>
                            <span class="value" id="checkout-total">$0.00</span>
                        </div>
                    </div>
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>No payment collected online</span>
                        </div>
                        <div class="trust-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            <span>Pricing confirmed before work begins</span>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.checkout-layout -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var currentStep = 1;
    var cart = window.ApexCart ? window.ApexCart.getItems() : [];

    // Redirect if cart empty
    if (!cart || cart.length === 0) {
        window.location.href = '<?php echo esc_url(home_url('/cart')); ?>';
        return;
    }

    // Render checkout sidebar
    renderCheckoutSummary(cart);

    // Step navigation
    document.getElementById('step1-next').addEventListener('click', function () {
        if (!validateStep1()) return;
        goToStep(2);
        renderReview();
    });

    document.getElementById('step2-back').addEventListener('click', function () {
        goToStep(1);
    });

    document.getElementById('submit-order-btn').addEventListener('click', function () {
        submitOrder();
    });

    function goToStep(n) {
        currentStep = n;
        document.querySelectorAll('.form-step').forEach(function(s) {
            s.classList.remove('active');
        });
        var el = document.getElementById('step-' + n);
        if (el) el.classList.add('active');

        // Update progress
        for (var i = 1; i <= 3; i++) {
            var prog = document.getElementById('prog-' + i);
            if (!prog) continue;
            prog.classList.remove('active', 'done');
            if (i < n)      prog.classList.add('done');
            else if (i === n) prog.classList.add('active');
        }

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function validateStep1() {
        var alert = document.getElementById('step1-alert');
        alert.className = 'form-alert';
        alert.textContent = '';

        var required = ['first_name','last_name','email','phone','address','city','state','zip'];
        for (var i = 0; i < required.length; i++) {
            var el = document.getElementById(required[i]);
            if (!el || !el.value.trim()) {
                alert.className = 'form-alert error';
                alert.textContent = 'Please fill in all required fields (marked with *).';
                el.focus();
                return false;
            }
        }

        // Basic email validation
        var email = document.getElementById('email').value.trim();
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert.className = 'form-alert error';
            alert.textContent = 'Please enter a valid email address.';
            document.getElementById('email').focus();
            return false;
        }

        // Magazine restriction — blocked states
        var restrictedStates = ['CA','CO','CT','DC','DE','HI','IL','MD','MA','NJ','NY','OR','PR','RI','VT','WA'];
        var selectedState = (document.getElementById('state').value || '').toUpperCase();

        if (restrictedStates.indexOf(selectedState) !== -1) {
            // Check if cart contains any magazine-related items
            var magKeywords = ['mag','magazine','ar-15','ar-10','ar15','ar10'];
            var hasMagItem = cart.some(function(item) {
                var name = (item.name || '').toLowerCase();
                return magKeywords.some(function(kw) { return name.indexOf(kw) !== -1; });
            });

            if (hasMagItem) {
                alert.className = 'form-alert error';
                alert.innerHTML =
                    '<strong>⚠ Shipping Restriction:</strong> We cannot ship magazine engraving orders to <strong>' +
                    escHtml(document.getElementById('state').options[document.getElementById('state').selectedIndex].text) +
                    '</strong> due to state magazine import laws.<br><br>' +
                    'Please remove magazine-related items from your cart to continue, or contact us at <a href="tel:6158621660" style="color:var(--apex-orange);">(615) 862-1660</a> to discuss alternatives.';
                document.getElementById('state').focus();
                return false;
            }
        }

        return true;
    }

    function renderReview() {
        var f = function(id) { return (document.getElementById(id) || {}).value || ''; };

        document.getElementById('review-customer').innerHTML = [
            '<strong>'+escHtml(f('first_name')+' '+f('last_name'))+'</strong><br>',
            escHtml(f('email'))+'<br>',
            escHtml(f('phone'))+'<br>',
            f('company') ? escHtml(f('company'))+'<br>' : '',
            '<br>',
            '<strong>Ship To:</strong><br>',
            escHtml(f('address'))+'<br>',
            escHtml(f('city')+', '+f('state')+' '+f('zip'))+'<br>',
            escHtml(f('country')),
        ].join('');

        var itemsEl = document.getElementById('review-items');
        itemsEl.innerHTML = '';
        var total = 0;

        cart.forEach(function(item) {
            var sub = parseFloat(item.price) * item.quantity;
            total += sub;
            var row = document.createElement('div');
            row.className = 'review-item';
            row.innerHTML = [
                '<div>',
                    '<div class="review-item-name">'+escHtml(item.name)+'</div>',
                    '<div class="review-item-qty">Qty: '+item.quantity+' &times; '+formatPrice(item.price)+'</div>',
                '</div>',
                '<div class="review-item-price">'+formatPrice(sub.toFixed(2))+'</div>',
            ].join('');
            itemsEl.appendChild(row);
        });

        // Total row
        var totalRow = document.createElement('div');
        totalRow.className = 'review-item';
        totalRow.style.cssText = 'font-weight:800;border-top:2px solid var(--apex-black);margin-top:8px;padding-top:12px;';
        totalRow.innerHTML = [
            '<div style="font-family:\'Barlow Condensed\',sans-serif;font-size:1rem;text-transform:uppercase;letter-spacing:0.05em;">Estimated Total</div>',
            '<div style="color:var(--apex-orange);font-family:\'Barlow Condensed\',sans-serif;font-size:1.2rem;">'+formatPrice(total.toFixed(2))+'</div>',
        ].join('');
        itemsEl.appendChild(totalRow);

        var notes = f('notes');
        if (notes.trim()) {
            document.getElementById('review-notes-wrap').style.display = 'block';
            document.getElementById('review-notes').textContent = notes;
        } else {
            document.getElementById('review-notes-wrap').style.display = 'none';
        }
    }

    function submitOrder() {
        var btn   = document.getElementById('submit-order-btn');
        var alert = document.getElementById('step2-alert');
        alert.className = 'form-alert';
        alert.textContent = '';

        btn.disabled = true;
        btn.innerHTML = '<span style="opacity:0.7">Submitting...</span>';

        var f = function(id) { return (document.getElementById(id) || {}).value || ''; };

        var data = new FormData();
        data.append('action',      'apex_submit_order');
        data.append('nonce',       apexAjax.nonce);
        data.append('first_name',  f('first_name'));
        data.append('last_name',   f('last_name'));
        data.append('email',       f('email'));
        data.append('phone',       f('phone'));
        data.append('company',     f('company'));
        data.append('address',     f('address'));
        data.append('city',        f('city'));
        data.append('state',       f('state'));
        data.append('zip',         f('zip'));
        data.append('country',     f('country'));
        data.append('notes',       f('notes'));
        data.append('cart_items',  JSON.stringify(cart));

        fetch(apexAjax.url, { method: 'POST', body: data })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success) {
                    // Clear the cart
                    window.ApexCart.clear();

                    // Show confirmation
                    document.getElementById('confirm-email').textContent = f('email');
                    document.getElementById('confirm-ref').textContent   = res.data.order_ref;
                    goToStep(3);
                } else {
                    alert.className = 'form-alert error';
                    alert.textContent = (res.data && res.data.message) || 'Something went wrong. Please try again or call us.';
                    btn.disabled = false;
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Submit Order Request';
                }
            })
            .catch(function() {
                alert.className = 'form-alert error';
                alert.textContent = 'Connection error. Please check your internet and try again, or call us directly.';
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Submit Order Request';
            });
    }

    function renderCheckoutSummary(items) {
        var el    = document.getElementById('checkout-summary-items');
        var total = 0;
        if (!el) return;
        el.innerHTML = '';

        items.forEach(function(item) {
            var sub = parseFloat(item.price) * item.quantity;
            total += sub;
            var row = document.createElement('div');
            row.className = 'checkout-summary-item';
            row.innerHTML = [
                '<div>',
                    '<div class="cs-item-name">'+escHtml(item.name)+'</div>',
                    '<div class="cs-item-qty">x'+item.quantity+'</div>',
                '</div>',
                '<div class="cs-item-price">'+formatPrice(sub.toFixed(2))+'</div>',
            ].join('');
            el.appendChild(row);
        });

        var totEl = document.getElementById('checkout-total');
        if (totEl) totEl.textContent = formatPrice(total.toFixed(2));
    }

    function formatPrice(val) {
        return '$' + parseFloat(val).toLocaleString('en-US', {minimumFractionDigits:2,maximumFractionDigits:2});
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
});
</script>

<?php get_footer(); ?>
