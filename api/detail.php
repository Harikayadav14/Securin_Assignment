<?php
// Include your database connection details
include '../config/config.php';
include '../db/db.php';  // Make sure your db.php handles the database connection

// Function to fetch CVE details by ID
function fetchCVEById($cveId) {
    global $conn;  // Use the connection from the included db.php file

    // Prepare SQL query to fetch CVE details by ID
    $sql = "SELECT * FROM CVEs WHERE cve_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind CVE ID parameter
    mysqli_stmt_bind_param($stmt, 's', $cveId);
    
    // Execute query
    mysqli_stmt_execute($stmt);
    
    // Get result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch CVE details
    $cve = mysqli_fetch_assoc($result);

    // Close statement
    mysqli_stmt_close($stmt);

    return $cve;
}

$cve = null;
// Check if CVE ID is provided in the query parameter
if (isset($_GET['id'])) {
    // Get CVE ID from the query parameter
    $cveId = $_GET['id'];

    // Call function to fetch CVE details by ID
    $cve = fetchCVEById($cveId);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CVE Detail</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 20px auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($cve): ?>
            <h1>CVE-<?php echo htmlspecialchars($cve['cve_id']); ?></h1>
            <p><strong>Description:</strong><br> <?php echo htmlspecialchars($cve['description']); ?></p>
            <h3>CVSS V2 Metrics:</h3>
            <p><strong>Severity:</strong> <?php echo htmlspecialchars($cve['severity']); ?><strong>                  Score: </strong><span style="color: red;"><?php echo htmlspecialchars($cve['score']); ?></span></p>
</p>
            <p><strong>Vector String:</strong> <?php echo htmlspecialchars($cve['vectorString']); ?></p>
            <table>
                <tr>
                    <th>Access Vector</th>
                    <th>Access Complexity</th>
                    <th>Authentication</th>
                    <th>Confidentiality Impact</th>
                    <th>Integrity Impact</th>
                    <th>Availability Impact</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($cve['accessVector']); ?></td>
                    <td><?php echo htmlspecialchars($cve['accessComplexity']); ?></td>
                    <td><?php echo htmlspecialchars($cve['authentication']); ?></td>
                    <td><?php echo htmlspecialchars($cve['confidentialityImpact']); ?></td>
                    <td><?php echo htmlspecialchars($cve['integrityImpact']); ?></td>
                    <td><?php echo htmlspecialchars($cve['availabilityImpact']); ?></td>
                </tr>
            </table>
            <p><strong>Scores:</strong><br><br><strong> Exploitability Score:</strong> <?php echo htmlspecialchars($cve['expScore']); ?><br><br><strong>Impact Score: </strong><?php echo htmlspecialchars($cve['impactScore']); ?></p>
        <?php else: ?>
            <p>CVE not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
