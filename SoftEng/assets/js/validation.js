document.addEventListener("DOMContentLoaded", function() {
    // Form validation for sign up
    const formSignUp = document.getElementById("signup");
    const error = document.querySelectorAll(".valid"); // Assuming you have error elements in your HTML

    formSignUp.addEventListener("submit", function(e) {
        let email = formSignUp.email.value;
        let pass = formSignUp.pass.value;
        let conPass = formSignUp.conPass.value;
        let college = formSignUp.college ? formSignUp.college.value : null; // Get college value
        let department = formSignUp.department ? formSignUp.department.value : null; // Get department value

        // Clear previous error messages
        error.forEach(err => err.innerHTML = "");

        // Validation functions
        function validEmail(Email) {
            let emailReg = /^(^[a-z][a-zA-Z0-9-_.]+@(gmail|outlook).(com|fr))$/;
            if (emailReg.test(Email)) {
                return true;
            } else {
                error[0].innerHTML = "Email not valid"; // Assuming error[0] is for email
                return false;
            }
        }

        function validPass(Pass) {
            let passReg = /^([A-Za-z0-9]{3,16})$/; // Password must be 3-16 characters long
            if (passReg.test(Pass)) {
                return true;
            } else {
                error[1].innerHTML = "Password must be 8-16 characters long."; // Assuming error[1] is for password
                return false;
            }
        }

        // Check if college and department are selected based on role
        let validForm = true;
        const selectedRole = formSignUp.role.value; // Get selected role

        if (selectedRole === "Dean" || selectedRole === "Program Chair") {
            if (!college) {
                error[2].innerHTML = "Please select a college."; // Assuming error[2] is for college
                validForm = false;
            }
            if (departmentContainer.style.display === 'block' && !department) {
                error[3].innerHTML = "Please select a department."; // Assuming error[3] is for department
                validForm = false;
            }
        }

        // Validate email and password
        const isEmailValid = validEmail(email);
        const isPassValid = validPass(pass);

        // Check if passwords match
        if (pass !== conPass) {
            error[4].innerHTML = "Passwords do not match."; // Assuming error[4] is for password confirmation
            validForm = false;
        }

        // Prevent form submission if any validation fails
        if (!validForm || !isEmailValid || !isPassValid) {
            e.preventDefault();
        }
    });
});
