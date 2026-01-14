<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Classroom Allocation Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.1), transparent 40%),
                radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.1), transparent 40%);
        }

        .hero-content {
            max-width: 800px;
            animation: fadeIn 0.8s ease-out;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--text));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
            width: 100%;
            padding: 0 1rem;
        }

        .feature-card {
            background: var(--surface-glass);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .features-section {
            padding: 6rem 2rem;
            background: #ffffff;
            /* Explicit white background */
            min-height: 100vh;
            /* Full height */
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
            max-width: 700px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
        }
    </style>
</head>

<body>
    <div class="hero-section">
        <div class="hero-content">
            <div style="margin-bottom: 2rem; display: flex; justify-content: center;">
                <!-- Logo -->
                <div
                    style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.8); padding: 0.75rem 1.5rem; border-radius: 999px; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.5);">
                    <img src="assets/images/logo.png" alt="EduSpace Logo" style="width: 32px; height: 32px;">
                    <span style="font-weight: 700; color: var(--text); font-size: 1.25rem;">EduSpace</span>
                </div>
            </div>
            <h1 class="hero-title">Smart Space Management for Modern Campuses</h1>
            <p class="hero-subtitle">
                Optimize every square foot. EduSpace helps you manage classroom allocations, prevent conflicts, and
                visualize occupancy in real-time.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="login.php" class="btn btn-primary"
                    style="width: auto; padding: 1rem 2.5rem; font-size: 1.1rem;">
                    Get Started
                </a>
                <a href="#features" class="btn" onclick="scrollToFeatures(event)"
                    style="width: auto; background: white; border: 1px solid var(--border);">
                    Discover Features
                </a>
            </div>
        </div>
    </div>

    <script>
        function scrollToFeatures(e) {
            e.preventDefault();
            const section = document.getElementById('features');
            section.scrollIntoView({ behavior: 'smooth' });
        }
    </script>

    <section id="features" class="features-section">
        <div class="container section-header">
            <h2 class="section-title">Why Choose EduSpace?</h2>
            <p class="section-subtitle">Comprehensive tools to streamline your academic resource management.</p>
        </div>

        <div class="hero-cards">
            <div class="feature-card">
                <span class="feature-icon">📊</span>
                <h3 style="margin-bottom: 0.5rem; color: var(--text);">Real-time Insights</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Live dashboard statistics on classroom
                    occupancy and utilization rates.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">⚡</span>
                <h3 style="margin-bottom: 0.5rem; color: var(--text);">Instant Scheduling</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Interactive booking system with conflict
                    detection and automated validation.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">🛡️</span>
                <h3 style="margin-bottom: 0.5rem; color: var(--text);">Conflict Prevention</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Smart algorithms automatically prevent
                    double-booking of resources.</p>
            </div>
            <div class="feature-card">
                <span class="feature-icon">📱</span>
                <h3 style="margin-bottom: 0.5rem; color: var(--text);">Mobile Friendly</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Access schedules and manage bookings from any
                    device, anywhere on campus.</p>
            </div>
        </div>
    </section>
</body>

</html>