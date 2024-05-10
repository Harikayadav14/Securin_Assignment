document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('cveTableContainer');

    fetch('D:\Downloads\harika\htdocs\last\api\fetch_cve_data.php')  // Adjust the URL as necessary
    .then(response => response.json())
    .then(data => {
        if (data && Array.isArray(data)) {
            let html = `<table>
                            <tr>
                                <th>CVE ID</th>
                                <th>Identifier</th>
                                <th>Published Date</th>
                                <th>Last Modified Date</th>
                                <th>Status</th>
                            </tr>`;

            data.forEach(item => {
                html += `<tr>
                            <td>${item.cve_id}</td>
                            <td>${item.identifier}</td>
                            <td>${new Date(item.published_date).toLocaleDateString()}</td>
                            <td>${new Date(item.last_modified_date).toLocaleDateString()}</td>
                            <td>${item.status}</td>
                         </tr>`;
            });

            html += `</table>`;
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p>No data to display or incorrect data format.</p>';
        }
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        container.innerHTML = '<p>Error fetching data. Please check the console for more information.</p>';
    });
});
