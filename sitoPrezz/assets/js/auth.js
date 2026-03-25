let authErrorTimeout;

function showErrorNotification(message) {
    const notification = document.getElementById('error-notification');
    const messageSpan = document.getElementById('error-message');

    if (!notification || !messageSpan) {
        return;
    }

    messageSpan.textContent = message;
    notification.style.display = 'block';

    if (authErrorTimeout) {
        clearTimeout(authErrorTimeout);
    }

    authErrorTimeout = setTimeout(() => {
        notification.style.display = 'none';
        authErrorTimeout = null;
    }, 3000);
}

async function submitAuthForm(form) {
    const formData = new FormData(form);
    const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    const rawResponse = await response.text();
    let payload;
    try {
        payload = JSON.parse(rawResponse);
    } catch (error) {
        const details = rawResponse.trim();
        showErrorNotification(details ? ('Server: ' + details.slice(0, 180)) : 'Risposta non valida dal server.');
        return;
    }

    if (!response.ok || !payload.status) {
        showErrorNotification(payload.message || 'Operazione non riuscita.');
        return;
    }

    window.location.href = payload.redirect || 'index.php';
}

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                showErrorNotification('Tutti i campi sono obbligatori.');
                return;
            }

            if (email.indexOf('@') === -1 || email.indexOf('.') === -1) {
                showErrorNotification('L\'indirizzo email non e valido.');
                return;
            }

            if (password.length < 6) {
                showErrorNotification('La password deve essere di almeno 6 caratteri.');
                return;
            }

            if (!/[A-Z]/.test(password)) {
                showErrorNotification('La password deve contenere almeno una lettera maiuscola.');
                return;
            }

            if (!/[0-9]/.test(password)) {
                showErrorNotification('La password deve contenere almeno un numero.');
                return;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                showErrorNotification('La password deve contenere almeno un simbolo speciale.');
                return;
            }

            await submitAuthForm(loginForm);
        });
    }

    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confermaPassword = document.getElementById('confirm_password').value;
            const termini = document.getElementById('termini');

            if (!username || !email || !password || !confermaPassword) {
                showErrorNotification('Tutti i campi sono obbligatori.');
                return;
            }

            if (username.length < 3) {
                showErrorNotification('Lo username deve essere di almeno 3 caratteri.');
                return;
            }

            if (!/^[A-Za-z0-9_]+$/.test(username)) {
                showErrorNotification('Lo username puo contenere solo lettere, numeri e underscore.');
                return;
            }

            if (email.indexOf('@') === -1 || email.indexOf('.') === -1) {
                showErrorNotification('L\'indirizzo email non e valido.');
                return;
            }

            if (password !== confermaPassword) {
                showErrorNotification('Le password non coincidono.');
                return;
            }

            if (password.length < 6) {
                showErrorNotification('La password deve essere di almeno 6 caratteri.');
                return;
            }

            if (!/[A-Z]/.test(password)) {
                showErrorNotification('La password deve contenere almeno una lettera maiuscola.');
                return;
            }

            if (!/[0-9]/.test(password)) {
                showErrorNotification('La password deve contenere almeno un numero.');
                return;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                showErrorNotification('La password deve contenere almeno un simbolo speciale.');
                return;
            }

            if (!termini || !termini.checked) {
                showErrorNotification('Devi accettare i Termini e le Condizioni per continuare.');
                return;
            }

            await submitAuthForm(signupForm);
        });
    }
});
