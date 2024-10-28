function manageWidthSelect(selects) {
    document.addEventListener('DOMContentLoaded', function() {
        function adjustSelectWidth() {
            let maxWidth = 0;
            
            selects.forEach(select => {
                select.style.width = 'auto';
                maxWidth = Math.max(maxWidth, select.offsetWidth);
            });

            const screenWidth = window.innerWidth;
            if (screenWidth <= 1122) {
                maxWidth = Math.min(maxWidth, 150);
            } else if (screenWidth < 992) { 
                maxWidth = Math.min(maxWidth, 180);
            }

            selects.forEach(select => {
                select.style.width = `${maxWidth}px`;
            });

        }

        adjustSelectWidth();

        window.addEventListener('resize', adjustSelectWidth);
    });
}