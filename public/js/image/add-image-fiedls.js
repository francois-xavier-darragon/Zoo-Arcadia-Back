function addImgeFilds(pathDefaut) {
    document.addEventListener('DOMContentLoaded', function() {
        const addImageBtn = document.getElementById('add-image-btn');
        const imageFieldsList = document.getElementById('image-fields-list');
    
        addImageBtn.addEventListener('click', function() {
            const newImageField = createImageField();
            imageFieldsList.appendChild(newImageField);
        });
    
        function createImageField() {
            const div = document.createElement('div');
            div.classList.add('image-upload-input');
    
            const editButtonId = 'edit-image-button-' + generateUniqueId();
            const removeButtonId = 'remove-image-button-' + generateUniqueId();
            const baliseImg = 'balise-Img' + generateUniqueId();
    
            div.innerHTML = `
                <div class="col">
                    <div class="col-lg-8">
                        <div class="row p-3 mt-4 position-relative">
                            <div class="image-card text-white">
                                <img src="${pathDefaut}" class="rounded defaut-img" style ="width: 187px; height: 125px;" alt="" id="${baliseImg}"/>
                                <p class="titre-image"></p>
                            </div>
                            <div class="position-absolute top-0 start-1 mt-3 me-3">
                                <label class="btn btn-outline-light" id="${editButtonId}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                    </svg>
                                    <input type="file" name="images[animalFile]" class="custom-file-input">
                                </label>
                                <button type="button" class="btn btn-outline-light" id="${removeButtonId}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
    
            return div;
        }
    
        function generateUniqueId() {
            return Math.random().toString(36).substring(7);
        }
    });
}

