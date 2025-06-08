// Lightbox para fotos de reseñas

    document.addEventListener('DOMContentLoaded', () => {
    const modal       = document.getElementById('image-modal');
    const modalImg    = document.getElementById('modal-img');
    const btnClose    = document.getElementById('modal-close');
    const btnPrev     = document.getElementById('modal-prev');
    const btnNext     = document.getElementById('modal-next');

    let currentPhotos = [];
    let currentIndex  = 0;

    document.querySelectorAll('.review-photos').forEach(container => {
    const photos = JSON.parse(container.dataset.photos);
    container.querySelectorAll('img[data-index]').forEach(img => {
    img.addEventListener('click', () => {
    currentPhotos = photos;
    currentIndex  = parseInt(img.dataset.index, 10);
    modalImg.src  = currentPhotos[currentIndex];
    modal.classList.remove('hidden');
});
});
});

    btnClose.addEventListener('click', () => modal.classList.add('hidden'));
    btnPrev.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + currentPhotos.length) % currentPhotos.length;
    modalImg.src = currentPhotos[currentIndex];
});
    btnNext.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % currentPhotos.length;
    modalImg.src = currentPhotos[currentIndex];
});

    modal.addEventListener('click', e => {
    if (e.target === modal) modal.classList.add('hidden');
});
    // --- Navegación por teclado ---
    document.addEventListener('keydown', e => {
    if (modal.classList.contains('hidden')) return;

    switch (e.key) {
    case 'ArrowLeft':
    btnPrev.click();
    break;
    case 'ArrowRight':
    btnNext.click();
    break;
    case 'Escape':
    btnClose.click();
    break;
}
});
});
