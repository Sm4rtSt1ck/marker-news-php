function enableEditing(sectionId) {
  var inputs = document.querySelectorAll('#' + sectionId + ' input, #' + sectionId + ' select, #' + sectionId + ' textarea');
  inputs.forEach(function(input) {
    input.disabled = false;
  });
  document.getElementById(sectionId + '-edit-btn').classList.add('hidden');
  document.getElementById(sectionId + '-save-btn').classList.remove('hidden');
  document.getElementById(sectionId + '-cancel-btn').classList.remove('hidden');
}
function cancelEditing(sectionId) {
  location.reload();
}