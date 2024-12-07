const eyeIcon=document.getElementById('eye');
const passwordField=document.getElementById('password');
 
eyeIcon.addEventListener('Click',()=>{
    if(passwordField.type==="password"&& passwordField.value)
    {
        passwordField.type="text";
        eyeIcon.classList.remove('fa-eye-slash')
        eyeIcon.classList.add('fa-eye')
    }
    else
    {
        passwordField.type="text";
       
        eyeIcon.classList.remove('fa-eye')
        eyeIcon.classList.add('fa-eye-slash')
    }
})
