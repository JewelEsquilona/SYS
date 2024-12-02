<?php
session_start();
include '../connection.php'; // Include your database connection

/**
 * Check user privileges based on their role.
 *
 * @param string $userRole
 * @return array
 */
function checkUserPrivileges($userRole) {
    switch ($userRole) {
        case 'Admin':
            return ['home.php', 'alumni.php', 'courses.php']; // Admin can access all
        case 'Registrar':
            return ['home.php', 'alumni.php']; // Registrar access
        case 'Dean':
            return ['alumni.php']; // Dean access
        case 'Program Chair':
            return ['alumni.php']; // Program Chair access
        case 'Alumni':
            return ['alumni.php']; // Alumni access
        default:
            return []; // No access
    }
}

/**
 * Set user session after login.
 *
 * @param array $userData
 */
function setUserSession($userData) {
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['user_role'] = $userData['role'];
    $_SESSION['alumni_id'] = $userData['Alumni_ID_Number'] ?? null; // For alumni
    $_SESSION['user_college'] = $userData['college'] ?? null; // For Dean
    $_SESSION['user_department'] = $userData['department'] ?? null; // For Program Chair
}

/**
 * Render sidebar based on user role.
 *
 * @param string $role
 */
function renderSidebar($role) {
    $accessiblePages = checkUserPrivileges($role);
    echo '<ul class="sidebar-menu">';
    foreach ($accessiblePages as $page) {
        echo '<li><a href="' . htmlspecialchars($page) . '">' . ucfirst(basename($page, '.php')) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Generate a unique ID based on user role.
 *
 * @param string $role
 * @param string|null $college
 * @param string|null $department
 * @return string|null
 */
function generateUniqueID($role, $college = null, $department = null) {
    global $con; // Assuming you have a global database connection

    if ($role === 'Alumni') {
        // Generate Alumni ID
        $stmt = $con->query("SELECT COUNT(*) FROM `2024-2025`");
        $count = $stmt->fetchColumn();
        return 'AL' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    } elseif ($role === 'Dean') {
        // Generate Dean ID
        $collegeAbbreviation = strtoupper(substr($college, 0, 3)); // e.g., ENG for Engineering
        $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE role = 'Dean' AND college = :college");
        $stmt->bindParam(':college', $college);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return 'DE' . $collegeAbbreviation . str_pad($count + 1, 3, '0', STR_PAD_LEFT); // e.g., DEENG001
    } elseif ($role === 'Program Chair') {
        // Generate Program Chair ID
        $departmentAbbreviation = strtoupper(substr($department, 0, 3)); // e.g., CS for Computer Science
        $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE role = 'Program Chair' AND department = :department");
        $stmt->bindParam(':department', $department);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return 'PC' . $departmentAbbreviation . str_pad($count + 1, 3, '0', STR_PAD_LEFT); // e.g., PCCS001
    }

    return null; // In case of an invalid role
}

/**
 * Check if the user is logged in.
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // Redirect to login if not logged in
        exit();
    }
}
?>
