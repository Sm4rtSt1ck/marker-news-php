document.addEventListener("DOMContentLoaded", function() {
    var subscribeBtn = document.getElementById('subscribe-btn');
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', function() {
            var subscriptionType = "";
            var subscriptionTarget = "";
            if (subscribeBtn.hasAttribute('data-user-id')) {
                subscriptionType = "user";
                subscriptionTarget = subscribeBtn.getAttribute('data-user-id');
            } else if (subscribeBtn.hasAttribute('data-media-id')) {
                subscriptionType = "media";
                subscriptionTarget = subscribeBtn.getAttribute('data-media-id');
            } else {
                alert("Неверные параметры подписки.");
                return;
            }
            
            var subscriptionId = subscribeBtn.getAttribute('data-subscription-id');
            if (subscriptionId !== null && subscriptionId !== "") {
                if (!subscriptionId) {
                    alert("Неверные параметры для отписки.");
                    return;
                }
                var formData = new FormData();
                formData.append('subscription_id', subscriptionId);
                
                fetch('/subscriptions/unsubscribe', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        subscribeBtn.textContent = "Подписаться";
                        subscribeBtn.removeAttribute('data-subscription-id');
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
            } else {
                var formData = new FormData();
                formData.append('subscription_type', subscriptionType);
                formData.append('subscription_target', subscriptionTarget);
                
                fetch('/subscribe', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        subscribeBtn.textContent = "Отписаться";
                        if (data.subscription_id) {
                            subscribeBtn.setAttribute('data-subscription-id', data.subscription_id);
                        }
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
            }
        });
    }
});
