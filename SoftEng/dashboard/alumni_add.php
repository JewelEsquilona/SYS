<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Alumni</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        include '../connection.php';

        $collegesQuery = "SELECT DISTINCT college FROM courses";
        $departmentsQuery = "SELECT DISTINCT department FROM courses";
        $sectionsQuery = "SELECT DISTINCT section FROM courses";

        $collegesStmt = $con->prepare($collegesQuery);
        $departmentsStmt = $con->prepare($departmentsQuery);
        $sectionsStmt = $con->prepare($sectionsQuery);

        $collegesStmt->execute();
        $departmentsStmt->execute();
        $sectionsStmt->execute();

        $existingColleges = $collegesStmt->fetchAll(PDO::FETCH_COLUMN);
        $existingDepartments = $departmentsStmt->fetchAll(PDO::FETCH_COLUMN);
        $existingSections = $sectionsStmt->fetchAll(PDO::FETCH_COLUMN);
        ?>

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

        <div class="button-add-alumni">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Alumni</button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Alumni</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="alumni_process.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="Student_Number" class="col-form-label">Student Number:</label>
                                    <input type="text" class="form-control" id="Student_Number" name="Student_Number" required autocomplete="off">
                                </div>
                                <input type="hidden" name="Alumni_ID_Number" value="<?php echo htmlspecialchars($alumniID); ?>">
                                <div class="mb-3">
                                    <label for="Last_Name" class="col-form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="Last_Name" name="Last_Name" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="First_Name" class="col-form-label">First Name:</label>
                                    <input type="text" class="form-control" id="First_Name" name="First_Name" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="Middle_Name" class="col-form-label">Middle Name:</label>
                                    <input type="text" class="form-control" id="Middle_Name" name="Middle_Name" autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="College" class="col-form-label">College:</label>
                                    <select class="form-select" id="College" name="College" required onchange="updateDepartments()">
                                        <option value="">Select College</option>
                                        <?php foreach ($existingColleges as $college): ?>
                                            <option value="<?= htmlspecialchars($college) ?>"><?= htmlspecialchars($college) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3" id="DepartmentContainer" style="display: none;">
                                    <label for="Department" class="col-form-label">Department:</label>
                                    <select class="form-select" id="Department" name="Department" required onchange="updateSections()">
                                        <option value="">Select Department</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="SectionContainer" style="display: none;">
                                    <label for="Section" class="col-form-label">Section:</label>
                                    <select class="form-select" id="Section" name="Section" required>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="Year_Graduated" class="col-form-label">Year Graduated:</label>
                                    <input type="text" class="form-control" id="Year_Graduated" name="Year_Graduated" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="Contact_Number" class="col-form-label">Contact Number:</label>
                                    <input type="text" class="form-control" id="Contact_Number" name="Contact_Number" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="Personal_Email" class="col-form-label">Personal Email:</label>
                                    <input type="email" class="form-control" id="Personal_Email" name="Personal_Email" required autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="Employment" class="col-form-label">Employment:</label>
                                    <select class="form-select" id="Employment" name="Employment" required onchange="toggleEmploymentFields()">
                                        <option value="">Select Employment Status</option>
                                        <option value="Employed">Employed</option>
                                        <option value="Self-Employed">Self-Employed</option>
                                        <option value="Actively Looking for a Job">Actively Looking for a Job</option>
                                        <option value="Never Been Employed">Never Been Employed</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="EmploymentStatusContainer" style="display: none;">
                                    <label for="Employment_Status" class="col-form-label">Employment Status:</label>
                                    <select class="form-select" id="Employment_Status" name="Employment_Status">
                                        <option value="">Select Employment Status</option>
                                        <option value="Regular/Permanent">Regular/Permanent</option>
                                        <option value="Casual">Casual</option>
                                        <option value="Contractual">Contractual</option>
                                        <option value="Temporary">Temporary</option>
                                        <option value="Part-time (seeking full-time)">Part-time (seeking full-time)</option>
                                        <option value="Part-time (not seeking full-time)">Part-time (but not seeking full-time)</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="PresentOccupationContainer" style="display: none;">
                                    <label for="Present_Occupation" class="col-form-label">Present Occupation:</label>
                                    <input type="text" class="form-control" id="Present_Occupation" name="Present_Occupation" autocomplete="off">
                                </div>
                                <div class="mb-3" id="EmployerNameContainer" style="display: none;">
                                    <label for="Name_of_Employer" class="col-form-label">Name of Employer:</label>
                                    <input type="text" class="form-control" id="Name_of_Employer" name="Name_of_Employer" autocomplete="off">
                                </div>
                                <div class="mb-3" id="EmployerAddressContainer" style="display: none;">
                                    <label for="Address_of_Employer" class="col-form-label">Address of Employer:</label>
                                    <input type="text" class="form-control" id="Address_of_Employer" name="Address_of_Employer" autocomplete="off">
                                </div>
                                <div class="mb-3" id="YearsInEmployerContainer" style="display: none;">
                                    <label for="Number_of_Years_in_Present_Employer" class="col-form-label">Number of Years in Present Employer:</label>
                                    <input type="number" class="form-control" id="Number_of_Years_in_Present_Employer" name="Number_of_Years_in_Present_Employer" autocomplete="off">
                                </div>
                                <div class="mb-3" id="TypeOfEmployerContainer" style="display: none;">
                                    <label for="Type_of_Employer" class="col-form-label">Type of Employer:</label>
                                    <input type="text" class="form-control" id="Type_of_Employer" name="Type_of_Employer" autocomplete="off">
                                </div>
                                <div class="mb-3" id="MajorLineOfBusinessContainer" style="display: none;">
                                    <label for="Major_Line_of_Business" class="col-form-label">Major Line of Business:</label>
                                    <input type="text" class="form-control" id="Major_Line_of_Business" name="Major_Line_of_Business" autocomplete="off">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script>
        function updateDepartments() {
            const college = document.getElementById('College').value;
            const departmentSelect = document.getElementById('Department');
            const sectionSelect = document.getElementById('Section');

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
                        document.getElementById('DepartmentContainer').style.display = 'block';
                    });
            } else {
                document.getElementById('DepartmentContainer').style.display = 'none';
            }
        }

        function updateSections() {
            const department = document.getElementById('Department').value;
            const sectionSelect = document.getElementById('Section');

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
                        document.getElementById('SectionContainer').style.display = 'block';
                    });
            } else {
                document.getElementById('SectionContainer').style.display = 'none';
            }
        }

        function toggleEmploymentFields() {
            const employmentStatus = document.getElementById('Employment').value;

            const fieldsToHide = [
                'EmploymentStatusContainer',
                'PresentOccupationContainer',
                'EmployerNameContainer',
                'EmployerAddressContainer',
                'YearsInEmployerContainer',
                'TypeOfEmployerContainer',
                'MajorLineOfBusinessContainer'
            ];
            fieldsToHide.forEach(field => {
                document.getElementById(field).style.display = 'none';
            });

            if (employmentStatus === 'Employed') {
                fieldsToHide.forEach(field => {
                    document.getElementById(field).style.display = 'block';
                });
            }
        }
    </script>
</body>

</html>
