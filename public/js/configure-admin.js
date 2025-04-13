document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adminSetupForm');
    const createAccountButton = document.getElementById('createAccountButton');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Basic validation
        if (password !== confirmPassword) {
            errorMessage.textContent = 'Passwords do not match';
            return;
        }

        if (password.length < 8) {
            errorMessage.textContent = 'Password must be at least 8 characters long';
            return;
        }

        errorMessage.textContent = '';

        // Show loading state
        createAccountButton.disabled = true;
        createAccountButton.innerHTML = `
            <div class="spinner"></div>
            Creating Account...
        `;

        try {
            formData = new FormData(this);
            adminAccessToken = localStorage.getItem('access_token');
            adminTokenSplit = adminAccessToken.split("|");
            adminId = adminTokenSplit[0];
            const response = await fetch(`http://localhost:8000/api/user/users/1`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${adminAccessToken}`
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            createAccountButton.disabled = false;
            console.log(result);

            if (result.success) {
                // Save key locally
                console.log(result.data)
                localStorage.setItem('configure-admin', true);
                window.location.href = window.appRoutes.configureBusinessUrl;
            } else {
                errorMessage.textContent = result.message || 'Invalid product key. Please try again.';
                createAccountButton.disabled = false;
                createAccountButton.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    Processing...
                `;
            }
        } catch (error) {
            console.error(error);
        }
    });
});
