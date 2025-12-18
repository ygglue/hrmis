<?php
session_start();

require_once "../../config/database.php";
require_once "../../app/auth/require.php";
requireAdmin();

$db = (new Database())->connect();
$error = '';

function generateUniqueId($pdo) {
    $id = random_int(100000, 999999999);
    
    // Check if it exists
    $stmt = $pdo->prepare("SELECT idemployees FROM employees WHERE idemployees = ?");
    $stmt->execute([$id]);
    
    if ($stmt->fetch()) {
        // If it exists, try again
        return generateUniqueId($pdo);
    }
    
    return $id;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $data = $_POST;

        function sanitizeText($val) {
            return ($val === '' || $val === null) ? 'N/A' : trim($val);
        }


        $mapped_data = [
            'first_name' => $data['first_name'] ?? null,
            'middle_name' => sanitizeText($data['middle_name'] ?? ''),
            'last_name' => $data['last_name'] ?? null, // Required
            'name_extension' => sanitizeText($data['name_extension'] ?? ''),
            'birthdate' => $data['birthdate'] ?? null, // Required
            'birth_city' => sanitizeText($data['birth_city'] ?? ''),
            'birth_province' => sanitizeText($data['birth_province'] ?? ''),
            'birth_country' => sanitizeText($data['birth_country'] ?? ''),
            'sex' => $data['sex'] ?? null, // Required
            'civil_status' => $data['civil_status'] ?? null, // Required
            'height_in_meter' => !empty($data['height_in_meter']) ? (float)$data['height_in_meter'] : 0,
            'weight_in_kg' => !empty($data['weight_in_kg']) ? (float)$data['weight_in_kg'] : 0,
            'contactno' => sanitizeText($data['contactno'] ?? ''),
            'blood_type' => sanitizeText($data['blood_type'] ?? ''),
            'gsis_no' => sanitizeText($data['gsis_no'] ?? ''),
            'sss_no' => sanitizeText($data['sss_no'] ?? ''),
            'philhealthno' => sanitizeText($data['philhealthno'] ?? ''),
            'tin' => sanitizeText($data['tin'] ?? ''),
            'employee_no' => !empty($data['employee_no']) ? (int)$data['employee_no'] : null,
            'citizenship' => sanitizeText($data['citizenship'] ?? ''),
            'res_spec_address' => sanitizeText($data['res_spec_address'] ?? ''),
            'res_street_address' => sanitizeText($data['res_street_address'] ?? ''),
            'res_vill_address' => sanitizeText($data['res_vill_address'] ?? ''),
            'res_barangay_address' => sanitizeText($data['res_barangay_address'] ?? ''),
            'res_city' => sanitizeText($data['res_city'] ?? ''),
            'res_municipality' => sanitizeText($data['res_municipality'] ?? ''),
            'res_province' => sanitizeText($data['res_province'] ?? ''),
            'res_zipcode' => sanitizeText($data['res_zipcode'] ?? ''),
            'perm_spec_address' => sanitizeText($data['perm_spec_address'] ?? ''),
            'perm_street_address' => sanitizeText($data['perm_street_address'] ?? ''),
            'perm_vill_address' => sanitizeText($data['perm_vill_address'] ?? ''),
            'perm_barangay_address' => sanitizeText($data['perm_barangay_address'] ?? ''),
            'perm_city' => sanitizeText($data['perm_city'] ?? ''),
            'perm_municipality' => sanitizeText($data['perm_municipality'] ?? ''),
            'perm_province' => sanitizeText($data['perm_province'] ?? ''),
            'perm_zipcode' => sanitizeText($data['perm_zipcode'] ?? ''),
            'telephone' => sanitizeText($data['telephone'] ?? ''),
            'mobile_no' => sanitizeText($data['mobile_no'] ?? ''),
            'email' => sanitizeText($data['email'] ?? ''),
            
            // Logic for Complex/Question Fields
            'Q34_details' => sanitizeText($data['Q34_details'] ?? ''),
            'Q35a' => isset($data['Q35a']) ? (int)$data['Q35a'] : 0,
            'Q35b' => isset($data['Q35b']) ? (int)$data['Q35b'] : 0,
            'Q35_details' => sanitizeText(trim(($data['Q35a_details'] ?? '') . ' ' . ($data['Q35b_details'] ?? ''))),
            'Q36' => $data['Q36'] ?? 0,
            'Q36_details' => sanitizeText($data['Q36_details'] ?? ''),
            'Q37' => isset($data['Q37']) ? (int)$data['Q37'] : 0,
            'Q37_details' => sanitizeText($data['Q37_details'] ?? ''),
            'Q38a' => isset($data['Q38a']) ? (int)$data['Q38a'] : 0,
            'Q38b' => isset($data['Q38b']) ? (int)$data['Q38b'] : 0,
            'Q38_details' => sanitizeText($data['Q38_details'] ?? ''),
            'Q39' => isset($data['Q39']) ? (int)$data['Q39'] : 0,
            'Q39_details' => sanitizeText($data['Q39_details'] ?? ''),
            'Q40a' => isset($data['Q40a']) ? (int)$data['Q40a'] : 0,
            'Q40a_details' => sanitizeText($data['Q40a_details'] ?? ''),
            'Q40b' => isset($data['Q40b']) ? (int)$data['Q40b'] : 0,
            'Q40b_details' => sanitizeText($data['Q40b_details'] ?? ''),
            'Q40c' => isset($data['Q40c']) ? (int)$data['Q40c'] : 0,
            'Q40c_details' => sanitizeText($data['Q40c_details'] ?? ''),
        ];

        $q34Val = $data['Q34'] ?? 'no';
        $mapped_data['Q34A'] = ($q34Val === 'a') ? 1 : 0;
        $mapped_data['Q34B'] = ($q34Val === 'b') ? 1 : 0;

        $columns = array_keys($mapped_data);
        $placeholders = array_map(function($col) { return ":$col"; }, $columns);

        $newId = generateUniqueId($db);
        
        $sql = "INSERT INTO employees (idemployees, " . implode(', ', $columns) . ") VALUES (". $newId . ", " . implode(', ', $placeholders) . ")";
        
        $stmt = $db->prepare($sql);
        
        foreach ($mapped_data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        if ($stmt->execute()) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: /index.php"); 
            }
            exit();
        } else {
            $error = "Failed to save employee...";
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit();
}

