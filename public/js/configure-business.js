document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('businessConfigForm');
    const saveButton = document.getElementById('saveButton');

    // Handle file input display
    const fileInputs = document.querySelectorAll('.file-input');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const uploadContent = this.previousElementSibling;
                const icon = uploadContent.querySelector('i');
                icon.className = 'fas fa-check';
                icon.style.color = '#10B981';

                const text = uploadContent.querySelector('.file-upload-text');
                text.innerHTML = `<span class="primary-text">${fileName}</span>`;
            }
        });
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Show loading state
        saveButton.disabled = true;
        saveButton.innerHTML = `
            <div class="spinner"></div>
            Saving...
        `;

        try {
            formData = new FormData(this);
            adminAccessToken = localStorage.getItem('access_token');
            adminTokenSplit = adminAccessToken.split("|");
            adminId = adminTokenSplit[0];
            const response = await fetch(`http://localhost:8000/api/user/config`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${adminAccessToken}`
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            saveButton.disabled = false;
            console.log(result);

            if (result.success) {
                // Save key locally
                console.log(result.data)
                localStorage.setItem('configure-business', true);
                window.location.href = window.appRoutes.loginUrl;
            } else {
                errorMessage.textContent = result.message || 'Invalid product key. Please try again.';
                saveButton.disabled = false;
                saveButton.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    Processing...
                `;
            }
        } catch (error) {
            console.error(error);
        }
    });
});
