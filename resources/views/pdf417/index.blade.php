<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PDF417 Barcode Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1976D2;
            --primary-dark: #1565C0;
            --primary-light: #BBDEFB;
            --accent-color: #FF4081;
            --text-primary: #212121;
            --text-secondary: #757575;
            --divider-color: #BDBDBD;
            --error-color: #D32F2F;
            --success-color: #388E3C;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.4;
            color: var(--text-primary);
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 1rem auto;
            padding: 0 0.5rem;
        }

        .card {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .header h1 {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.25rem;
            font-size: 1.5rem;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.25rem;
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--divider-color);
            border-radius: 3px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px var(--primary-light);
        }

        .form-control.error {
            border-color: var(--error-color);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.75rem;
            margin-top: 0.15rem;
            display: none;
        }

        .btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 3px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 100%;
            max-width: 150px;
            display: block;
            margin: 1rem auto;
        }

        .btn:hover {
            background-color: var(--primary-dark);
        }

        .btn:disabled {
            background-color: var(--divider-color);
            cursor: not-allowed;
        }

        .btn i {
            margin-right: 0.25rem;
        }

        #barcodeResult {
            text-align: center;
            margin-top: 1rem;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 3px;
            margin-bottom: 0.75rem;
            display: none;
            font-size: 0.9rem;
        }

        .alert-error {
            background-color: #FFEBEE;
            color: var(--error-color);
            border: 1px solid #FFCDD2;
        }

        .alert-success {
            background-color: #E8F5E9;
            color: var(--success-color);
            border: 1px solid #C8E6C9;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 0.75rem 0;
        }

        .loading i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .loading p {
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        select.form-control {
            height: 35px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0.5rem auto;
            }

            .card {
                padding: 0.75rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>PDF417 Barcode Generator</h1>
                <p>Generate a PDF417 barcode for identification documents</p>
            </div>

            <div id="alertBox" class="alert"></div>

            <form id="barcodeForm" method="POST" action="{{ route('pdf417.generate') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="NUMBER">Document Number</label>
                        <input type="text" id="NUMBER" name="NUMBER" class="form-control"
                               pattern="\d{4}-\d{2}-\d{4}" placeholder="1234-56-7890" required>
                        <span class="error-message">Please enter a valid document number (format: 1234-56-7890)</span>
                    </div>

                    <div class="form-group">
                        <label for="LASTNAME">Last Name</label>
                        <input type="text" id="LASTNAME" name="LASTNAME" class="form-control"
                               pattern="[a-zA-Z\s]+" placeholder="Enter last name" required>
                        <span class="error-message">Please enter a valid last name (letters and spaces only)</span>
                </div>

                    <div class="form-group">
                        <label for="FIRSTNAME">First Name</label>
                        <input type="text" id="FIRSTNAME" name="FIRSTNAME" class="form-control"
                               pattern="[a-zA-Z\s]+" placeholder="Enter first name" required>
                        <span class="error-message">Please enter a valid first name (letters and spaces only)</span>
                </div>

                    <div class="form-group">
                        <label for="MIDDLENAME">Middle Name</label>
                        <input type="text" id="MIDDLENAME" name="MIDDLENAME" class="form-control"
                               pattern="[a-zA-Z\s]*" placeholder="Enter middle name (optional)">
                        <span class="error-message">Middle name can only contain letters and spaces</span>
                </div>

                    <div class="form-group">
                        <label for="ADDRESS">Address</label>
                        <input type="text" id="ADDRESS" name="ADDRESS" class="form-control"
                               placeholder="Enter full address" required>
                        <span class="error-message">Please enter a valid address</span>
                </div>

                    <div class="form-group">
                        <label for="CITY">City</label>
                        <input type="text" id="CITY" name="CITY" class="form-control"
                               placeholder="Enter city" required>
                        <span class="error-message">Please enter a valid city</span>
                </div>

                    <div class="form-group">
                        <label for="ZIP">ZIP Code</label>
                        <input type="text" id="ZIP" name="ZIP" class="form-control"
                               pattern="\d{5}" placeholder="12345" required>
                        <span class="error-message">Please enter a valid 5-digit ZIP code</span>
                </div>

                    <div class="form-group">
                        <label for="STATE">State</label>
                        <input type="text" id="STATE" name="STATE" class="form-control"
                               pattern="[A-Z]{2}" placeholder="TX" required maxlength="2" style="text-transform: uppercase;">
                        <span class="error-message">Please enter a valid 2-letter state code (e.g., TX, CA, NY)</span>
                </div>

                    <div class="form-group">
                        <label for="CLASS">Class</label>
                        <input type="text" id="CLASS" name="CLASS" class="form-control"
                               pattern="[A-Z1-9]+" placeholder="Enter class" required>
                        <span class="error-message">Please enter a valid class (uppercase letters and numbers only)</span>
                </div>

                    <div class="form-group">
                        <label for="SEX">Sex</label>
                        <select id="SEX" name="SEX" class="form-control" required>
                            <option value="">Select gender</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                    </select>
                        <span class="error-message">Please select a gender</span>
                </div>

                    <div class="form-group">
                        <label for="DONOR">Organ Donor</label>
                        <select id="DONOR" name="DONOR" class="form-control" required>
                            <option value="">Select donor status</option>
                            <option value="YES">Yes</option>
                        <option value="NO">No</option>
                    </select>
                        <span class="error-message">Please select donor status</span>
                </div>

                    <div class="form-group">
                        <label for="DOB">Date of Birth</label>
                        <input type="text" id="DOB" name="DOB" class="form-control"
                               pattern="\d{8}" placeholder="MMDDYYYY" required>
                        <span class="error-message">Please enter a valid date (format: MMDDYYYY)</span>
                </div>

                    <div class="form-group">
                        <label for="DOI">Date of Issue</label>
                        <input type="text" id="DOI" name="DOI" class="form-control"
                               pattern="\d{8}" placeholder="MMDDYYYY" required>
                        <span class="error-message">Please enter a valid date (format: MMDDYYYY)</span>
                </div>

                    <div class="form-group">
                        <label for="DOE">Date of Expiry</label>
                        <input type="text" id="DOE" name="DOE" class="form-control"
                               pattern="\d{8}" placeholder="MMDDYYYY" required>
                        <span class="error-message">Please enter a valid date (format: MMDDYYYY)</span>
                </div>

                    <div class="form-group">
                        <label for="HEIGHT">Height (inches)</label>
                        <input type="number" id="HEIGHT" name="HEIGHT" class="form-control"
                               min="30" max="100" placeholder="Enter height" required>
                        <span class="error-message">Please enter a valid height (30-100 inches)</span>
                </div>

                    <div class="form-group">
                        <label for="WEIGHT">Weight (lbs)</label>
                        <input type="number" id="WEIGHT" name="WEIGHT" class="form-control"
                               min="50" max="500" placeholder="Enter weight" required>
                        <span class="error-message">Please enter a valid weight (50-500 lbs)</span>
                </div>

                    <div class="form-group">
                        <label for="EYE">Eye Color</label>
                        <select id="EYE" name="EYE" class="form-control" required>
                            <option value="">Select eye color</option>
                        <option value="BLK">Black</option>
                        <option value="BLU">Blue</option>
                            <option value="BRO">Brown</option>
                        <option value="GRY">Gray</option>
                        <option value="GRN">Green</option>
                        <option value="HAZ">Hazel</option>
                        <option value="MAR">Maroon</option>
                    </select>
                        <span class="error-message">Please select an eye color</span>
                </div>

                    <div class="form-group">
                        <label for="HAIR">Hair Color</label>
                        <select id="HAIR" name="HAIR" class="form-control" required>
                            <option value="">Select hair color</option>
                        <option value="BLK">Black</option>
                        <option value="BLN">Blonde</option>
                            <option value="BRO">Brown</option>
                        <option value="GRY">Gray</option>
                        <option value="RED">Red</option>
                        <option value="WHI">White</option>
                        <option value="BAL">Bald</option>
                    </select>
                        <span class="error-message">Please select a hair color</span>
                </div>

                    <div class="form-group">
                        <label for="RESTRICTIONS">Restrictions</label>
                        <input type="text" id="RESTRICTIONS" name="RESTRICTIONS" class="form-control"
                               placeholder="Enter restrictions" required>
                        <span class="error-message">Please enter restrictions or NONE</span>
                </div>

                    <div class="form-group">
                        <label for="ENDORSEMENT">Endorsements</label>
                        <input type="text" id="ENDORSEMENT" name="ENDORSEMENT" class="form-control"
                               placeholder="Enter endorsements" required>
                        <span class="error-message">Please enter endorsements or NONE</span>
                    </div>
                </div>

                <button type="submit" class="btn" id="generateBtn">
                    <i class="fas fa-barcode"></i> Generate Barcode
                </button>
            </form>

            <div class="loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Generating barcode...</p>
            </div>

            <div id="barcodeResult"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('barcodeForm');
            const alertBox = document.getElementById('alertBox');
            const loading = document.querySelector('.loading');
            const barcodeResult = document.getElementById('barcodeResult');
            const generateBtn = document.getElementById('generateBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Initialize form with default values for easier testing
            function initializeFormDefaults() {
                const defaults = {
                    'RESTRICTIONS': 'NONE',
                    'ENDORSEMENT': 'NONE'
                };

                for (const [field, value] of Object.entries(defaults)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input && !input.value) {
                        input.value = value;
                    }
                }
            }

            initializeFormDefaults();

            // Show error message for invalid fields with improved feedback
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                // Clear error state when input receives focus
                input.addEventListener('focus', function() {
                    this.classList.remove('error');
                    const errorMessage = this.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.style.display = 'none';
                    }
                });

                // Handle invalid state
                input.addEventListener('invalid', function(e) {
                    e.preventDefault();
                    this.classList.add('error');
                    const errorMessage = this.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.style.display = 'block';
                    }

                    // Focus the first invalid field
                    if (document.querySelector('.error') === this) {
                        this.focus();
                    }
                });

                // Remove error state on input
                input.addEventListener('input', function() {
                    this.classList.remove('error');
                    const errorMessage = this.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.style.display = 'none';
                    }
                });
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validate form before submission
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Reset UI state
                alertBox.style.display = 'none';
                barcodeResult.innerHTML = '';
                generateBtn.disabled = true;
                loading.style.display = 'block';

                try {
                    const formData = new FormData(this);

                    // Add a timestamp to prevent caching
                    formData.append('_ts', Date.now());

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData,
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        if (response.status === 422) {
                            // Handle validation errors
                            const validationErrors = await response.json();
                            let errorMessage = 'Please correct the following errors:<ul>';

                            for (const [field, errors] of Object.entries(validationErrors.errors || {})) {
                                errorMessage += `<li>${errors[0]}</li>`;
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('error');
                                    const errorMsg = input.nextElementSibling;
                                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                                        errorMsg.textContent = errors[0];
                                        errorMsg.style.display = 'block';
                                    }
                                }
                            }

                            errorMessage += '</ul>';
                            showAlert(errorMessage, 'error');
                        } else {
                            throw new Error(`Server responded with status: ${response.status}`);
                        }
                        return;
                }

                const data = await response.json();

                if (data.success) {
                        barcodeResult.innerHTML = data.barcode;
                        showAlert(data.message || 'Barcode generated successfully!', 'success');

                        // Scroll to the result
                        barcodeResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                        showAlert(data.message || 'Failed to generate barcode', 'error');
                }
            } catch (error) {
                    console.error('Error details:', error);
                    showAlert('An error occurred while generating the barcode: ' + error.message, 'error');
                } finally {
                    generateBtn.disabled = false;
                    loading.style.display = 'none';
                }
            });

            function showAlert(message, type) {
                alertBox.className = `alert alert-${type}`;
                alertBox.innerHTML = message;
                alertBox.style.display = 'block';

                if (type === 'error') {
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    </script>
</body>
</html>
