document.addEventListener('DOMContentLoaded', function () {

    // ── Abrir / cerrar sidebar ──────────────────────────
    const cartToggleBtn = document.getElementById('cart-toggle-btn');
    const cartCloseBtn  = document.getElementById('cart-close-btn');
    const cartSidebar   = document.getElementById('cart-sidebar');
    const cartOverlay   = document.getElementById('cart-overlay');

    function openCart() {
        cartSidebar.classList.add('is-open');
        cartOverlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeCart() {
        cartSidebar.classList.remove('is-open');
        cartOverlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    cartToggleBtn?.addEventListener('click', openCart);
    cartCloseBtn?.addEventListener('click', closeCart);
    cartOverlay?.addEventListener('click', closeCart);

    // ── Helpers sessionStorage ──────────────────────────
    function getCart() {
        return JSON.parse(sessionStorage.getItem('boskoa_cart') || '[]');
    }

    function saveCart(cart) {
        sessionStorage.setItem('boskoa_cart', JSON.stringify(cart));
    }

    function updateCartCount() {
        const cart  = getCart();
        const total = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 1), 0);
        const badge = document.querySelector('.cart-count');
        if (badge) badge.textContent = total;
    }

    // ── Renderizar items en el sidebar ──────────────────
    function renderCart() {
        const cart = getCart();
        const body = document.querySelector('.cart-sidebar__body');
        if (!body) return;

        if (cart.length === 0) {
            body.innerHTML = `<p style="color:#888; text-align:center; padding: 2rem 0;">${cartStrings.cartEmpty}</p>`;
            return;
        }

        const totalPrice = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (parseInt(item.quantity) || 1)), 0);

        body.innerHTML = `
            <ul class="mini-cart-list">
                ${cart.map((item, index) => {
                    const price = parseFloat(item.price) || 0;
                    const qty   = parseInt(item.quantity) || 1;
                    return `
                    <li class="mini-cart-item">
                        <img src="${item.image}" alt="${item.title}">
                        <div class="mini-cart-info">
                            <p class="mini-cart-title">${item.title}</p>
                            <div class="mini-cart-qty-control">
                                <button class="qty-btn qty-minus" data-index="${index}">−</button>
                                <input 
                                    type="number" 
                                    class="qty-input" 
                                    data-index="${index}" 
                                    data-base-price="${price}"
                                    value="${qty}" 
                                    min="1">
                                <button class="qty-btn qty-plus" data-index="${index}">+</button>
                            </div>
                            <p class="mini-cart-price" id="item-price-${index}">
                                $${(price * qty).toFixed(2)}
                            </p>
                        </div>
                        <button class="mini-cart-remove" data-index="${index}">✕</button>
                    </li>
                `}).join('')}
            </ul>

            <div class="mini-cart-total">
                <span>${cartStrings.total}:</span>
                <strong id="cart-total">$${totalPrice.toFixed(2)}</strong>
            </div>

            <button class="mini-cart-checkout">${cartStrings.bookNow}</button>
            <button class="mini-cart-clear">${cartStrings.clearCart}</button>
        `;

        // ── Cambio manual en input ──
        body.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function () {
                const index     = parseInt(this.dataset.index);
                const basePrice = parseFloat(this.dataset.basePrice) || 0;
                let   newQty    = parseInt(this.value) || 1;
                if (newQty < 1) newQty = 1;
                this.value = newQty;

                const cart = getCart();
                cart[index].quantity = newQty;
                saveCart(cart);

                document.getElementById(`item-price-${index}`).textContent =
                    `$${(basePrice * newQty).toFixed(2)}`;

                const newTotal = cart.reduce((sum, i) => sum + ((parseFloat(i.price) || 0) * (parseInt(i.quantity) || 1)), 0);
                document.getElementById('cart-total').textContent = `$${newTotal.toFixed(2)}`;

                updateCartCount();
            });
        });

        // ── Botón − ──
        body.querySelectorAll('.qty-minus').forEach(btn => {
            btn.addEventListener('click', function () {
                const index = parseInt(this.dataset.index);
                const input = body.querySelector(`.qty-input[data-index="${index}"]`);
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });

        // ── Botón + ──
        body.querySelectorAll('.qty-plus').forEach(btn => {
            btn.addEventListener('click', function () {
                const index = parseInt(this.dataset.index);
                const input = body.querySelector(`.qty-input[data-index="${index}"]`);
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('change'));
            });
        });

        // ── Eliminar item ──
        body.querySelectorAll('.mini-cart-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                const cart = getCart();
                cart.splice(parseInt(this.dataset.index), 1);
                saveCart(cart);
                renderCart();
                updateCartCount();
            });
        });

        // ── Vaciar carrito ──
        body.querySelector('.mini-cart-clear')?.addEventListener('click', function () {
            saveCart([]);
            renderCart();
            updateCartCount();
        });

        // ── Checkout → abre cart checkout modal ──
        body.querySelector('.mini-cart-checkout')?.addEventListener('click', function () {
            openCartCheckoutModal();
        });
    }

    // ── Cart Checkout Modal ─────────────────────────────
    function openCartCheckoutModal() {
        const modal = document.getElementById('cart-checkout-modal');
        if (!modal) return;

        const cart = getCart();

        // Rellenar resumen en el modal
        const summary = document.getElementById('cart-modal-summary');
        if (summary && cart.length > 0) {
            const totalPrice = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (parseInt(item.quantity) || 1)), 0);
            summary.innerHTML = `
                <div class="cart-modal-items">
                    <h3>${cartStrings.orderSummary}</h3>
                    <ul class="cart-modal-list">
                        ${cart.map(item => {
                            const price = parseFloat(item.price) || 0;
                            const qty   = parseInt(item.quantity) || 1;
                            return `
                            <li class="cart-modal-list-item">
                                <span class="cart-modal-item-title">${item.title}</span>
                                <span class="cart-modal-item-detail">
                                    ${qty} × $${price.toFixed(2)} = <strong>$${(price * qty).toFixed(2)}</strong>
                                </span>
                            </li>
                        `}).join('')}
                    </ul>
                </div>
                <div class="cart-modal-total">
                    <span>${cartStrings.total}:</span>
                    <strong>$${totalPrice.toFixed(2)}</strong>
                </div>
            `;
        }

        // Pasar los items al input hidden como JSON
        const cartInput = document.getElementById('cart-items-input');
        if (cartInput) {
            cartInput.value = JSON.stringify(cart);
        }

        // Pre-llenar el mensaje con los items del carrito
        const messageField = document.getElementById('cart_contact_message');
        if (messageField) {
            const itemsList = cart.map(item => {
                const price = parseFloat(item.price) || 0;
                const qty   = parseInt(item.quantity) || 1;
                return `- ${item.title} × ${qty} ($${(price * qty).toFixed(2)})`;
            }).join('\n');
            const total = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (parseInt(item.quantity) || 1)), 0);
            messageField.value = `${cartStrings.bookingIntro}\n\n${itemsList}\n\nTotal: $${total.toFixed(2)}\n\n${cartStrings.bookingOutro}`;
        }

        // Cerrar sidebar y abrir modal
        closeCart();
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeCartCheckoutModal() {
        const modal = document.getElementById('cart-checkout-modal');
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // ── Listeners del modal de checkout ────────────────
    const checkoutModal = document.getElementById('cart-checkout-modal');

    document.querySelector('.cart-checkout-modal-close')?.addEventListener('click', closeCartCheckoutModal);

    if (checkoutModal) {
        checkoutModal.addEventListener('click', function (e) {
            if (e.target === checkoutModal) {
                closeCartCheckoutModal();
            }
        });
    }

    // ── Submit del formulario de checkout ───────────────
    const cartCheckoutForm = document.getElementById('cart-checkout-form');
    if (cartCheckoutForm) {
        cartCheckoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = cartCheckoutForm.querySelector('.cart-checkout-submit-btn');
            const originalText = submitBtn ? submitBtn.textContent : cartStrings.bookNow;

            const cartInput = document.getElementById('cart-items-input');
            if (cartInput) {
                cartInput.value = JSON.stringify(getCart());
            }

            if (typeof executeRecaptcha === 'function') {
                executeRecaptcha()
                    .then(function (token) {
                        document.getElementById('cartRecaptchaToken').value = token;
                        if (submitBtn) {
                            submitBtn.textContent = cartStrings.sending;
                            submitBtn.disabled = true;
                        }
                        cartCheckoutForm.submit();
                    })
                    .catch(function (error) {
                        console.error('reCAPTCHA error:', error);
                        alert(cartStrings.errorMsg);
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        }
                    });
            } else {
                if (submitBtn) {
                    submitBtn.textContent = cartStrings.sending;
                    submitBtn.disabled = true;
                }
                cartCheckoutForm.submit();
            }
        });
    }

    // ── Agregar al carrito ──────────────────────────────
    const addBtn = document.querySelector('.add-to-cart-btn');

    if (addBtn) {
        addBtn.addEventListener('click', function () {

            const quantity = parseInt(document.getElementById('person-count')?.value || 1);
            const cart     = getCart();

            const item = {
                id:       this.dataset.id,
                title:    this.dataset.title,
                price:    parseFloat(this.dataset.price) || 0,
                image:    this.dataset.image,
                quantity: quantity
            };

            const existing = cart.find(i => i.id === item.id);
            if (existing) {
                existing.quantity += quantity;
            } else {
                cart.push(item);
            }

            saveCart(cart);
            updateCartCount();
            renderCart();
            openCart();

            addBtn.textContent = cartStrings.added;
            setTimeout(() => addBtn.textContent = cartStrings.addToCart, 2000);
        });
    }

    // ── Verificar status de contacto en URL ─────────────
    (function checkCartContactStatus() {
        const url = new URL(window.location.href);
        const status = url.searchParams.get('cart_contact');

        if (status === 'success') {
            alert(cartStrings.successMsg);
            url.searchParams.delete('cart_contact');
            window.history.replaceState({}, document.title, url.toString());
            saveCart([]);
            updateCartCount();
            renderCart();
        } else if (status === 'error') {
            alert(cartStrings.errorMsg);
            url.searchParams.delete('cart_contact');
            window.history.replaceState({}, document.title, url.toString());
        }
    })();

    // ── Inicializar intl-tel-input ───────────────────────
    function initPhoneInputs() {
        if (typeof intlTelInput === 'undefined') return;

        var phones = [
            {
                input:  document.getElementById('contact_phone'),
                hidden: document.getElementById('contact_phone_full'),
                form:   document.getElementById('booking-form')
            },
            {
                input:  document.getElementById('cart_contact_phone'),
                hidden: document.getElementById('cart_contact_phone_full'),
                form:   document.getElementById('cart-checkout-form')
            }
        ];

        phones.forEach(function(item) {
            if (!item.input) return;

            var iti = intlTelInput(item.input, {
                initialCountry: 'cr',
                preferredCountries: ['cr', 'us', 'mx', 'co'],
                separateDialCode: true,
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js'
            });

            if (item.form && item.hidden) {
                item.form.addEventListener('submit', function() {
                    item.hidden.value = iti.getNumber();
                }, true);
            }
        });
    }

    // ── Inicializar ─────────────────────────────────────
    initPhoneInputs();
    updateCartCount();
    renderCart();

});