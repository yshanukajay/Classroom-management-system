<?php
require_once '../includes/auth.php';
requireLogin();

$pageTitle = "Dashboard";

// Fetch Dynamic Stats
// 1. Total Classrooms
$sql = "SELECT COUNT(*) as total FROM classrooms WHERE status = 'Active'";
$res = $mysqli->query($sql);
$totalClassrooms = $res->fetch_assoc()['total'];

// 2. Pending Allocations (Simulated logic: allocations in future)
// In a real system you might have a 'status' column in allocations table
$sql = "SELECT COUNT(*) as count FROM allocations WHERE status = 'Active'"; // Assuming all active for now
// Actually allocations table didn't have status in initial schema, let's just count total allocations for today to show 'Occupancy'
$today = date('D'); // Mon, Tue...
$sql = "SELECT COUNT(DISTINCT classroom_id) as occupied FROM allocations WHERE day_of_week = '$today'";
$res = $mysqli->query($sql);
$occupied = $res->fetch_assoc()['occupied'];

// Occupancy Rate
$occupancyRate = ($totalClassrooms > 0) ? round(($occupied / $totalClassrooms) * 100) : 0;

// Recent Allocations
$recentSql = "SELECT a.*, c.name as room_name 
              FROM allocations a 
              JOIN classrooms c ON a.classroom_id = c.id 
              ORDER BY a.id DESC LIMIT 5";
$recentAllocations = $mysqli->query($recentSql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CAMS Admin</title>
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
            <div style="margin-bottom: 3rem;">
                <h1 class="auth-title">Overview</h1>
                <p class="auth-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>

            <div class="stats-grid">
                <div class="card">
                    <div class="stat-label">Active Classrooms</div>
                    <div class="stat-value"><?php echo $totalClassrooms; ?></div>
                    <div class="badge badge-success">Operational</div>
                </div>
                <div class="card">
                    <div class="stat-label">Today's Utilization</div>
                    <div class="stat-value"><?php echo $occupancyRate; ?>%</div>
                    <div class="badge badge-neutral"><?php echo $occupied; ?> rooms in use</div>
                </div>
                <div class="card">
                    <div class="stat-label">Total Requests</div>
                    <div class="stat-value">--</div>
                    <div class="badge badge-warning">Coming Soon</div>
                </div>
            </div>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 class="auth-title" style="font-size: 1.25rem; margin: 0;">Recent Allocations</h3>
                    <a href="schedule.php" class="btn btn-primary" style="width: auto;">View Full Schedule</a>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Time</th>
                                <th>Day</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recentAllocations->num_rows > 0): ?>
                                <?php while ($row = $recentAllocations->fetch_assoc()): ?>
                                    <tr>
                                        <td style="font-weight: 500; color: var(--primary);">
                                            <?php echo htmlspecialchars($row['room_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div
                                                    style="width: 24px; height: 24px; background: #E0E7FF; border-radius: 50%; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                                    <?php echo substr($row['instructor'], 0, 1); ?>
                                                </div>
                                                <?php echo htmlspecialchars($row['instructor']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo date('H:i', strtotime($row['start_time'])) . ' - ' . date('H:i', strtotime($row['end_time'])); ?>
                                        </td>
                                        <td><span
                                                class="badge badge-neutral"><?php echo htmlspecialchars($row['day_of_week']); ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-light);">No allocations
                                        found yet.</td>
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