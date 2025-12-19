<?php
session_start();
require_once "../../config/database.php";
require_once "../auth/require.php";

requireAdmin();

$db = (new Database())->connect();

$stmt = $db->query('SELECT idemployees, CONCAT(last_name, ", ", first_name, " ", middle_name) AS "full_name" FROM employees');
$stmt->execute();

$employees = [];
$random_color_int = 1;

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $employees[] = [
        'id' => $row['idemployees'],
        'name' => trim($row['full_name']),
        'position' => 'Employee',
        'avatar_color' => '#' . substr(md5($random_color_int), 0, 6)
    ];
    $random_color_int += 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/hrmis/">
    <title>Admin Dashboard - HRMIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="background-gradient"></div>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container navbar-content">
            <a href="/hrmis/app/admin/dashboard.php" class="navbar-brand">
                <span class="text-gradient">HRMIS</span> 
                <span class="badge-admin">Admin</span> 
            </a>
            <div class="search-wrapper">
                <span class="material-symbols-outlined search-icon">search</span>
                <input type="text" id="employeeSearch" placeholder="Search employees..." autocomplete="off">
            </div>
            <div class="navbar-actions">
                <span class="user-greeting">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                <a href="app/auth/logout.php" class="btn btn-secondary btn-sm">
                    <span class="material-symbols-outlined" style="font-size: 1.2rem;">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="header-actions mb-4">
            <div>
                <h1>Employee Directory</h1>
                <p class="text-secondary">Manage and view all registered employees</p>
            </div>
            <a href="app/employee/add.php" class="btn btn-primary">
                <span class="material-symbols-outlined">add</span>
                Add New
            </a>
        </div>

        <div class="employee-grid">
            <?php foreach ($employees as $employee): ?>
                <a href="app/employee/update.php?id=<?php echo $employee['id']; ?>" class="employee-card">
                    <div class="employee-avatar" style="background: <?php echo $employee['avatar_color']; ?>">
                        <?php echo substr($employee['name'], 0, 1); ?>
                    </div>
                    <div class="employee-info">
                        <h3 class="employee-name"><?php echo $employee['name']; ?></h3>
                        <p class="employee-position"><?php echo $employee['position']; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
            
            <a href="app/employee/add.php" class="employee-card new-employee" id="addEmployeeCard">
                <div class="new-icon">
                    <span class="material-symbols-outlined">add</span>
                </div>
                <div class="employee-info">
                    <h3 class="employee-name">Add New</h3>
                </div>
            </a>
        </div>
    </div>

    <style>
        .container {
            max-width: 1400px;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .badge-admin {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-greeting {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        /* 6-Column Grid */
        /* 6-Column Grid */
        .employee-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 20px;
            animation: fadeInUp 0.6s ease;
            width: 100%;
        }

        /* Employee Card Square Styling */
        .employee-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 20px;
            height: 100%;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .employee-card:hover {
            border-color: var(--border-focus);
            background: var(--bg-hover);
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .employee-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .employee-info h3 {
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .employee-info p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Add New Card */
        .new-employee {
            background: rgba(255, 255, 255, 0.02);
            border-style: dashed;
        }

        .new-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: var(--text-secondary);
            transition: all var(--transition-fast);
        }

        .employee-card:hover .new-icon {
            background: var(--primary-gradient);
            color: white;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1400px) {
            .employee-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        @media (max-width: 1100px) {
            .employee-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 900px) {
            .employee-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 600px) {
            .employee-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .navbar-actions .user-greeting {
                display: none;
            }
        }

        @media (max-width: 400px) {
            .employee-grid {
                grid-template-columns: 1fr;
            }
        }
        /* Proper Search Bar Styles */
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 400px;
            margin: 0 40px;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: color var(--transition-fast);
        }

        #employeeSearch {
            width: 100%;
            padding: 12px 16px 12px 48px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 100px;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all var(--transition-normal);
            outline: none;
        }

        #employeeSearch:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            width: 110%;
        }

        #employeeSearch:focus + .search-icon {
            color: #667eea;
        }

        /* Hide cards that don't match search */
        .employee-card.hidden {
            display: none;
        }

        @media (max-width: 900px) {
            .search-wrapper {
                margin: 0 20px;
                max-width: none;
            }
            #employeeSearch:focus {
                width: 100%;
            }
        }
    </style>

    <script src="assets/js/scripts.js"></script>
    <script>
        const employeeSearch = document.getElementById('employeeSearch')
        const addEmployeeCard = document.getElementById('addEmployeeCard')

        // Real-time Search Functionality
        employeeSearch.addEventListener('input', function(e) {
            if (employeeSearch.value != '' && addEmployeeCard.checkVisibility()) {
                addEmployeeCard.classList.add('hidden');
            } else {
                addEmployeeCard.classList.remove('hidden');
            }
            const searchTerm = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.employee-card:not(.new-employee)');
            
            cards.forEach(card => {
                const name = card.querySelector('.employee-name').textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
