document.addEventListener('DOMContentLoaded', function () {
    const queryParams = new URLSearchParams(window.location.search);
    const cveId = queryParams.get('cve_id');
    fetch(`api/api.php?cve_id=${cveId}`)
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('cveDetailContainer');
        container.innerHTML = `<div><strong>CVE ID:</strong> ${data.cve_id}</div><div><strong>Description:</strong> ${data.description}</div>`;
    });
});
