/**
 * HRMIS - Employee Form Specific JavaScript
 * Handles employee form submission, validation, and interactions
 * Requires: scripts.js
 */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('employeeForm');
    const resetBtn = document.getElementById('resetBtn');
    const sameAsResidentialCheckbox = document.getElementById('sameAsResidential');
    const formGrid = document.getElementById('formGrid');

    // ============================================
    // ADD REQUIRED ICON IN LABEL IF CONTROL IS REQUIRED
    // ============================================
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.type != 'radio') {
            const labels = input.labels;
            if (input.required) {
                labels.forEach(label => {
                    const requiredIcon = document.createElement('span');
                    requiredIcon.className = 'required';
                    requiredIcon.textContent = '*';
                    label.appendChild(requiredIcon);
                })
            }
        }
    });

    // ============================================
    // RESET BUTTON
    // ============================================

    resetBtn.addEventListener('click', function () {
        if (confirmAction('Are you sure you want to reset the form? All entered data will be lost.')) {
            form.reset();
            showMessage('Form has been reset.', 'success');
        }
    });

    // ============================================
    // ADDRESS COPYING
    // ============================================

    // Same as residential address checkbox handler
    sameAsResidentialCheckbox.addEventListener('change', function () {
        const permanentFields = document.getElementById('permanentAddressFields');

        if (this.checked) {
            copyResidentialToPermanent();
            permanentFields.style.opacity = '0.5';
            permanentFields.style.pointerEvents = 'none';

            // Disable permanent address fields
            const inputs = permanentFields.querySelectorAll('input');
            inputs.forEach(input => input.disabled = true);
        } else {
            permanentFields.style.opacity = '1';
            permanentFields.style.pointerEvents = 'auto';

            // Enable permanent address fields
            const inputs = permanentFields.querySelectorAll('input');
            inputs.forEach(input => input.disabled = false);
        }
    });

    // Copy residential address to permanent address
    function copyResidentialToPermanent() {
        const mapping = [
            ['res_spec_address', 'perm_spec_address'],
            ['res_street_address', 'perm_street_address'],
            ['res_vill_address', 'perm_vill_address'],
            ['res_barangay_address', 'perm_barangay_address'],
            ['res_city', 'perm_city'],
            ['res_municipality', 'perm_municipality'],
            ['res_province', 'perm_province'],
            ['res_zipcode', 'perm_zipcode']
        ];

        mapping.forEach(([resId, permId]) => {
            const resField = document.getElementById(resId);
            const permField = document.getElementById(permId);
            if (resField && permField) {
                permField.value = resField.value;
            }
        });
    }

    // Auto-copy residential to permanent when residential fields change
    const residentialFields = [
        'res_spec_address', 'res_street_address', 'res_vill_address', 'res_barangay_address',
        'res_city', 'res_municipality', 'res_province', 'res_zipcode'
    ];

    residentialFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function () {
                if (sameAsResidentialCheckbox.checked) {
                    copyResidentialToPermanent();
                }
            });
        }
    });

    // ============================================
    // FORM VALIDATION
    // ============================================

    function validateEmployeeForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'rgba(245, 87, 108, 0.8)';

                // Reset border color after animation
                setTimeout(() => {
                    field.style.borderColor = '';
                }, 2000);
            }
        });

        if (!isValid) {
            showMessage('Please fill in all required fields.', 'error');
            scrollToFirstInvalid(form);
        }

        return isValid;
    }

    // Handle radio button values
    function handleRadioButtons(data) {
        const radioGroups = ['Q34', 'Q35a', 'Q35b', 'Q36', 'Q37', 'Q38', 'Q39', 'Q40a', 'Q40b', 'Q40c'];

        radioGroups.forEach(group => {
            const checked = form.querySelector(`input[name="${group}"]:checked`);
            if (checked) {
                data[group] = checked.value;
            } else {
                data[group] = null;
            }
        });
    }

    // Handle checkbox values
    function handleCheckboxes(data) {
        // Add any checkbox handling if needed
        data.sameAsResidential = sameAsResidentialCheckbox.checked;
    }

    // ============================================
    // FIELD-SPECIFIC VALIDATION
    // ============================================

    // Email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            if (this.value && !isValidEmail(this.value)) {
                this.style.borderColor = 'rgba(245, 87, 108, 0.5)';
                showMessage('Please enter a valid email address.', 'error');
            }
        });
    }

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        formatPhoneInput(input);
    });

    // Birthdate validation
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput) {
        birthdateInput.addEventListener('change', function () {
            if (!isNotFutureDate(this.value)) {
                this.value = '';
                showMessage('Birthdate cannot be in the future.', 'error');
                return;
            }

            // Calculate age
            const age = calculateAge(this.value);
            if (age < 18) {
                showMessage('Employee must be at least 18 years old.', 'error');
            }
        });

        // Set max date to today
        const today = new Date().toISOString().split('T')[0];
        birthdateInput.setAttribute('max', today);
    }

    // ============================================
    // CONDITIONAL FIELDS
    // ============================================

    // Question detail fields - show/hide based on Yes/No answers
    setupConditionalFields();

    function setupConditionalFields() {
        const conditionalMappings = [
            { radios: ['Q34'], detail: 'Q34_details' },
            { radios: ['Q35a'], detail: 'Q35a_details' },
            { radios: ['Q35b'], detail: 'Q35b_details' },
            { radios: ['Q36'], detail: 'Q36_details' },
            { radios: ['Q37'], detail: 'Q37_details' },
            { radios: ['Q38'], detail: 'Q38_details' },
            { radios: ['Q39'], detail: 'Q39_details' },
            { radios: ['Q40a'], detail: 'Q40a_details' },
            { radios: ['Q40b'], detail: 'Q40b_details' },
            { radios: ['Q40c'], detail: 'Q40c_details' }
        ];

        conditionalMappings.forEach(mapping => {
            mapping.radios.forEach(radioName => {
                const radios = document.querySelectorAll(`input[name="${radioName}"]`);
                radios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        const detailField = document.getElementById(mapping.detail);
                        if (detailField) {
                            const parentGroup = detailField.closest('.form-group');
                            // For Q34, show details if 'a' or 'b' is selected
                            if (radioName === 'Q34') {
                                if (this.value === 'a' || this.value === 'b') {
                                    parentGroup.style.display = 'flex';
                                    detailField.focus();
                                } else {
                                    parentGroup.style.display = 'none';
                                    detailField.value = '';
                                }
                            } else if (this.value === '1' || this.value === 'Yes') {
                                parentGroup.style.display = 'flex';
                                detailField.focus();
                            } else {
                                parentGroup.style.display = 'none';
                                detailField.value = '';
                            }
                        }
                    });
                });
            });
        });
    }

    // ============================================
    // REAL-TIME VALIDATION
    // ============================================

    setupRequiredFieldValidation(form);

    // ============================================
    // SCROLL ANIMATIONS
    // ============================================

    setupScrollAnimation('.form-section');

    // ============================================
    // AUTO-SAVE FUNCTIONALITY
    // ============================================

    // Auto-save to localStorage
    const autoSaveForm = debounce(function () {
        const data = collectFormData(form);
        handleRadioButtons(data);
        saveToLocalStorage('employeeFormDraft', data);
        devLog('Form auto-saved');
    }, 1000);

    form.addEventListener('input', autoSaveForm);

    // Load form from localStorage on page load
    function loadFormFromLocalStorage() {
        const savedData = loadFromLocalStorage('employeeFormDraft');
        if (savedData) {
            Object.keys(savedData).forEach(key => {
                const field = form.elements[key];
                if (field) {
                    if (field.type === 'radio') {
                        const radio = form.querySelector(`input[name="${key}"][value="${savedData[key]}"]`);
                        if (radio) radio.checked = true;
                    } else {
                        field.value = savedData[key];
                    }
                }
            });
            showMessage('Previous draft loaded. Continue editing or reset to start fresh.', 'info');
        }
    }

    // Uncomment to enable auto-load on page load
    // loadFormFromLocalStorage();

    // Clear localStorage on successful submission
    form.addEventListener('submit', function () {
        removeFromLocalStorage('employeeFormDraft');
    });
});
