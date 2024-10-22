function initializeCarousel(options = {}) {
    const {
        carousel: carouselId,
        cardSelector : cardSelector,
        columnClasses = {         
            xs: 'col-12',
            sm: 'col-sm-6',
            md: 'col-md-4',
            lg: 'col-lg-3'
        },
        breakpoints = {           
            sm: 576,
            md: 768,
            lg: 992
        },
        itemsPerBreakpoint = {    
            xs: 1,
            sm: 2,
            md: 3,
            lg: 4
        },
        itemClasses = ''
    } = options;

    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.querySelector(`#${carouselId}`);
        if (!carousel) {
            console.error(`Carousel with id ${carouselId} not found`);
            return;
        }

        const carouselInner = carousel.querySelector('.carousel-inner');
        if (!carouselInner) {
            console.error('Carousel inner element not found');
            return;
        }

        const allCards = Array.from(carousel.querySelectorAll(cardSelector));
        if (!allCards.length) {
            console.error(`No cards found in carousel: ${cardSelector}`);
            return;
        }

        function adjustCarousel() {
            const width = window.innerWidth;
            let itemsPerSlide, colClass;

            if (width < breakpoints.sm) {
                itemsPerSlide = itemsPerBreakpoint.xs;
                colClass = columnClasses.xs;
            } else if (width < breakpoints.md) {
                itemsPerSlide = itemsPerBreakpoint.sm;
                colClass = columnClasses.sm;
            } else if (width < breakpoints.lg) {
                itemsPerSlide = itemsPerBreakpoint.md;
                colClass = columnClasses.md;
            } else {
                itemsPerSlide = itemsPerBreakpoint.lg;
                colClass = columnClasses.lg;
            }

            carouselInner.innerHTML = '';

            let slides = [];
            let currentSlide = [];
            let previousSlide = [];

            allCards.forEach((card, index) => {
                currentSlide.push(card);

                if (currentSlide.length === itemsPerSlide || index === allCards.length - 1) {
                    if (currentSlide.length < itemsPerSlide && previousSlide.length) {
                        const missingCount = itemsPerSlide - currentSlide.length;
                        currentSlide = currentSlide.concat(previousSlide.slice(0, missingCount));
                    }
                    slides.push(currentSlide);
                    previousSlide = currentSlide;
                    currentSlide = [];
                }
            });

            slides.forEach((slideCards, i) => {
                const slide = document.createElement('div');
                slide.className = 'carousel-item' + (i === 0 ? ' active' : '');
                slide.innerHTML = '<div class="row justify-content-center"></div>';
                const row = slide.querySelector('.row');

                slideCards.forEach(card => {
                    const col = document.createElement('div');
                    col.className = `${itemClasses} ${colClass}`;
                    const cardClone = card.cloneNode(true);
                    cardClone.style.width = itemsPerSlide === 1 ? 'auto' : '100%';
                    col.appendChild(cardClone);
                    row.appendChild(col);
                });

                carouselInner.appendChild(slide);
            });

            const bsCarousel = bootstrap.Carousel.getInstance(carousel);
            if (bsCarousel) {
                bsCarousel.dispose();
            }
            new bootstrap.Carousel(carousel);
        }

        window.addEventListener('resize', adjustCarousel);
        adjustCarousel();
    });
}