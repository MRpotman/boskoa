document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("person-count");
    const priceElement = document.getElementById("dynamic-price");

    if (!input || !priceElement) return;

    const basePrice = parseFloat(priceElement.dataset.basePrice);

    input.addEventListener("input", function () {

        let quantity = parseInt(this.value);

        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
            this.value = 1;
        }

        const total = basePrice * quantity;

        priceElement.textContent = "$" + total.toLocaleString();
    });

});
