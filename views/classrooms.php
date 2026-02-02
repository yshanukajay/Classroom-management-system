<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

requireLogin();

// Fetch all classrooms
$sql = "SELECT * FROM classrooms ORDER BY name";
$result = $mysqli->query($sql);
// Preload today's allocations to compute dynamic statuses (avoid per-row queries)
$today = date('D');
$currentTime = date('H:i:s');
$allocMap = [];
$allocRes = $mysqli->query("SELECT classroom_id, SUM(start_time <= '$currentTime' AND end_time > '$currentTime') as now_count, COUNT(*) as today_count FROM allocations WHERE day_of_week = '$today' GROUP BY classroom_id");
while ($a = $allocRes->fetch_assoc()) {
    $allocMap[(int)$a['classroom_id']] = [
        'now' => (int)$a['now_count'],
        'today' => (int)$a['today_count']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classrooms - EduSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Classroom-management-system/assets/css/style.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text);">Facilities</h1>
                    <p style="color: var(--text-light);">Manage and view all campus resources</p>
                </div>
                <!-- Action Area -->
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <?php if (isAdmin()): ?>
                        <button class="btn btn-primary" style="width: auto; padding: 0.75rem 1.5rem;"
                            onclick="openClassroomModal()">
                            + Add Classroom
                        </button>
                    <?php endif; ?>

                    <div style="text-align: right;">
                        <span style="display: block; font-weight: 600; color: var(--text);">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                        <span style="display: block; font-size: 0.875rem; color: var(--text-light);">
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </span>
                    </div>
                    <div
                        style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <div class="stats-grid"
                style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); margin-top: 2rem;">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="stat-card" style="position: relative; overflow: hidden;">
                            <!-- Admin Actions -->
                            <?php if (isAdmin()): ?>
                                <div style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem;">
                                    <button onclick='editClassroom(<?php echo json_encode($row); ?>)'
                                        style="background: white; border: 1px solid var(--border); border-radius: 6px; padding: 4px 8px; cursor: pointer; color: var(--text-light); transition: all 0.2s;"
                                        title="Edit">
                                        ✏️
                                    </button>
                                    <button onclick="deleteClassroom(<?php echo $row['id']; ?>)"
                                        style="background: white; border: 1px solid var(--border); border-radius: 6px; padding: 4px 8px; cursor: pointer; color: var(--danger); transition: all 0.2s;"
                                        title="Delete">
                                        🗑️
                                    </button>
                                </div>
                            <?php endif; ?>

                            <div
                                style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: start; padding-right: 4rem;">
                                <div>
                                    <span
                                        style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary); font-weight: 600; background: #EEF2FF; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        <?php echo htmlspecialchars($row['type']); ?>
                                    </span>
                                    <h3 style="margin-top: 0.5rem; font-size: 1.25rem; color: var(--text);">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </h3>
                                </div>
                                <div
                                    style="background: var(--background); padding: 0.5rem; border-radius: 8px; text-align: center; min-width: 60px;">
                                    <div style="font-size: 0.75rem; color: var(--text-light);">Cap</div>
                                    <div style="font-weight: 700; color: var(--text);">
                                        <?php echo $row['capacity']; ?>
                                    </div>
                                </div>
                            </div>

                            <div style="border-top: 1px solid var(--border); padding-top: 1rem; margin-top: 1rem;">
                                <p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 0.5rem;">
                                    <strong>Facilities:</strong>
                                </p>
                                <p style="font-size: 0.9rem; color: var(--text); line-height: 1.5;">
                                    <?php echo htmlspecialchars($row['facilities'] ?: 'None listed'); ?>
                                </p>
                            </div>

                            <div style="margin-top: 1rem; display: flex; justify-content: flex-end;">
                                <?php
                                    // Determine display status: prefer explicit DB status (Maintenance/Inactive), otherwise derive from allocations
                                    $rid = (int)$row['id'];
                                    $displayStatus = $row['status'];
                                    $color = '#10B981'; // green by default
                                    $dotColor = '';
                                    $allocNow = $allocMap[$rid]['now'] ?? 0;
                                    $allocToday = $allocMap[$rid]['today'] ?? 0;

                                    if ($row['status'] === 'Maintenance') {
                                        $displayStatus = 'Maintenance';
                                        $color = '#F59E0B';
                                    } elseif ($row['status'] === 'Inactive') {
                                        $displayStatus = 'Inactive';
                                        $color = '#9CA3AF';
                                    } elseif ($allocNow > 0) {
                                        $displayStatus = 'Occupied';
                                        $color = '#DC2626';
                                    } elseif ($allocToday > 0) {
                                        $displayStatus = 'Booked Today';
                                        $color = '#F59E0B';
                                    } else {
                                        $displayStatus = 'Active';
                                        $color = '#10B981';
                                    }
                                ?>
                                <span style="display: flex; align-items: center; gap: 0.5rem; color: <?php echo $color; ?>; font-weight: 500; font-size: 0.9rem;">
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: currentColor;"></span>
                                    <?php echo htmlspecialchars($displayStatus); ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-light);">
                        No classrooms found.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Classroom Modal -->
            <div class="modal-overlay" id="classroomModal">
                <div class="modal-content">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2 class="auth-title" style="font-size: 1.25rem; margin: 0;" id="modalTitle">Add Classroom</h2>
                        <button onclick="closeModal('classroomModal')"
                            style="border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                    </div>

                    <form id="classroomForm">
                        <input type="hidden" name="action" id="formAction" value="create">
                        <input type="hidden" name="id" id="classroomId">

                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-input" required
                                placeholder="e.g. Lab 1">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" id="type" class="form-input" required>
                                    <option value="Classroom">Classroom</option>
                                    <option value="Laboratory">Laboratory</option>
                                    <option value="Lecture Hall">Lecture Hall</option>
                                    <option value="Seminar">Seminar</option>
                                    <option value="Auditorium">Auditorium</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Capacity</label>
                                <input type="number" name="capacity" id="capacity" class="form-input" required
                                    placeholder="30">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Facilities</label>
                            <input type="text" name="facilities" id="facilities" class="form-input"
                                placeholder="Projector, PCs...">
                        </div>

                        <div class="form-group" id="statusGroup" style="display:none;">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-input">
                                <option value="Active">Active</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn">Save Classroom</button>
                    </form>
                </div>
            </div>

            <!-- CRUD JS -->
            <script src="/Classroom-management-system/assets/js/main.js"></script>
            <script>
                function openClassroomModal() {
                    document.getElementById('modalTitle').innerText = 'Add Classroom';
                    document.getElementById('formAction').value = 'create';
                    document.getElementById('classroomForm').reset();
                    document.getElementById('statusGroup').style.display = 'none';
                    document.getElementById('submitBtn').innerText = 'Add Classroom';
                    openModal('classroomModal');
                }

                function editClassroom(data) {
                    document.getElementById('modalTitle').innerText = 'Edit Classroom';
                    document.getElementById('formAction').value = 'update';
                    document.getElementById('classroomId').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('type').value = data.type;
                    document.getElementById('capacity').value = data.capacity;
                    document.getElementById('facilities').value = data.facilities;

                    document.getElementById('statusGroup').style.display = 'block';
                    document.getElementById('status').value = data.status;

                    document.getElementById('submitBtn').innerText = 'Update Classroom';
                    openModal('classroomModal');
                }

                async function deleteClassroom(id) {
                    if (!confirm('Are you sure you want to delete this classroom?')) return;

                    try {
                        const response = await fetch('api/classrooms.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'delete', id: id })
                        });
                        const result = await response.json();
                        if (result.status === 'success') {
                            showToast(result.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(result.message, 'error');
                        }
                    } catch (e) {
                        showToast('Error deleting classroom', 'error');
                    }
                }

                // Handle Form Submit
                document.getElementById('classroomForm').addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    try {
                        const response = await fetch('api/classrooms.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data)
                        });
                        const result = await response.json();
                        if (result.status === 'success') {
                            showToast(result.message);
                            closeModal('classroomModal');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showToast(result.message, 'error');
                        }
                    } catch (e) {
                        showToast('Error saving classroom', 'error');
                    }
                });
            </script>
        </main>
    </div>
</body>

</html>