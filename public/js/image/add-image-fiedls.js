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
        
        let entityDataElement;
        let entityId;
        let url;

        switch(entityName) {
            case 'animal':
                entityDataElement = document.getElementById('animal-data');
                entityId = entityDataElement.dataset.animalId;
                url = entityDataElement.dataset.removeImageUrl.replace('ANIMAL_ID', entityId);
                break;
            case 'habitat':
                entityDataElement = document.getElementById('habitat-data');
                entityId = entityDataElement.dataset.habitatId;
                url = entityDataElement.dataset.removeImageUrl.replace('HABITAT_ID', entityId);
                break;
            case 'enclosure':
                entityDataElement = document.getElementById('enclosure-data');
                entityId = entityDataElement.dataset.enclosureId;
                url = entityDataElement.dataset.removeImageUrl.replace('ENCLOSURE_ID', entityId);
                break;    
            case 'service':
                entityDataElement = document.getElementById('service-data');
                entityId = entityDataElement.dataset.serviceId;
                url = entityDataElement.dataset.removeImageUrl.replace('SERVICE_ID', entityId);
                break;
        }
        
        addImageBtn.addEventListener('click', function() {
             if (index < maxImages) {
                const newImageField = createImageField(index, defaultImagePath, imgId);
                imageFieldsList.appendChild(newImageField);
                index++;
                ensureUniqueImgIds();  
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
                imgId = image.id ? image.id : idx;
                const imageField = createImageField(idx, imagePath, imgId);
                imageFieldsList.appendChild(imageField);
            });
            ensureUniqueImgIds();
        } else {
            const defaultImageField = createImageField(index, defaultImagePath, imgId);
            imageFieldsList.appendChild(defaultImageField);
            index++;
        }
    
        function createImageField(index, imagePath, imgId) {
            const clone = template.content.cloneNode(true);
            const div = clone.querySelector('.image-card');
            const img = clone.querySelector('img');
            const input = clone.querySelector('input[type="file"]');
            const label = clone.querySelector('label');
            const button = clone.querySelector('button');
        
            img.src = imagePath;
           
            img.id = `balise-Img-${index}`;
            div.id = `div-Img-${index}`;

            img.setAttribute('data-img-id', imgId || index)
            input.name = `${entityName}[images][${index}][${filedsName}]`;
            label.setAttribute('id', `edit-image-button-${index}`);
            button.setAttribute('id', `remove-image-button-${index}`);
            
            const btnTrash = clone.getElementById(`remove-image-button-${index}`);
            const btnEdit = clone.getElementById(`edit-image-button-${index}`);
            newImage(btnEdit, img)
            ensureUniqueImgIds();

            switch(entityName) {
                case 'animal':
                    removeExistingImage(btnTrash, entityId, url, defaultImagePath, btnEdit, img, div);
                    break;
                case 'habitat':
                    removeExistingImage(btnTrash, entityId, url, defaultImagePath, btnEdit, img, div);
                    break;
                case 'enclosure':
                    removeExistingImage(btnTrash, entityId, url, defaultImagePath, btnEdit, img, div);
                    break;    
                case 'service':
                    removeExistingImage(btnTrash, entityId, url, defaultImagePath, btnEdit, img, div);
                    break;
            }
           
            if(img.src == "/images/default/default-750x500.png") {
                btnDnone(btnTrash);
            } else{
                btnDnone(btnEdit);
            }
         
            if(divSelected) {
                divSelected.classList.add('d-none');
            }

            return clone;
        }

        function ensureUniqueImgIds() {
            const images = document.querySelectorAll('.image-card img');
            
            const seenIds = new Set();
            
            images.forEach((img, index) => {
                const currentId = img.getAttribute('data-img-id');
                
                if (seenIds.has(currentId)) {
                    img.setAttribute('data-img-id', index);
                } else {

                    seenIds.add(currentId);
                }
            });
        }
    });
}
