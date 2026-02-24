document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('contact_phone');

    const iti = window.intlTelInput(phoneInput, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
        initialCountry: "cr",          // País por defecto (cámbialo según tu audiencia)
        preferredCountries: ["cr", "mx", "ar", "co", "us"], // Aparecen arriba en la lista
        separateDialCode: true,        // Muestra el código separado del input
    });
phoneInput.addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
    }
});


phoneInput.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
});
    const form = phoneInput.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            const fullNumber = iti.getNumber();
            document.getElementById('contact_phone_full').value = fullNumber;
        });
    }
    
});
