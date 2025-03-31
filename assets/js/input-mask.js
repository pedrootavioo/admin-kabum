import InputMask from 'inputmask';

// CPF
Inputmask({
    mask: '999.999.999-99',
    placeholder: '',
    clearIncomplete: false,
    greedy: false
}).mask(document.querySelectorAll('.cpf-mask'));

// DATES
InputMask({ mask: '99/99/9999' }).mask(document.querySelectorAll('.date-mask'));

// PHONE
Inputmask({
    mask: ['(99) 9999-9999', '(99) 99999-9999'],
    keepStatic: true,
    placeholder: '',
    clearIncomplete: true
}).mask(document.querySelectorAll('.phone-mask'));

// ZIPCODE
Inputmask({
    mask: '99999-999',
    placeholder: '',
    clearIncomplete: false,
    greedy: false
}).mask(document.querySelectorAll('.zipcode-mask'));