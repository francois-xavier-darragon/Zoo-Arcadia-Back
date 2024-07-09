import { btnDnone, newImage, removeExistingImage } from './image-management.js';

export function addImgeFilds(entityId, existingImages, defaultImagePath, divSelected, entityName, filedsName) {
    document.addEventListener('DOMContentLoaded', function() {
        const addImageBtn = document.getElementById('add-image-btn');
        const imageFieldsList = document.getElementById('image-fields-list');
        const errorMessage = document.getElementById('image-error-message');
        const errorBloc = document.getElementById('image-error-bloc');
    
        let index = imageFieldsList.children.length;
        const template = document.getElementById('image-field-template')
        const maxImages = 8
    
        let currentImageCount = existingImages.length;

        if (currentImageCount > 0) {
           index = currentImageCount;
        }
    
        addImageBtn.addEventListener('click', function() {
             if (index < maxImages) {
                const newImageField = createImageField(index, defaultImagePath);
                imageFieldsList.appendChild(newImageField);
                index++;
                errorMessage.textContent = '';
                errorBloc.classList.add('d-none')
            } else {
                errorBloc.classList.remove('d-none')
                errorMessage.innerHTML = `<em>Vous ne pouvez pas ajouter plus de 8 images !</em>`;
            }
        });
    
        if (currentImageCount > 0) {
            existingImages.forEach(function(image, idx) {
                const imagePath = image.path ? image.path : defaultImagePath;
                const imageField = createImageField(idx, imagePath);
                imageFieldsList.appendChild(imageField);
            });
        } else {
            const defaultImageField = createImageField(index, defaultImagePath);
            imageFieldsList.appendChild(defaultImageField);
            index++;
        }
    
        function createImageField(index, imagePath) {
            const clone = template.content.cloneNode(true);
    
            const img = clone.querySelector('img');
            const input = clone.querySelector('input[type="file"]');
            const label = clone.querySelector('label');
            const button = clone.querySelector('button');
    
            img.src = imagePath;
            img.id = `balise-Img-${index}`;
            input.name = `${entityName}[images][${index}][${filedsName}]`;
            label.setAttribute('id', `edit-image-button-${index}`);
            button.setAttribute('id', `remove-image-button-${index}`);
            
            const btnTrash = clone.getElementById(`remove-image-button-${index}`);
            const btnEdit = clone.getElementById(`edit-image-button-${index}`);

            if(entityId === null ){
                btnTrash.classList.add('d-none');
            } else if(index < existingImages.length ) {
                btnEdit.classList.add('d-none');
            } else if(index >= existingImages.length) {
                btnTrash.classList.add('d-none');
            }
         
            if(divSelected) {
                divSelected.classList.add('d-none');
            }

            return clone;
        }
    });
}

