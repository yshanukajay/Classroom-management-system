<?php
require_once '../includes/auth.php';
requireLogin();

$pageTitle = "Dashboard";

// Fetch Dynamic Stats
// 1. Total Classrooms (all)
$sql = "SELECT COUNT(*) as total FROM classrooms";
$res = $mysqli->query($sql);
$totalRooms = $res->fetch_assoc()['total'];

// 2. Occupancy (today)
// Count distinct classrooms that have allocations today to show occupancy
$today = date('D'); // Mon, Tue...
$sql = "SELECT COUNT(DISTINCT classroom_id) as occupied FROM allocations WHERE day_of_week = '$today'";
$res = $mysqli->query($sql);
$occupied = $res->fetch_assoc()['occupied'];

// Occupancy Rate (based on today's allocations)
$occupancyRate = ($totalRooms > 0) ? round(($occupied / $totalRooms) * 100) : 0;

// Recent Allocations
$recentSql = "SELECT a.*, c.name as room_name 
              FROM allocations a 
              JOIN classrooms c ON a.classroom_id = c.id 
              ORDER BY a.id DESC LIMIT 5";
$recentAllocations = $mysqli->query($recentSql);

// Upcoming Allocations (Next 3 classes today)
$currentTime = date('H:i:s');
$upcomingSql = "SELECT a.*, c.name as room_name 
                FROM allocations a 
                JOIN classrooms c ON a.classroom_id = c.id 
                WHERE day_of_week = '$today' AND start_time > '$currentTime' 
                ORDER BY start_time ASC LIMIT 3";
$upcomingAllocations = $mysqli->query($upcomingSql);

// Real-time Availability
// maintenance rooms
// occupied today (any time today)
$occupiedTodayRes = $mysqli->query("SELECT DISTINCT classroom_id FROM allocations WHERE day_of_week = '$today'");
$occupiedTodayRooms = [];
while ($r = $occupiedTodayRes->fetch_assoc()) { $occupiedTodayRooms[] = (int)$r['classroom_id']; }
$occupiedTodayIds = count($occupiedTodayRooms) ? implode(',', $occupiedTodayRooms) : '0';
// currently occupied right now (based on day and current time)
$currentTime = date('H:i:s');
$occupiedNowRes = $mysqli->query("SELECT DISTINCT classroom_id FROM allocations WHERE day_of_week = '$today' AND start_time <= '$currentTime' AND end_time > '$currentTime'");
$occupiedNowRooms = [];
while ($r = $occupiedNowRes->fetch_assoc()) { $occupiedNowRooms[] = (int)$r['classroom_id']; }
// for SQL IN lists
$occupiedNowIds = count($occupiedNowRooms) ? implode(',', $occupiedNowRooms) : '0';
// maintenance and inactive counts
$maintenanceRes = $mysqli->query("SELECT COUNT(*) as count FROM classrooms WHERE status = 'Maintenance'");
$maintenanceCount = $maintenanceRes->fetch_assoc()['count'];
$inactiveRes = $mysqli->query("SELECT COUNT(*) as count FROM classrooms WHERE status = 'Inactive'");
$inactiveCount = $inactiveRes->fetch_assoc()['count'];
// unavailable = maintenance OR inactive OR booked for today (union)
$unavailableRes = $mysqli->query("SELECT COUNT(*) as count FROM classrooms WHERE status IN ('Maintenance','Inactive') OR id IN ($occupiedTodayIds)");
$unavailableCount = $unavailableRes->fetch_assoc()['count'];
// free count and active stat
$freeCount = $totalRooms - $unavailableCount;
if ($freeCount < 0) { $freeCount = 0; }
$activeCountForStat = $mysqli->query("SELECT COUNT(*) as count FROM classrooms WHERE status = 'Active'")->fetch_assoc()['count'];
$total = $totalRooms;
$availabilityWidth = ($total > 0) ? ($freeCount / $total) * 100 : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CAMS Admin</title>
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
            <div style="margin-bottom: 3rem;">
                <h1 class="auth-title">Overview</h1>
                <p class="auth-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Quick Actions -->
                <div class="card" style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 class="auth-title" style="font-size: 1.25rem; margin-bottom: 0.5rem;">Quick Actions</h3>
                        <p class="auth-subtitle" style="margin: 0; font-size: 0.9rem;">Manage your campus efficiently</p>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <a href="schedule.php" class="btn btn-primary" style="text-decoration: none; padding: 0.75rem 1.5rem; width: auto;">+ Book Room</a>
                        <a href="classrooms.php" class="btn" style="text-decoration: none; padding: 0.75rem 1.5rem; width: auto; background: #F1F5F9; color: var(--text);">Manage Rooms</a>
                    </div>
                </div>

                <!-- Real-time Availability Visual -->
                <div class="card" style="display: flex; flex-direction: column; justify-content: center;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-weight: 600; color: var(--text);">Current Availability</span>
                        <span style="font-weight: 700; color: var(--primary);"><?php echo $freeCount; ?> / <?php echo $total; ?> Free</span>
                    </div>
                    <div style="height: 10px; background: #E2E8F0; border-radius: 99px; overflow: hidden;">
                        <div style="height: 100%; width: <?php echo $availabilityWidth; ?>%; background: var(--success); transition: width 0.5s;"></div>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="card">
                    <div class="stat-label">Active Classrooms</div>
                    <div class="stat-value"><?php echo $activeCountForStat; ?></div>
                    <div class="badge badge-success">Operational</div>
                </div>
                <!-- Upcoming Classes -->
                <div class="card" style="grid-column: span 2;">
                    <div class="stat-label" style="margin-bottom: 1rem;">Upcoming Classes (Next Few Hours)</div>
                    <?php if ($upcomingAllocations->num_rows > 0): ?>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <?php while ($up = $upcomingAllocations->fetch_assoc()): ?>
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; background: #F8FAFC; border-radius: 0.75rem;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 40px; background: #EEF2FF; color: var(--primary); display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; font-weight: 700;">
                                            <?php echo date('H:i', strtotime($up['start_time'])); ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: var(--text);"><?php echo htmlspecialchars($up['course_name']); ?></div>
                                            <div style="font-size: 0.85rem; color: var(--text-light);"><?php echo htmlspecialchars($up['room_name']); ?> • <?php echo htmlspecialchars($up['instructor']); ?></div>
                                        </div>
                                    </div>
                                    <span class="badge badge-neutral">Standard</span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; color: var(--text-light); padding: 1rem;">No more classes scheduled for today.</div>
                    <?php endif; ?>
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