<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

requireLogin();

// Admin check
if (!isAdmin()) {
    header("Location: dashboard.php");
    exit;
}

// Fetch all users
$stmt = $mysqli->prepare("SELECT id, username, full_name, role, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - EduSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/classroom_allocation_management_system/assets/css/style.css">
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
                    <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text);">User Management</h1>
                    <p style="color: var(--text-light);">View and manage system users</p>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="text-align: right;">
                        <span style="display: block; font-weight: 600; color: var(--text);">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                        <span style="display: block; font-size: 0.875rem; color: var(--text-light);">
                            <?php echo ucfirst($_SESSION['role']); ?>
                        </span>
                    </div>
                </div>
            </header>

            <div class="card" style="margin-top: 2rem;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td style="font-weight: 500;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div
                                                    style="width: 32px; height: 32px; background: #EEF2FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 700;">
                                                    <?php echo strtoupper(substr($row['username'], 0, 1)); ?>
                                                </div>
                                                <?php echo htmlspecialchars($row['full_name']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($row['username']); ?>
                                        </td>
                                        <td>
                                            <span
                                                class="badge <?php echo $row['role'] === 'admin' ? 'badge-primary' : 'badge-neutral'; ?>">
                                                <?php echo ucfirst($row['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--text-light);">No users found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>