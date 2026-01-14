<?php
// Get current page name to set active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="auth-header"
        style="text-align: left; margin-bottom: 2rem; padding: 0 1rem; display: flex; align-items: center; gap: 0.75rem;">
        <img src="/classroom_allocation_management_system/assets/images/logo.png" alt="EduSpace Logo"
            style="width: 28px; height: 28px;">
        <h2 class="auth-title" style="font-size: 1.5rem; margin: 0;">EduSpace</h2>
    </div>

    <nav style="padding: 0 1rem;">
        <a href="dashboard.php" class="nav-item <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <span>Dashboard</span>
        </a>
        <a href="classrooms.php" class="nav-item <?php echo $current_page === 'classrooms.php' ? 'active' : ''; ?>">
            <span>Classrooms</span>
        </a>
        <a href="schedule.php" class="nav-item <?php echo $current_page === 'schedule.php' ? 'active' : ''; ?>">
            <span>Schedule</span>
        </a>
        <a href="reports.php" class="nav-item <?php echo $current_page === 'reports.php' ? 'active' : ''; ?>">
            <span>Reports</span>
        </a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="users.php" class="nav-item <?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
                <span>Users</span>
            </a>
        <?php endif; ?>

        <a href="logout.php" class="nav-item" style="color: var(--danger); margin-top: 2rem;">
            <span>Logout</span>
        </a>
    </nav>
</aside>