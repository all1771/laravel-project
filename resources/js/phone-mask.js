export function initPhoneMask(selector = 'input[name="phone"], input[name="customer_phone"]') {
  document.querySelectorAll(selector).forEach((input) => {
    input.addEventListener('input', (e) => {
      let v = e.target.value.replace(/\D/g, '');
      if (v.startsWith('8') || v.startsWith('7')) v = v.slice(1);
      v = v.slice(0, 10);
      let s = '+7';
      if (v.length > 0) s += ' (' + v.slice(0, 3);
      if (v.length >= 3) s += ') ' + v.slice(3, 6);
      if (v.length >= 6) s += '-' + v.slice(6, 8);
      if (v.length >= 8) s += '-' + v.slice(8, 10);
      e.target.value = s;
    });
    input.addEventListener('focus', (e) => {
      if (e.target.value === '') e.target.value = '+7 (';
    });
    input.addEventListener('blur', (e) => {
      if (e.target.value === '+7 (' || e.target.value === '+7') e.target.value = '';
    });
  });
}
