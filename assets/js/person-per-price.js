document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("person-count");
    const priceElement = document.getElementById("dynamic-price");
    const modalPrice = document.getElementById("modal-dynamic-price");
    const modalPersonsInput = document.getElementById("modal-persons");
    const modalTotalInput = document.getElementById("modal-total-price");
    const textarea = document.getElementById("contact_message");
   

    if (!input || !priceElement) return;

    const basePrice = parseFloat(priceElement.dataset.basePrice);
    const tourTitle = document.querySelector(".booking-modal-subtitle")?.textContent.split(" - ")[0];

    input.addEventListener("input", function () {

        let quantity = parseInt(this.value);

        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
            this.value = 1;
        }

        const total = basePrice * quantity;

        const formattedTotal = "$" + total.toLocaleString();
        priceElement.textContent = formattedTotal;

        if (modalPrice) {
            modalPrice.textContent = formattedTotal;
        }

        if (modalPersonsInput) {
            modalPersonsInput.value = quantity;
        }

        if (modalTotalInput) {
            modalTotalInput.value = total;
        }

        if (textarea) {
            textarea.value = `I would like to book the tour "${tourTitle}" for ${quantity} person(s). Total price: ${formattedTotal}. Please contact me with more information.`;
        }

    });

});
