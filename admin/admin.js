// Initialize Modal
let updateModal;
document.addEventListener("DOMContentLoaded", function () {
    updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
    loadBookings();
});

// Fetch Data from Backend
function loadBookings() {
    const status = document.getElementById('filterStatus').value;
    const city = document.getElementById('filterCity').value;

    const formData = new FormData();
    formData.append('action', 'fetch');
    formData.append('status', status);
    formData.append('city', city);

    fetch('admin_backend.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('bookingTableBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center">No bookings found</td></tr>';
                return;
            }

            data.forEach(row => {
                // --- NEW: Split Date and Time ---
                let dateTime = row.booking_date; // e.g., "2023-10-25 14:30:00"
                let dateOnly = dateTime.split(' ')[0];
                let timeOnly = dateTime.split(' ')[1];

                // Badge Colors
                let badgeClass = 'bg-secondary';
                if (row.booking_status === 'pending') badgeClass = 'bg-warning text-dark';
                if (row.booking_status === 'assigned') badgeClass = 'bg-info text-dark';
                if (row.booking_status === 'completed') badgeClass = 'bg-success';
                if (row.booking_status === 'canceled') badgeClass = 'bg-danger';

                const tr = `
                <tr>
                    <td>#${row.id}</td>
                    <td style="font-weight: 500;">${dateOnly}</td>
                    <td class="time-text"> ${timeOnly}</td>
                    <td>
                        <strong>${row.customer_name}</strong><br>
                        <small class="text-muted">${row.phone}</small>
                    </td>
                    <td>${row.category} <br> <small>(${row.sub_category})</small></td>
                    <td>${row.city}</td>
                    <td><span class="badge status-badge ${badgeClass}">${row.booking_status.toUpperCase()}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="openUpdateModal(${row.id}, '${row.customer_name}', '${row.booking_status}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </td>
                </tr>
            `;
                tbody.innerHTML += tr;
            });
        })
        .catch(err => console.error("Error loading data:", err));
}

// Open Modal with specific data
function openUpdateModal(id, name, currentStatus) {
    document.getElementById('updateId').value = id;
    document.getElementById('customerNameDisplay').innerText = name;
    document.getElementById('newStatus').value = currentStatus;
    updateModal.show();
}

// Submit Update
function submitUpdate() {
    const id = document.getElementById('updateId').value;
    const newStatus = document.getElementById('newStatus').value;

    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', id);
    formData.append('new_status', newStatus);

    fetch('admin_backend.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateModal.hide();
                loadBookings(); // Refresh table
            } else {
                alert("Error updating status.");
            }
        })
        .catch(err => console.error("Error updating:", err));
}