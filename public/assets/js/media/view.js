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

  