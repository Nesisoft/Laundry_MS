<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Manager</title>
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

        .manager-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            padding: 40px;
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

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background-color: var(--primary-color);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 24px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .progress-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100px;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .step-circle.active {
            background-color: var(--accent-color);
        }

        .step-circle.completed {
            background-color: var(--success-color);
        }

        .step-label {
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .step-connector {
            height: 3px;
            background-color: #ddd;
            width: 60px;
            margin-top: 15px;
        }

        .step-connector.active {
            background-color: var(--accent-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .radio-option input {
            width: auto;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .back-btn {
            background-color: #f8f9fa;
            color: var(--primary-color);
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #e9ecef;
        }

        .finish-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .finish-btn:hover {
            background-color: var(--primary-color);
        }

        .error-message {
            color: var(--danger-color);
            margin-top: 5px;
            font-size: 12px;
            display: none;
        }

        .password-requirements {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 3px;
        }

        .requirement-icon {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: white;
            background-color: #ddd;
        }

        .requirement-icon.valid {
            background-color: var(--success-color);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>
    <div class="manager-container">
        <div class="header">
            <div class="logo">App</div>
            <h1>Add Manager Account</h1>
            <p class="subtitle">Create an account for the system administrator</p>
        </div>

        <div class="progress-indicator">
            <div class="progress-step">
                <div class="step-circle completed">✓</div>
                <div class="step-label">Verification</div>
            </div>
            <div class="step-connector active"></div>
            <div class="progress-step">
                <div class="step-circle completed">✓</div>
                <div class="step-label">Configuration</div>
            </div>
            <div class="step-connector active"></div>
            <div class="progress-step">
                <div class="step-circle active">3</div>
                <div class="step-label">Add Manager</div>
            </div>
        </div>

        <form id="manager-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="first-name">First Name*</label>
                    <input type="text" id="first-name" required>
                    <div class="error-message" id="first-name-error">First name is required</div>
                </div>

                <div class="form-group">
                    <label for="last-name">Last Name*</label>
                    <input type="text" id="last-name" required>
                    <div class="error-message" id="last-name-error">Last name is required</div>
                </div>

                <div class="form-group">
                    <label for="phone-number">Phone Number*</label>
                    <input type="tel" id="phone-number" placeholder="+233XXXXXXXXX" required>
                    <div class="error-message" id="phone-number-error">Please enter a valid phone number</div>
                </div>

                <div class="form-group">
                    <label>Gender*</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="gender" value="male" checked> Male
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="gender" value="female"> Female
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username*</label>
                    <input type="text" id="username" required>
                    <div class="error-message" id="username-error">Username is required and must be unique</div>
                </div>

                <div class="form-group">
                    <label for="salary">Salary (Optional)</label>
                    <input type="number" id="salary" min="0" step="0.01">
                </div>

                <div class="form-group full-width">
                    <label for="password">Password*</label>
                    <input type="password" id="password" required>
                    <div class="error-message" id="password-error">Please create a valid password</div>

                    <div class="password-requirements">
                        <div class="requirement">
                            <span class="requirement-icon" id="length-check">✓</span>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="requirement">
                            <span class="requirement-icon" id="uppercase-check">✓</span>
                            <span>At least one uppercase letter</span>
                        </div>
                        <div class="requirement">
                            <span class="requirement-icon" id="lowercase-check">✓</span>
                            <span>At least one lowercase letter</span>
                        </div>
                        <div class="requirement">
                            <span class="requirement-icon" id="number-check">✓</span>
                            <span>At least one number</span>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="confirm-password">Confirm Password*</label>
                    <input type="password" id="confirm-password" required>
                    <div class="error-message" id="confirm-password-error">Passwords do not match</div>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="back-btn" id="back-btn">Back</button>
                <button type="submit" class="finish-btn" id="finish-btn">Finish Setup</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm-password');

            // Password validation requirements
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const lowercaseCheck = document.getElementById('lowercase-check');
            const numberCheck = document.getElementById('number-check');

            // Live password validation
            passwordInput.addEventListener('input', function() {
                const password = passwordInput.value;

                // Check length
                if (password.length >= 8) {
                    lengthCheck.classList.add('valid');
                    lengthCheck.textContent = '✓';
                } else {
                    lengthCheck.classList.remove('valid');
                    lengthCheck.textContent = '';
                }

                // Check uppercase
                if (/[A-Z]/.test(password)) {
                    uppercaseCheck.classList.add('valid');
                    uppercaseCheck.textContent = '✓';
                } else {
                    uppercaseCheck.classList.remove('valid');
                    uppercaseCheck.textContent = '';
                }

                // Check lowercase
                if (/[a-z]/.test(password)) {
                    lowercaseCheck.classList.add('valid');
                    lowercaseCheck.textContent = '✓';
                } else {
                    lowercaseCheck.classList.remove('valid');
                    lowercaseCheck.textContent = '';
                }

                // Check number
                if (/[0-9]/.test(password)) {
                    numberCheck.classList.add('valid');
                    numberCheck.textContent = '✓';
                } else {
                    numberCheck.classList.remove('valid');
                    numberCheck.textContent = '';
                }
            });

            // Form validation and submission
            document.getElementById('manager-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset all error messages
                document.querySelectorAll('.error-message').forEach(el => {
                    el.style.display = 'none';
                });

                let isValid = true;

                // Validate first name
                const firstName = document.getElementById('first-name').value.trim();
                if (!firstName) {
                    document.getElementById('first-name-error').style.display = 'block';
                    isValid = false;
                }

                // Validate last name
                const lastName = document.getElementById('last-name').value.trim();
                if (!lastName) {
                    document.getElementById('last-name-error').style.display = 'block';
                    isValid = false;
                }

                // Validate phone number
                const phoneNumber = document.getElementById('phone-number').value.trim();
                const phoneRegex = /^\+?[0-9]{10,15}$/;
                if (!phoneNumber || !phoneRegex.test(phoneNumber)) {
                    document.getElementById('phone-number-error').style.display = 'block';
                    isValid = false;
                }

                // Validate username
                const username = document.getElementById('username').value.trim();
                if (!username) {
                    document.getElementById('username-error').style.display = 'block';
                    isValid = false;
                }

                // Validate password
                const password = passwordInput.value;
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
                if (!password || !passwordRegex.test(password)) {
                    document.getElementById('password-error').style.display = 'block';
                    isValid = false;
                }

                // Validate confirm password
                const confirmPassword = confirmPasswordInput.value;
                if (password !== confirmPassword) {
                    document.getElementById('confirm-password-error').style.display = 'block';
                    isValid = false;
                }

                if (isValid) {
                    // For demo: Create manager data
                    const managerData = {
                        firstName,
                        lastName,
                        phoneNumber,
                        gender: document.querySelector('input[name="gender"]:checked').value,
                        username,
                        salary: document.getElementById('salary').value.trim(),
                        role: 'manager'
                    };

                    localStorage.setItem('manager_data', JSON.stringify(managerData));

                    // Redirect to login page
                    window.location.href = 'login.html';
                }
            });

            // Back button handler
            document.getElementById('back-btn').addEventListener('click', function() {
                window.location.href = 'system-configuration.html';
            });
        });
    </script>
</body>

</html>
