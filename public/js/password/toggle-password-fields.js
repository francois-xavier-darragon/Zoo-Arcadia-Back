function togglePasswordFields() {
    const passwordFields = document.getElementById('passwordFields');
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    
    if(passwordFields.style.display === 'none') {
        passwordFields.style.display = 'block';
        changePasswordBtn.style.display = 'none';
    }
}