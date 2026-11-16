<?php
session_start();
require 'dbconnect.php';

$q = "SELECT * FROM winetbl";
$result = mysqli_query($con, $q);

$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'employee' && isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'CEO', 'staff']);
$displayName = $_SESSION['display_name'] ?? ''; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velawine - เลือกซื้อสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500&display=swap" rel="stylesheet">
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

                <a href="index.php" class="btn btn-outline-secondary" style="padding: 9px 20px;">
                    <i class="fa-solid fa-house me-1"></i> หน้าหลัก
                </a>

                <?php if ($isAdmin): ?>
                    <a href="index_for_staff.php" class="btn text-dark fw-semibold" style="background-color: #ffc107; border: none;">
                        <i class="fa-solid fa-gears me-1"></i> จัดการข้อมูลหลังบ้าน
                    </a>
                <?php endif; ?>

                <!-- ปุ่มดูประวัติการสั่งซื้อ (แสดงเฉพาะเมื่อ Login เป็นลูกค้า) -->
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer'): ?>
                    <a href="order_history.php" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" style="border-radius: 20px;">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> ประวัติการสั่งซื้อ
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_type'])): ?>
                    <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-wine btn-sm text-white px-3 fw-bold" style="background-color: #58111A;">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        <div class="row mb-5">
            <div class="col-12 text-center py-4 px-3 bg-white rounded-4 shadow-sm">
                <h2 class="mb-2" style="color: #58111A; font-weight: 700; font-size: 32px;">
                    <i class="fa-solid fa-wine-bottle me-2" style="color: #8B1338;"></i> คอลเลกชันไวน์นำเข้าทั้งหมด
                </h2>
                <p class="text-muted mb-0" style="font-size: 16px;">
                    คลิกที่รูปภาพหรือชื่อสินค้า เพื่อดูรายละเอียดและเลือกจำนวนขวดที่ต้องการ
                </p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Content Area -->
            <main class="col-lg-12">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $wid = $row['wid'] ?? $row['id'];
                            $img_name = !empty($row['wimg']) ? $row['wimg'] : 'no-image.png';
                            $img_path = "images/" . $img_name;
                    ?>
                    <div class="col">
                        <div class="card h-100 wine-card d-flex flex-column">
                            <a href="product_detail.php?code=<?php echo $wid; ?>" class="text-decoration-none text-dark flex-grow-1 d-flex flex-column">
                                <div class="wine-img-container p-3">
                                    <img src="<?php echo htmlspecialchars($img_path); ?>" class="wine-img" alt="<?php echo htmlspecialchars($row['wname']); ?>">
                                </div>
                                <div class="wine-card-body d-flex flex-column justify-content-between flex-grow-1 text-center">
                                    <div>
                                        <h5 class="wine-name"><?php echo htmlspecialchars($row['wname']); ?></h5>
                                    </div>
                                    <div>
                                        <p class="wine-made text-muted small mb-2"><?php echo htmlspecialchars($row['country']); ?></p>
                                    </div>
                                    <div class="mt-3">
                                        <span class="wine-price">฿<?php echo number_format($row['price'], 2); ?></span>
                                    </div>
                                </div>
                            </a>

                            <div class="wine-card-footer text-center">
                                <a href="product_detail.php?code=<?php echo $wid; ?>" class="text-white text-decoration-none fw-semibold d-block py-1">
                                    <i class="fa-solid fa-eye me-1"></i> ดูรายละเอียดสินค้า
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                    ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">ไม่พบข้อมูลสินค้า</p>
                    </div>
                    <?php
                    }
                    if ($result) {
                        mysqli_free_result($result);
                    }
                    mysqli_close($con);
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>