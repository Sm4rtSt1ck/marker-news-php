function showFullBio(event) {
    event.preventDefault();
    document.getElementById('bio-short').style.display = 'none';
    document.getElementById('bio-full').style.display = 'block';
  }
  
  function hideFullBio(event) {
    event.preventDefault();
    document.getElementById('bio-full').style.display = 'none';
    document.getElementById('bio-short').style.display = 'block';
  }

function toggleSubscribe(profileUserId) {
  const btn = document.getElementById('subscribe-btn');
  if (btn.textContent === 'Подписаться') {
    btn.textContent = 'Отписаться';
  } else {
    btn.textContent = 'Подписаться';
  }
}
