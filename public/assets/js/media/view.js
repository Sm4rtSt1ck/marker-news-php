function showFullDescription(event) {
    event.preventDefault();
    document.getElementById('desc-short').style.display = 'none';
    document.getElementById('desc-full').style.display = 'block';
  }
  
  function hideFullDescription(event) {
    event.preventDefault();
    document.getElementById('desc-full').style.display = 'none';
    document.getElementById('desc-short').style.display = 'block';
  }
  
  // Subscribe/ubsubscribe
  document.addEventListener("DOMContentLoaded", function() {
    var subscribeBtn = document.getElementById('subscribe-btn');
    if (subscribeBtn) {
      subscribeBtn.addEventListener('click', function() {
        if (subscribeBtn.textContent.trim() === 'Подписаться') {
          subscribeBtn.textContent = 'Отписаться';
        } else {
          subscribeBtn.textContent = 'Подписаться';
        }
      });
    }
  });
  