<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous" />
</head>
<body class="bg-content">
    <main class="dashboard d-flex">
        <?php include "component/sidebar.php"; ?>
        <div class="container-fluid px-4">
            <?php include "component/header.php"; ?>
            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            include '../connection.php';
            if (!isset($con)) {
                die("Connection variable is not set.");
            }

            try {
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
                $deleteId = $_POST['delete_id'];
                $deleteQuery = "DELETE FROM courses WHERE id = ?";
                $deleteStmt = $con->prepare($deleteQuery);
                $deleteStmt->execute([$deleteId]);
                echo "<div class='alert alert-success'>Course deleted successfully!</div>";
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
                $editId = $_POST['edit_id'];
                $editCollege = trim($_POST['edit_college']);
                $editDepartment = trim($_POST['edit_department']);
                $editSection = trim($_POST['edit_section']);

                $updateQuery = "UPDATE courses SET college = ?, department = ?, section = ? WHERE id = ?";
                $updateStmt = $con->prepare($updateQuery);
                $updateStmt->execute([$editCollege, $editDepartment, $editSection, $editId]);
                echo "<div class='alert alert-success'>Course updated successfully!</div>";
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                $collegeName = trim($_POST['CollegeName']);
                $departmentName = trim($_POST['DepartmentName']);
                $existingCollege = $_POST['existingColleges'];
                $existingDepartment = $_POST['existingDepartments'];
                $college = ($_POST['collegeOption'] === 'existing' && !empty($existingCollege)) ? $existingCollege : $collegeName;
                $department = ($_POST['departmentOption'] === 'existing' && !empty($existingDepartment)) ? $existingDepartment : $departmentName;
                $query = "INSERT INTO courses (college, department, section) VALUES (?, ?, ?)";
                $stmt = $con->prepare($query);

                foreach ($_POST['SectionName'] as $sectionName) {
                    $stmt->execute([$college, $department, trim($sectionName)]);
                }
                echo "<div class='alert alert-success'>Sections added successfully!</div>";
            }
            $collegesQuery = "SELECT DISTINCT college FROM courses";
            $departmentsQuery = "SELECT DISTINCT department FROM courses";
            $collegesStmt = $con->prepare($collegesQuery);
            $departmentsStmt = $con->prepare($departmentsQuery);
            $collegesStmt->execute();
            $departmentsStmt->execute();

            $existingColleges = $collegesStmt->fetchAll(PDO::FETCH_COLUMN);
            $existingDepartments = $departmentsStmt->fetchAll(PDO::FETCH_COLUMN);
            ?>
            <div class="button-add-student">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Courses</button>
                
                <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCourseModalLabel">Add Courses</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">College:</label>
                                        <div>
                                            <input type="radio" name="collegeOption" id="newCollege" value="new" checked>
                                            <label for="newCollege">Add New College</label>
                                            <input type="radio" name="collegeOption" id="existingCollege" value="existing">
                                            <label for="existingCollege">Select Existing College</label>
                                        </div>
                                        <input type="text" class="form-control mt-2" id="CollegeName" name="CollegeName" placeholder="Enter new college" required>
                                        <select class="form-control mt-2" id="existingColleges" name="existingColleges" style="display: none;">
                                            <option value="">Select existing college</option>
                                            <?php foreach ($existingColleges as $college): ?>
                                                <option value="<?= htmlspecialchars($college) ?>"><?= htmlspecialchars($college) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department:</label>
                                        <div>
                                            <input type="radio" name="departmentOption" id="newDepartment" value="new" checked>
                                            <label for="newDepartment">Add New Department</label>
                                            <input type="radio" name="departmentOption" id="existingDepartment" value="existing">
                                            <label for="existingDepartment">Select Existing Department</label>
                                        </div>
                                        <input type="text" class="form-control mt-2" id="DepartmentName" name="DepartmentName" placeholder="Enter new department" required>
                                        <select class="form-control mt-2" id="existingDepartments" name="existingDepartments" style="display: none;">
                                            <option value="">Select existing department</option>
                                            <?php foreach ($existingDepartments as $department): ?>
                                                <option value="<?= htmlspecialchars($department) ?>"><?= htmlspecialchars($department) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="SectionName" class="form-label">Sections:</label>
                                        <div id="sectionInputs">
                                            <input type="text" class="form-control mt-2" name="SectionName[]" placeholder="Enter section name" required>
                                        </div>
                                        <button type="button" class="btn btn-secondary mt-2" id="addSectionBtn">Add Another Section</button>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">Add Course</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="courses">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>College</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $query = "SELECT id, college, department, section FROM courses ORDER BY college, department, section";
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($results) > 0) {
                        $lastCollege = '';
                        $lastDepartment = '';

                        foreach ($results as $row) {
                            $collegeDisplay = ($lastCollege !== $row['college']) ? $row['college'] : '';
                            $departmentDisplay = ($lastDepartment !== $row['department']) ? $row['department'] : '';

                            $lastCollege = $row['college'];
                            $lastDepartment = $row['department'];

                            echo "<tr>
                                    <td>{$collegeDisplay}</td>
                                    <td>{$departmentDisplay}</td>
                                    <td>{$row['section']}</td>
                                    <td>
                                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editCourseModal' data-id='{$row['id']}' data-college='{$row['college']}' data-department='{$row['department']}' data-section='{$row['section']}'>Edit</button>
                                        <form method='POST' action='' style='display:inline;'>
                                            <input type='hidden' name='delete_id' value='{$row['id']}'>
                                            <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this course?\");'>Delete</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No courses found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Course Modal -->
            <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="">
                                <input type="hidden" id="editCourseId" name="edit_id">
                                <div class="mb-3">
                                    <label for="editCollegeName" class="form-label">College:</label>
                                    <input type="text" class="form-control" id="editCollegeName" name="edit_college" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editDepartmentName" class="form-label">Department:</label>
                                    <input type="text" class="form-control" id="editDepartmentName" name="edit_department" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editSectionName" class="form-label">Section:</label>
                                    <input type="text" class="form-control" id="editSectionName" name="edit_section" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="update" class="btn btn-primary">Update Course</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script>
        const editCourseModal = document.getElementById('editCourseModal');
        editCourseModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const college = button.getAttribute('data-college');
            const department = button.getAttribute('data-department');
            const section = button.getAttribute('data-section');

            const modalTitle = editCourseModal.querySelector('.modal-title');
            const modalBodyInputCollege = editCourseModal.querySelector('#editCollegeName');
            const modalBodyInputDepartment = editCourseModal.querySelector('#editDepartmentName');
            const modalBodyInputSection = editCourseModal.querySelector('#editSectionName');

            modalTitle.textContent = `Edit Course: ${section}`;
            modalBodyInputCollege.value = college;
            modalBodyInputDepartment.value = department;
            modalBodyInputSection.value = section;

            editCourseModal.querySelector('#editCourseId').value = id;
        });

        document.querySelectorAll('input[name="collegeOption"]').forEach((elem) => {
            elem.addEventListener('change', function() {
                const isNew = this.value === 'new';
                const collegeInput = document.getElementById('CollegeName');
                const existingCollegeSelect = document.getElementById('existingColleges');

                collegeInput.style.display = isNew ? 'block' : 'none';
                existingCollegeSelect.style.display = isNew ? 'none' : 'block';
                collegeInput.required = isNew; 
            });
        });

        document.querySelectorAll('input[name="departmentOption"]').forEach((elem) => {
            elem.addEventListener('change', function() {
                const isNew = this.value === 'new';
                const departmentInput = document.getElementById('DepartmentName');
                const existingDepartmentSelect = document.getElementById('existingDepartments');

                departmentInput.style.display = isNew ? 'block' : 'none';
                existingDepartmentSelect.style.display = isNew ? 'none' : 'block';
                departmentInput.required = isNew; 
            });
        });

        document.getElementById('addSectionBtn').addEventListener('click', function() {
            const sectionInputs = document.getElementById('sectionInputs');
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'SectionName[]';
            newInput.className = 'form-control mt-2';
            newInput.placeholder = 'Enter section name';
            sectionInputs.appendChild(newInput);
        });
    </script>
</body>
</html>



