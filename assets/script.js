function updateStatus(ticketId, newStatus) {
    fetch('ajax_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${ticketId}&status=${newStatus}`
    })
    .then(response => response.text())
    .then(data => {
        alert('Status updated to: ' + newStatus);
    })
    .catch(error => console.error('Error:', error));
}