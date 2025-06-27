const addressIdHiddenField = document.getElementById('other_form_address_id');
const addressCards = document.querySelectorAll('.address-card');
const submitButton = document.querySelector('#submit-button');


addressCards.forEach(card => {
    card.addEventListener('click', () => checkInputs(card));
})

// Initial check for already selected card
addressCards.forEach(card => {
    const input = card.querySelector('input[name="address_id"]');
    if(input.checked){
        card.classList.add('selected', 'border-primary');
        updateAddressInCart(input.dataset.id).then( ()=> submitButton.disabled = false );
    }
});

function checkInputs(clickedCard){
    const clickedInput = clickedCard.querySelector('input[name="address_id"]');

    clickedInput.checked = true;
    updateAddressInCart(clickedInput.dataset.id);

    clickedCard.classList.add('selected', 'border-primary');

    addressCards.forEach(card => {
        const input = card.querySelector('input[name="address_id"]');
        if(card !== clickedCard){
            input.checked = false;
            card.classList.remove('selected', 'border-primary');
        }
    })
}

async function updateAddressInCart(id){
    const response = await fetch(`${url}/api/v2/carts/set-address/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ addressId: id })
    });
}
