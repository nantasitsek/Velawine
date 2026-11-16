<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'employee' || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'CEO')) {
    header("Location: login.php");
    exit();
}
$displayName = $_SESSION['display_name'] ?? 'Admin';

$isAdmin = (
    $_SESSION['user_type'] == 'employee' &&
    in_array($_SESSION['role'] ?? '', ['admin', 'CEO', 'staff'])
);
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เมนูระบบหลังบ้าน (Back Office) - Velawine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="font-family: 'Prompt', sans-serif;">
    <!-- Header -->
    <header class="py-4 mb-4 bg-white border-bottom shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="text-decoration-none">
                <h1 class="brand mb-0">
                    <i class="fa-solid fa-wine-glass-empty me-2" style="color: #8B1338;"></i>Velawine
                </h1>
            </a>
            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['user_type'])): ?>
                    <span class="fw-semibold text-dark">
                        <i class="fa-solid fa-user-circle me-1" style="color: #8B1338;"></i> สวัสดีคุณ <?php echo htmlspecialchars($displayName); ?>
                    </span>
                <?php endif; ?>

                <a href="product.php" class="btn btn-wine px-3 text-white fw-bold shadow-sm" style="background-color: #8B1338; border-radius: 20px;">
                    <i class="fa-solid fa-store me-1"></i> เลือกซื้อสินค้า
                </a>

                <?php if (isset($_SESSION['user_type'])): ?>
                    <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-dark btn-sm px-3 fw-bold">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <div class="container py-4" style="max-width: 950px; margin: 0 auto;">
        <div class="text-center mb-5">
            <div class="col-12 text-center py-4 px-3 bg-white rounded-4 shadow-sm">
                <h2 class="fw-bold" style="color: #58111A; font-size: 36px;">
                    <i class="fa-solid fa-gears me-2"></i> เมนูจัดการระบบหลังบ้าน
                </h2>
                <p class="text-muted">เลือกเมนูการจัดการที่ต้องการใช้งานเพื่อควบคุมระบบร้านค้า Velawine</p>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <!-- 1. จัดการสินค้า -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 rounded-4 text-center p-4">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3 text-danger fs-1"><i class="fa-solid fa-boxes-stacked"></i></div>
                            <h4 class="card-title fw-bold mb-2" style="color: #58111A;">จัดการสินค้า</h4>
                            <p class="card-text text-muted small mb-4">เพิ่ม, แก้ไข, ลบ รายการไวน์, ราคา, สต็อก และรายละเอียดสินค้าทั้งหมด</p>
                        </div>
                        <a href="manage_product.php" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #58111A; border-radius: 8px;">
                            เข้าสู่ระบบจัดการสินค้า <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. จัดการข้อมูลลูกค้า -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 rounded-4 text-center p-4">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3 text-primary fs-1"><i class="fa-solid fa-users"></i></div>
                            <h4 class="card-title fw-bold mb-2" style="color: #58111A;">จัดการข้อมูลลูกค้า</h4>
                            <p class="card-text text-muted small mb-4">เพิ่ม, ปรับปรุง, ลบ ข้อมูลสมาชิกและที่อยู่จัดส่งของลูกค้าอย่างละเอียด</p>
                        </div>
                        <a href="manage_customer.php" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #58111A; border-radius: 8px;">
                            เข้าสู่ระบบจัดการลูกค้า <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. สรุปข้อมูลยอดขาย -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 rounded-4 text-center p-4">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3 text-success fs-1"><i class="fa-solid fa-chart-line"></i></div>
                            <h4 class="card-title fw-bold mb-2" style="color: #58111A;">สรุปข้อมูลยอดขาย</h4>
                            <p class="card-text text-muted small mb-4">ตรวจสอบรายงานยอดขายรวม, สินค้าขายดี Top 3 และสถิติภาพรวม</p>
                        </div>
                        <a href="sales_report.php" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #58111A; border-radius: 8px;">
                            ดูรายงานยอดขาย <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. จัดการข้อมูลผู้ดูแล -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 rounded-4 text-center p-4">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-3 text-warning fs-1"><i class="fa-solid fa-user-tie"></i></div>
                            <h4 class="card-title fw-bold mb-2" style="color: #58111A;">จัดการข้อมูลผู้ดูแล</h4>
                            <p class="card-text text-muted small mb-4">เพิ่ม, ปรับปรุง, ลบ ข้อมูลผู้ดูแลเว็บไซต์และสิทธิ์การใช้งาน</p>
                        </div>
                        <a href="manage_employee.php" class="btn text-white w-100 py-2 fw-semibold" style="background-color: #58111A; border-radius: 8px;">
                            เข้าสู่ระบบจัดการผู้ดูแล <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>