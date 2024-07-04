function btnDnone(btnToHide) {
    document.addEventListener('DOMContentLoaded', function() {
        if (btnToHide) {
            btnToHide.classList.add('d-none');
        }
    });
}

function newImage(newImage){
    newImage.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            const imageUrl = URL.createObjectURL(file);
            document.querySelector('.image-card img').src = imageUrl;
        }
    });
}

function removeExistingImage(removeButton, id, url, path, btnToHide) {
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
                document.getElementById('{{ form.avatar.removeUserAvatarFile.vars.id }}').value = 1;
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