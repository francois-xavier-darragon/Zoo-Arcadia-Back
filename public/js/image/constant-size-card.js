document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        let globalMaxHeight = 0;
        
        function calculateAndApplyMaxHeight() {
            const oldStyle = document.querySelector('style[data-card-height]');
            if (oldStyle) oldStyle.remove();

            document.querySelectorAll('.carousel-item').forEach(item => {
                item.classList.add('d-flex');
                item.style.visibility = 'hidden';
                item.style.position = 'absolute';
            });

            document.querySelectorAll('.card-enclose').forEach(card => {
                card.style.height = '';
            });

            document.querySelectorAll('.card-enclose').forEach(card => {
                const height = card.getBoundingClientRect().height;
                globalMaxHeight = Math.max(globalMaxHeight, height);
            });

            const style = document.createElement('style');
            style.setAttribute('data-card-height', ''); 
            style.innerHTML = `.card-enclose { height: ${globalMaxHeight}px !important; }`;
            document.head.appendChild(style);

            document.querySelectorAll('.carousel-item').forEach(item => {
                item.classList.remove('d-flex');
                item.style.visibility = '';
                item.style.position = '';
            });

        }

        const cardImages = Array.from(document.querySelectorAll('.card-img-top'));
        const imagePromises = cardImages.map(img => {
            return new Promise((resolve) => {
                if (img.complete) resolve();
                else {
                    img.onload = () => resolve();
                    img.onerror = () => resolve();
                }
            });
        });

        Promise.all(imagePromises).then(() => {
            calculateAndApplyMaxHeight();
        });

        document.querySelector('#enclosure-carousel').addEventListener('slid.bs.carousel', calculateAndApplyMaxHeight);

        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                globalMaxHeight = 0;
                calculateAndApplyMaxHeight();
            }, 250);
        });
    }, 100);
});