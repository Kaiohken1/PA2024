const priceInput = document.getElementById('price');
const flexPriceCheckbox = document.getElementById('flexPrice');

if (flexPriceCheckbox.value == 1) {
    flexPriceCheckbox.checked = true;
    priceInput.disabled = true;
}

flexPriceCheckbox.addEventListener('change', function() {
    if (this.checked) {
        priceInput.disabled = true;
        priceInput.value = '';
    } else {
        priceInput.disabled = false;
    }
});
