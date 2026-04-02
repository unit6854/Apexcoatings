/**
 * Apex Main JS — nav, scroll effects, animations
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        /* ============================================================
           STICKY NAV — requestAnimationFrame-throttled passive scroll
           ============================================================ */
        var nav = document.getElementById('apex-nav');
        if (nav) {
            var ticking   = false;
            var lastScrollY = window.scrollY;

            function onScroll() {
                var y = window.scrollY;
                if (y === lastScrollY) { ticking = false; return; }
                lastScrollY = y;
                // Toggle class without forced reflow — classList changes are batched
                nav.classList.toggle('scrolled', y > 60);
                ticking = false;
            }

            // rAF throttle: guaranteed max one handler per frame (16ms at 60fps)
            window.addEventListener('scroll', function () {
                if (!ticking) {
                    requestAnimationFrame(onScroll);
                    ticking = true;
                }
            }, { passive: true });

            onScroll(); // initialise state immediately
        }

        /* ============================================================
           MOBILE NAV TOGGLE
           ============================================================ */
        var toggleBtn = document.getElementById('nav-toggle');
        var navMenu   = document.getElementById('nav-menu');

        if (toggleBtn && navMenu) {
            toggleBtn.addEventListener('click', function () {
                var isOpen = navMenu.classList.toggle('open');
                toggleBtn.setAttribute('aria-expanded', String(isOpen));
            });

            // Close menu on link click
            navMenu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    navMenu.classList.remove('open');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                });
            });

            // Close on outside click
            document.addEventListener('click', function (e) {
                if (nav && !nav.contains(e.target)) {
                    navMenu.classList.remove('open');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        /* ============================================================
           SCROLL REVEAL
           ============================================================ */
        var reveals = document.querySelectorAll('.reveal');

        if ('IntersectionObserver' in window) {
            var revealObs = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var el  = entry.target;
                        var idx = Array.prototype.indexOf.call(el.parentNode.children, el);
                        el.style.transitionDelay = (idx * 0.07) + 's';
                        el.classList.add('visible');
                        revealObs.unobserve(el);
                    }
                });
            }, { threshold: 0.12 });

            reveals.forEach(function (el) { revealObs.observe(el); });
        } else {
            // Fallback: show all immediately
            reveals.forEach(function (el) { el.classList.add('visible'); });
        }

        /* Product card image hover is handled purely in CSS for best performance */

        /* ============================================================
           SMOOTH SCROLL for anchor links
           ============================================================ */
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    var offset = (nav ? nav.offsetHeight : 80) + 16;
                    var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }
            });
        });

        /* ============================================================
           CONTACT FORM (if on contact page)
           ============================================================ */
        var contactForm = document.getElementById('apex-contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                e.preventDefault();

                var btn   = contactForm.querySelector('[type=submit]');
                var alert = document.getElementById('contact-alert');
                if (alert) { alert.className = 'form-alert'; alert.textContent = ''; }

                btn.disabled = true;
                var origText = btn.textContent;
                btn.textContent = 'Sending...';

                var data = new FormData(contactForm);
                data.append('action', 'apex_submit_contact');
                data.append('nonce',  apexAjax.nonce);

                fetch(apexAjax.url, { method: 'POST', body: data })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (alert) {
                            alert.className = 'form-alert ' + (res.success ? 'success' : 'error');
                            alert.textContent = (res.data && res.data.message) || (res.success ? 'Message sent!' : 'Error. Please try again.');
                        }
                        if (res.success) {
                            contactForm.reset();
                        }
                    })
                    .catch(function () {
                        if (alert) {
                            alert.className = 'form-alert error';
                            alert.textContent = 'Connection error. Please call us directly.';
                        }
                    })
                    .finally(function () {
                        btn.disabled = false;
                        btn.textContent = origText;
                    });
            });
        }

        /* ============================================================
           GALLERY LIGHTBOX — keyboard accessibility
           ============================================================ */
        var portfolioItems = document.querySelectorAll('.portfolio-item');
        portfolioItems.forEach(function (item) {
            item.setAttribute('tabindex', '0');
            item.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') item.click();
            });
        });

    }); // DOMContentLoaded

})();
