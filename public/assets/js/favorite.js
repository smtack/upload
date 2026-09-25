document.addEventListener('DOMContentLoaded', () => {
    const favoriteBtn = document.getElementById('favoriteBtn');
    const favoriteIcon = document.getElementById('favoriteIcon');

    if (!favoriteBtn) return;

    favoriteBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        try {
            const response = await fetch(favoriteBtn.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                favoriteBtn.dataset.favorited = data.is_favorited ? 'true' : 'false';

                favoriteIcon.src = data.is_favorited
                    ? favoriteBtn.dataset.unfavoriteIcon
                    : favoriteBtn.dataset.favoriteIcon;
            } else {
                console.log('Failed to favorite', data.message);
            }
        } catch (error) {
            console.error('AJAX Error: ', error);
        }
    });
});