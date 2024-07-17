function manageWidthSelect(selects) {
    document.addEventListener('DOMContentLoaded', function() {
        let maxWidth = 0;
    
        selects.forEach(select => {
            select.style.width = 'auto';
            const width = select.offsetWidth;
            if (width > maxWidth) {
                maxWidth = width;
            }
        });
    
        selects.forEach(select => {
            select.style.width = `${maxWidth}px`;
        });
    });
}