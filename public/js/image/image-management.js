
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

export function newImage(btnEdit, imgElement){
   
    btnEdit.addEventListener('change', function(event) {
        const files = event.target.files;

        if (files.length > 0) {
            const imageContainer = document.querySelector('.image-container'); 
            console.log(imageContainer)
            imageContainer.innerHTML = '';

            Array.from(files).forEach(file => {
                const imageUrl = URL.createObjectURL(file);
                //document.querySelector('.image-card img').src = imageUrl;
                imgElement.src = imageUrl; 
            });
        }
    });
}

export function removeExistingImage(removeButton, id, url, path, btnToHide, existingImg) {
    removeButton.addEventListener("click", function() {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.querySelector('.image-card img').src = path
                existingImg
                btnToHide.classList.remove('d-none');
                removeButton.classList.add('d-none');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while trying to remove the avatar.');
        });
    });

    
}