<?php
// Include your database connection details
include '../config/config.php';
include '../db/db.php';  // Make sure your db.php handles the database connection
//include '../api/fetch_cve_data.php';

// Function to fetch all CVEs from the database along with total records
function fetchCVEsPerPage($pageNumber, $recordsPerPage) {
    global $conn;  // Use the connection from the included db.php file
    $offset = ($pageNumber - 1) * $recordsPerPage;
    $sql = "SELECT * FROM CVEs LIMIT $offset, $recordsPerPage";
    $result = mysqli_query($conn, $sql);

    $cves = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cves[] = $row;
        }
    }
    // Close the database connection
    mysqli_close($conn);

    return ['cves' => $cves];
}

// Get the selected number of records per page from the URL parameter
$recordsPerPage = isset($_GET['recordsPerPage']) ? intval($_GET['recordsPerPage']) : 10;
$pageNumber = isset($_GET['page']) ? intval($GET['page']) : 1;
// Call the function to fetch CVE data
$data = fetchCVEsPerPage($pageNumber, $recordsPerPage);
$cves = $data['cves'];
//$totalPages = ceil($totalRecords / $recordsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CVE Data</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Add hover effect to rows */
        tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            text-direction: none;
            padding: 8px 16px;
            color: #000;
            border: 1px solid #ddd;
            margin: 0 4px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <h2>CVE List</h2>
    <h5>Total Records: <?php echo count($cves); ?></h5>
    
    <!-- Dropdown menu to select records per page -->
    <label for="recordsPerPage">Records Per Page:</label>
    <select id="recordsPerPage" onchange="reloadPage(this.value)">
        <option value="10" <?php if ($recordsPerPage == 10) echo "selected"; ?>>10</option>
        <option value="20" <?php if ($recordsPerPage == 50) echo "selected"; ?>>50</option>
        <option value="50" <?php if ($recordsPerPage == 100) echo "selected"; ?>>100</option>
    </select>
    
    <table>
        <thead>
            <tr>
            
                <th>CVE ID</th>
                <th>Identifier</th>
                <th>Published Date</th>
                <th>Last Modified Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cves as $cve): ?>
                <tr onclick="window.location.href='detail.php?id=<?php echo $cve['cve_id']; ?>'">
                    
                    <td><?php echo $cve['cve_id']; ?></td>
                    <td><?php echo $cve['identifier']; ?></td>
                    <td><?php echo $cve['published_date']; ?></td>
                    <td><?php echo $cve['last_modified_date']; ?></td>
                    <td><?php echo $cve['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        
    </div>
    <script>
        // JavaScript function to reload the page with selected records per page
        function reloadPage(recordsPerPage) {
            window.location.href = '?recordsPerPage=' + recordsPerPage;
        }
    </script>
</body>
</html>