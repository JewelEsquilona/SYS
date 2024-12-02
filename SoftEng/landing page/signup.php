<?php
include '../connection.php'; // Include your database connection

// Fetch distinct colleges for the dropdown
try {
    $collegesQuery = "SELECT DISTINCT college FROM courses";
    $collegesStmt = $con->prepare($collegesQuery);
    $collegesStmt->execute();
    $existingColleges = $collegesStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Initialize unique number variable
$uniqueNumber = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $confirmPassword = $_POST['conPass'];
    $college = $_POST['college'] ?? null; // Get college from form
    $department = $_POST['department'] ?? null; // Get department from form

    // Debugging: Check if data is received
    error_log("Role: $role, Email: $email, College: $college, Department: $department");

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Generate a unique number (you can customize this logic)
        $uniqueNumber = uniqid('UNQ-', true); // Example: UNQ-1234567890.123456

        // Insert into the database
        $stmt = $con->prepare("INSERT INTO users (email, password, role, unique_number, college, department) VALUES (?, ?, ?, ?, ?, ?)");
        
        try {
            if ($stmt->execute([$email, $hashedPassword, $role, $uniqueNumber, $college, $department])) {
                // Fetch the unique number
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('uniqueNumber').textContent = 'Your Unique Number: ' + '$uniqueNumber';
                            document.getElementById('step3').style.display = 'block'; // Show Step 3
                        });
                      </script>";
            } else {
                echo "<script>alert('Registration failed! Please try again.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Signup Form</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-sign-in">
    <form id="signup" method="POST" action="">
        <div class="container">
            <h2 class="sign-in">Sign Up</h2>
            <div class="d-flex justify-content-center">
                <p>Enter your credentials to create your account</p>
            </div>
            <div class="steps">
                <div class="step active"></div>
                <div class="step"></div>
                <div class="step"></div>
            </div>

            <!-- Slide 1: Role Selection -->
            <div class="slider active" id="step1">
                <h5>Please Select a Role</h5>
                <div class="radio-options">
                    <label><input type="radio" name="role" value="Admin" required> Admin</label>
                    <label><input type="radio" name="role" value="Registrar"> Registrar</label>
                    <label><input type="radio" name="role" value="Dean"> Dean</label>
                    <label><input type="radio" name="role" value="Program Chair"> Program Chair</label>
                    <label><input type="radio" name="role" value="Alumni"> Alumni</label>
                </div>
                <div class="text-center">
                    <button type="button" class="next continue-button">Continue</button>
                </div>
                <div class="continue-sign-in-text">
                    <p class="mt-4">Already have an account? <a href="index.php">Sign In</a></p>
                </div>
            </div>

            <!-- Slide 2: Account Information -->
            <div class="slider" id="step2" style="display: none;">
                <div id="collegeDepartmentInfo" style="display: none;">
                    <label for="college">Select College:</label>
                    <select id="college" name="college" required>
                        <option value="" selected>Select College</option>
                        <?php foreach ($existingColleges as $college): ?>
                            <option value="<?= htmlspecialchars($college) ?>"><?= htmlspecialchars($college) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div id="departmentContainer" style="display: none;">
                        <label for="department">Select Department:</label>
                        <select id="department" name="department" required>
                            <option value="" selected>Select Department</option>
                        </select>
                    </div>
                </div>

                <div id="accountInfo">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="pass" required autocomplete="new-password">
                    <label for="conPass">Confirm Password:</label>
                    <input type="password" id="conPass" name="conPass" required autocomplete="new-password">
                </div>

                <div class="button-container">
                    <button type="button" class="back">Back</button>
                    <button type="submit" class="next">Submit</button>
                </div>
            </div>

            <!-- Slide 3: Unique Number Confirmation -->
            <div class="slider" id="step3" style="display: none;">
                <div class="text-center">
                    <h3>Your Unique Number</h3>
                    <p>This will be used for login purposes. Please don't forget.</p>
                </div>
                <div class="text-center">
                    <div class="alert alert-info" style="display: inline-block; padding: 15px; border-radius: 5px;">
                    <p id="uniqueNumber"></p>
                    </div>
                </div>
                <div class="button-container text-center">
                    <button type="button" onclick="window.location.href='index.php'" style="margin: auto;">I Understand</button>
                </div>
            </div>
        </div>
    </form>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="../assets/js/validation.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slider');
            const steps = document.querySelectorAll('.step');

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.display = (i === index) ? 'block' : 'none';
                });
                steps.forEach((step, i) => {
                    step.classList.toggle('active', i <= index);
                });

                // Reset form fields when going back to Slide 1 or Slide 2
                if (index === 0) {
                    resetForm();
                } else if (index === 1) {
                    resetDepartment();
                    resetCollege();
                }
            }

            function nextSlide() {
                currentSlide++;
                if (currentSlide >= slides.length) {
                    currentSlide = slides.length - 1;
                }
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide--;
                if (currentSlide < 0) {
                    currentSlide = 0;
                }
                showSlide(currentSlide);
            }

            function resetForm() {
                const radioButtons = document.querySelectorAll('input[name="role"]');
                radioButtons.forEach(radio => radio.checked = false);
                document.getElementById('accountInfo').style.display = 'block';
                document.getElementById('collegeDepartmentInfo').style.display = 'none';
            }

            function resetDepartment() {
                const departmentSelect = document.getElementById('department');
                departmentSelect.innerHTML = '<option value="" selected>Select Department</option>';
                departmentSelect.value = ''; // Reset selected value
                document.getElementById('departmentContainer').style.display = 'none'; // Hide department container
            }

            function resetCollege() {
                const collegeSelect = document.getElementById('college');
                collegeSelect.value = ''; // Reset selected value
            }

            // Handle role selection
            document.querySelectorAll('input[name="role"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const selectedRole = this.value;
                    document.getElementById('accountInfo').style.display = 'block';
                    document.getElementById('collegeDepartmentInfo').style.display = 'none';
                    document.getElementById('departmentContainer').style.display = 'none'; // Hide department container

                    if (selectedRole === 'Admin' || selectedRole === 'Registrar') {
                        document.getElementById('collegeDepartmentInfo').style.display = 'none';
                    } else if (selectedRole === 'Dean') {
                        document.getElementById('collegeDepartmentInfo').style.display = 'block';
                        document.getElementById('departmentContainer').style.display = 'none'; // Ensure department is hidden
                    } else if (selectedRole === 'Program Chair') {
                        document.getElementById('collegeDepartmentInfo').style.display = 'block';
                        document.getElementById('departmentContainer').style.display = 'none'; // Initially hide department container
                    } else if (selectedRole === 'Alumni') {
                        window.location.href = 'register.php'; // Redirect to register page
                    }
                });
            });

            document.querySelectorAll('.next').forEach(button => {
                button.addEventListener('click', nextSlide);
            });

            document.querySelectorAll('.back').forEach(button => {
                button.addEventListener('click', prevSlide);
            });

            // Fetch departments based on selected college
            document.getElementById('college').addEventListener('change', function() {
                const college = this.value;
                const departmentSelect = document.getElementById('department');

                // Clear previous departments
                departmentSelect.innerHTML = '<option value="" selected>Select Department</option>';

                if (college) {
                    fetch(`../dashboard/get_departments.php?college=${encodeURIComponent(college)}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(department => {
                                const option = document.createElement('option');
                                option.value = department.department; // Ensure this matches your database field
                                option.textContent = department.department; // Ensure this matches your database field
                                departmentSelect.appendChild(option);
                            });
                            // Show the department container only for Program Chair
                            const role = document.querySelector('input[name="role"]:checked');
                            if (role && role.value === 'Program Chair') {
                                document.getElementById('departmentContainer').style.display = 'block';
                            }
                        })
                        .catch(error => console.error('Error fetching departments:', error));
                } else {
                    document.getElementById('departmentContainer').style.display = 'none'; // Hide if no college selected
                }
            });

            // Prevent form submission if required fields are not filled
            document.getElementById('signup').addEventListener('submit', function(event) {
                const college = document.getElementById('college').value;
                const departmentContainer = document.getElementById('departmentContainer');
                const department = departmentContainer.style.display === 'block' ? document.getElementById('department').value : '';

                if (!college || (departmentContainer.style.display === 'block' && !department)) {
                    event.preventDefault(); // Prevent form submission
                    alert('Please select both college and department if applicable.');
                }
            });

            showSlide(currentSlide);
        });
    </script>
</body>

</html>
