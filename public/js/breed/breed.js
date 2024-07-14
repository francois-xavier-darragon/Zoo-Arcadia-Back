
const addBreedField = document.getElementById('addBreedField');
const addBreedBtn = document.getElementById('addBreedBtn');
const selectBreedFields = document.getElementById('selectBreedFields');
const selectBreedBtn = document.getElementById('selectBreedBtn');

function mangeDomeBreed(){
    manageBreed(addBreedBtn, addBreedField, selectBreedBtn)
    
    if(selectBreedBtn != null) {
        manageBreed(selectBreedBtn, selectBreedFields, addBreedBtn)
    }

    manageEditBreed(addBreedBtn, selectBreedFields, addBreedField)
}

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
