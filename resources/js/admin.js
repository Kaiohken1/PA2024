const priceInput = document.getElementById('price');
const flexPriceCheckbox = document.getElementById('flexPrice');

flexPriceCheckbox.addEventListener('change', function() {
    if (this.checked) {
        priceInput.disabled = true;
        priceInput.value = '';
    } else {
        priceInput.disabled = false;
    }
});
