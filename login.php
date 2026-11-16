<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'dbconnect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "กรุณากรอก Username และ Password ให้ครบถ้วน";
    } else {
        // 1. ตรวจสอบในตารางพนักงาน (employee)
        $sql_emp = "SELECT * FROM employee WHERE eusername = ?";
        $stmt_emp = mysqli_prepare($con, $sql_emp);
        mysqli_stmt_bind_param($stmt_emp, "s", $username);
        mysqli_stmt_execute($stmt_emp);
        $result_emp = mysqli_stmt_get_result($stmt_emp);
        $row_emp = mysqli_fetch_assoc($result_emp);
        mysqli_stmt_close($stmt_emp);

        if ($row_emp && $password == $row_emp['epassword']) {
            session_regenerate_id(true);
            $_SESSION['user_type'] = 'employee';
            $_SESSION['username'] = $row_emp['eusername'];
            $_SESSION['role'] = $row_emp['role'];
            
            // กำหนด display_name ไม่ให้ซ้ำซ้อน
            if ($row_emp['role'] == 'admin') {
                $_SESSION['display_name'] = 'Admin';
            } elseif ($row_emp['role'] == 'CEO') {
                $_SESSION['display_name'] = $row_emp['ename'] . ' (CEO)';
            } else {
                $_SESSION['display_name'] = $row_emp['ename'];
            }

            header("Location: index_for_staff.php");
            exit();
        }

        // 2. ตรวจสอบในตารางลูกค้า (customer)
        $sql_cus = "SELECT * FROM customer WHERE cusername = ? OR cid = ?";
        $stmt_cus = mysqli_prepare($con, $sql_cus);
        mysqli_stmt_bind_param($stmt_cus, "ss", $username, $username);
        mysqli_stmt_execute($stmt_cus);
        $result_cus = mysqli_stmt_get_result($stmt_cus);
        $row_cus = mysqli_fetch_assoc($result_cus);
        mysqli_stmt_close($stmt_cus);

        if ($row_cus && $password == $row_cus['cpassword']) {
            session_regenerate_id(true);
            $_SESSION['user_type'] = 'customer';
            $_SESSION['cid'] = $row_cus['cid'];
            $_SESSION['username'] = $row_cus['cusername'];
            $_SESSION['display_name'] = $row_cus['cname'] . ' ' . $row_cus['csurname'];

            header("Location: product.php");
            exit();
        }

        $error = "Username หรือ Password ไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Velawine - Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh; font-family: 'Prompt', sans-serif;">
    <div class="card shadow p-5 rounded-4 border-0" style="width: 100%; max-width: 480px; background: #fff;">
        <div class="text-center mb-4">
            <a href="index.php" class="text-decoration-none">
                <h1 class="brand" style="font-size: 2.5rem;">
                    <i class="fa-solid fa-wine-glass-empty me-2" style="color: #8B1338;"></i>Velawine
                </h1>
            </a>
            <p class="text-muted" style="font-size: 1.05rem;">เข้าสู่ระบบจัดการและร้านค้า</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 1rem;">Username / รหัสลูกค้า</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control fs-6" placeholder="กรอก Username หรือรหัสลูกค้า" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: 1rem;">Password</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control fs-6" placeholder="กรอกรหัสผ่าน" required>
                </div>
            </div>
            <button type="submit" class="btn w-100 text-white py-3 fw-semibold shadow-sm" style="background-color: #58111A; font-size: 1.1rem; border-radius: 8px;">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="text-muted" style="font-size: 0.95rem;">ยังไม่มีบัญชี?</span> <a href="sign_up.php" class="text-decoration-none fw-semibold" style="color: #8B1338; font-size: 0.95rem;">สมัครสมาชิก</a>
        </div>
    </div>
</body>
</html>