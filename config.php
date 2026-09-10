<?php
// Railway-ready database + website API configuration.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');

function env_value(string $name, ?string $default = null): ?string {
    $value = getenv($name);
    return ($value === false || $value === '') ? $default : $value;
}

// Railway: prefer the public MySQL URL when the web service cannot resolve
// the private *.railway.internal hostname. Fall back to MYSQL_URL and then
// the individual MYSQL* variables.
$host = env_value('MYSQLHOST', '127.0.0.1');
$port = (int) env_value('MYSQLPORT', '3306');
$dbname = env_value('MYSQLDATABASE', 'djgaming');
$username = env_value('MYSQLUSER', 'root');
$password = env_value('MYSQLPASSWORD', '');

$mysqlUrl = env_value('MYSQL_PUBLIC_URL') ?: env_value('MYSQL_URL') ?: env_value('DATABASE_URL');
if ($mysqlUrl && preg_match('/^mysql:\/\//i', $mysqlUrl)) {
    $parts = parse_url($mysqlUrl);
    if ($parts !== false && !empty($parts['host'])) {
        $host = $parts['host'];
        $port = isset($parts['port']) ? (int)$parts['port'] : $port;
        $dbname = isset($parts['path']) && ltrim($parts['path'], '/') !== '' ? ltrim($parts['path'], '/') : $dbname;
        $username = isset($parts['user']) ? urldecode($parts['user']) : $username;
        $password = isset($parts['pass']) ? urldecode($parts['pass']) : $password;
    }
}

if (!extension_loaded('pdo_mysql')) {
    http_response_code(500);
    die('Database Connection failed: PDO MySQL driver is not installed.');
}

try {
    // Do not fail before PDO gets a chance to connect. Public Railway proxy
    // hosts (e.g. *.proxy.rlwy.net) are intentionally used when available.

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // License/API users used by login.php and admin.php.
    $pdo->exec("CREATE TABLE IF NOT EXISTS maintenance_settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        maintenance_mode TINYINT(1) DEFAULT 0,
        message TEXT,
        version VARCHAR(20),
        link VARCHAR(255),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $check = $pdo->query("SELECT COUNT(*) FROM maintenance_settings");
    if ((int)$check->fetchColumn() === 0) {
        $pdo->exec("INSERT INTO maintenance_settings (maintenance_mode, message, version, link)
                    VALUES (0, 'Site is under maintenance', '1.1', 'https://t.me/djgamingvip')");
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        deviceID VARCHAR(100) DEFAULT NULL,
        status ENUM('active','inactive') DEFAULT 'active',
        access ENUM('1','unlimited') DEFAULT '1',
        validUntil DATETIME DEFAULT NULL,
        registeredDate DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS file_manager (
        id INT AUTO_INCREMENT PRIMARY KEY,
        file_name VARCHAR(255) NOT NULL,
        original_name VARCHAR(255) NOT NULL,
        file_size BIGINT NOT NULL,
        file_type VARCHAR(100),
        upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        download_count INT DEFAULT 0,
        status ENUM('active','inactive') DEFAULT 'active'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Separate website accounts so the journal frontend does not conflict with license users.
    $pdo->exec("CREATE TABLE IF NOT EXISTS web_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        phone VARCHAR(30) NOT NULL,
        institute VARCHAR(255) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

} catch (PDOException $e) {
    http_response_code(500);
    die('Database Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// MySQLi connection retained for any legacy code.
$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    http_response_code(500);
    die('MySQLi Connection failed: ' . htmlspecialchars($conn->connect_error, ENT_QUOTES, 'UTF-8'));
}
$conn->set_charset('utf8mb4');

// JSON API used by index.php.
if (isset($_GET['action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_GET['action'];

    try {
        if ($action === 'check') {
            if (!empty($_SESSION['web_user_id'])) {
                $stmt = $pdo->prepare('SELECT id, name, email, phone, institute, created_at FROM web_users WHERE id = ?');
                $stmt->execute([$_SESSION['web_user_id']]);
                $user = $stmt->fetch();
                if ($user) {
                    echo json_encode(['loggedIn' => true, 'user' => $user]);
                    exit;
                }
                unset($_SESSION['web_user_id']);
            }
            echo json_encode(['loggedIn' => false]);
            exit;
        }

        if ($action === 'logout') {
            unset($_SESSION['web_user_id']);
            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'login') {
            $email = trim($_POST['email'] ?? '');
            $pass = (string)($_POST['password'] ?? '');
            if ($email === '' || $pass === '') {
                echo json_encode(['success' => false, 'error' => 'Email and password are required']);
                exit;
            }
            $stmt = $pdo->prepare('SELECT * FROM web_users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if (!$user || !password_verify($pass, $user['password_hash'])) {
                echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
                exit;
            }
            session_regenerate_id(true);
            $_SESSION['web_user_id'] = (int)$user['id'];
            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'register') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $institute = trim($_POST['institute'] ?? '');
            $pass = (string)($_POST['password'] ?? '');

            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $institute === '') {
                echo json_encode(['success' => false, 'error' => 'Please enter valid registration details']);
                exit;
            }
            if (!preg_match('/^\d{10}$/', $phone)) {
                echo json_encode(['success' => false, 'error' => 'Phone number must be 10 digits']);
                exit;
            }
            if (strlen($pass) < 6) {
                echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters']);
                exit;
            }

            $stmt = $pdo->prepare('SELECT id FROM web_users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'error' => 'Email is already registered']);
                exit;
            }

            $stmt = $pdo->prepare('INSERT INTO web_users (name, email, phone, institute, password_hash) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $email, $phone, $institute, password_hash($pass, PASSWORD_DEFAULT)]);
            echo json_encode(['success' => true]);
            exit;
        }

        echo json_encode(['success' => false, 'error' => 'Unknown action']);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Server/database error']);
    }
    exit;
}
?>
