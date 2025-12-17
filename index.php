<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/hrmis/">
    <title>HRMIS - Human Resource Management Information System</title>
    <meta name="description" content="Modern and efficient human resource management system for streamlined employee data management.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="background-gradient"></div>
    
    <div class="container">
        <main class="landing-hero">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="text-gradient">HRMIS</span>
                </h1>
                <p class="hero-subtitle">Human Resource Management Information System</p>
                <p class="hero-description">
                    Streamline your workforce management with our modern, efficient, and user-friendly platform. 
                    Manage employee records, track performance, and optimize HR operations all in one place.
                </p>
                <div class="hero-actions">
                    <a href="app/auth/login.php" class="btn btn-primary">
                        <span class="btn-text">Login to Dashboard</span>
                        <span class="btn-icon">→</span>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <style>
        /* Landing Page Specific Styles */
        .landing-hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -2px;
            animation: fadeInDown 0.6s ease;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: var(--text-secondary);
            font-weight: 400;
            margin-bottom: 32px;
            animation: fadeInDown 0.7s ease;
        }

        .hero-description {
            font-size: 1.125rem;
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 48px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 0.8s ease;
        }

        .hero-actions {
            animation: fadeInUp 0.9s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 3.5rem;
            }

            .hero-subtitle {
                font-size: 1.25rem;
            }

            .hero-description {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.125rem;
            }
        }
    </style>
</body>
</html>