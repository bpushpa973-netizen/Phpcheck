<?php
// admin.php
session_start();
require_once 'config.php';

// --- Admin Credentials ---
$admin_user = "admin";
$admin_pass = "admin1234";

// --- Handle Authentication ---
if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid admin credentials!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// --- Require Login for following actions ---
if (isset($_SESSION['admin'])) {
    
    // Handle User Creation
    if (isset($_POST['create_user'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, access, validUntil, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['new_username'], 
                $_POST['new_password'], 
                $_POST['access'], 
                $_POST['validUntil'], 
                $_POST['status']
            ]);
            $success = "User created successfully!";
        } catch (PDOException $e) {
            $error = "Error creating user: " . $e->getMessage();
        }
    }

    // Handle User Update
    if (isset($_POST['update_user'])) {
        try {
            $id = $_POST['user_id'];
            $new_password = $_POST['new_password'];
            $access = $_POST['access'];
            $validUntil = $_POST['validUntil'];
            $status = $_POST['status'];
            
            if (!empty($new_password)) {
                $stmt = $pdo->prepare("UPDATE users SET password = ?, access = ?, validUntil = ?, status = ? WHERE id = ?");
                $stmt->execute([$new_password, $access, $validUntil, $status, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET access = ?, validUntil = ?, status = ? WHERE id = ?");
                $stmt->execute([$access, $validUntil, $status, $id]);
            }
            $success = "User updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating user: " . $e->getMessage();
        }
    }

    // Handle User Deletion
    if (isset($_GET['delete'])) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $success = "User deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting user: " . $e->getMessage();
        }
    }

    // Fetch Data for Display
    $users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate stats
    $total = count($users);
    $active = 0;
    $expired = 0;
    $unlimited = 0;
    $today = date('Y-m-d H:i:s');
    
    foreach ($users as $u) {
        if ($u['status'] == 'active') {
            if ($u['validUntil'] && $u['validUntil'] < $today) {
                $expired++;
            } else {
                $active++;
            }
        }
        if ($u['access'] == 'unlimited') {
            $unlimited++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>DJ GAMING VIP ADMIN | Nexus Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0c12;
            color: #eef2ff;
            overflow-x: hidden;
        }

        /* Premium Dark Theme with Neon Accents */
        :root {
            --primary: #ff2d55;
            --primary-dark: #e61e45;
            --primary-glow: rgba(255, 45, 85, 0.4);
            --secondary: #0a84ff;
            --secondary-glow: rgba(10, 132, 255, 0.3);
            --purple: #bf5af2;
            --bg-dark: #0a0c12;
            --bg-card: #12141c;
            --bg-elevated: #1a1d2a;
            --border-dim: #252a3a;
            --text-primary: #ffffff;
            --text-secondary: #8e9aaf;
            --text-muted: #5a647e;
            --success: #30d158;
            --warning: #ff9f0a;
            --danger: #ff453a;
            --radius-sm: 0.75rem;
            --radius-md: 1rem;
            --radius-lg: 1.5rem;
        }

        /* Animated Background */
        .bg-cyber {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,0,0,0.08) 0%, rgba(10,12,18,1) 70%);
            z-index: -2;
        }

        .bg-cyber::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                transparent 0px,
                transparent 2px,
                rgba(255,0,0,0.03) 2px,
                rgba(255,0,0,0.03) 4px
            );
            animation: shift 20s linear infinite;
            pointer-events: none;
        }

        @keyframes shift {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Glassmorphism */
        .glass {
            background: rgba(18, 20, 28, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 0, 0, 0.15);
        }

        /* Login Page */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .login-card {
            background: rgba(18, 20, 28, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 0, 0, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,0,0,0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 460px;
            animation: fadeInUp 0.6s ease;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff2d55, #D50100);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 0 30px rgba(255, 45, 85, 0.4);
        }

        .login-icon i {
            font-size: 2.8rem;
            color: white;
        }

        /* Main Layout */
        .dashboard-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        /* Navbar */
        .navbar-premium {
            background: rgba(18, 20, 28, 0.9);
            backdrop-filter: blur(16px);
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 0, 0, 0.2);
            padding: 0.75rem 2rem;
            margin-bottom: 2rem;
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff, #FF0000, #D50100);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }

        .logo i {
            background: none;
            -webkit-text-fill-color: #FF0000;
            margin-right: 8px;
        }

        /* Stats Cards - NO COLOR CHANGE */
        .stat-card {
            background: linear-gradient(145deg, #12141c, #0e1018);
            border-radius: var(--radius-md);
            border: 1px solid rgba(255, 45, 85, 0.15);
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff2d55, #bf5af2, #0a84ff);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 45, 85, 0.4);
            box-shadow: 0 15px 35px -15px rgba(255,45,85,0.3);
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            background: rgba(255, 0, 0, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #FF0000;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            font-family: 'Orbitron', monospace;
            background: linear-gradient(135deg, #fff, #8e9aaf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Table Card */
        .table-card {
            background: #12141c;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 0, 0, 0.15);
            overflow: hidden;
            margin-top: 1.5rem;
        }

        .card-header-custom {
            padding: 1.25rem 1.8rem;
            background: rgba(0,0,0,0.3);
            border-bottom: 1px solid rgba(255, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-primary-neon {
            background: linear-gradient(135deg, #FF0000, #FF0000);
            border: none;
            border-radius: 40px;
            padding: 0.6rem 1.8rem;
            font-weight: 700;
            color: #FFFFFF;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(178, 0, 0, 0.4);
        }

        .btn-primary-neon:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px hsla(0, 100%, 39%, 0.60);
            background: linear-gradient(135deg, #D50100, #D50100);
            color: #FFFFFF;
        }

        .btn-save-red {
            background: linear-gradient(135deg, #FF0000, #CC0000);
            border: none;
            border-radius: 40px;
            padding: 0.6rem 1.8rem;
            font-weight: 700;
            color: white;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(255, 0, 0, 0.4);
        }

        .btn-save-red:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(255, 0, 0, 0.6);
            background: linear-gradient(135deg, #FF3333, #FF0000);
            color: white;
        }

        /* Table Styles */
        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #FF0000;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255, 0, 0, 0.2);
        }

        .user-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(255, 0, 0, 0.08);
            color: #cbd5e6;
        }

        .user-table tr:hover td {
            background: rgba(255, 0, 0, 0.05);
        }

        /* Badges */
        .badge-access {
            background: #1a1d2a;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            border-left: 3px solid #FF0000;
        }

        .badge-status {
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .status-active {
            background: rgba(48, 209, 88, 0.15);
            color: #30d158;
            border: 1px solid rgba(48, 209, 88, 0.3);
        }

        .status-inactive {
            background: rgba(255, 69, 58, 0.15);
            color: #ff453a;
            border: 1px solid rgba(255, 69, 58, 0.3);
        }

        /* Action Buttons */
        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: #8e9aaf;
            transition: all 0.2s;
            margin: 0 3px;
        }

        .action-btn.edit:hover {
            background: #FFD501;
            color: #000000;
            transform: scale(1.05);
        }

        .action-btn.delete:hover {
            background: #FF0000;
            color: white;
        }

        /* Modal Premium - FIXED POPUP ISSUE */
        .modal-premium .modal-content {
            background: #12141c;
            border-radius: var(--radius-lg);
            border: 1px solid rgba(255, 0, 0, 0.3);
            box-shadow: 0 30px 50px rgba(0,0,0,0.6);
        }

        .modal-premium .modal-header {
            border-bottom: 1px solid rgba(255, 0, 0, 0.2);
            padding: 1.3rem 1.8rem;
        }

        .modal-premium .modal-footer {
            border-top: 1px solid rgba(255, 0, 0, 0.2);
            padding: 1rem 1.8rem;
        }

        /* Form Controls */
        .form-premium .form-control,
        .form-premium .form-select {
            background: #1a1d2a;
            border: 1px solid #2a2f40;
            border-radius: var(--radius-sm);
            padding: 0.7rem 1rem;
            color: white;
            transition: all 0.2s;
        }

        .form-premium .form-control:focus,
        .form-premium .form-select:focus {
            border-color: #FF0000;
            box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.2);
            background: #1f2230;
        }

        .form-premium .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #FF0000;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
        }

        /* Alerts */
        .alert-premium {
            border-radius: var(--radius-md);
            background: rgba(18, 20, 28, 0.95);
            backdrop-filter: blur(8px);
            border-left: 4px solid;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            border-left-color: #30d158;
        }

        .alert-danger {
            border-left-color: #FF0000;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* Utility */
        .text-gradient {
            background: linear-gradient(135deg, #fff, #FF0000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        code {
            background: #1a1d2a;
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #FFD501;
        }

        /* Modal z-index fix */
        .modal {
            z-index: 1060;
        }

        .modal-backdrop {
            z-index: 1050;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            .user-table th, .user-table td {
                padding: 0.75rem;
            }
            .navbar-premium {
                padding: 0.75rem 1rem;
            }
            .logo {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

<div class="bg-cyber"></div>

<?php if (!isset($_SESSION['admin'])): ?>
    <!-- Login Page -->
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-icon">
                <i class="bi bi-joystick"></i>
            </div>
            <h2 class="text-center mb-2" style="font-family: 'Orbitron'; font-weight: 800;">
                <span class="text-gradient">DJ GAMING VIP</span>
            </h2>
            <p class="text-center text-secondary mb-4">Admin Portal · Nexus Access</p>

            <?php if (isset($error)): ?>
                <div class="alert-premium alert-danger mb-4">
                    <i class="bi bi-shield-exclamation me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="form-premium">
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person-circle me-1"></i> USERNAME</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-key me-1"></i> PASSWORD</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary-neon w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i> ACCESS DASHBOARD
                </button>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Main Dashboard -->
    <div class="dashboard-container">
        <!-- Navbar -->
        <nav class="navbar-premium">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo">
                        <i class="bi bi-crown-fill"></i> DJ GAMING <span style="color:#FF0000;">VIP</span> ADMIN
                    </div>
                    <span class="badge" style="background:#FF0000; padding:0.3rem 0.8rem; border-radius:20px;">
                        <i class="bi bi-shield-check"></i> ROOT
                    </span>
                </div>
                <div class="d-flex align-items-center gap-3 mt-2 mt-sm-0">
                    <span class="text-secondary">
                        <i class="bi bi-person-fill me-1" style="color:#FF0000;"></i>
                        <?php echo htmlspecialchars($admin_user); ?>
                    </span>
                    <a href="?logout=1" class="btn" style="background:rgba(255,69,58,0.15); border-radius:40px; padding:0.4rem 1.2rem; color:#ff453a; border:1px solid rgba(255,69,58,0.3);">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Alert Messages -->
        <?php if (isset($success)): ?>
            <div class="alert-premium alert-success fade-in">
                <i class="bi bi-check-circle-fill me-2" style="color:#30d158;"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert-premium alert-danger fade-in">
                <i class="bi bi-exclamation-triangle-fill me-2" style="color:#FF0000;"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Stats Row - NO COLOR CHANGED -->
        <div class="row g-4 mb-4 fade-in">
            <div class="col-md-3">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value"><?php echo $total; ?></div>
                            <div class="text-secondary mt-1"><i class="bi bi-people-fill me-1"></i>Total Users</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-database"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value"><?php echo $active; ?></div>
                            <div class="text-secondary mt-1"><i class="bi bi-person-check-fill me-1"></i>Active</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-gem"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value"><?php echo $expired; ?></div>
                            <div class="text-secondary mt-1"><i class="bi bi-hourglass-split me-1"></i>Expired</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-calendar-x"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value"><?php echo $unlimited; ?></div>
                            <div class="text-secondary mt-1"><i class="bi bi-infinity me-1"></i>Unlimited</div>
                        </div>
                        <div class="stat-icon"><i class="bi bi-stars"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Management Table -->
        <div class="table-card fade-in">
            <div class="card-header-custom">
                <div>
                    <i class="bi bi-grid-3x3-gap-fill me-2" style="color:#FF0000;"></i>
                    <span style="font-weight: 700;">USER MANAGEMENT · NEXUS DATABASE</span>
                </div>
                <button class="btn btn-primary-neon" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-person-plus-fill me-2"></i>CREATE USER
                </button>
            </div>
            <div class="table-responsive">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>USERNAME</th>
                            <th>PASSWORD</th>
                            <th>ACCESS</th>
                            <th>STATUS</th>
                            <th>EXPIRY DATE</th>
                            <th style="text-align: center;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-secondary">No users found. Create your first user.</td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-person-circle me-2" style="color:#FF0000;"></i>
                                        <span class="fw-semibold"><?php echo htmlspecialchars($user['username']); ?></span>
                                    </td>
                                    <td><code><?php echo htmlspecialchars($user['password']); ?></code></td>
                                    <td>
                                        <?php if ($user['access'] == 'unlimited'): ?>
                                            <span class="badge-access"><i class="bi bi-infinity"></i> Unlimited</span>
                                        <?php else: ?>
                                            <span class="badge-access"><i class="bi bi-phone"></i> Single Device</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge-status <?php echo $user['status'] == 'active' ? 'status-active' : 'status-inactive'; ?>">
                                            <i class="bi <?php echo $user['status'] == 'active' ? 'bi-check-circle' : 'bi-x-circle'; ?>"></i>
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($user['validUntil']): ?>
                                            <i class="bi bi-calendar3 me-1 opacity-50"></i>
                                            <?php echo date('d M Y, h:i A', strtotime($user['validUntil'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <a href="?delete=<?php echo $user['id']; ?>" class="action-btn delete" onclick="return confirm('⚠️ Delete this user permanently?')" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit User Modals - rendered outside the table to avoid invalid HTML inside tbody -->
    <?php if (!empty($users)): foreach ($users as $user): ?>
    <div class="modal fade modal-premium" id="editModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $user['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?php echo $user['id']; ?>" style="color:#FF0000;">
                            <i class="bi bi-pencil-square me-2"></i> EDIT USER · <?php echo htmlspecialchars($user['username']); ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body form-premium">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">USERNAME</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NEW PASSWORD <span class="text-muted">(leave as is to keep current)</span></label>
                            <input type="text" name="new_password" class="form-control" value="<?php echo htmlspecialchars($user['password']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ACCESS LEVEL</label>
                            <select name="access" class="form-select">
                                <option value="1" <?php echo $user['access'] == '1' ? 'selected' : ''; ?>>Single Device (1)</option>
                                <option value="unlimited" <?php echo $user['access'] == 'unlimited' ? 'selected' : ''; ?>>Unlimited</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">EXPIRY DATE & TIME</label>
                            <input type="datetime-local" name="validUntil" class="form-control" value="<?php echo !empty($user['validUntil']) ? date('Y-m-d\TH:i', strtotime($user['validUntil'])) : ''; ?>">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">STATUS</label>
                            <select name="status" class="form-select">
                                <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background:#1a1d2a; border-radius:40px; color:#8e9aaf;" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" name="update_user" class="btn btn-save-red">SAVE CHANGES</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; endif; ?>

    <!-- Create User Modal -->
    <div class="modal fade modal-premium" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel" style="color:#FF0000;">
                            <i class="bi bi-person-plus-fill me-2"></i> CREATE NEW USER
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body form-premium">
                        <div class="mb-3">
                            <label class="form-label">USERNAME *</label>
                            <input type="text" name="new_username" class="form-control" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PASSWORD *</label>
                            <input type="text" name="new_password" class="form-control" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">DEVICE ACCESS</label>
                            <select name="access" class="form-select">
                                <option value="1">Single Device (1)</option>
                                <option value="unlimited">Unlimited</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">EXPIRY DATE</label>
                            <input type="datetime-local" name="validUntil" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">STATUS</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background:#1a1d2a; border-radius:40px; color:#8e9aaf;" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" name="create_user" class="btn-save-red">
                            <i class="bi bi-save me-1"></i> CREATE USER
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS needs to be loaded for modals to work -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure modals work properly with dynamic content
        document.addEventListener('DOMContentLoaded', function() {
            // Re-initialize any modal triggers if needed
            var editButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    var target = this.getAttribute('data-bs-target');
                    if (target && target.startsWith('#editModal')) {
                        // Modal will be handled by Bootstrap automatically
                        console.log('Opening modal: ' + target);
                    }
                });
            });
        });
    </script>
<?php endif; ?>

</body>
</html>