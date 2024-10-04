import { btnDnone, newImage, removeExistingImage } from './image-management.js';

export function manageAvatar(userAvatar, path) {
    document.addEventListener('DOMContentLoaded', function(){
        const userDataElement = document.getElementById('user-data');
        const userId = userDataElement.dataset.userId;

        const btnTrash = document.getElementById('remove-avatar-button');
        const btnEdit = document.getElementById('edit-avatar-button');
        const baliseImg = document.getElementById('balise-img');

        let url = userDataElement.dataset.removeAvatarUrl;
    
        if(userId === null) {
            btnDnone(btnTrash)
            newImage(btnEdit, baliseImg) 
        } else {

            if(userAvatar != null) {
                btnDnone(btnEdit)
            } else {
                btnDnone(btnTrash)
            }

            url = url.replace('USER_ID', userId);
            
            removeExistingImage(btnTrash, userId, url, path, btnEdit, baliseImg)
            newImage(btnEdit, baliseImg);

        }
    })
}