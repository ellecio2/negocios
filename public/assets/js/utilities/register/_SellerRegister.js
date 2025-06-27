$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip({
        container: '#tooltip-link'
    });
});

const rncInput = document.getElementById('rnc_input');
const cedulaInput = document.getElementById('cedula_input');
const form = document.getElementById('registerForm');

const checkbox = form.querySelector('#is-physical-person');
const rncGroup = form.querySelector('#rnc_group');
const name = form.querySelector('#name_user');
const shopName = form.querySelector('#name');

const rncFileInput = form.querySelector('#registro_mercantil')
const cedulaFileInput = form.querySelector('#cedula_photo')

const invalidFeedBackCedula = form.querySelector('#invalid-feedback-cedula');
const invalidFeedBackRnc = form.querySelector('#invalid-feedback-rnc');

checkbox.addEventListener('change', function() {
    if(this.checked){
        rncGroup.classList.add('d-none');
        if(shopName.value.length === 0){
            shopName.value = name.value;
        }
        rncInput.required = false;
        rncFileInput.required = false;
    }else{
        rncGroup.classList.remove('d-none');
        rncInput.required = true;
        rncFileInput.required = true;
        shopName.value = '';
    }
});

form.addEventListener('submit', function(e) {
    e.preventDefault();

    if(cedulaFileInput.files.length === 0){
        invalidFeedBackCedula.classList.remove('d-none');
        console.log('entra al if cuando cedula file esta vacio')
        return;
    }else{
        invalidFeedBackCedula.classList.add('d-none');
    }

    if(!checkbox.checked){
        if(rncFileInput.files.length === 0){
            invalidFeedBackRnc.classList.remove('d-none');
            console.log('entra al if cuando rnfile esta vacio')
            return;
        } else {
            invalidFeedBackRnc.classList.add('d-none');
        }
    }

    this.submit();
});

