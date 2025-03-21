document.addEventListener("DOMContentLoaded", function() {
    const reactionButton = document.getElementById('reaction-button');
    const reactionOptions = document.getElementById('reaction-options');
  
    
if (reactionButton && reactionOptions) {
      reactionButton.addEventListener('click', function() {
        if (reactionOptions.style.display === 'none' || reactionOptions.style.display === '') {
          reactionOptions.style.display = 'block';
        } else {
          reactionOptions.style.display = 'none';
        }
      });
    }
  
    
const reactionMapping = {
      like: "#33cc33",      
      dislike: "#000000",   
      angry: "#ff0000",     
      surprised: "#ffff00", 
      happy: "#0000ff"      
};
  
    
const reactionOptionButtons = document.querySelectorAll('.reaction-option');
    reactionOptionButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        const postId = button.getAttribute('data-post-id');
        const reaction = button.getAttribute('data-reaction');
  
        const formData = new FormData();
        formData.append('action', 'like');
        formData.append('post_id', postId);
        formData.append('reaction', reaction);
  
        fetch('/post/interaction', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            
        reactionButton.style.backgroundColor = lightenColor(data.reaction_color, 20);
        reactionButton.style.border = "4px solid " + reactionMapping[reaction];
        reactionOptions.style.display = 'none';
          } else if (data.error) {
            alert(data.error);
          }
        })
        .catch(error => {
          console.error('Ошибка:', error);
        });
      });
    });
  
    
});
  
  
function lightenColor(hex, percent) {
    hex = hex.replace(/^#/, '');
    var num = parseInt(hex, 16),
        r = (num >> 16) + Math.round(2.55 * percent),
        g = (num >> 8 & 0x00FF) + Math.round(2.55 * percent),
        b = (num & 0x0000FF) + Math.round(2.55 * percent);
    return '#' + (
      0x1000000 +
      (r < 255 ? (r < 1 ? 0 : r) : 255) * 0x10000 +
      (g < 255 ? (g < 1 ? 0 : g) : 255) * 0x100 +
      (b < 255 ? (b < 1 ? 0 : b) : 255)
    ).toString(16).slice(1);
  }
  
  
    
    const commentText = document.getElementById('comment-text');
    const submitComment = document.getElementById('submit-comment');
    const commentWarning = document.getElementById('comment-warning');
    const maxCommentLength = parseInt(commentText.getAttribute('data-maxlength'), 10);
  
    commentText.addEventListener('input', function() {
      if (commentText.value.trim().length > 0) {
        submitComment.disabled = false;
      } else {
        submitComment.disabled = true;
      }
      if (commentText.value.length >= maxCommentLength) {
        commentWarning.textContent = 'Максимальная длина в ' + maxCommentLength + ' символов достигнута';
        commentWarning.style.display = 'block';
      } else {
        commentWarning.textContent = '';
        commentWarning.style.display = 'none';
      }
    });
  
    submitComment.addEventListener('click', function() {
      const postId = submitComment.getAttribute('data-post-id');
      const comment = commentText.value.trim();
      if (comment.length === 0) return;
      
      const formData = new FormData();
      formData.append('action', 'comment');
      formData.append('post_id', postId);
      formData.append('comment_text', comment);
      
      fetch('/post/interaction', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const commentData = data.comment;
          const commentDiv = document.createElement('div');
          commentDiv.className = 'comment';
          commentDiv.setAttribute('data-comment-id', commentData.comment_id);
          commentDiv.innerHTML = `
            <div class="comment-author">
              <img src="${commentData.avatar_url ? commentData.avatar_url : '/assets/images/default_avatar.png'}" alt="Аватар" class="avatar-small">
              <span class="comment-author-name">${commentData.author_name}</span>
            </div>
            <div class="comment-text">${commentData.text}</div>
            <div class="comment-date">${commentData.created_at}</div>
          `;
          const commentsList = document.getElementById('comments-list');
          commentsList.insertAdjacentElement('afterbegin', commentDiv);
          commentText.value = '';
          submitComment.disabled = true;
        } else if (data.error) {
          alert(data.error);
        }
      })
      .catch(error => {
        console.error('Ошибка:', error);
      });
    });
  
  function lightenColor(hex, percent) {
    hex = hex.replace(/^#/, '');
    var num = parseInt(hex, 16),
        r = (num >> 16) + Math.round(2.55 * percent),
        g = (num >> 8 & 0x00FF) + Math.round(2.55 * percent),
        b = (num & 0x0000FF) + Math.round(2.55 * percent);
    return '#' + (
      0x1000000 +
      (r < 255 ? (r<1?0:r) : 255) * 0x10000 +
      (g < 255 ? (g<1?0:g) : 255) * 0x100 +
      (b < 255 ? (b<1?0:b) : 255)
    ).toString(16).slice(1);
  }
  