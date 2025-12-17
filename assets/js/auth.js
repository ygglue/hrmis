/**
 * HRMIS - Authentication Pages JavaScript
 * Handles login, register, and auth-related interactions
 * Requires: scripts.js
 */

document.addEventListener('DOMContentLoaded', function () {

    // ============================================
    // PASSWORD VISIBILITY TOGGLE
    // ============================================

    const togglePasswordButtons = document.querySelectorAll('.toggle-password');

    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const eyeIcon = this.querySelector('.material-symbols-outlined');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = 'visibility';
            }
        });
    });

    // ============================================
    // REGISTER FORM VALIDATION
    // ============================================

    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        // Real-time password match validation
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', function () {
                if (passwordInput.value && this.value) {
                    if (passwordInput.value !== this.value) {
                        this.style.borderColor = 'rgba(245, 87, 108, 0.5)';
                    } else {
                        this.style.borderColor = 'rgba(75, 254, 159, 0.5)';
                    }
                }
            });
        }

        // Password strength indicator (optional)
        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const strength = calculatePasswordStrength(this.value);
                updatePasswordStrength(strength);
            });
        }

        // Form submission validation
        registerForm.addEventListener('submit', function (e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                e.preventDefault();
                showMessage('Passwords do not match!', 'error');
                confirmPasswordInput.focus();
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                showMessage('Password must be at least 8 characters long!', 'error');
                passwordInput.focus();
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('.btn-primary');
            setButtonLoading(submitBtn, true);
        });
    }

    // ============================================
    // LOGIN FORM VALIDATION
    // ============================================

    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                e.preventDefault();
                showMessage('Please enter both username and password!', 'error');
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('.btn-primary');
            setButtonLoading(submitBtn, true);
        });
    }

    // ============================================
    // PASSWORD STRENGTH CALCULATOR
    // ============================================

    function calculatePasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z\d]/.test(password)) strength++;

        return strength;
    }

    function updatePasswordStrength(strength) {
        // This is optional - you can add visual strength indicator
        // For now, just log it
        devLog('Password strength:', strength);
    }

    // ============================================
    // AUTO-DISMISS SUCCESS MESSAGES
    // ============================================

    const successMessages = document.querySelectorAll('.message.success');
    successMessages.forEach(message => {
        setTimeout(() => {
            message.style.animation = 'fadeOut 0.4s ease';
            setTimeout(() => message.remove(), 400);
        }, 5000);
    });

    // ============================================
    // REMEMBER ME FUNCTIONALITY
    // ============================================

    const rememberMeCheckbox = document.getElementById('remember_me');
    const usernameInput = document.getElementById('username');

    if (rememberMeCheckbox && usernameInput) {
        // Load saved username if exists
        const savedUsername = loadFromLocalStorage('rememberedUsername');
        if (savedUsername) {
            usernameInput.value = savedUsername;
            rememberMeCheckbox.checked = true;
        }

        // Save username on form submit if remember me is checked
        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                if (rememberMeCheckbox.checked) {
                    saveToLocalStorage('rememberedUsername', usernameInput.value);
                } else {
                    removeFromLocalStorage('rememberedUsername');
                }
            });
        }
    }

    // ============================================
    // FORM FIELD ANIMATIONS
    // ============================================

    const formInputs = document.querySelectorAll('.auth-form input, .auth-form select');

    formInputs.forEach(input => {
        // Add focus animation
        input.addEventListener('focus', function () {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function () {
            this.parentElement.classList.remove('focused');
        });
    });

    // ============================================
    // PREVENT DOUBLE SUBMISSION
    // ============================================

    const authForms = document.querySelectorAll('.auth-form');

    authForms.forEach(form => {
        let isSubmitting = false;

        form.addEventListener('submit', function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
        });
    });
});
