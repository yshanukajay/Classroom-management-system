<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Classroom Allocation Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/images/logo.png?v=2" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Hero Spotlight */
        /* Ensure hero content is above the spotlight */
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
                <a href="index.php"
                    style="display: flex; align-items: center; gap: 1rem; background: rgba(255,255,255,0.8); padding: 0.75rem 1.5rem; border-radius: 999px; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.5); text-decoration: none;">
                    <img src="assets/images/logo.png?v=2" alt="EduSpace Logo" style="width: 32px; height: 32px;">
                    <span style="font-weight: 700; color: var(--text); font-size: 1.25rem;">EduSpace</span>
                </a>
            </div>
            <h1 class="hero-title">Smart Space Management for Modern Campuses</h1>
            <p class="hero-subtitle">
                Optimize every square foot. EduSpace helps you manage classroom allocations, prevent conflicts, and
                visualize occupancy in real-time.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="login.php" class="btn btn-primary"
                    style="width: auto; padding: 1rem 2.5rem; font-size: 1.1rem;">
                    Get Started
                </a>
                <a href="#features" class="btn" onclick="scrollToSection(event, 'features')"
                    style="width: auto; background: white; border: 1px solid var(--border);">
                    Discover Features
                </a>
                <a href="#architecture" class="btn" onclick="scrollToSection(event, 'architecture')"
                    style="width: auto; background: white; border: 1px solid var(--border);">
                    System Architecture
                </a>
            </div>
        </div>
    </div>

    <script>
        function scrollToSection(e, sectionId) {
            e.preventDefault();
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Spotlight Effect (Features & Hero)
        document.addEventListener('DOMContentLoaded', () => {
            // Features Spotlight
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach(card => {
                card.onmousemove = e => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                }
            });


        });    </script>

    <section id="features" class="features-section">
        <div class="container section-header">
            <h2 class="section-title">Why Choose EduSpace?</h2>
            <p class="section-subtitle">Comprehensive tools to streamline your academic resource management.</p>
        </div>

        <div class="hero-cards">
            <!-- Feature 1 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <path d="M18 17V9" />
                        <path d="M13 17V5" />
                        <path d="M8 17v-3" />
                    </svg>
                </div>
                <h3 class="feature-title">Real-time Insights</h3>
                <p class="feature-desc">
                    Gain comprehensive visibility with our live dashboard. Track classroom occupancy and utilization
                    trends.
                </p>
                <ul class="feature-list"
                    style="margin-top: 1rem; list-style: none; color: var(--text-light); font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--primary);">•</span> Live Occupancy Tracking</li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--primary);">•</span> Daily Utilization Metrics</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--primary);">•</span> Visual Data Presentation</li>
                </ul>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <path d="M13 16l-4-4 4-4" />
                    </svg>
                </div>
                <h3 class="feature-title">Instant Scheduling</h3>
                <p class="feature-desc">
                    Empower staff with a seamless booking experience. Intuitive interface for instant reservations.
                </p>
                <ul class="feature-list"
                    style="margin-top: 1rem; list-style: none; color: var(--text-light); font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--warning);">•</span> One-Click Booking</li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--warning);">•</span> Auto-Approval Workflow</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--warning);">•</span> Calendar Integration</li>
                </ul>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <h3 class="feature-title">Conflict Prevention</h3>
                <p class="feature-desc">
                    Say goodbye to scheduling clashes. Advanced algorithms automatically detect double-bookings.
                </p>
                <ul class="feature-list"
                    style="margin-top: 1rem; list-style: none; color: var(--text-light); font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--success);">•</span> Smart Overlap Detection</li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--success);">•</span> Instant Error Feedback</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--success);">•</span> Automated Resolution</li>
                </ul>
            </div>

            <!-- Feature 4 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
                        <line x1="12" y1="18" x2="12.01" y2="18" />
                    </svg>
                </div>
                <h3 class="feature-title">Mobile Friendly</h3>
                <p class="feature-desc">
                    Manage your campus on the go. Check schedules and book rooms from anywhere.
                </p>
                <ul class="feature-list"
                    style="margin-top: 1rem; list-style: none; color: var(--text-light); font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: #3B82F6;">•</span> Responsive Dashboard</li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: #3B82F6;">•</span> Cross-Platform Support</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;"><span style="color: #3B82F6;">•</span>
                        Touch-Optimized UI</li>
                </ul>
            </div>

            <!-- Feature 5 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h3 class="feature-title">Secure Access</h3>
                <p class="feature-desc">
                    Role-based authentication ensures only authorized personnel can modify schedules.
                </p>
                <ul class="feature-list"
                    style="margin-top: 1rem; list-style: none; color: var(--text-light); font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--danger);">•</span> Admin vs Staff Roles</li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--danger);">•</span> Session Management</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: var(--danger);">•</span> Data Integrity Protection</li>
                </ul>
            </div>

            <!-- Feature 6 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                </div>
                <h3 class="feature-title">Automated Reports</h3>
                <p class="feature-desc">
                    Generate detailed analytics on resource usage for efficient planning.
                </p>
                <ul class="feature-list"
                    style="margin-top: 1rem; list-style: none; color: var(--text-light); font-size: 0.9rem;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: #8B5CF6;">•</span> Peak Usage Analysis</li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;"><span
                            style="color: #8B5CF6;">•</span> Capacity Planning</li>
                    <li style="display: flex; align-items: center; gap: 0.5rem;"><span style="color: #8B5CF6;">•</span>
                        Exportable Summaries</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="architecture" class="deep-dive-section">
        <div class="deep-dive-container">
            <div class="section-header">
                <h2 class="section-title">System Architecture</h2>
                <p class="section-subtitle">Engineered for speed, consistency, and intelligent campus management.</p>
            </div>

            <div class="arch-grid">
                <!-- Tech Card 1: Engine -->
                <div class="tech-card">
                    <div class="tech-icon-box">
                        <!-- Blueprint Visual: Core Processor -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
                            <rect x="9" y="9" width="6" height="6" />
                            <line x1="9" y1="1" x2="9" y2="4" />
                            <line x1="15" y1="1" x2="15" y2="4" />
                            <line x1="9" y1="20" x2="9" y2="23" />
                            <line x1="15" y1="20" x2="15" y2="23" />
                            <line x1="20" y1="9" x2="23" y2="9" />
                            <line x1="20" y1="14" x2="23" y2="14" />
                            <line x1="1" y1="9" x2="4" y2="9" />
                            <line x1="1" y1="14" x2="4" y2="14" />
                        </svg>
                    </div>
                    <h3 class="tech-card-title">Allocation Engine</h3>
                    <div class="tech-divider"></div>
                    <p class="tech-card-text">
                        A high-performance query layer that processes booking requests in
                        <strong>sub-millisecond</strong>
                        timeframes. It instantly cross-references classroom capacity matricies with active schedules
                        using optimized
                        indexes, creating a zero-latency feedback loop.
                    </p>
                </div>

                <!-- Tech Card 2: Protocol -->
                <div class="tech-card">
                    <div class="tech-icon-box">
                        <!-- Blueprint Visual: Protocol/Lock -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            <path d="M12 8v4" />
                            <path d="M12 16h.01" />
                        </svg>
                    </div>
                    <h3 class="tech-card-title">Conflict Protocol</h3>
                    <div class="tech-divider"></div>
                    <p class="tech-card-text">
                        We enforce strict <strong>ACID compliance</strong> at the database level. Before any write
                        operation,
                        our FCFS (First-Come-First-Serve) algorithm initiates a row-level lock to verify temporal
                        uniqueness,
                        guaranteeing zero double-bookings.
                    </p>
                </div>

                <!-- Tech Card 3: Admin -->
                <div class="tech-card">
                    <div class="tech-icon-box">
                        <!-- Blueprint Visual: Data Analytics -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                            <polyline points="7.5 4.21 12 6.81 16.5 4.21" />
                            <polyline points="7.5 19.79 7.5 14.6 3 12" />
                            <polyline points="21 12 16.5 14.6 16.5 19.79" />
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                            <line x1="12" y1="22.08" x2="12" y2="12" />
                        </svg>
                    </div>
                    <h3 class="tech-card-title">Data Administration</h3>
                    <div class="tech-divider"></div>
                    <p class="tech-card-text">
                        The system aggregates usage telemetry to generate actionable heatmaps. Administrators can
                        identify
                        underutilized assets and optimize energy consumption based on <strong>predictive occupancy
                            models</strong>
                        rather than static schedules.
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>

</html>