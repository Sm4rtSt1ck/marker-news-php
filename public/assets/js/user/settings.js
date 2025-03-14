// Max length of field checker
document.addEventListener("DOMContentLoaded", function() {
    var fields = document.querySelectorAll('input[data-maxlength], textarea[data-maxlength]');
    fields.forEach(function(field) {
      var warning = document.createElement('div');
      warning.className = 'max-length-warning';
      warning.style.display = 'none';
      field.parentNode.insertBefore(warning, field.nextSibling);
      
      field.addEventListener('input', function() {
        var max = parseInt(field.getAttribute('data-maxlength'), 10);
        if (field.value.length >= max) {
          warning.textContent = 'Максимальная длина - ' + max + ' символов';
          warning.style.display = 'block';
        } else {
          warning.textContent = '';
          warning.style.display = 'none';
        }
      });
    });
  });
  