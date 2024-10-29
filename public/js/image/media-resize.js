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

function adjustElementSizes() {
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.animal-button');
        const details = document.querySelectorAll('.animal-detail');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const animalId = this.getAttribute('data-animal-id');
                const firstMediaContainer = document.querySelector('.media-container');
                const firstCardDescription = document.querySelector('.card-description');

                details.forEach(detail => {
                    if (detail.id === `animal-${animalId}`) {
                        detail.classList.remove('d-none');
                        setTimeout(() => {
                            const visibleMediaContainer = detail.querySelector('.media-container');
                            const visibleCardDescription = detail.querySelector('.card-description');
                            
                            if (visibleMediaContainer && visibleCardDescription) {
                                visibleMediaContainer.style.width = `${firstMediaContainer.offsetWidth}px`;
                                visibleMediaContainer.style.height = `${firstMediaContainer.offsetHeight}px`;
                                visibleCardDescription.style.width = `${firstCardDescription.offsetWidth}px`;
                                visibleCardDescription.style.height = `${firstCardDescription.offsetHeight}px`;
                            }
                        }, 0);
                    } else {
                        detail.classList.add('d-none');
                    }
                });

                fetch(`/animal/${animalId}`, { method: 'POST' });
            });

            window.addEventListener('resize', adjustSizes);
        });
    });
}