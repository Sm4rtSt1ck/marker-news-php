document.addEventListener("DOMContentLoaded", function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const subscriptionItems = document.querySelectorAll('.subscription-item');
    
    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            subscriptionItems.forEach(function(item) {
                if (filter === "all" || item.getAttribute('data-type') === filter) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    const unsubscribeButtons = document.querySelectorAll('.unsubscribe-btn');
    unsubscribeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const subscriptionId = button.getAttribute('data-subscription-id');
            if (!confirm("Вы действительно хотите отписаться?")) return;
            
            const formData = new FormData();
            formData.append('action', 'unsubscribe');
            formData.append('subscription_id', subscriptionId);
            
            fetch('/subscriptions/unsubscribe', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Удаляем элемент из списка
                    button.parentElement.remove();
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
