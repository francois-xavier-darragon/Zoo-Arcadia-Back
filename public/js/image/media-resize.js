function adjustCardSize() {
    document.addEventListener('DOMContentLoaded', function() {
        const containers = document.querySelectorAll('.media-container');

        function adjustSize() {
            
            if (containers.length === 1) {
                const img = document.querySelector('.media-container img');
                const cardDescription = document.querySelector('.card-description');
                
                if (img && cardDescription) {
                    const imgHeight = img.offsetHeight;
                    cardDescription.style.height = `${imgHeight}px`;
                }
            } 

            else {
                containers.forEach(container => {
                    const img = container.querySelector('img');
                    const cardDescription = container.closest('.row').querySelector('.card-description');
                    
                    if (img && cardDescription) {
                        const imgHeight = img.offsetHeight;
                        cardDescription.style.height = `${imgHeight}px`;
                    }
                });
            }
        }

        if (containers.length === 1) {
            const img = document.querySelector('.media-container img');
            img.onload = adjustSize;
        } else {
            containers.forEach(container => {
                const img = container.querySelector('img');
                img.onload = adjustSize;
            });
        }
        
        window.addEventListener('resize', adjustSize);
        adjustSize();
    });
}