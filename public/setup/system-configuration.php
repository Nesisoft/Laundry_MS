<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Configuration</title>
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

        .config-container {
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
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            margin-top: 6px;
        }

        .file-upload input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 5px;
            color: #495057;
            cursor: pointer;
            width: 100%;
            text-align: center;
            transition: all 0.3s;
        }

        .file-upload-btn:hover {
            background-color: #e9ecef;
            border-color: var(--accent-color);
        }

        .selected-file {
            margin-top: 8px;
            font-size: 12px;
            color: #666;
            display: none;
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

        .next-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .next-btn:hover {
            background-color: var(--primary-color);
        }

        .error-message {
            color: var(--danger-color);
            margin-top: 5px;
            font-size: 12px;
            display: none;
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
    <div class="config-container">
        <div class="header">
            <div class="logo">App</div>
            <h1>System Configuration</h1>
            <p class="subtitle">Please configure your system settings</p>
        </div>

        <div class="progress-indicator">
            <div class="progress-step">
                <div class="step-circle completed">✓</div>
                <div class="step-label">Verification</div>
            </div>
            <div class="step-connector active"></div>
            <div class="progress-step">
                <div class="step-circle active">2</div>
                <div class="step-label">Configuration</div>
            </div>
            <div class="step-connector"></div>
            <div class="progress-step">
                <div class="step-circle">3</div>
                <div class="step-label">Add Manager</div>
            </div>
        </div>

        <form id="config-form">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="business-name">Business Name*</label>
                    <input type="text" id="business-name" required>
                    <div class="error-message" id="business-name-error">Business name is required</div>
                </div>

                <div class="form-group">
                    <label for="branch-name">Branch Name*</label>
                    <input type="text" id="branch-name" required>
                    <div class="error-message" id="branch-name-error">Branch name is required</div>
                </div>

                <div class="form-group">
                    <label for="phone-number">Phone Number*</label>
                    <input type="tel" id="phone-number" placeholder="+233XXXXXXXXX" required>
                    <div class="error-message" id="phone-number-error">Please enter a valid phone number</div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" placeholder="your@email.com">
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label for="logo">Company Logo</label>
                    <div class="file-upload">
                        <div class="file-upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Select Logo
                        </div>
                        <input type="file" id="logo" accept="image/*">
                    </div>
                    <div class="selected-file" id="logo-filename"></div>
                </div>

                <div class="form-group">
                    <label for="banner">Company Banner</label>
                    <div class="file-upload">
                        <div class="file-upload-btn">
                            <i class="fas fa-cloud-upload-alt"></i> Select Banner
                        </div>
                        <input type="file" id="banner" accept="image/*">
                    </div>
                    <div class="selected-file" id="banner-filename"></div>
                </div>

                <div class="form-group full-width">
                    <label for="motto">Company Motto</label>
                    <textarea id="motto" rows="2" placeholder="Enter your company motto or slogan"></textarea>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="back-btn" id="back-btn">Back</button>
                <button type="submit" class="next-btn" id="next-btn">Next</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle file selection display
            document.getElementById('logo').addEventListener('change', function(e) {
                const filename = e.target.files[0] ? e.target.files[0].name : '';
                const filenameElement = document.getElementById('logo-filename');
                filenameElement.textContent = filename;
                filenameElement.style.display = filename ? 'block' : 'none';
            });

            document.getElementById('banner').addEventListener('change', function(e) {
                const filename = e.target.files[0] ? e.target.files[0].name : '';
                const filenameElement = document.getElementById('banner-filename');
                filenameElement.textContent = filename;
                filenameElement.style.display = filename ? 'block' : 'none';
            });

            // Form validation and submission
            document.getElementById('config-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset all error messages
                document.querySelectorAll('.error-message').forEach(el => {
                    el.style.display = 'none';
                });

                let isValid = true;

                // Validate business name
                const businessName = document.getElementById('business-name').value.trim();
                if (!businessName) {
                    document.getElementById('business-name-error').style.display = 'block';
                    isValid = false;
                }

                // Validate branch name
                const branchName = document.getElementById('branch-name').value.trim();
                if (!branchName) {
                    document.getElementById('branch-name-error').style.display = 'block';
                    isValid = false;
                }

                // Validate phone number
                const phoneNumber = document.getElementById('phone-number').value.trim();
                const phoneRegex = /^\+?[0-9]{10,15}$/;
                if (!phoneNumber || !phoneRegex.test(phoneNumber)) {
                    document.getElementById('phone-number-error').style.display = 'block';
                    isValid = false;
                }

                // Validate email (if provided)
                const email = document.getElementById('email').value.trim();
                if (email) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        document.getElementById('email-error').style.display = 'block';
                        isValid = false;
                    }
                }

                if (isValid) {
                    const formData = new FormData();
                    formData.append('business_name', businessName);
                    formData.append('branch_name', branchName);
                    formData.append('phone_number', phoneNumber);
                    formData.append('email', email);
                    formData.append('motto', document.getElementById('motto').value.trim());

                    const logoInput = document.getElementById('logo');
                    const bannerInput = document.getElementById('banner');
                    if (logoInput.files[0]) formData.append('logo', logoInput.files[0]);
                    if (bannerInput.files[0]) formData.append('banner', bannerInput.files[0]);

                    fetch('http://localhost:8000/api/config/set-all', {
                            method: 'POST',
                            headers: {
                                // 'Content-Type': 'multipart/form-data', ❌ Don't set this manually when using FormData
                                'Accept': 'application/json',
                                // Add token if needed: 'Authorization': 'Bearer YOUR_TOKEN_HERE'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Optionally store confirmation in localStorage
                                localStorage.setItem('system_config', JSON.stringify(data.config || formData));
                                // Redirect to add manager
                                window.location.href = 'add-manager.php';
                            } else {
                                alert(data.message || 'Something went wrong!');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Network error occurred!');
                        });
                }
            });

            // Back button handler
            document.getElementById('back-btn').addEventListener('click', function() {
                window.location.href = 'product-key-verification.html';
            });
        });
    </script>
</body>

</html>
