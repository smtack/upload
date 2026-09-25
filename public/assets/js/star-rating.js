document.addEventListener('DOMContentLoaded', () => {
    const starForm = document.getElementById('starForm');

    if (!starForm) return;

    starForm.addEventListener('change', (e) => {
        if (!e.target.classList.contains('radio-input')) return;

        const formData = new FormData(starForm);
        const queryParams = new URLSearchParams(formData).toString();
        const requestURL = `${starForm.action}?${queryParams}`;

        fetch(requestURL, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Rating updated: ', data.message);
            } else {
                console.log('Failed to submit rating: ', data.message);
            }
        })
        .catch(error => console.error('AJAX Error:', error));
    });
});