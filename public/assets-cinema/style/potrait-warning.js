function updateOrientation() {
    const isPortrait = window.innerHeight > window.innerWidth;
    const warning = document.getElementById('orientation-warning');
    const content = document.getElementById('page');

    if (isPortrait) {
        warning.style.display = 'flex';
        content.style.display = 'none';
    } else {
        warning.style.display = 'none';
        content.style.display = 'block';
    }
}

window.addEventListener('load', updateOrientation);
window.addEventListener('resize', updateOrientation);
window.addEventListener('orientationchange', updateOrientation);