function toggleForms() {
    const loginForm = document.querySelector('.login-form');
    const signupForm = document.querySelector('.signup-form');

    loginForm.classList.toggle('active');
    signupForm.classList.toggle('active');
}