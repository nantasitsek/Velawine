<?php
require 'dbconnect.php';

$code = $_GET['code'] ?? '';
$qpro = "SELECT * FROM winetbl WHERE wid = '$code'";
$respro = mysqli_query($con, $qpro);
$rowpro = mysqli_fetch_array($respro, MYSQLI_ASSOC);

// จัดการเรื่องรูปภาพสินค้า
$img_name = !empty($rowpro['wimg']) ? $rowpro['wimg'] : 'no-image.png';
$img_path = "images/" . $img_name;
if (!file_exists($img_path)) {
    $img_path = "images/no-image.png";
}

session_start();
// ถ้ายังไม่ได้ Login ให้กลับไปหน้า Login
if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = (
    $_SESSION['user_type'] == 'employee' &&
    in_array($_SESSION['role'] ?? '', ['admin', 'CEO', 'staff'])
);
$displayName = $_SESSION['display_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($rowpro['wname'] ?? 'Wine Details'); ?> - Velawine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
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

                <?php if ($isAdmin): ?>
                    <a href="index_for_staff.php" class="btn btn-warning btn-sm fw-semibold text-dark">
                        <i class="fa-solid fa-gears me-1"></i> จัดการข้อมูลหลังบ้าน
                    </a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_type'])): ?>
                    <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-dark btn-sm px-3 fw-bold">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        <div class="mb-4">
            <a href="product.php" class="btn-back">
                <i class="fa-solid fa-arrow-left me-1"></i> ย้อนกลับไปหน้าสินค้า
            </a>
        </div>

        <?php if ($rowpro): ?>
            <div class="detail-card">
                <form action="checkout.php" method="post">
                    <div class="row g-4 align-items-center">
                    
                        <div class="col-lg-5 text-center">
                            <div class="wine-detail-img-container p-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="min-height: 420px;">
                                <img src="<?php echo htmlspecialchars($img_path); ?>" alt="<?php echo htmlspecialchars($rowpro['wname']); ?>" style="max-height: 400px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <h2 class="wine-title"><?php echo htmlspecialchars($rowpro['wname']); ?></h2>
                            <h3 class="wine-price mb-4" style="color: #8B1338; font-weight: 700;">
                                ฿<?php echo number_format($rowpro['price'], 2); ?>
                            </h3>

                            <fieldset class="facts-fieldset mb-4">
                                <legend class="facts-legend"><i class="fa-solid fa-circle-info me-1"></i> Facts about the wine</legend>
                                <div class="spec-grid">
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-earth-americas me-1"></i> Country</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['country']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-location-dot me-1"></i> Region</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['region']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-calendar-days me-1"></i> Year</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['year']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-glass-martini me-1"></i> Wine type</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['wtype']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Wine style</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['wstyle']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-seedling me-1"></i> Grapes</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['grape']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-wine-bottle me-1"></i> Bottle closure</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['bottlclosure']); ?></p>
                                    </div>
                                    <div class="spec-item">
                                        <span class="spec-label"><i class="fa-solid fa-percent me-1"></i> Alcohol content</span>
                                        <p class="spec-value"><?php echo htmlspecialchars($rowpro['%abv']); ?></p>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="d-flex align-items-center gap-3 pt-3 border-top mt-4">
                                <label for="qty" class="fw-bold me-2" style="font-family: 'Prompt', sans-serif; font-size: 16px; color: #333;">จำนวน (ขวด):</label>
                                <input type="number" 
                                    id="qty" 
                                    name="qty[<?php echo $rowpro['wid']; ?>]" 
                                    class="form-control text-center" 
                                    style="width: 90px; height: 48px; font-size: 18px; font-family: 'Prompt', sans-serif; font-weight: 600;" 
                                    min="1" 
                                    max=<?php echo $rowpro['quantity']; ?>
                                    value="1" 
                                    required>
    
                                <button type="submit" class="btn btn-checkout flex-grow-1 py-3" style="font-family: 'Prompt', sans-serif; font-size: 16px; font-weight: 600;">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> สั่งซื้อสินค้านี้
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center py-5">
                <h4>ไม่พบข้อมูลไวน์รายการนี้</h4>
                <p>กรุณาตรวจสอบรหัสสินค้าใหม่อีกครั้ง</p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container text-center py-3">
            <small>© 2026 Velawine. All Rights Reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>