
const addBreedField = document.getElementById('addBreedField');
const addBreedBtn = document.getElementById('addBreedBtn');
const selectBreedFields = document.getElementById('selectBreedFields');
const selectBreedBtn = document.getElementById('selectBreedBtn');

function addBreed() {
    if(addBreedField.style.display === 'none') {
        addBreedField.style.display = 'block';
        addBreedBtn.style.display = 'none';
        selectBreedBtn.style.display = 'none';
    }
}

function selectBreed() {
    if(selectBreedFields.style.display === 'none') {
        selectBreedFields.style.display = 'block';
        selectBreedBtn.style.display = 'none';
        addBreedBtn.style.display = 'none';
    }
}