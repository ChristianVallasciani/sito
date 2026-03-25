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

    let payload;
    try {
        payload = await response.json();
    } catch (error) {
        showErrorNotification('Risposta non valida dal server.');
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

            const nome = document.getElementById('name').value.trim();
            const cognome = document.getElementById('surname').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confermaPassword = document.getElementById('confirm_password').value;
            const termini = document.getElementById('termini');

            if (!nome || !cognome || !email || !password || !confermaPassword) {
                showErrorNotification('Tutti i campi sono obbligatori.');
                return;
            }

            if (nome.length < 2) {
                showErrorNotification('Il nome deve essere di almeno 2 lettere.');
                return;
            }

            if (cognome.length < 2) {
                showErrorNotification('Il cognome deve essere di almeno 2 lettere.');
                return;
            }

            if (!/^[A-Za-z]+$/.test(nome) || !/^[A-Za-z]+$/.test(cognome)) {
                showErrorNotification('Nome e cognome devono contenere solo lettere.');
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
