<?php
include '../connection.php';

if (isset($_GET['Alumni_ID_Number'])) {
    $id = $_GET['Alumni_ID_Number'];

    $stmt = $con->prepare("SELECT 
                            a.Alumni_ID_Number,
                            a.Student_Number, 
                            a.Last_Name, 
                            a.First_Name, 
                            a.Middle_Name, 
                            a.College, 
                            a.Department, 
                            a.Section, 
                            a.Year_Graduated, 
                            a.Contact_Number, 
                            a.Personal_Email, 
                            ed.Employment,
                            ed. Employment_Status, 
                            ed.Present_Occupation, 
                            ed.Name_of_Employer, 
                            ed.Address_of_Employer, 
                            ed.Number_of_Years_in_Present_Employer, 
                            ed.Type_of_Employer, 
                            ed.Major_Line_of_Business
                          FROM `2024-2025` a 
                          LEFT JOIN `2024-2025_ed` ed ON a.Alumni_ID_Number = ed.Alumni_ID_Number 
                          WHERE a.Alumni_ID_Number = :id");

    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();

    $alumni = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$alumni) {
        header('Location: alumni_list.php');
        exit;
    }
} else {
    header('Location: alumni_list.php');
    exit;
}

$collegesQuery = "SELECT DISTINCT college FROM courses";
$collegesStmt = $con->prepare($collegesQuery);
$collegesStmt->execute();
$existingColleges = $collegesStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alumni</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/reg.css">
</head>

