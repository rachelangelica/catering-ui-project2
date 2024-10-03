document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const orderItems = document.querySelectorAll('.order-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const status = button.getAttribute('data-status');

            orderItems.forEach(item => {
                if (status === 'all' || item.getAttribute('data-status') === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});