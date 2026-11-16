<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'dbconnect.php';

$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'employee' && in_array($_SESSION['role'] ?? '', ['admin', 'CEO', 'staff']);
$displayName = $_SESSION['display_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velawine - ร้านจำหน่ายไวน์นำเข้าพรีเมียม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="font-family: 'Prompt', sans-serif;">
    <!-- Header -->
    <header class="py-4 mb-4 bg-white border-bottom shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="brand mb-0">
                <i class="fa-solid fa-wine-glass-empty me-2" style="color: #8B1338;"></i>Velawine
            </h1>
            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['user_type'])): ?>
                    <span class="fw-semibold text-dark">
                        <i class="fa-solid fa-user-circle me-1" style="color: #8B1338;"></i> สวัสดีคุณ <?php echo htmlspecialchars($displayName); ?>
                    </span>
                <?php endif; ?>

                <!-- ปุ่มเลือกซื้อสินค้าบน Navbar -->
                <a href="product.php" class="btn btn-wine px-3 text-white fw-bold shadow-sm" style="background-color: #8B1338; border-radius: 20px;">
                    <i class="fa-solid fa-store me-1"></i> เลือกซื้อสินค้า
                </a>

                <?php if ($isAdmin): ?>
                    <a href="index_for_staff.php" class="btn text-dark fw-semibold" style="background-color: #ffc107; border: none;">
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

    <!-- Detail -->
    <div class="container py-4">
        <div class="about-card text-center p-5 mb-5 shadow-sm bg-white rounded-4 border-0">
            <h2 class="about-title mb-4" style="font-family: 'Playfair Display', serif; color: #58111A; font-size: 38px; font-weight: 700;">Welcome to Velawine</h2>
            <p class="about-text mx-auto text-secondary mb-4" style="font-family: 'Prompt', sans-serif; font-size: 17px; line-height: 2.0;">
                ร้าน <strong>Velawine</strong> หรือเวลาไวน์ของเรา เป็นผู้นำเข้าและจัดจำหน่ายไวน์คุณภาพเยี่ยมระดับเวิลด์คลาสจากหลากหลายแหล่งผลิตชื่อดังทั่วทุกมุมโลก ไม่ว่าจะเป็นแคว้นวินเทจจากประเทศอิตาลี ฝรั่งเศส ชิลี ออสเตรเลีย หรือสเปน เรามีความมุ่งมั่นที่จะคัดสรรไวน์ทุกขวดด้วยความพิถีพิถัน ผ่านเกณฑ์มาตรฐานการเก็บรักษาและรสชาติระดับสูงสุด เพื่อให้คนรักไวน์ในประเทศไทยได้สัมผัสกับสุนทรียภาพ กลิ่นอาย และรสชาติอันเป็นเอกลักษณ์ในทุกช่วงเวลาพิเศษ
            </p>
            <div class="mt-4">
                <a href="product.php" class="btn btn-lg text-white px-5 py-3 shadow" style="background: linear-gradient(135deg, #58111A, #8B1338); border-radius: 30px; font-weight: 600;">
                    <i class="fa-solid fa-cart-shopping me-2"></i> เลือกซื้อสินค้าและดูรายการไวน์ทั้งหมดได้เลย
                </a>
            </div>
        </div>

        <!-- Section: ประวัติความเป็นมาและปรัชญาของร้าน -->
        <div class="row align-items-center g-5 mb-5 bg-white p-5 rounded-4 shadow-sm mt-4">
            <div class="col-lg-6">
                <h3 style="color: #58111A; font-weight: 700;" class="mb-3">
                    <i class="fa-solid fa-book-open-reader me-2"></i> เรื่องราวและความเป็นมา (Our Heritage)
                </h3>
                <p class="text-secondary" style="line-height: 1.9;">
                    จุดเริ่มต้นของ <strong>Velawine</strong> เกิดจากกลุ่มผู้หลงใหลในวัฒนธรรมการดื่มไวน์และการเก็บบ่มไวน์ (Wine Enthusiasts) ที่ต้องการสร้างสรรค์พื้นที่รวบรวมไวน์หายากและไวน์คุณภาพดีจากไร่องุ่นออร์แกนิกและไร่ประวัติศาสตร์มาไว้ในที่เดียว 
                </p>
                <p class="text-secondary" style="line-height: 1.9;">
                    เราเชื่อว่า "เวลา" คือหัวใจสำคัญในการบ่มเพาะรสชาติของไวน์ เช่นเดียวกับความใส่ใจที่เรามอบให้แก่ลูกค้าทุกท่าน ทุกขวดที่ส่งมอบผ่านร้านเราจึงไม่ใช่แค่เครื่องดื่ม แต่คือประสบการณ์และศิลปะแห่งการเฉลิมฉลอง
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4 rounded-4" style="background-color: #f9f6f0; border: 2px dashed #d8c9c9;">
                    <i class="fa-solid fa-wine-bottle fa-5x mb-3" style="color: #8B1338;"></i>
                    <h4 style="color: #58111A;" class="fw-bold">Handcrafted Selection</h4>
                    <p class="text-muted small mb-0 font-prompt">คัดเลือกโดยซอมเมอเย่ร์ผู้เชี่ยวชาญ เพื่อรสชาติที่ลงตัวกับทุกมื้ออาหาร</p>
                </div>
            </div>
        </div>

        <!-- Section: จุดเด่นของเรา (Highlights) -->
        <div class="row text-center g-4 mb-5">
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm h-100 rounded-4 bg-white">
                    <div class="mb-3">
                        <i class="fa-solid fa-award fa-3x" style="color: #8B1338;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #58111A;">ไวน์นำเข้าของแท้ 100%</h5>
                    <p class="text-secondary small mb-0 font-prompt">คัดสรรโดยตรงจากไร่องุ่นชั้นนำในประเทศอิตาลี ฝรั่งเศส ชิลี และแหล่งผลิตระดับโลกอื่น ๆ มั่นใจในคุณภาพทุกหยด</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm h-100 rounded-4 bg-white">
                    <div class="mb-3">
                        <i class="fa-solid fa-truck-fast fa-3x" style="color: #8B1338;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #58111A; font-family:">จัดส่งปลอดภัยควบคุมอุณหภูมิ</h5>
                    <p class="text-secondary small mb-0 font-prompt">มีระบบการจัดส่งสินค้าที่ได้มาตรฐาน ควบคุมอุณหภูมิและความชื้นอย่างเหมาะสม มั่นใจว่าไวน์ไม่เสียหาย</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm h-100 rounded-4 bg-white">
                    <div class="mb-3">
                        <i class="fa-solid fa-headset fa-3x" style="color: #8B1338;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #58111A; font-family:">บริการให้คำปรึกษาผู้เชี่ยวชาญ</h5>
                    <p class="text-secondary small mb-0 font-prompt">มีทีมงานคอยแนะนำรสชาติไวน์ วินเทจ และการจับคู่ไวน์กับมื้ออาหาร (Wine Pairing) ให้เข้ากันอย่างลงตัว</p>
                </div>
            </div>
        </div>

        <!-- Section: หมวดหมู่ไวน์แนะนำ (Wine Categories Preview) -->
        <div class="mb-5 text-center">
            <h3 style="color: #58111A; font-weight: 700;" class="mb-2">ประเภทไวน์ที่เราให้บริการ</h3>
            <p class="text-muted mb-4 font-prompt">สำรวจคอลเลกชันไวน์หลากหลายสไตล์ที่ตอบโจทย์ทุกโอกาสพิเศษของคุณ</p>
            
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <div class="col">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 bg-white rounded-4">
                        <h5 class="fw-bold" style="color: #8B1338;">Red Wine</h5>
                        <p class="text-secondary small mb-0 font-prompt">ไวน์แดงรสชาติเข้มข้น หอมกลิ่นเบอร์รี่และโอ๊ก เหมาะกับเนื้อสัตว์และสเต็ก</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 bg-white rounded-4">
                        <h5 class="fw-bold" style="color: #8B1338;">White Wine</h5>
                        <p class="text-secondary small mb-0 font-prompt">ไวน์ขาวรสสัมผัสสดชื่น หอมกลิ่นฟรุตตี้ เหมาะกับอาหารทะเลและปลา</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 bg-white rounded-4">
                        <h5 class="fw-bold" style="color: #8B1338;">Sparkling Wine</h5>
                        <p class="text-secondary small mb-0 font-prompt">ไวน์ฟองฟู่สดใส แชมเปญระดับพรีเมียม สำหรับงานเฉลิมฉลองและปาร์ตี้</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 bg-white rounded-4">
                        <h5 class="fw-bold" style="color: #8B1338;">Dessert & Fortified</h5>
                        <p class="text-secondary small mb-0 font-prompt">ไวน์หวานและไวน์เสริมรสชาติเข้มข้น เหมาะสำหรับจิบคู่ของหวานและชีส</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center shadow-sm mt-auto py-4 bg-white border-top">
        <div class="container">
            <address class="mb-2" style="font-style: normal; color: #555;">
                <i class="fa-solid fa-location-dot me-1" style="color: #8B1338;"></i> ถนนพหลโยธิน ตำบลประชาธิปัตย์ อำเภอธัญบุรี ปทุมธานี 12130<br>
                <i class="fa-brands fa-facebook me-1" style="color: #8B1338;"></i> FB: Velawine &nbsp;|&nbsp; 
                <i class="fa-solid fa-phone me-1" style="color: #8B1338;"></i> Tel.: 02 234 5678
            </address>
            <small class="text-muted">&copy; 2026 Velawine. All Rights Reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>