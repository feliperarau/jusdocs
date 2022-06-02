const submitButton = document.getElementById('valelform');
const validatedInput = document.getElementById('form-field-email');

function allUpper() {
  let isValid = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/.test(validatedInput.value);

  if (isValid) {
    submitButton.removeAttribute('disabled');
  } else {
    submitButton.setAttribute('disabled', '');
  }
}

validatedInput.addEventListener('input', allUpper);