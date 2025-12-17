<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee Record - Employee Management System</title>
    <meta name="description" content="Add new employee records to the employee management system with comprehensive personal, contact, and address information.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/employee_form.css">
</head>
<body>
    <div class="background-gradient"></div>
    
    <div class="container">
        <header class="form-header">
            <h1>Add New Employee</h1>
            <p>Complete the form below to add a new employee record to the system</p>
        </header>

        <form id="employeeForm" class="employee-form">
            <!-- Personal Information Section -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">👤</div>
                    <h2>Personal Information</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">First Name <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="name_extension">Name Extension</label>
                        <input type="text" id="name_extension" name="name_extension" placeholder="Jr., Sr., III, etc." maxlength="5">
                    </div>
                    
                    <div class="form-group">
                        <label for="employee_no">Employee Number <span class="required">*</span></label>
                        <input type="number" id="employee_no" name="employee_no" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="birthdate">Birthdate <span class="required">*</span></label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sex">Sex <span class="required">*</span></label>
                        <select id="sex" name="sex" required>
                            <option value="">Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="civil_status">Civil Status <span class="required">*</span></label>
                        <select id="civil_status" name="civil_status" required>
                            <option value="">Select...</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="citizenship">Citizenship</label>
                        <input type="text" id="citizenship" name="citizenship">
                    </div>
                    
                    <div class="form-group">
                        <label for="blood_type">Blood Type</label>
                        <select id="blood_type" name="blood_type">
                            <option value="">Select...</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="height_in_meter">Height (meters)</label>
                        <input type="number" id="height_in_meter" name="height_in_meter" step="0.01" placeholder="e.g., 1.75">
                    </div>
                    
                    <div class="form-group">
                        <label for="weight_in_kg">Weight (kg)</label>
                        <input type="number" id="weight_in_kg" name="weight_in_kg" step="0.1" placeholder="e.g., 70.5">
                    </div>
                </div>
            </section>

            <!-- Birth Place Information -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">🏥</div>
                    <h2>Birth Place Information</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="birth_city">Birth City</label>
                        <input type="text" id="birth_city" name="birth_city">
                    </div>
                    
                    <div class="form-group">
                        <label for="birth_province">Birth Province</label>
                        <input type="text" id="birth_province" name="birth_province">
                    </div>
                    
                    <div class="form-group">
                        <label for="birth_country">Birth Country</label>
                        <input type="text" id="birth_country" name="birth_country">
                    </div>
                </div>
            </section>

            <!-- Contact Information -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">📞</div>
                    <h2>Contact Information</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="contactno">Contact Number</label>
                        <input type="tel" id="contactno" name="contactno">
                    </div>
                    
                    <div class="form-group">
                        <label for="mobile_no">Mobile Number</label>
                        <input type="tel" id="mobile_no" name="mobile_no" maxlength="15">
                    </div>
                    
                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="tel" id="telephone" name="telephone">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email">
                    </div>
                </div>
            </section>

            <!-- Government IDs -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">🆔</div>
                    <h2>Government ID Numbers</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="gsis_no">GSIS Number</label>
                        <input type="text" id="gsis_no" name="gsis_no">
                    </div>
                    
                    <div class="form-group">
                        <label for="sss_no">SSS Number</label>
                        <input type="text" id="sss_no" name="sss_no">
                    </div>
                    
                    <div class="form-group">
                        <label for="philhealthno">PhilHealth Number</label>
                        <input type="text" id="philhealthno" name="philhealthno">
                    </div>
                    
                    <div class="form-group">
                        <label for="tin">TIN</label>
                        <input type="text" id="tin" name="tin">
                    </div>
                </div>
            </section>

            <!-- Residential Address -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">🏠</div>
                    <h2>Residential Address</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="res_spec_address">House/Block/Lot No.</label>
                        <input type="text" id="res_spec_address" name="res_spec_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_street_address">Street</label>
                        <input type="text" id="res_street_address" name="res_street_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_vill_address">Subdivision/Village</label>
                        <input type="text" id="res_vill_address" name="res_vill_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_barangay_address">Barangay</label>
                        <input type="text" id="res_barangay_address" name="res_barangay_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_city">City</label>
                        <input type="text" id="res_city" name="res_city">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_municipality">Municipality</label>
                        <input type="text" id="res_municipality" name="res_municipality">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_province">Province</label>
                        <input type="text" id="res_province" name="res_province">
                    </div>
                    
                    <div class="form-group">
                        <label for="res_zipcode">Zip Code</label>
                        <input type="text" id="res_zipcode" name="res_zipcode">
                    </div>
                </div>
            </section>

            <!-- Permanent Address -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">📍</div>
                    <h2>Permanent Address</h2>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="sameAsResidential">
                        <span>Same as Residential Address</span>
                    </label>
                </div>
                
                <div class="form-grid" id="permanentAddressFields">
                    <div class="form-group">
                        <label for="perm_spec_address">House/Block/Lot No.</label>
                        <input type="text" id="perm_spec_address" name="perm_spec_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_street_address">Street</label>
                        <input type="text" id="perm_street_address" name="perm_street_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_vill_address">Subdivision/Village</label>
                        <input type="text" id="perm_vill_address" name="perm_vill_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_barangay_address">Barangay</label>
                        <input type="text" id="perm_barangay_address" name="perm_barangay_address">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_city">City</label>
                        <input type="text" id="perm_city" name="perm_city">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_municipality">Municipality</label>
                        <input type="text" id="perm_municipality" name="perm_municipality">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_province">Province</label>
                        <input type="text" id="perm_province" name="perm_province">
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_zipcode">Zip Code</label>
                        <input type="text" id="perm_zipcode" name="perm_zipcode">
                    </div>
                </div>
            </section>

            <!-- Additional Questions Section -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">📋</div>
                    <h2>Additional Information</h2>
                </div>
                
                <!-- Question 34 -->
                <div class="question-group">
                    <label class="question-label">34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be appointed?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q34" value="a">
                            <span>a. within the third degree?</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q34" value="b">
                            <span>b. within the fourth degree (for Local Government Unit - Career Employees)?</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q34" value="no">
                            <span>No</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="Q34_details">If YES, give details:</label>
                        <textarea id="Q34_details" name="Q34_details" rows="2"></textarea>
                    </div>
                </div>

                <!-- Question 35 -->
                <div class="question-group">
                    <label class="question-label">35. Answer the following questions:</label>
                    
                    <div class="sub-question">
                        <label class="question-label">a. Have you ever been found guilty of any administrative offense?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q35a" value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q35a" value="0">
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q35a_details">If YES, give details:</label>
                            <textarea id="Q35a_details" name="Q35a_details" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="sub-question">
                        <label class="question-label">b. Have you been criminally charged before any court?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q35b" value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q35b" value="0">
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q35b_details">If YES, give details:</label>
                            <textarea id="Q35b_details" name="Q35b_details" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Question 36 -->
                <div class="question-group">
                    <label class="question-label">36. Have you ever been criminally charged before any court?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q36" value="Yes">
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q36" value="No">
                            <span>No</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="Q36_details">If YES, give details:</label>
                        <textarea id="Q36_details" name="Q36_details" rows="2"></textarea>
                    </div>
                </div>

                <!-- Question 37 -->
                <div class="question-group">
                    <label class="question-label">37. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q37" value="1">
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q37" value="0">
                            <span>No</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="Q37_details">If YES, give details:</label>
                        <textarea id="Q37_details" name="Q37_details" rows="2"></textarea>
                    </div>
                </div>

                <!-- Question 38 -->
                <div class="question-group">
                    <label class="question-label">38. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q38" value="1">
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q38" value="0">
                            <span>No</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="Q38_details">If YES, give details:</label>
                        <textarea id="Q38_details" name="Q38_details" rows="2"></textarea>
                    </div>
                </div>

                <!-- Question 39 -->
                <div class="question-group">
                    <label class="question-label">39. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q39" value="1">
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q39" value="0">
                            <span>No</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="Q39_details">If YES, give details:</label>
                        <textarea id="Q39_details" name="Q39_details" rows="2"></textarea>
                    </div>
                </div>

                <!-- Question 40 -->
                <div class="question-group">
                    <label class="question-label">40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:</label>
                    
                    <div class="sub-question">
                        <label class="question-label">a. Are you a member of any indigenous group?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q40a" value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q40a" value="0">
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q40a_details">If YES, please specify:</label>
                            <input type="text" id="Q40a_details" name="Q40a_details">
                        </div>
                    </div>

                    <div class="sub-question">
                        <label class="question-label">b. Are you a person with disability?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q40b" value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q40b" value="0">
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q40b_details">If YES, please specify ID No:</label>
                            <input type="text" id="Q40b_details" name="Q40b_details">
                        </div>
                    </div>

                    <div class="sub-question">
                        <label class="question-label">c. Are you a solo parent?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q40c" value="1">
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q40c" value="0">
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q40c_details">If YES, please specify ID No:</label>
                            <input type="text" id="Q40c_details" name="Q40c_details">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="resetBtn">Reset Form</button>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-text">Submit Employee Record</span>
                    <span class="btn-icon">→</span>
                </button>
            </div>
        </form>
    </div>


    <script src="../../assets/js/scripts.js"></script>
    <script src="../../assets/js/employee_form.js"></script>
</body>
</html>
