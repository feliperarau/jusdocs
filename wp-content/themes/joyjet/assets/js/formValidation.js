import $ from "jquery";
/**
 * Check if string has numbers
 * @param {*} str string to check
 * @returns bool
 */
export const hasNumber = (str) => {
  return /\d/.test(str);
};

/**
 * Check if string has lowercase
 * @param {*} str string to check
 * @returns bool
 */
export const lowerCaseCheck = (str) => {
  var regex = /^(?=.*[a-z])/;

  return regex.test(str);
};

/**
 * Check if string has uppercase
 * @param {*} str string to check
 * @returns bool
 */
export const upperCaseCheck = (str) => {
  var regex = /^(?=.*[A-Z])/;

  return regex.test(str);
};

/**
 * Validates user password for length and equality to confirmation.
 *
 * @param {int} minLength password min length
 * @param {string} newPw new password
 * @param {string} newPwConfirm new password confirmation
 * @param {string} currentPassword user current password (if applied)
 * @returns bool
 */
export const checkPassword = (
  minLength,
  newPw,
  newPwConfirm,
  currentPassword
) => {
  var valid = true;

  // If has current Password Field
  if (currentPassword !== false) {
    if (currentPassword.length === 0) {
      return false;
    }
  }

  // If the password attends the minimum length
  if (newPw.length < minLength) {
    return false;
  }

  // If is the same PW though confirmation field
  if (newPw !== newPwConfirm) {
    return false;
  }

  return valid;
};
/**
 * Apply validation class to form fields
 * @param {string} $condition valid or invalid
 * @param  {...any} fields fields to validate
 */
export const validate = ($condition, ...fields) => {
  var validClass = "is-valid";
  var invalidClass = "is-invalid";

  for (let e = 0; e < fields.length; e++) {
    const field = $(fields[e]);

    // Reset field validation
    field.removeClass(invalidClass);
    field.removeClass(validClass);

    if ($condition === "valid") {
      field.removeClass(invalidClass);
      field.addClass(validClass);
    } else {
      field.removeClass(validClass);
      field.addClass(invalidClass);
    }
  }
};
