<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PDF417 Barcode Generator</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                {{-- <h1><i class="fas fa-barcode"></i> PDF417 Barcode Generator</h1>
                <p>Generate standardized PDF417 barcodes for identification documents</p> --}}
            </div>

            <div class="content-wrapper">
                <div id="alertBox" class="alert"></div>

                <div class="actions-container mb-3">
                    <button type="button" class="btn btn-large" id="fillSampleDataBtn" style="background-color: var(--warning-color);">
                        <i class="fas fa-fill-drip"></i> Fill with Sample Data
                    </button>
                </div>

                <form id="barcodeForm" method="post" action="{{ route('pdf417.generate') }}">
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
                                      placeholder="Example: TX-DL 12345678" required
                                      title="Document Number (letters, numbers, and common characters allowed)"
                                      oninvalid="this.setCustomValidity('Please enter a document number')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a document number</span>
                                <div class="form-helper-text">Can contain letters, numbers, spaces, and special characters</div>
                            </div>

                            <div class="form-group">
                                <label for="LASTNAME">Last Name <span class="required-tag">*</span></label>
                                <input type="text" id="LASTNAME" name="LASTNAME" class="form-control"
                                      pattern="[a-zA-Z\s]+" placeholder="Enter last name" required
                                      title="Letters and spaces only" oninvalid="this.setCustomValidity('Last name can only contain letters and spaces')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid last name (letters and spaces only)</span>
                                <div class="form-helper-text">Enter your last name using only letters and spaces</div>
                            </div>

                            <div class="form-group">
                                <label for="FIRSTNAME">First Name <span class="required-tag">*</span></label>
                                <input type="text" id="FIRSTNAME" name="FIRSTNAME" class="form-control"
                                      pattern="[a-zA-Z\s]+" placeholder="Enter first name" required
                                      title="Letters and spaces only" oninvalid="this.setCustomValidity('First name can only contain letters and spaces')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid first name (letters and spaces only)</span>
                                <div class="form-helper-text">Enter your first name using only letters and spaces</div>
                            </div>

                            <div class="form-group">
                                <label for="MIDDLENAME">Middle Name <span class="optional-tag">Optional</span></label>
                                <input type="text" id="MIDDLENAME" name="MIDDLENAME" class="form-control"
                                      pattern="[a-zA-Z\s]*" placeholder="Enter middle name (if any)"
                                      title="Letters and spaces only" oninvalid="this.setCustomValidity('Middle name can only contain letters and spaces')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Middle name can only contain letters and spaces</span>
                                <div class="form-helper-text">Enter your middle name using only letters and spaces</div>
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
                                <div class="form-helper-text">Select M for Male or F for Female</div>
                            </div>

                            <div class="form-group">
                                <label for="HEIGHT">Height (inches) <span class="required-tag">*</span></label>
                                <input type="number" id="HEIGHT" name="HEIGHT" class="form-control"
                                      min="30" max="100" placeholder="Enter height" required
                                      title="Height between 30-100 inches" oninvalid="this.setCustomValidity('Please enter a valid height between 30-100 inches')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid height (30-100 inches)</span>
                                <div class="form-helper-text">Enter height in inches (30-100)</div>
                            </div>

                            <div class="form-group">
                                <label for="WEIGHT">Weight (lbs) <span class="required-tag">*</span></label>
                                <input type="number" id="WEIGHT" name="WEIGHT" class="form-control"
                                      min="50" max="500" placeholder="Enter weight" required
                                      title="Weight between 50-500 lbs" oninvalid="this.setCustomValidity('Please enter a valid weight between 50-500 lbs')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid weight (50-500 lbs)</span>
                                <div class="form-helper-text">Enter weight in pounds (50-500)</div>
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
                                <div class="form-helper-text">Select your eye color from the dropdown</div>
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
                                <div class="form-helper-text">Select your hair color from the dropdown</div>
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
                                      placeholder="Example: 123-45-6789"
                                      pattern="(\d{3}-\d{2}-\d{4}|\d{9})"
                                      title="Enter SSN as 123-45-6789 or 123456789"
                                      oninvalid="this.setCustomValidity('Please enter a valid Social Security Number')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please enter a valid Social Security Number (format: XXX-XX-XXXX or XXXXXXXXX)</span>
                                <div class="form-helper-text">Enter as XXX-XX-XXXX or XXXXXXXXX (with or without dashes)</div>
                            </div>

                            <div class="form-group">
                                <label for="AUDITINFO">Audit Information <span class="optional-tag">Optional</span></label>
                                <input type="text" id="AUDITINFO" name="AUDITINFO" class="form-control"
                                      placeholder="Example: Audit #123-A45" maxlength="50"
                                      pattern="[a-zA-Z0-9\s\-\.,'#]+"
                                      title="Letters, numbers, spaces, and common characters allowed"
                                      oninvalid="this.setCustomValidity('Please use only letters, numbers, spaces, and common characters')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please use only letters, numbers, spaces, and common characters</span>
                                <div class="form-helper-text">Can contain letters, numbers, spaces, and special characters</div>
                            </div>

                            <div class="form-group">
                                <label for="INVENTORYNUM">Inventory Control Number <span class="optional-tag">Optional</span></label>
                                <input type="text" id="INVENTORYNUM" name="INVENTORYNUM" class="form-control"
                                      placeholder="Example: INV-2023-45678" maxlength="30"
                                      pattern="[a-zA-Z0-9\s\-\.,'#]+"
                                      title="Letters, numbers, spaces, and common characters allowed"
                                      oninvalid="this.setCustomValidity('Please use only letters, numbers, spaces, and common characters')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please use only letters, numbers, spaces, and common characters</span>
                                <div class="form-helper-text">Can contain letters, numbers, spaces, and special characters</div>
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
                                <label for="DISCRIMINATOR">Discriminator (DD Number) <span class="optional-tag">Optional</span></label>
                                <input type="text" id="DISCRIMINATOR" name="DISCRIMINATOR" class="form-control"
                                      placeholder="Example: DD-123-456-789" maxlength="25"
                                      pattern="[a-zA-Z0-9\s\-\.,'#]+"
                                      title="Letters, numbers, spaces, and common characters allowed"
                                      oninvalid="this.setCustomValidity('Please use only letters, numbers, spaces, and common characters')"
                                      oninput="this.setCustomValidity('')">
                                <span class="error-message">Please use only letters, numbers, spaces, and common characters</span>
                                <div class="form-helper-text">Can contain letters, numbers, spaces, and special characters</div>
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
            const fillSampleDataBtn = document.getElementById('fillSampleDataBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Fill sample data function
            function fillSampleData() {
                console.log('Filling sample data...');

                const sampleData = {
                    'NUMBER': 'TX-DL 12345678',
                    'LASTNAME': 'Smith',
                    'FIRSTNAME': 'John',
                    'MIDDLENAME': 'Robert',
                    'DOB': '01151985',
                    'SEX': 'M',
                    'HEIGHT': '70',
                    'WEIGHT': '180',
                    'EYE': 'BRO',
                    'HAIR': 'BRO',
                    'DONOR': 'YES',
                    'ADDRESS': '123 Main Street',
                    'CITY': 'Austin',
                    'STATE': 'TX',
                    'ZIP': '78701',
                    'CLASS': 'C',
                    'RESTRICTIONS': 'NONE',
                    'ENDORSEMENT': 'NONE',
                    'DOI': '01012022',
                    'DOE': '01012027',
                    'SSN': '123-45-6789',
                    'AUDITINFO': 'Audit #123-A45',
                    'INVENTORYNUM': 'INV-2023-45678',
                    'REVISIONDATE': '05152023',
                    'DISCRIMINATOR': 'DD-123-456-789'
                };

                try {
                    // Fill the form with sample data
                    Object.keys(sampleData).forEach(field => {
                        const input = document.querySelector(`[name="${field}"]`);
                        if (input) {
                            console.log(`Filling ${field} with ${sampleData[field]}`);
                            input.value = sampleData[field];

                            // Trigger change event for select elements
                            if (input.tagName === 'SELECT') {
                                const event = new Event('change');
                                input.dispatchEvent(event);
                            }

                            // Add valid-input class for visual feedback
                            input.classList.add('valid-input');

                            // Trigger input event to update validation
                            const inputEvent = new Event('input');
                            input.dispatchEvent(inputEvent);
                        } else {
                            console.warn(`Field ${field} not found in the form`);
                        }
                    });

                    // Show success message
                    showAlert('Form filled with sample data. You can edit fields as needed before generating the barcode.', 'success');
                } catch (error) {
                    console.error('Error filling sample data:', error);
                    showAlert('Error filling sample data. Please try again.', 'error');
                }
            }

            // Add click event listener to the fill sample data button
            if (fillSampleDataBtn) {
                fillSampleDataBtn.addEventListener('click', fillSampleData);
                console.log('Fill sample data button event listener attached');
            } else {
                console.error('Fill sample data button not found');
            }

            // Add auto-formatting for specific fields
            const formatterConfig = {
                // Remove strict NUMBER formatter to make it more flexible like a city field
                // 'NUMBER': {
                //     pattern: /^(\d{0,4})[-]?(\d{0,2})[-]?(\d{0,4})$/,
                //     format: function(value) {
                //         const parts = value.match(this.pattern);
                //         if (!parts) return value;
                //
                //         let formatted = '';
                //         if (parts[1]) formatted += parts[1];
                //         if (parts[1] && parts[2]) formatted += '-';
                //         if (parts[2]) formatted += parts[2];
                //         if (parts[2] && parts[3]) formatted += '-';
                //         if (parts[3]) formatted += parts[3];
                //
                //         return formatted;
                //     }
                // },
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

                        // Ensure the barcode container is visible
                        const barcodeContainer = document.querySelector('.barcode-container');
                        if (barcodeContainer) {
                            barcodeContainer.style.display = 'block';
                        }

                        // Scroll to the result with a slight delay to ensure content is rendered
                        setTimeout(() => {
                            barcodeResult.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100);
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
