let cartCount = 0;
let cartTimeout;
let errorTimeout;

function showNotification(message) {
    const notification = document.getElementById('cart-notification');
    const messageSpan = document.getElementById('cart-message');

    if (!notification || !messageSpan) return;

    messageSpan.textContent = message;
    notification.style.display = 'block';

    if (cartTimeout) clearTimeout(cartTimeout);

    cartTimeout = setTimeout(() => {
        notification.style.display = 'none';
        cartTimeout = null;
    }, 3000);
}

function showErrorNotification(message) {
    const notification = document.getElementById('error-notification');
    const messageSpan = document.getElementById('error-message');

    if (!notification || !messageSpan) return;

    messageSpan.textContent = message;
    notification.style.display = 'block';

    if (errorTimeout) clearTimeout(errorTimeout);

    errorTimeout = setTimeout(() => {
        notification.style.display = 'none';
        errorTimeout = null;
    }, 3000);
}

function showLoginNotification(action) {
    const notification = document.getElementById('login-error-notification');
    const messageSpan = document.getElementById('login-error-message');
    
    if(action === "cart") {
        messageSpan.textContent = "Devi prima loggarti per aggiungere prodotti al carrello.";
    } else if(action === "trama") {
        messageSpan.textContent = "Devi prima loggarti per vedere la trama del film.";
    }

    notification.style.display = 'block';

    if (errorTimeout) clearTimeout(errorTimeout);

    errorTimeout = setTimeout(() => {
        notification.style.display = 'none';
        errorTimeout = null;
    }, 3000);
}



function addToCart(button) {
    cartCount++;
    const cartCountSpan = document.getElementById('cart-count');
    if (cartCountSpan) cartCountSpan.textContent = cartCount;

    const card = button.closest('.card');
    const title = card.querySelector('.card-title').textContent;

    const message = `“${title}” aggiunto al carrello!`;
    showNotification(message);
}

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                e.preventDefault();
                showErrorNotification('Tutti i campi sono obbligatori.');
                return;
            }

            if (username.length < 5) {
                e.preventDefault();
                showErrorNotification('Il tuo username deve essere di almeno 5 caratteri.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showErrorNotification('La password deve essere di almeno 6 caratteri.');
                return;
            }

            if (!/[A-Z]/.test(password)) {
                e.preventDefault();
                showErrorNotification('La password deve contenere almeno una lettera maiuscola.');
                return;
            }

            if (!/[0-9]/.test(password)) {
                e.preventDefault();
                showErrorNotification('La password deve contenere almeno un numero.');
                return;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                e.preventDefault();
                showErrorNotification('La password deve contenere almeno un simbolo speciale.');
                return;
            }
        });
    }

    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', function (e) {
            const nome = document.getElementById('nome').value.trim();
            const cognome = document.getElementById('cognome').value.trim();
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confermaPassword = document.getElementById('confermapassword').value;
            const termini = document.getElementById('termini');

            if (!nome || !cognome || !email || !username || !password || !confermaPassword) {
                e.preventDefault();
                showErrorNotification('Tutti i campi sono obbligatori.');
                return;
            }

            if (nome.length < 2) {
                e.preventDefault();
                showErrorNotification('Il nome deve essere di almeno 2 lettere.');
                return;
            }

            if (cognome.length < 2) {
                e.preventDefault();
                showErrorNotification('Il cognome deve essere di almeno 2 lettere.');
                return;
            }

            if (username.length < 5) {
                e.preventDefault();
                showErrorNotification('Il tuo username deve essere di almeno 5 caratteri.');
                return;
            }

            if (email.indexOf('@') === -1 || email.indexOf('.') === -1) {
                e.preventDefault();
                showErrorNotification('L\'email non è valida.');
                return;
            }

            if (password !== confermaPassword) {
                e.preventDefault();
                showErrorNotification('Le password non coincidono.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showErrorNotification('La password deve essere di almeno 6 caratteri.');
                return;
            }

            if (!/[A-Z]/.test(password)) {
                e.preventDefault();
                showErrorNotification('La password deve contenere almeno una lettera maiuscola.');
                return;
            }

            if (!/[0-9]/.test(password)) {
                e.preventDefault();
                showErrorNotification('La password deve contenere almeno un numero.');
                return;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                e.preventDefault();
                showErrorNotification('La password deve contenere almeno un simbolo speciale.');
                return;
            }

            if (!termini || !termini.checked) {
                e.preventDefault();
                showErrorNotification('Devi accettare i Termini e le Condizioni per continuare.');
                return;
            }
        });
    }
});
