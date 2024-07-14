import { btnDnone, newImage, removeExistingImage } from './image-management.js';

export function addImgeFilds(existingImages, defaultImagePath, divSelected, entityName, filedsName) {
    document.addEventListener('DOMContentLoaded', function() {
        const addImageBtn = document.getElementById('add-image-btn');
        const imageFieldsList = document.getElementById('image-fields-list');
        const errorMessage = document.getElementById('image-error-message');
        const errorBloc = document.getElementById('image-error-bloc');
    
        let index = imageFieldsList.children.length;
        const template = document.getElementById('image-field-template')
        const maxImages = 8
        let imgId = null;

        let currentImageCount = existingImages.length;

        if (currentImageCount > 0) {
           index = currentImageCount;
        }
    
        const animalDataElement = document.getElementById('animal-data');
        const animalId = animalDataElement.dataset.animalId;
        let url = animalDataElement.dataset.removeImageUrl;
        url = url.replace('ANIMAL_ID', animalId);

        addImageBtn.addEventListener('click', function() {
             if (index < maxImages) {
                const newImageField = createImageField(index, defaultImagePath, imgId);
                imageFieldsList.appendChild(newImageField);
                index++;
                errorMessage.textContent = '';
                errorBloc.classList.add('d-none')
            } else {
                errorBloc.classList.remove('d-none')
                addImageBtn.classList.add('d-none')
                errorMessage.innerHTML = `<em>Vous avez atteint le nombre maximum d'images téléchargeable ! !</em>`;
            }
        });
    
        if (currentImageCount > 0) {
            existingImages.forEach(function(image, idx) {
                const imagePath = image.path ? image.path : defaultImagePath;
                imgId = image.id ? image.id :null;
                const imageField = createImageField(idx, imagePath, imgId);
                imageFieldsList.appendChild(imageField);
            });
        } else {
            const defaultImageField = createImageField(index, defaultImagePath, imgId);
            imageFieldsList.appendChild(defaultImageField);
            index++;
        }
    
        function createImageField(index, imagePath, imgId) {
            const clone = template.content.cloneNode(true);
            const img = clone.querySelector('img');
            const input = clone.querySelector('input[type="file"]');
            const label = clone.querySelector('label');
            const button = clone.querySelector('button');
   
            img.src = imagePath;
           
            img.id = `balise-Img-${index}`;
            img.setAttribute('data-img-id', imgId)
            input.name = `${entityName}[images][${index}][${filedsName}]`;
            label.setAttribute('id', `edit-image-button-${index}`);
            button.setAttribute('id', `remove-image-button-${index}`);
            
            const btnTrash = clone.getElementById(`remove-image-button-${index}`);
            const btnEdit = clone.getElementById(`edit-image-button-${index}`);
            newImage(btnEdit, img)
            removeExistingImage(btnTrash, animalId, url, defaultImagePath, btnEdit, img)

            if(animalId === null){
                btnDnone(btnTrash)
            } else if(index < existingImages.length ) {
                btnDnone(btnEdit)
            } else if(index >= existingImages.length) {
                btnDnone(btnTrash)
            }
         
            if(divSelected) {
                divSelected.classList.add('d-none');
            }

            return clone;
        }
    });
}
