const eyeiconpassword = document.getElementById('eye-password');
const password = document.getElementById('password');

eyeiconpassword.addEventListener('click', () => {
    if (password.type === "password") {
        password.type = "text";
        eyeiconpassword.classList.remove('fa-eye');
        eyeiconpassword.classList.add('fa-eye-slash');
    } else {
        password.type = "password";
        eyeiconpassword.classList.remove('fa-eye-slash');
        eyeiconpassword.classList.add('fa-eye');
    }
});

const eyeiconconfirmpassword = document.getElementById('eye-confirmpassword');
const confirmpassword = document.getElementById('confirmpassword');

eyeiconconfirmpassword.addEventListener('click', () => {
    if (confirmpassword.type === "password") {
        confirmpassword.type = "text";
        eyeiconconfirmpassword.classList.remove('fa-eye');
        eyeiconconfirmpassword.classList.add('fa-eye-slash');
    } else {
        confirmpassword.type = "password";
        eyeiconconfirmpassword.classList.remove('fa-eye-slash');
        eyeiconconfirmpassword.classList.add('fa-eye');
    }
});
