
export function btnDnone(btnToHide) {
    function hideButton() {
        if (btnToHide) {
            btnToHide.classList.add('d-none');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hideButton);
    } else {
        hideButton();
    }
}

export function newImage(btnEdit, imgElement, editUrl){
   
    btnEdit.addEventListener('change', function(event) {
        const files = event.target.files;
        const imageContainer = document.querySelector('.image-container'); 
        if (files.length > 0 ) {
            
            imageContainer.innerHTML = '';

            Array.from(files).forEach((file) => {
               const imageUrl = URL.createObjectURL(file);
               imgElement.src = imageUrl;
            });
        }
    });
}

export function removeExistingImage(removeButton, id, url, path, btnToHide, existingImg, updateIndex, div) {
    removeButton.addEventListener("click", function() {
        
        const imageId = existingImg.dataset.imgId
      
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: id, imgId: imageId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                
                existingImg.src = path
                
                existingImg.removeAttribute('data-img-id');

                if (btnToHide) {
                    btnToHide.value = '';
                }

                btnToHide.classList.remove('d-none');
                removeButton.classList.add('d-none');

                div.remove();
                updateIndex();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while trying to remove the image.');
        });
    });
}

