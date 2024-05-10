<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'D:\Downloads\harika\htdocs\SecurinAssessment-Marepalli_Harika_yadav-Amrita_Vishwa_vidhyapeetham\config\config.php';  // Adjust the path as necessary
include 'D:\Downloads\harika\htdocs\SecurinAssessment-Marepalli_Harika_yadav-Amrita_Vishwa_vidhyapeetham\db\db.php';          // Adjust the path as necessary

function fetchCVEs($startIndex, $resultsPerPage) {
    echo "Fetching CVEs...\n";
    $url = API_BASE_URL . "?startIndex=$startIndex&resultsPerPage=$resultsPerPage";
    $context = stream_context_create(['http' => ['ignore_errors' => true]]);
    $json = file_get_contents($url, false, $context);

    if ($json === FALSE) {
        echo "Failed to fetch data from API.\n";
        return;
    }

    echo "API Response: " . $json . "\n"; // Outputs the API response

    $data = json_decode($json, true);
    // $totalResults = $data['totalResults']
    if (is_null($data) || !isset($data['vulnerabilities'])) {
        echo "Invalid data received from API.\n";
        return;
    }

    foreach ($data['vulnerabilities'] as $item) {
        echo "Processing CVE: " . $item['cve']['id'] . "\n";
        $cve_id = $item['cve']['id'];
        $identifier = $item['cve']['sourceIdentifier'];
        $publishedDate = $item['cve']['published'];
        $lastModifiedDate = $item['cve']['lastModified'];
        $status = $item['cve']['vulnStatus'];
        $description = $item['cve']['descriptions'][0]['value'];
        $severity = $item['cve']['metrics']['cvssMetricV2'][0]['baseSeverity'];
        $score = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['accessComplexity'];
        $vectorString = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['vectorString'];
        $accessVector = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['accessVector'];
        $accessComplexity = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['accessComplexity'];
        $authentication = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['authentication'];
        $confidentialityImpact = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['confidentialityImpact'];
        $integrityImpact = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['integrityImpact'];
        $availabilityImpact = $item['cve']['metrics']['cvssMetricV2'][0]['cvssData']['availabilityImpact'];
        $expScore = $item['cve']['metrics']['cvssMetricV2'][0]['exploitabilityScore'];
        $impactScore = $item['cve']['metrics']['cvssMetricV2'][0]['impactScore'];
        storeCVE($cve_id, $identifier, $publishedDate, $lastModifiedDate, $status, $description, $severity, $score, $vectorString, $accessVector, $accessComplexity, $authentication, $confidentialityImpact, $integrityImpact, $availabilityImpact, $expScore, $impactScore);
    }
}

function storeCVE($cve_id, $identifier, $publishedDate, $lastModifiedDate, $status, $description, $severity, $score, $vectorString, $accessVector, $accessComplexity, $authentication, $confidentialityImpact, $integrityImpact, $availabilityImpact, $expScore, $impactScore) {
    global $conn;
    $sql = "INSERT INTO CVEs (cve_id, identifier, published_date, last_modified_date, status, description, severity, score, vectorString, accessVector, accessComplexity, authentication, confidentialityImpact, integrityImpact, availabilityImpact, expScore, impactScore) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo "MySQL prepare error: " . mysqli_error($conn) . "\n";
        return;
    }

    // Bind parameters to the prepared statement
    $result = mysqli_stmt_bind_param($stmt, 'sssssssssssssssss', 
        $cve_id, $identifier, $publishedDate, $lastModifiedDate, $status,
        $description, $severity, $score, $vectorString, $accessVector, $accessComplexity,
        $authentication, $confidentialityImpact, $integrityImpact, $availabilityImpact,
        $expScore, $impactScore);

    if (!$result) {
        echo "Binding parameter error: " . mysqli_stmt_error($stmt) . "\n";
        return;
    }

    // Execute the prepared statement
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        echo "Execute error: " . mysqli_stmt_error($stmt) . "\n";
    } else {
        echo "Record updated/inserted successfully.\n";
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}


//fetchCVEs(0, 10);  // Fetch first 10 records; adjust as needed
$totalResults = fetchCVEs(0, 10);  // Fetch first 10 records; adjust as needed
$totalRecords = $totalResults;
?>