?>

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

        <form id="employeeForm" class="employee-form" method='POST'>
            <!-- Personal Information Section -->
            <section class="form-section">
                <div class="section-header">
                    <div class="section-icon">👤</div>
                    <h2>Personal Information</h2>
                </div>
                
                <div class="form-grid" id="formGrid" >
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="name_extension">Name Extension</label>
                        <input type="text" id="name_extension" name="name_extension" placeholder="Jr., Sr., III, etc." maxlength="5">
                    </div>
                    
                    <div class="form-group">
                        <label for="employee_no">Employee Number </label>
                        <input type="number" id="employee_no" name="employee_no" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="birthdate">Birthdate </label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="sex">Sex </label>
                        <select id="sex" name="sex" required>
                            <option value="" hidden>Select...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="civil_status">Civil Status </label>
                        <select id="civil_status" name="civil_status" required>
                            <option value="" hidden>Select...</option>
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
                        <select id="blood_type" name="blood_type" required>
                            <option value="" hidden>Select...</option>
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
                        <input type="number" id="height_in_meter" name="height_in_meter" step="0.01" placeholder="e.g., 1.75" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="weight_in_kg">Weight (kg)</label>
                        <input type="number" id="weight_in_kg" name="weight_in_kg" step="0.1" placeholder="e.g., 70.5" required>
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
                        <input type="text" id="birth_city" name="birth_city" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="birth_province">Birth Province</label>
                        <input type="text" id="birth_province" name="birth_province" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="birth_country">Birth Country</label>
                        <input type="text" id="birth_country" name="birth_country" required>
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
                        <input type="tel" id="contactno" name="contactno" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mobile_no">Mobile Number</label>
                        <input type="tel" id="mobile_no" name="mobile_no" maxlength="15" required>
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
                        <input type="text" id="tin" name="tin" required>
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
                        <input type="text" id="res_barangay_address" name="res_barangay_address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="res_city">City</label>
                        <input type="text" id="res_city" name="res_city" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="res_municipality">Municipality</label>
                        <input type="text" id="res_municipality" name="res_municipality" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="res_province">Province</label>
                        <input type="text" id="res_province" name="res_province" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="res_zipcode">Zip Code</label>
                        <input type="text" id="res_zipcode" name="res_zipcode" required>
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
                        <input type="text" id="perm_barangay_address" name="perm_barangay_address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_city">City</label>
                        <input type="text" id="perm_city" name="perm_city" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_municipality">Municipality</label>
                        <input type="text" id="perm_municipality" name="perm_municipality" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_province">Province</label>
                        <input type="text" id="perm_province" name="perm_province" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="perm_zipcode">Zip Code</label>
                        <input type="text" id="perm_zipcode" name="perm_zipcode" required>
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
                    <span class="required">*</span>
                    <label class="question-label">34. Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be appointed?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q34" value="a" required>
                            <span>a. within the third degree?</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q34" value="b" required>
                            <span>b. within the fourth degree (for Local Government Unit - Career Employees)?</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q34" value="no" required>
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
                        <span class="required">*</span>
                        <label class="question-label">a. Have you ever been found guilty of any administrative offense?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q35a" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q35a" value="0" required>
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q35a_details">If YES, give details:</label>
                            <textarea id="Q35a_details" name="Q35a_details" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="sub-question">
                        <span class="required">*</span>
                        <label class="question-label">b. Have you been criminally charged before any court?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q35b" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q35b" value="0" required>
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
                    <span class="required">*</span>
                    <label class="question-label">36. Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q36" value="1" required>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q36" value="0" required>
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
                    <span class="required">*</span>
                    <label class="question-label">37. Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q37" value="1" required>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q37" value="0" required>
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
                    <label class="question-label">38. Answer the following questions:</label>
                    <div class="sub-question">
                        <span class="required">*</span>
                        <label class="question-label">a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q38a" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q38a" value="0" required>
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q38a_details">If YES, give details:</label>
                            <textarea id="Q38a_details" name="Q35a_details" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="sub-question">
                        <span class="required">*</span>
                        <label class="question-label">b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q38b" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q38b" value="0" required>
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q38b_details">If YES, give details:</label>
                            <textarea id="Q38b_details" name="Q35b_details" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Question 39 -->
                <div class="question-group">
                    <span class="required">*</span>
                    <label class="question-label">39. Have you acquired the status of an immigrant or permanent resident of another country?</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="Q39" value="1" required>
                            <span>Yes</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="Q39" value="0" required>
                            <span>No</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="Q37_details">If YES, give details:</label>
                        <textarea id="Q37_details" name="Q37_details" rows="2"></textarea>
                    </div>
                </div>

                <!-- Question 40 -->
                <div class="question-group">
                    <label class="question-label">40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:</label>
                    
                    <div class="sub-question">
                        <span class="required">*</span>
                        <label class="question-label">a. Are you a member of any indigenous group?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q40a" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q40a" value="0" required>
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q40a_details">If YES, please specify:</label>
                            <input type="text" id="Q40a_details" name="Q40a_details" >
                        </div>
                    </div>

                    <div class="sub-question">
                        <span class="required">*</span>
                        <label class="question-label">b. Are you a person with disability?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q40b" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q40b" value="0" required>
                                <span>No</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="Q40b_details">If YES, please specify ID No:</label>
                            <input type="text" id="Q40b_details" name="Q40b_details">
                        </div>
                    </div>

                    <div class="sub-question">
                        <span class="required">*</span>
                        <label class="question-label">c. Are you a solo parent?</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="Q40c" value="1" required>
                                <span>Yes</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="Q40c" value="0" required>
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
                <?php if ($error): ?>
                    <div class="message error">
                        <span>⚠</span>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
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
