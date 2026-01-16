<?php
require_once '../includes/auth.php';
requireLogin();

$classrooms_res = $mysqli->query("SELECT * FROM classrooms ORDER BY name");
$modal_classrooms = $mysqli->query("SELECT * FROM classrooms ORDER BY name"); // Second handle for modal
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Schedule - CAMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Classroom-management-system/assets/css/style.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <main class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 class="auth-title">Master Schedule</h1>
                    <p class="auth-subtitle">Manage class allocations</p>
                </div>
                <button class="btn btn-primary" style="width: auto;" onclick="openModal('allocationModal')">+ New
                    Allocation</button>
            </div>

            <!-- Schedule Grid Placeholder (Enhanced in future to be dynamic JS) -->
            <div class="card">
                <div class="alert alert-info" style="background: #EFF6FF; color: var(--primary);">
                    ℹ️ Pro Tip: Use the button above to add new classes. They will appear here instantly.
                </div>
                <!-- Logic to display current schedule simpler for PHP demo -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Course</th>
                                <th>Instructor</th>
                                <?php if (isAdmin()): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT a.*, c.name as room_name FROM allocations a JOIN classrooms c ON a.classroom_id = c.id ORDER BY FIELD(day_of_week, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), start_time";
                            $res = $mysqli->query($sql);
                            if ($res->num_rows > 0):
                                while ($row = $res->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><span class="badge badge-neutral"><?php echo $row['day_of_week']; ?></span></td>
                                        <td><?php echo date('H:i', strtotime($row['start_time'])) . ' - ' . date('H:i', strtotime($row['end_time'])); ?>
                                        </td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            <?php echo htmlspecialchars($row['room_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['instructor']); ?></td>
                                        <?php if (isAdmin()): ?>
                                            <td>
                                                <button onclick="deleteAllocation(<?php echo $row['id']; ?>)"
                                                    style="background: #FEE2E2; border: none; padding: 4px 8px; border-radius: 4px; color: #DC2626; cursor: pointer; font-size: 0.8rem; font-weight: 600;">
                                                    Delete
                                                </button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">No allocations yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Allocation Modal -->
    <div class="modal-overlay" id="allocationModal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 class="auth-title" style="font-size: 1.25rem; margin: 0;">New Allocation</h2>
                <button onclick="closeModal('allocationModal')"
                    style="border: none; background: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
            </div>

            <form id="allocationForm">
                <div class="form-group">
                    <label class="form-label">Classroom</label>
                    <select name="classroom_id" class="form-input" required>
                        <option value="">Select Room</option>
                        <?php while ($c = $modal_classrooms->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?> (Cap:
                                <?php echo $c['capacity']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Course Name</label>
                    <input type="text" name="course_name" class="form-input" placeholder="e.g. CS101" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Instructor</label>
                    <input type="text" name="instructor" class="form-input" placeholder="Dr. Smith" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Day</label>
                        <select name="day_of_week" class="form-input" required>
                            <option value="Mon">Monday</option>
                            <option value="Tue">Tuesday</option>
                            <option value="Wed">Wednesday</option>
                            <option value="Thu">Thursday</option>
                            <option value="Fri">Friday</option>
                        </select>
                    </div>
                    <div><!-- Spacer --></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-input" required>
                    </div>
                </div>

                <!-- Success/Error Message Container -->
                <div id="formMessage" class="form-message"
                    style="margin-bottom: 1rem; color: var(--danger); font-size: 0.9rem;"></div>

                <button type="submit" class="btn btn-primary">Create Allocation</button>
            </form>
        </div>
    </div>

    <!-- Main JS -->
    <script src="/Classroom-management-system/assets/js/main.js"></script>
    <script>
        // Specific init if needed, otherwise handled by main.js logic
        async function deleteAllocation(id) {
            if (!confirm('Are you sure you want to cancel this booking?')) return;

            try {
                const response = await fetch('../api/allocations.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });

                // Get text first to debug if JSON matches
                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Server returned invalid JSON:', text);
                    showToast('Server error: ' + text.substring(0, 50), 'error');
                    return;
                }

                if (result.status === 'success') {
                    showToast(result.message);
                    setTimeout(() => location.reload(), 500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (e) {
                console.error(e);
                showToast('Network error deleting allocation', 'error');
            }
        }
    </script>
</body>

</html>