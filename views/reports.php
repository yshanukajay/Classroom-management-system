<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

requireLogin();

// Stats Queries
$total_allocations = $mysqli->query("SELECT COUNT(*) as count FROM allocations")->fetch_assoc()['count'];
$most_busy_day = $mysqli->query("SELECT day_of_week, COUNT(*) as count FROM allocations GROUP BY day_of_week ORDER BY count DESC LIMIT 1")->fetch_assoc();
$most_used_room = $mysqli->query("SELECT c.name, COUNT(*) as count FROM allocations a JOIN classrooms c ON a.classroom_id = c.id GROUP BY a.classroom_id ORDER BY count DESC LIMIT 1")->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - EduSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/classroom_allocation_management_system/assets/css/style.css">
    <style>
        .report-section {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1.5rem;
            border: 1px solid var(--border);
            margin-bottom: 2rem;
        }
    </style>
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
                    <h1 style="font-size: 1.75rem; font-weight: 700; color: var(--text);">System Analytics</h1>
                    <p style="color: var(--text-light);">Usage reports and allocation insights</p>
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

            <div class="stats-grid">
                <div class="stat-card">
                    <h3 class="stat-label">Total Allocations</h3>
                    <p class="stat-value">
                        <?php echo $total_allocations; ?>
                    </p>
                </div>
                <div class="stat-card">
                    <h3 class="stat-label">Most Busy Day</h3>
                    <p class="stat-value" style="font-size: 1.5rem;">
                        <?php echo $most_busy_day ? $most_busy_day['day_of_week'] : 'N/A'; ?>
                    </p>
                    <p style="font-size: 0.875rem; color: var(--text-light);">
                        <?php echo $most_busy_day ? $most_busy_day['count'] . ' bookings' : ''; ?>
                    </p>
                </div>
                <div class="stat-card">
                    <h3 class="stat-label">Most Used Room</h3>
                    <p class="stat-value" style="font-size: 1.25rem;">
                        <?php echo $most_used_room ? htmlspecialchars($most_used_room['name']) : 'N/A'; ?>
                    </p>
                    <p style="font-size: 0.875rem; color: var(--text-light);">
                        <?php echo $most_used_room ? $most_used_room['count'] . ' bookings' : ''; ?>
                    </p>
                </div>
            </div>

            <div class="report-section" style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--text);">Allocation Distribution</h3>
                <div style="height: 20px; background: #EEF2FF; border-radius: 99px; overflow: hidden; display: flex;">
                    <div style="width: 60%; background: var(--primary);"></div>
                    <div style="width: 25%; background: #10B981;"></div>
                    <div style="width: 15%; background: #F59E0B;"></div>
                </div>
                <div style="display: flex; gap: 2rem; margin-top: 1rem; font-size: 0.9rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: var(--primary); border-radius: 50%;"></div>
                        <span>Lecture Halls</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: #10B981; border-radius: 50%;"></div>
                        <span>Laboratories</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: #F59E0B; border-radius: 50%;"></div>
                        <span>Other</span>
                    </div>
                </div>
                <p style="margin-top: 1rem; color: var(--text-light); font-size: 0.85rem;">* Visualization based on
                    static data for demonstration.</p>
            </div>

        </main>
    </div>
</body>

</html>