<body>
    <div class="container form-container mt-5">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <header class="mb-4">Update Alumni</header>
        <form action="alumni_process.php" method="POST" class="form">
            <input type="hidden" name="alumni_id" value="<?php echo htmlspecialchars($alumni['Alumni_ID_Number'] ?? ''); ?>">

            <div class="mb-3">
                <label for="student-number" class="form-label">Student Number</label>
                <input type="text" id="student-number" name="student_number" class="form-control" value="<?php echo htmlspecialchars($alumni['Student_Number'] ?? ''); ?>" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($alumni['Last_Name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($alumni['First_Name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($alumni['Middle_Name'] ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="college" class="form-label">College</label>
                    <select class="form-control" id="college" name="college" required onchange="updateDepartments()">
                        <option value="">Select College</option>
                        <?php foreach ($existingColleges as $college): ?>
                            <option value="<?= htmlspecialchars($college) ?>" <?= ($alumni['College'] == $college) ? 'selected' : ''; ?>><?= htmlspecialchars($college) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="department" class="form-label">Department</label>
                    <select class="form-control" id="department" name="department" required onchange="updateSections()">
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="section" class="form-label">Section</label>
                    <select class="form-control" id="section" name="section" required>
                        <option value="">Select Section</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="year_graduated" class="form-label">Year Graduated</label>
                    <input type="text" class="form-control" id="year_graduated" name="year_graduated" value="<?php echo htmlspecialchars($alumni['Year_Graduated'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($alumni['Contact_Number'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="personal_email" class="form-label">Personal Email</label>
                    <input type="email" class="form-control" id="personal_email" name="personal_email" value="<?php echo htmlspecialchars($alumni['Personal_Email'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="mb-3 row align-items-center">
                <div class="col-md-6">
                    <label for="employment" class="form-label">Employment</label>
                    <select id="employment" name="employment" class="form-control" required onchange="toggleEmploymentFields()">
                        <option value="">Select Employment</option>
                        <option value="Employed" <?= ($alumni['Employment'] == 'Employed') ? 'selected' : ''; ?>>Employed</option>
                        <option value="Self-employed" <?= ($alumni['Employment'] == 'Self-employed') ? 'selected' : ''; ?>>Self-employed</option>
                        <option value="Actively looking for a job" <?= ($alumni['Employment'] == 'Actively looking for a job') ? 'selected' : ''; ?>>Actively Looking for a Job</option>
                        <option value="Never been employed" <?= ($alumni['Employment'] == 'Never been employed') ? 'selected' : ''; ?>>Never Been Employed</option>
                    </select>
                </div>
                <div class="col-md-6" id="employment-status-container" style="display: <?= ($alumni['Employment'] == 'Employed') ? 'block' : 'none'; ?>">
                    <label for="employment_status" class="form-label">Employment Status</label>
                    <select id="employment_status" name="employment_status" class="form-control" required>
                        <option value="">Select Employment Status</option>
                        <option value="Regular/Permanent" <?= ($alumni['Employment_Status'] == 'Regular/Permanent') ? 'selected' : ''; ?>>Regular/Permanent</option>
                        <option value="Casual" <?= ($alumni['Employment_Status'] == 'Casual') ? 'selected' : ''; ?>>Casual</option>
                        <option value="Contractual" <?= ($alumni['Employment_Status'] == 'Contractual') ? 'selected' : ''; ?>>Contractual</option>
                        <option value="Temporary" <?= ($alumni['Employment_Status'] == 'Temporary') ? 'selected' : ''; ?>>Temporary</option>
                        <option value="Part-time (seeking full-time)" <?= ($alumni['Employment_Status'] == 'Part-time (seeking full-time)') ? 'selected' : ''; ?>>Part-time (seeking full-time)</option>
                        <option value="Part-time (but not seeking full-time)" <?= ($alumni['Employment_Status'] == 'Part-time (but not seeking full-time)') ? 'selected' : ''; ?>>Part-time (but not seeking full-time)</option>
                        <option value="Other" <?= ($alumni['Employment_Status'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div id="employmentFields" style="display: <?= ($alumni['Employment'] == 'Employed') ? 'block' : 'none'; ?>;">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="present_occupation" class="form-label">Present Occupation</label>
                        <input type="text" class="form-control" id="present_occupation" name="present_occupation" value="<?php echo htmlspecialchars($alumni['Present_Occupation'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="name_of_employer" class="form-label">Name of Employer</label>
                        <input type="text" class="form-control" id="name_of_employer" name="name_of_employer" value="<?php echo htmlspecialchars($alumni['Name_of_Employer'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="address_of_employer" class="form-label">Address of Employer</label>
                        <input type="text" class="form-control" id="address_of_employer" name="address_of_employer" value="<?php echo htmlspecialchars($alumni['Address_of_Employer'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="number_of_years_in_present_employer" class="form-label">Years in Present Employer</label>
                        <input type="number" class="form-control" id="number_of_years_in_present_employer" name="number_of_years_in_present_employer" value="<?php echo htmlspecialchars($alumni['Number_of_Years_in_Present_Employer'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="type_of_employer" class="form-label">Type of Employer</label>
                        <input type="text" class="form-control" id="type_of_employer" name="type_of_employer" value="<?php echo htmlspecialchars($alumni['Type_of_Employer'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="major_line_of_business" class="form-label">Major Line of Business</label>
                        <input type="text" class="form-control" id="major_line_of_business" name="major_line_of_business" value="<?php echo htmlspecialchars($alumni['Major_Line_of_Business'] ?? ''); ?>">
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="history.back()">Back</button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const collegeSelect = document.getElementById('college');
        const departmentSelect = document.getElementById('department');
        const sectionSelect = document.getElementById('section');

        updateDepartments(collegeSelect.value, "<?= htmlspecialchars($alumni['Department'] ?? '') ?>", "<?= htmlspecialchars($alumni['Section'] ?? '') ?>");

        collegeSelect.addEventListener('change', function() {
            updateDepartments(this.value, '', '');
        });

        departmentSelect.addEventListener('change', function() {
            updateSections(this.value, '');
        });
    });

    function updateDepartments(college, selectedDepartment, selectedSection) {
        const departmentSelect = document.getElementById('department');
        const sectionSelect = document.getElementById('section');

        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        sectionSelect.innerHTML = '<option value="">Select Section</option>';

        if (college) {
            fetch(`get_departments.php?college=${college}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(department => {
                        const option = document.createElement('option');
                        option.value = department;
                        option.textContent = department;
                        departmentSelect.appendChild(option);
                    });
                    departmentSelect.value = selectedDepartment;

                    updateSections(departmentSelect.value, selectedSection);
                });
        }
    }

    function updateSections(department, selectedSection) {
        const sectionSelect = document.getElementById('section');

        sectionSelect.innerHTML = '<option value="">Select Section</option>';

        if (department) {
            fetch(`get_sections.php?department=${department}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section;
                        option.textContent = section;
                        sectionSelect.appendChild(option);
                    });
                    sectionSelect.value = selectedSection;
                });
        }
    }

    function toggleEmploymentFields() {
        const employmentStatus = document.getElementById('employment').value;
        const employmentStatusContainer = document.getElementById('employment-status-container');
        const employmentFields = document.getElementById('employmentFields');

        if (employmentStatus === 'Employed') {
            employmentStatusContainer.style.display = 'block';
            employmentFields.style.display = 'block';
        } else {
            employmentStatusContainer.style.display = 'none';
            employmentFields.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleEmploymentFields(); 
    });
</script>

</body>

</html>
