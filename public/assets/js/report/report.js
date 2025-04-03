document.addEventListener("DOMContentLoaded", function() {
    var descField = document.getElementById('description');
    var warning = document.getElementById('description-warning');
    var max = parseInt(descField.getAttribute('data-maxlength'), 10);
    
    descField.addEventListener('input', function() {
      if (descField.value.length >= max) {
        warning.textContent = 'Максимальная длина в ' + max + ' символов достигнута';
        warning.style.display = 'block';
      } else {
        warning.textContent = '';
        warning.style.display = 'none';
      }
    });
  });
  