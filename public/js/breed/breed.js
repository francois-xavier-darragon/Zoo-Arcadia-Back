
function manageBreed(btn, divForm, btnSelect) {
    btn.addEventListener('click',function (e) {
        if(divForm.style.display === 'none') {
            divForm.style.display = 'block';
            btn.style.display = 'none';
        } 
        if(btnSelect != null) {
            btnSelect.style.display = 'none';
        }
    });
}

function manageEditBreed(btn, divSelectForm, divForm) {
    btn.addEventListener('click',function (e) {
        if(divSelectForm.style.display === 'block') {
            divSelectForm.style.display = 'none';
            btn.style.display = 'none';
            divForm.style.display = 'block';
        }   
    });
}