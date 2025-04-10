<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Key Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #003262;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #ecf0f1;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verification-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo {
            width: 120px;
            height: 120px;
            background-color: var(--primary-color);
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: bold;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 24px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .form-group input:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .key-input {
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: monospace;
            font-size: 18px;
        }

        .error-message {
            color: var(--danger-color);
            margin-top: 5px;
            font-size: 14px;
            display: none;
        }

        .verify-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .verify-btn:hover {
            background-color: var(--primary-color);
        }

        .loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-icon {
            display: none;
            color: var(--success-color);
            font-size: 80px;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="verification-container">
        <div class="logo-container">
            <div class="logo">App</div>
        </div>
        <h1>Product Key Verification</h1>
        <p>Please enter your product key to activate the application. If you don't have a product key, please contact support.</p>

        <div class="form-group">
            <label for="product-key">Product Key</label>
            <input type="text" id="product-key" class="key-input" placeholder="XXXX-XXXX-XXXX-XXXX" maxlength="19">
            <div class="error-message" id="key-error">Invalid product key format. Please check and try again.</div>
        </div>

        <button class="verify-btn" id="verify-btn">
            <div class="btn-content">
                <span>Verify & Activate</span>
                <div class="loader" id="loader"></div>
            </div>
        </button>

        <div class="footer">
            &copy; 2025 Your Company. All rights reserved.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productKeyInput = document.getElementById('product-key');
            const verifyBtn = document.getElementById('verify-btn');
            const keyError = document.getElementById('key-error');
            const loader = document.getElementById('loader');

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

            verifyBtn.addEventListener('click', async function() {
                const productKey = productKeyInput.value.trim();

                if (!isValidProductKey(productKey)) {
                    keyError.style.display = 'block';
                    productKeyInput.focus();
                    return;
                }

                keyError.style.display = 'none';
                loader.style.display = 'inline-block';
                verifyBtn.disabled = true;

                try {
                    const response = await fetch('http://localhost:8000/api/auth/verify-pk', {
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

                    loader.style.display = 'none';
                    verifyBtn.disabled = false;

                    if (result.success) {
                        // Save key locally
                        localStorage.setItem('product_key', productKey);

                        // Redirect to config setup
                        window.location.href = 'system-configuration.php';
                    } else {
                        keyError.textContent = result.message || 'Invalid product key.';
                        keyError.style.display = 'block';
                    }
                } catch (error) {
                    loader.style.display = 'none';
                    verifyBtn.disabled = false;
                    keyError.textContent = 'An error occurred. Please try again.';
                    keyError.style.display = 'block';
                    console.error(error);
                }
            });


            function isValidProductKey(key) {
                // Basic validation - should be in format XXXX-XXXX-XXXX-XXXX
                const regex = /^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/;
                return regex.test(key);
            }
        });
    </script>
</body>

</html>
