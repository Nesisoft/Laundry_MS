document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const errorMessage = document.getElementById('errorMessage');

    // Pre-fill email if admin data exists (for demo purposes)
    const adminData = JSON.parse(localStorage.getItem('adminData') || '{}');

    if (adminData.username) {
        document.getElementById('username').value = adminData.username;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        if (!username || !password) {
            errorMessage.textContent = 'Please enter both username and password';
            return;
        }

        errorMessage.textContent = '';

        // Show loading state
        loginButton.disabled = true;
        loginButton.innerHTML = `
            <div class="spinner"></div>
            Logging in...
        `;

        try {
            formData = {
                username: username,
                password: password
            }
            console.log(formData)
            const response = await fetch('http://localhost:8000/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            loginButton.disabled = false;
            console.log(result);

            if (result.success) {
                // Save key locally
                console.log(result.data)
                localStorage.setItem('access_token', result.data.token);
                window.location.href = window.appRoutes.dashboardUrl;
            } else {
                errorMessage.textContent = result.message || 'Invalid product key. Please try again.';
                loginButton.disabled = false;
                loginButton.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    Verify Product Key
                `;
            }
        } catch (error) {
            console.error(error);
        }
    });
});
