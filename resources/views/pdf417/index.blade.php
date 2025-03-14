<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PDF417 Barcode Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --primary-light: #eef2ff;
            --accent-color: #f72585;
            --success-color: #2ec4b6;
            --warning-color: #ff9e00;
            --error-color: #e63946;
            --text-primary: #2b2d42;
            --text-secondary: #6c757d;
            --text-light: #8d99ae;
            --surface-color: #ffffff;
            --background-color: #f8f9fa;
            --border-color: #dee2e6;
            --border-radius: 8px;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--background-color);
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card {
            background: var(--surface-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .header h1 {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 300;
        }

        .content-wrapper {
            padding: 30px;
        }

        .form-section {
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 25px;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-left: 10px;
            border-left: 3px solid var(--primary-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 5px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: #fff;
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .form-control.error {
            border-color: var(--error-color);
            background-color: #fff8f8;
        }

        .form-control.valid-input {
            border-color: var(--success-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%232ec4b6' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px 12px;
            padding-right: 40px;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.75rem;
            margin-top: 5px;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-helper-text {
            font-size: 0.7rem;
            color: var(--text-light);
            margin-top: 5px;
        }

        .btn {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover {
            background-color: var(--primary-dark);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            background-color: var(--text-light);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-large {
            width: 100%;
            max-width: 250px;
            padding: 14px 24px;
            font-size: 1rem;
            margin: 30px auto;
        }

        .actions-container {
            display: flex;
            justify-content: center;
        }

        #barcodeResult {
            text-align: center;
            margin-top: 30px;
        }

        .barcode-container {
            padding: 40px;
            background-color: #fff;
            display: inline-block;
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            margin: 30px 0;
            max-width: 90%;
            transition: var(--transition);
        }

        .barcode-container:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .barcode-image {
            max-width: 100%;
            height: auto;
            display: block;
            min-width: 350px;
        }

        .barcode-caption {
            margin-top: 20px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        .download-btn {
            margin-top: 20px;
            background-color: var(--success-color);
            display: inline-flex;
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        .download-btn:hover {
            background-color: #25a79b;
        }

        .alert {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            display: none;
            font-size: 0.95rem;
            box-shadow: var(--shadow-sm);
            animation: fadeIn 0.3s ease;
        }

        .alert-error {
            background-color: #FFF5F5;
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        .alert-success {
            background-color: #F0FFF4;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .loading {
            display: none;
            text-align: center;
            margin: 30px 0;
        }

        .loading i {
            color: var(--primary-color);
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading p {
            font-size: 1rem;
            margin-top: 10px;
            color: var(--text-secondary);
        }

        select.form-control {
            height: 46px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236c757d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px 12px;
            padding-right: 40px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .optional-tag {
            font-size: 0.7rem;
            background-color: var(--primary-light);
            color: var(--primary-color);
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
            font-weight: 400;
        }

        .required-tag {
            color: var(--error-color);
            margin-left: 3px;
        }

        /* Improved responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }

            .content-wrapper {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .header h1 {
                font-size: 1.7rem;
            }

            .btn-large {
                max-width: 100%;
            }

            .barcode-container {
                padding: 25px;
            }

            .barcode-image {
                min-width: 0;
            }
        }

        /* Added animations for better interactivity */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(67, 97, 238, 0); }
            100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
        }

        .pulse {
            animation: pulse 1.5s infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1><i class="fas fa-barcode"></i> PDF417 Barcode Generator</h1>
                <p>Generate standardized PDF417 barcodes for identification documents</p>
            </div>

            <div class="content-wrapper">
                <div id="alertBox" class="alert"></div>

                <form id="barcodeForm" method="POST" action="{{ route('pdf417.generate') }}">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user-circle"></i> Personal Information
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="NUMBER">Document Number <span class="required-tag">*</span></label>
                                <input type="text" id="NUMBER" name="NUMBER" class="form-control"
                                      placeholder="Enter document number" required
                                      title="Document Number" oninvalid="this.setCustomValidity('Please enter a document number')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a document number</span>
                            </div>

                            <div class="form-group">
                                <label for="LASTNAME">Last Name <span class="required-tag">*</span></label>
                                <input type="text" id="LASTNAME" name="LASTNAME" class="form-control"
                                      pattern="[a-zA-Z\s]+" placeholder="Enter last name" required
                                      title="Letters and spaces only" oninvalid="this.setCustomValidity('Last name can only contain letters and spaces')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid last name (letters and spaces only)</span>
                            </div>

                            <div class="form-group">
                                <label for="FIRSTNAME">First Name <span class="required-tag">*</span></label>
                                <input type="text" id="FIRSTNAME" name="FIRSTNAME" class="form-control"
                                      pattern="[a-zA-Z\s]+" placeholder="Enter first name" required
                                      title="Letters and spaces only" oninvalid="this.setCustomValidity('First name can only contain letters and spaces')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid first name (letters and spaces only)</span>
                            </div>

                            <div class="form-group">
                                <label for="MIDDLENAME">Middle Name <span class="optional-tag">Optional</span></label>
                                <input type="text" id="MIDDLENAME" name="MIDDLENAME" class="form-control"
                                      pattern="[a-zA-Z\s]*" placeholder="Enter middle name (if any)"
                                      title="Letters and spaces only" oninvalid="this.setCustomValidity('Middle name can only contain letters and spaces')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Middle name can only contain letters and spaces</span>
                            </div>

                            <div class="form-group">
                                <label for="DOB">Date of Birth <span class="required-tag">*</span></label>
                                <input type="text" id="DOB" name="DOB" class="form-control"
                                      pattern="\d{8}" placeholder="MMDDYYYY" required
                                      title="Format: MMDDYYYY" oninvalid="this.setCustomValidity('Please enter date in MMDDYYYY format')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid date (format: MMDDYYYY)</span>
                                <div class="form-helper-text">Example: 01011990 for January 1, 1990</div>
                            </div>

                            <div class="form-group">
                                <label for="SEX">Gender <span class="required-tag">*</span></label>
                                <select id="SEX" name="SEX" class="form-control" required
                                        oninvalid="this.setCustomValidity('Please select a gender')"
                                        oninput="this.setCustomValidity('')">
                                    <option value="">Select gender</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                                <span class="error-message">Please select a gender</span>
                            </div>

                            <div class="form-group">
                                <label for="HEIGHT">Height (inches) <span class="required-tag">*</span></label>
                                <input type="number" id="HEIGHT" name="HEIGHT" class="form-control"
                                      min="30" max="100" placeholder="Enter height" required
                                      title="Height between 30-100 inches" oninvalid="this.setCustomValidity('Please enter a valid height between 30-100 inches')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid height (30-100 inches)</span>
                            </div>

                            <div class="form-group">
                                <label for="WEIGHT">Weight (lbs) <span class="required-tag">*</span></label>
                                <input type="number" id="WEIGHT" name="WEIGHT" class="form-control"
                                      min="50" max="500" placeholder="Enter weight" required
                                      title="Weight between 50-500 lbs" oninvalid="this.setCustomValidity('Please enter a valid weight between 50-500 lbs')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid weight (50-500 lbs)</span>
                            </div>

                            <div class="form-group">
                                <label for="EYE">Eye Color <span class="required-tag">*</span></label>
                                <select id="EYE" name="EYE" class="form-control" required
                                        oninvalid="this.setCustomValidity('Please select an eye color')"
                                        oninput="this.setCustomValidity('')">
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
                                <label for="HAIR">Hair Color <span class="required-tag">*</span></label>
                                <select id="HAIR" name="HAIR" class="form-control" required
                                        oninvalid="this.setCustomValidity('Please select a hair color')"
                                        oninput="this.setCustomValidity('')">
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
                                <label for="DONOR">Organ Donor <span class="required-tag">*</span></label>
                                <select id="DONOR" name="DONOR" class="form-control" required
                                        oninvalid="this.setCustomValidity('Please select donor status')"
                                        oninput="this.setCustomValidity('')">
                                    <option value="">Select donor status</option>
                                    <option value="YES">Yes</option>
                                    <option value="NO">No</option>
                                </select>
                                <span class="error-message">Please select donor status</span>
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-map-marker-alt"></i> Address Information
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="ADDRESS">Street Address <span class="required-tag">*</span></label>
                                <input type="text" id="ADDRESS" name="ADDRESS" class="form-control"
                                      placeholder="Enter full address" required minlength="5" maxlength="100"
                                      title="Enter your full address" oninvalid="this.setCustomValidity('Please enter a valid address (min 5 characters)')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid address (minimum 5 characters)</span>
                            </div>

                            <div class="form-group">
                                <label for="CITY">City <span class="required-tag">*</span></label>
                                <input type="text" id="CITY" name="CITY" class="form-control"
                                      placeholder="Enter city" required pattern="[a-zA-Z\s\-']+" minlength="2"
                                      title="Enter city name" oninvalid="this.setCustomValidity('Please enter a valid city name')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid city name</span>
                            </div>

                            <div class="form-group">
                                <label for="STATE">State <span class="required-tag">*</span></label>
                                <input type="text" id="STATE" name="STATE" class="form-control"
                                      pattern="[A-Z]{2}" placeholder="TX" required maxlength="2" style="text-transform: uppercase;"
                                      title="2-letter state code (e.g., TX)" oninvalid="this.setCustomValidity('Please enter a valid 2-letter state code (e.g., TX)')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid 2-letter state code (e.g., TX, CA, NY)</span>
                                <div class="form-helper-text">2-letter state code (e.g., TX, CA, NY)</div>
                            </div>

                            <div class="form-group">
                                <label for="ZIP">ZIP Code <span class="required-tag">*</span></label>
                                <input type="text" id="ZIP" name="ZIP" class="form-control"
                                      pattern="\d{5}" placeholder="12345" required
                                      title="5-digit ZIP code" oninvalid="this.setCustomValidity('Please enter a valid 5-digit ZIP code')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid 5-digit ZIP code</span>
                            </div>
                        </div>
                    </div>

                    <!-- License Information -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-id-card"></i> License Information
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="CLASS">License Class <span class="required-tag">*</span></label>
                                <input type="text" id="CLASS" name="CLASS" class="form-control"
                                      pattern="[A-Z0-9]+" placeholder="Enter class" required
                                      title="Uppercase letters and numbers only" style="text-transform: uppercase;"
                                      oninvalid="this.setCustomValidity('Please enter class using only uppercase letters and numbers')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid class (uppercase letters and numbers only)</span>
                            </div>

                            <div class="form-group">
                                <label for="RESTRICTIONS">Restrictions <span class="required-tag">*</span></label>
                                <input type="text" id="RESTRICTIONS" name="RESTRICTIONS" class="form-control"
                                      placeholder="Enter restrictions or NONE" required minlength="1" maxlength="50"
                                      title="Enter restrictions or NONE" oninvalid="this.setCustomValidity('Please enter restrictions or NONE')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter restrictions or NONE</span>
                            </div>

                            <div class="form-group">
                                <label for="ENDORSEMENT">Endorsements <span class="required-tag">*</span></label>
                                <input type="text" id="ENDORSEMENT" name="ENDORSEMENT" class="form-control"
                                      placeholder="Enter endorsements or NONE" required minlength="1" maxlength="50"
                                      title="Enter endorsements or NONE" oninvalid="this.setCustomValidity('Please enter endorsements or NONE')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter endorsements or NONE</span>
                            </div>

                            <div class="form-group">
                                <label for="DOI">Date of Issue <span class="required-tag">*</span></label>
                                <input type="text" id="DOI" name="DOI" class="form-control"
                                      pattern="\d{8}" placeholder="MMDDYYYY" required
                                      title="Format: MMDDYYYY" oninvalid="this.setCustomValidity('Please enter date in MMDDYYYY format')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid date (format: MMDDYYYY)</span>
                                <div class="form-helper-text">Example: 01012023 for January 1, 2023</div>
                            </div>

                            <div class="form-group">
                                <label for="DOE">Date of Expiry <span class="required-tag">*</span></label>
                                <input type="text" id="DOE" name="DOE" class="form-control"
                                      pattern="\d{8}" placeholder="MMDDYYYY" required
                                      title="Format: MMDDYYYY" oninvalid="this.setCustomValidity('Please enter date in MMDDYYYY format')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid date (format: MMDDYYYY)</span>
                                <div class="form-helper-text">Example: 01012028 for January 1, 2028</div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information (Optional) -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-clipboard-list"></i> Additional Information
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="SSN">Social Security Number <span class="optional-tag">Optional</span></label>
                                <input type="text" id="SSN" name="SSN" class="form-control"
                                      placeholder="Enter SSN"
                                      title="Social Security Number"
                                      oninvalid="this.setCustomValidity('Please enter a valid Social Security Number')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid Social Security Number</span>
                            </div>

                            <div class="form-group">
                                <label for="AUDITINFO">Audit Information <span class="optional-tag">Optional</span></label>
                                <input type="text" id="AUDITINFO" name="AUDITINFO" class="form-control"
                                      placeholder="Audit information" maxlength="50"
                                      title="Audit information" oninvalid="this.setCustomValidity('Invalid audit information')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Invalid audit information format</span>
                            </div>

                            <div class="form-group">
                                <label for="INVENTORYNUM">Inventory Control Number <span class="optional-tag">Optional</span></label>
                                <input type="text" id="INVENTORYNUM" name="INVENTORYNUM" class="form-control"
                                      placeholder="Inventory control number" maxlength="30"
                                      title="Inventory control number" oninvalid="this.setCustomValidity('Invalid inventory control number')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Invalid inventory control number format</span>
                            </div>

                            <div class="form-group">
                                <label for="REVISIONDATE">Revision Date <span class="optional-tag">Optional</span></label>
                                <input type="text" id="REVISIONDATE" name="REVISIONDATE" class="form-control"
                                      pattern="\d{8}" placeholder="MMDDYYYY"
                                      title="Format: MMDDYYYY" oninvalid="this.setCustomValidity('Please enter date in MMDDYYYY format')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid date format (MMDDYYYY)</span>
                            </div>

                            <div class="form-group">
                                <label for="DISCRIMINATOR">Discriminator <span class="optional-tag">Optional</span></label>
                                <input type="text" id="DISCRIMINATOR" name="DISCRIMINATOR" class="form-control"
                                      placeholder="Discriminator" maxlength="25"
                                      title="Discriminator" oninvalid="this.setCustomValidity('Invalid discriminator format')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Invalid discriminator format</span>
                            </div>
                        </div>
                    </div>

                    <div class="actions-container">
                        <button type="submit" class="btn btn-large pulse" id="generateBtn">
                            <i class="fas fa-barcode"></i> Generate Barcode
                        </button>
                    </div>
                </form>

                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Generating your barcode...</p>
                </div>

                <div id="barcodeResult"></div>
            </div>
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

            // Add auto-formatting for specific fields
            const formatterConfig = {
                'NUMBER': {
                    pattern: /^(\d{0,4})[-]?(\d{0,2})[-]?(\d{0,4})$/,
                    format: function(value) {
                        const parts = value.match(this.pattern);
                        if (!parts) return value;

                        let formatted = '';
                        if (parts[1]) formatted += parts[1];
                        if (parts[1] && parts[2]) formatted += '-';
                        if (parts[2]) formatted += parts[2];
                        if (parts[2] && parts[3]) formatted += '-';
                        if (parts[3]) formatted += parts[3];

                        return formatted;
                    }
                },
                'DOB': {
                    pattern: /^(\d{0,2})(\d{0,2})(\d{0,4})$/,
                    validate: function(value) {
                        if (value.length !== 8) return false;

                        const month = parseInt(value.substring(0, 2));
                        const day = parseInt(value.substring(2, 4));
                        const year = parseInt(value.substring(4, 8));

                        return month >= 1 && month <= 12 &&
                               day >= 1 && day <= 31 &&
                               year >= 1900 && year <= new Date().getFullYear();
                    }
                },
                'DOI': {
                    pattern: /^(\d{0,2})(\d{0,2})(\d{0,4})$/,
                    validate: function(value) {
                        if (value.length !== 8) return false;

                        const month = parseInt(value.substring(0, 2));
                        const day = parseInt(value.substring(2, 4));
                        const year = parseInt(value.substring(4, 8));

                        return month >= 1 && month <= 12 &&
                               day >= 1 && day <= 31 &&
                               year >= 1900 && year <= new Date().getFullYear();
                    }
                },
                'DOE': {
                    pattern: /^(\d{0,2})(\d{0,2})(\d{0,4})$/,
                    validate: function(value) {
                        if (value.length !== 8) return false;

                        const month = parseInt(value.substring(0, 2));
                        const day = parseInt(value.substring(2, 4));
                        const year = parseInt(value.substring(4, 8));

                        return month >= 1 && month <= 12 &&
                               day >= 1 && day <= 31 &&
                               year >= 1900;
                    }
                }
            };

            // Apply auto-formatting to specified fields
            Object.keys(formatterConfig).forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (!input) return;

                input.addEventListener('input', function(e) {
                    const config = formatterConfig[fieldName];
                    const value = e.target.value.replace(/[^\d]/g, ''); // Remove non-digits

                    if (config.format) {
                        e.target.value = config.format.call(config, value);
                    }

                    if (config.validate && e.target.value.length === 8) {
                        const isValid = config.validate.call(config, e.target.value);
                        if (!isValid) {
                            e.target.setCustomValidity('Please enter a valid date');
                        } else {
                            e.target.setCustomValidity('');
                        }
                    }
                });
            });

            // Auto-uppercase certain fields
            const uppercaseFields = ['STATE', 'CLASS'];
            uppercaseFields.forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (!input) return;

                input.addEventListener('input', function(e) {
                    e.target.value = e.target.value.toUpperCase();
                });
            });

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
            console.log('Form initialized with default values');

            // Visual feedback on field completion
            const allRequiredInputs = form.querySelectorAll('[required]');
            allRequiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.checkValidity()) {
                        this.classList.add('valid-input');
                    } else {
                        this.classList.remove('valid-input');
                    }
                });
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('Form submission started');

                // Validate form before submission
                if (!form.checkValidity()) {
                    console.error('Form validation failed');
                    form.reportValidity();
                    return;
                }

                console.log('Form validation passed');

                // Reset UI state
                alertBox.style.display = 'none';
                barcodeResult.innerHTML = '';
                generateBtn.disabled = true;
                loading.style.display = 'block';

                try {
                    const formData = new FormData(this);

                    // Log form data for debugging
                    console.log('Form data being submitted:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    // Add a timestamp to prevent caching
                    formData.append('_ts', Date.now());

                    console.log('Sending fetch request to:', form.action);

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

                    console.log('Response received:', response);
                    console.log('Response status:', response.status);

                    if (!response.ok) {
                        console.error('Response not OK:', response.statusText);
                        if (response.status === 422) {
                            // Handle validation errors
                            const validationErrors = await response.json();
                            console.error('Validation errors:', validationErrors);

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

                    let data;
                    try {
                        data = await response.json();
                        console.log('Response data:', data);
                    } catch (jsonError) {
                        console.error('JSON parse error:', jsonError);
                        const responseText = await response.text();
                        console.error('Raw response text:', responseText);
                        throw new Error('Failed to parse server response as JSON');
                    }

                    if (data.success) {
                        console.log('Barcode generated successfully');
                        barcodeResult.innerHTML = data.barcode;
                        showAlert(data.message || 'Barcode generated successfully!', 'success');

                        // Scroll to the result
                        barcodeResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        console.error('Barcode generation failed:', data.message);
                        showAlert(data.message || 'Failed to generate barcode', 'error');
                    }
                } catch (error) {
                    console.error('Error details:', error);
                    console.error('Error stack:', error.stack);
                    showAlert('An error occurred while generating the barcode: ' + error.message, 'error');
                } finally {
                    generateBtn.disabled = false;
                    loading.style.display = 'none';
                    console.log('Form submission process completed');
                }
            });

            function showAlert(message, type) {
                console.log(`Showing ${type} alert: ${message}`);
                alertBox.className = `alert alert-${type}`;
                alertBox.innerHTML = message;
                alertBox.style.display = 'block';

                if (type === 'error') {
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    </script>

    <!-- Download barcode functionality -->
    <script>
        function downloadBarcode() {
            const barcodeImage = document.getElementById('barcodeImage');

            if (!barcodeImage) {
                console.error('Barcode image not found');
                return;
            }

            // Create a temporary anchor element for download
            const downloadLink = document.createElement('a');

            // Get image src (base64 data)
            const imageSrc = barcodeImage.getAttribute('src');
            downloadLink.href = imageSrc;

            // Set filename
            const formattedDate = new Date().toISOString().replace(/[:.]/g, '-').substring(0, 19);
            downloadLink.download = `PDF417-Barcode-${formattedDate}.png`;

            // Append to document, click, and remove
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            console.log('Barcode download initiated');
        }
    </script>
</body>
</html>
