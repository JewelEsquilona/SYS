<?php 

// Start session 
if (!session_id()) { 
    session_start(); 
} 

include_once '../connection.php'; 

$res_status = $res_msg = ''; 
if (isset($_POST['importSubmit'])) { 
    // Allowed mime types 
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'); 
     
    // Validate whether selected file is a CSV file 
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) { 
         
        // If the file is uploaded 
        if (is_uploaded_file($_FILES['file']['tmp_name'])) { 
             
            // Open uploaded CSV file with read-only mode 
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r'); 
             
            // Skip the first line 
            fgetcsv($csvFile); 
             
            // Parse data from CSV file line by line 
            while (($line = fgetcsv($csvFile)) !== FALSE) { 
                $line_arr = !empty($line) ? array_filter($line) : ''; 
                if (!empty($line_arr)) { 
                    // Get row data 
                    $student_number = trim($line_arr[0]); 
                    $last_name = trim($line_arr[1]); 
                    $first_name = trim($line_arr[2]); 
                    $middle_name = trim($line_arr[3]); 
                    $college = trim($line_arr[4]); 
                    $department = trim($line_arr[5]); 
                    $section = trim($line_arr[6]); 
                    $year_graduated = trim($line_arr[7]); 
                    $contact_number = trim($line_arr[8]); 
                    $personal_email = trim($line_arr[9]); 
                    $employment = trim($line_arr[10]); 
                    $employment_status = trim($line_arr[11]); 
                    $present_occupation = trim($line_arr[12]); 
                    $name_of_employer = trim($line_arr[13]); 
                    $address_of_employer = trim($line_arr[14]); 
                    $number_of_years_in_present_employer = trim($line_arr[15]); 
                    $type_of_employer = trim($line_arr[16]); 
                    $major_line_of_business = isset($line_arr[17]) ? trim($line_arr[17]) : ''; 

                    // Check whether alumni already exists in the database with the same email 
                    $prevQuery = "SELECT `Alumni_ID_Number` FROM `2024-2025` WHERE `Personal_Email` = :email"; 
                    $stmt = $con->prepare($prevQuery);
                    $stmt->bindParam(':email', $personal_email);
                    $stmt->execute();
                     
                    if ($stmt->rowCount() > 0) { 
                        // Update alumni data in the database 
                        $alumni = $stmt->fetch(PDO::FETCH_ASSOC);
                        $alumni_id_number = $alumni['Alumni_ID_Number'];

                        $updateQuery = "UPDATE `2024-2025` SET `Student_Number` = :student_number, `Last_Name` = :last_name, `First_Name` = :first_name, `Middle_Name` = :middle_name, `College` = :college, `Department` = :department, `Section` = :section, `Year_Graduated` = :year_graduated, `Contact_Number` = :contact_number WHERE `Alumni_ID_Number` = :alumni_id_number";
                        $updateStmt = $con->prepare($updateQuery);
                        $updateStmt->execute([
                            ':student_number' => $student_number,
                            ':last_name' => $last_name,
                            ':first_name' => $first_name,
                            ':middle_name' => $middle_name,
                            ':college' => $college,
                            ':department' => $department,
                            ':section' => $section,
                            ':year_graduated' => $year_graduated,
                            ':contact_number' => $contact_number,
                            ':alumni_id_number' => $alumni_id_number // Corrected this line
                        ]);

                        // Update employment data
                        $updateEmploymentQuery = "UPDATE `2024-2025_ed` SET `Employment` = :employment, `Employment_Status` = :employment_status, `Present_Occupation` = :present_occupation, `Name_of_Employer` = :name_of_employer, `Address_of_Employer` = :address_of_employer, `Number_of_Years_in_Present_Employer` = :number_of_years_in_present_employer, `Type_of_Employer` = :type_of_employer, `Major_Line_of_Business` = :major_line_of_business WHERE `Alumni_ID_Number` = :alumni_id_number";
                        $updateEmploymentStmt = $con->prepare($updateEmploymentQuery);
                        $updateEmploymentStmt->execute([
                            ':employment' => $employment,
                            ':employment_status' => $employment_status,
                            ':present_occupation' => $present_occupation,
                            ':name_of_employer' => $name_of_employer,
                            ':address_of_employer' => $address_of_employer,
                            ':number_of_years_in_present_employer' => $number_of_years_in_present_employer,
                            ':type_of_employer' => $type_of_employer,
                            ':major_line_of_business' => $major_line_of_business,
                            ':alumni_id_number' => $alumni_id_number // Corrected this line
                        ]);
                    } else { 
                        // Insert alumni data in the database 
                        $insertQuery = "INSERT INTO `2024-2025` (`Student_Number`, `Last_Name`, `First_Name`, `Middle_Name`, `College`, `Department`, `Section`, `Year_Graduated`, `Contact_Number`, `Personal_Email`) VALUES (:student_number, :last_name, :first_name, :middle_name, :college, :department, :section, :year_graduated, :contact_number, :personal_email)"; 
                        $insertStmt = $con->prepare($insertQuery);
                        $insertStmt->execute([
                            ':student_number' => $student_number,
                            ':last_name' => $last_name,
                            ':first_name' => $first_name,
                            ':middle_name' => $middle_name,
                            ':college' => $college,
                            ':department' => $department,
                            ':section' => $section,
                            ':year_graduated' => $year_graduated,
                            ':contact_number' => $contact_number,
                            ':personal_email' => $personal_email
                        ]); 
                        
                        // Get the last inserted Alumni ID Number
                        $alumni_id_number = $con->lastInsertId();

                        // Insert employment data in the database 
                        $insertEmploymentQuery = "INSERT INTO `2024-2025_ed` (`Alumni_ID_Number`, `Employment`, `Employment_Status`, `Present_Occupation`, `Name_of_Employer`, `Address_of_Employer`, `Number_of_Years_in_Present_Employer`, `Type_of_Employer`, `Major_Line_of_Business`) VALUES (:alumni_id_number, :employment, :employment_status, :present_occupation, :name_of_employer, :address_of_employer, :number_of_years_in_present_employer, :type_of_employer, :major_line_of_business)"; 
                        $insertEmploymentStmt = $con->prepare($insertEmploymentQuery);
                        $insertEmploymentStmt->execute([
                            ':alumni_id_number' => $alumni_id_number,
                            ':employment' => $employment,
                            ':employment_status' => $employment_status,
                            ':present_occupation' => $present_occupation,
                            ':name_of_employer' => $name_of_employer,
                            ':address_of_employer' => $address_of_employer,
                            ':number_of_years_in_present_employer' => $number_of_years_in_present_employer,
                            ':type_of_employer' => $type_of_employer,
                            ':major_line_of_business' => $major_line_of_business
                        ]); 
                    } 
                } 
            } 
             
            // Close opened CSV file 
            fclose($csvFile); 
             
            $res_status = 'success'; 
            $res_msg = 'Alumni data has been imported successfully.'; 
        } else { 
            $res_status = 'danger'; 
            $res_msg = 'Something went wrong, please try again.'; 
        } 
    } else { 
        $res_status = 'danger'; 
        $res_msg = 'Please select a valid CSV file.'; 
    } 
 
    // Store status in SESSION 
    $_SESSION['response'] = array( 
        'status' => $res_status, 
        'msg' => $res_msg 
    ); 
} 
 
// Redirect to the listing page 
header("Location: alumni_list.php"); 
exit(); 
?>
