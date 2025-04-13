document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verifyKeyForm');
    const productKeyInput = document.getElementById('productKey');
    const verifyButton = document.getElementById('verifyButton');
    const errorMessage = document.getElementById('errorMessage');

    // Format product key as user types (XXXX-XXXX-XXXX-XXXX)
    productKeyInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
        let formattedValue = '';

        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0 && formattedValue.length < 19) {
                formattedValue += '-';
            }
            formattedValue += value[i];
        }

        e.target.value = formattedValue;
    });

    function isValidProductKey(key) {
        // Basic validation - should be in format XXXX-XXXX-XXXX-XXXX
        const regex = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/;
        return regex.test(key);
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const productKey = productKeyInput.value.trim();

        if (!isValidProductKey(productKey)) {
            productKeyInput.focus();
            errorMessage.textContent = 'Please enter a product key';
            return;
        }

        errorMessage.textContent = '';

        // Show loading state
        verifyButton.disabled = true;
        verifyButton.innerHTML = `
            <div class="spinner"></div>
            Verifying...
        `;

        try {
            const response = await fetch('http://localhost:8000/api/auth/verify-product-key', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    product_key: productKey.replace(/-/g, '') // Or keep dashes depending on API
                })
            });

            const result = await response.json();

            verifyButton.disabled = false;
            console.log(result);

            if (result.success) {
                // Save key locally
                console.log(result.data)
                localStorage.setItem('access_token', result.data.token);
                localStorage.setItem('verify-pk', true);
                window.location.href = window.appRoutes.configureAdminUrl;
            } else {
                errorMessage.textContent = result.message || 'Invalid product key. Please try again.';
                verifyButton.disabled = false;
                verifyButton.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    Verify Product Key
                `;
            }
        } catch (error) {
            console.error(error);
        }
    });
});
