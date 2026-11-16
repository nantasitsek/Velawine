<?php
session_start();
require 'dbconnect.php';

// ตรวจสอบว่าลูกค้าล็อกอินหรือยัง
if (!isset($_SESSION['cid'])) {
    header("Location: login.php");
    exit();
}
$isAdmin = (
    $_SESSION['user_type'] == 'employee' &&
    in_array($_SESSION['role'] ?? '', ['admin', 'CEO', 'staff'])
);

$selected_cid = $_SESSION['cid'];

$qtys = $_SESSION['cart'] ?? [];
if (empty($qtys)) {
    header("Location: product.php");
    exit();
}

// ดึงข้อมูลลูกค้าจากฐานข้อมูล
$stmt_c = mysqli_prepare($con, "SELECT * FROM customer WHERE cid = ?");
mysqli_stmt_bind_param($stmt_c, "i", $selected_cid);
mysqli_stmt_execute($stmt_c);
$result_customer = mysqli_stmt_get_result($stmt_c);
$customer_info = mysqli_fetch_assoc($result_customer);
if (!$customer_info) {
    die("ไม่พบข้อมูลลูกค้า");
}

// คำนวณรายการสินค้า
$cart_items = [];
$grand_total = 0;
foreach ($qtys as $wid => $quantity) {
    $wid = (int)$wid;
    $quantity = (int)$quantity;
    if ($quantity > 0) {
        $stmt = mysqli_prepare($con, "SELECT * FROM winetbl WHERE wid = ?");
        mysqli_stmt_bind_param($stmt, "i", $wid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $subtotal = $row['price'] * $quantity;
            $grand_total += $subtotal;
            $row['order_qty'] = $quantity;
            $row['subtotal'] = $subtotal;
            $cart_items[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ชำระเงินและยืนยันคำสั่งซื้อ - Velawine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="font-family: 'Prompt', sans-serif;">
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
                        <i class="fa-solid fa-user-circle me-1" style="color: #8B1338;"></i> สวัสดีคุณ <?php echo htmlspecialchars($displayName ?? $_SESSION['display_name'] ?? ''); ?>
                    </span>
                <?php endif; ?>
                <a href="product.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-store me-1"></i> เลือกซื้อสินค้า
                </a>
                <?php if ($isAdmin): ?>
                    <a href="index_for_staff.php" class="btn text-dark fw-semibold" style="background-color: #ffc107; border: none;">
                        <i class="fa-solid fa-gears me-1"></i> จัดการข้อมูลหลังบ้าน
                    </a>
                <?php endif; ?>
                <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
            </div>
        </div>
    </header>

    <div class="container py-5">
        <h3 class="mb-4" style="color: #58111A; font-weight: 700;">
            <i class="fa-solid fa-credit-card me-2"></i> ยืนยันข้อมูลการจัดส่งและเลือกวิธีชำระเงิน
        </h3>
        
        <form action="save_order.php" method="POST">
            <input type="hidden" name="cid" value="<?php echo htmlspecialchars($selected_cid); ?>">
            <input type="hidden" name="total_amount" value="<?php echo $grand_total; ?>">
            <?php foreach ($cart_items as $item): ?>
                <input type="hidden" name="qty[<?php echo $item['wid']; ?>]" value="<?php echo $item['order_qty']; ?>">
            <?php endforeach; ?>

            <div class="row g-4">
                <!-- ข้อมูลจัดส่ง -->
                <div class="col-md-6">
                    <div class="card shadow-sm p-4 h-100 border-0 rounded-4">
                        <h5 class="mb-3 text-secondary">
                            <i class="fa-solid fa-location-dot me-2" style="color: #8B1338;"></i> สถานที่จัดส่ง / ผู้รับ
                        </h5>
                        <p class="mb-1">
                            <strong>ชื่อร้าน/ลูกค้า:</strong> 
                            <?php echo htmlspecialchars($customer_info['cname'] . ' ' . $customer_info['csurname']); ?>
                        </p>
                        <p class="mb-1">
                            <strong>ที่อยู่:</strong> 
                            <?php 
                            echo htmlspecialchars(
                                $customer_info['address'] . ' ' . 
                                $customer_info['soi'] . ' ' . 
                                $customer_info['street'] . ' ' . 
                                $customer_info['sub_district'] . ' ' . 
                                $customer_info['district'] . ' ' . 
                                $customer_info['province'] . ' ' . 
                                $customer_info['post_id']
                            ); 
                            ?>
                        </p>
                        <p class="mb-0">
                            <strong>เบอร์โทรศัพท์:</strong> 
                            <?php echo htmlspecialchars($customer_info['tel'] ?? 'ไม่ได้ระบุ'); ?>
                        </p>
                    </div>
                </div>

                <!-- เลือกวิธีชำระเงิน -->
                <div class="col-md-6">
                    <div class="card shadow-sm p-4 h-100 border-0 rounded-4">
                        <h5 class="mb-3 text-secondary">
                            <i class="fa-solid fa-wallet me-2" style="color: #8B1338;"></i> เลือกวิธีชำระเงิน
                        </h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="qr" value="QR PromptPay" checked>
                            <label class="form-check-label" for="qr">
                                <i class="fa-solid fa-qrcode me-2 text-success"></i> สแกน QR Code พร้อมเพย์
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="Bank Transfer">
                            <label class="form-check-label" for="transfer">
                                <i class="fa-solid fa-building-columns me-2 text-primary"></i> โอนเงินผ่านบัญชีธนาคาร
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="Cash on Delivery">
                            <label class="form-check-label" for="cash">
                                <i class="fa-solid fa-money-bill-wave me-2 text-warning"></i> ชำระเงินปลายทาง
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- สรุปรายการสินค้าที่จะได้รับ -->
            <div class="card shadow-sm p-4 mt-4 border-0 rounded-4">
                <h5 class="mb-3 text-secondary">รายการสินค้าทั้งหมด (<?php echo count($cart_items); ?> รายการ)</h5>
                <ul class="list-group list-group-flush mb-3">
                    <?php foreach ($cart_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span><?php echo htmlspecialchars($item['wname']); ?> (x<?php echo $item['order_qty']; ?>)</span>
                        <span class="fw-bold">฿<?php echo number_format($item['subtotal'], 2); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="d-flex justify-content-between align-items-center fs-4 border-top pt-3">
                    <span class="fw-bold">ยอดสุทธิที่ต้องชำระ:</span>
                    <span class="fw-bold" style="color: #8B1338;">฿<?php echo number_format($grand_total, 2); ?></span>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="checkout.php" class="btn btn-outline-secondary px-4 py-2 me-2">ย้อนกลับ</a>
                <button type="submit" class="btn btn-wine btn-lg px-5 py-2 text-white" style="background: linear-gradient(135deg, #58111A, #8B1338); font-weight: 600;">
                    <i class="fa-solid fa-check-circle me-2"></i> ยืนยันการสั่งซื้อสินค้า
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>