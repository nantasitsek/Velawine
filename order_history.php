<?php
session_start();
require 'dbconnect.php';

// ตรวจสอบว่าเข้าสู่ระบบเป็น "ลูกค้า" หรือยัง (ถ้าไม่ใช่ ดีดไปหน้า Login)
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'customer') {
    header("Location: login.php");
    exit();
}

$cid = $_SESSION['cid'];
$displayName = $_SESSION['display_name'] ?? '';

$sql_orders = "SELECT * FROM orders WHERE cid = ? ORDER BY order_date ASC";
$stmt = mysqli_prepare($con, $sql_orders);
mysqli_stmt_bind_param($stmt, "i", $cid);
mysqli_stmt_execute($stmt);
$result_orders = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ - Velawine</title>
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
                <span class="fw-semibold text-dark">
                    <i class="fa-solid fa-user-circle me-1" style="color: #8B1338;"></i> สวัสดีคุณ <?php echo htmlspecialchars($displayName); ?>
                </span>
                <a href="product.php" class="btn btn-wine px-3 text-white fw-bold shadow-sm" style="background-color: #8B1338; border-radius: 20px;">
                    <i class="fa-solid fa-store me-1"></i> เลือกซื้อสินค้าต่อ
                </a>
                <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container py-4" style="max-width: 900px;">
        <div class="mb-4">
            <h2 class="fw-bold" style="color: #58111A;">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> ประวัติการสั่งซื้อสินค้าของคุณ
            </h2>
            <p class="text-muted">ตรวจสอบรายการคำสั่งซื้อ สถานะ และรายละเอียดสินค้าที่คุณเคยสั่งไว้</p>
        </div>

        <?php if (mysqli_num_rows($result_orders) > 0): ?>
            <?php 
            $i = 1; 
            while ($order = mysqli_fetch_assoc($result_orders)): 
            ?>
                <div class="card shadow-sm border-0 rounded-4 mb-4 p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div>
                            <span class="fw-bold fs-5 text-dark">คำสั่งซื้อที่ <?php echo $i++; ?></span>
                            <span class="text-muted small ms-3"><i class="fa-regular fa-calendar-days me-1"></i> <?php echo $order['order_date']; ?></span>
                        </div>

                        <?php
                            $status = $order['status'];
                            $badgeClass = 'bg-secondary';

                            switch ($status) {
                                case 'รอดำเนินการ':
                                case 'pending':
                                $badgeClass = 'bg-secondary';
                                break;

                                case 'ชำระเงินแล้ว':
                                case 'กำลังจัดส่ง':
                                $badgeClass = 'bg-warning text-dark';
                                break;

                                case 'จัดส่งสำเร็จ':
                                case 'completed':
                                $badgeClass = 'bg-success';
                                break;

                                case 'ยกเลิก':
                                case 'cancelled':
                               $badgeClass = 'bg-danger';
                               break;
                            }
                        ?>

                        <div>
                            <span class="badge <?php echo $badgeClass; ?> px-3 py-2" style="font-size: 0.9rem;">
                            <?php echo htmlspecialchars($status); ?>
                            </span>
                        </div>
                    </div>

                    <!-- รายการสินค้าภายในบิลนั้นๆ -->
                    <div class="table-responsive">
                        <table class="table align-middle mb-2">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th>สินค้า</th>
                                    <th class="text-center">ราคา/หน่วย</th>
                                    <th class="text-center">จำนวน</th>
                                    <th class="text-end">ราคารวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $oid = $order['order_id'];
                                $sql_detail = "SELECT od.*, w.wname FROM order_details od 
                                               JOIN winetbl w ON od.wid = w.wid 
                                               WHERE od.order_id = $oid";
                                $result_detail = mysqli_query($con, $sql_detail);
                                while ($item = mysqli_fetch_assoc($result_detail)):
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($item['wname']); ?></td>
                                    <td class="text-center">฿<?php echo number_format($item['unit_price'], 2); ?></td>
                                    <td class="text-center"><?php echo $item['quantity']; ?> ขวด</td>
                                    <td class="text-end fw-semibold" style="color: #8B1338;">฿<?php echo number_format($item['subtotal'], 2); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                        <span class="text-muted small">
                            <i class="fa-solid fa-credit-card me-1"></i> วิธีชำระเงิน: <strong><?php echo htmlspecialchars($order['payment_method']); ?></strong>
                        </span>
                        <div class="text-end">
                            <span class="text-muted me-2">ยอดชำระสุทธิ:</span>
                            <span class="fs-4 fw-bold" style="color: #8B1338;">฿<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <div class="text-muted fs-1 mb-3"><i class="fa-solid fa-box-open"></i></div>
                <h4 class="fw-semibold text-muted">คุณยังไม่มีประวัติการสั่งซื้อสินค้า</h4>
                <p class="text-muted small mb-4">เลือกซื้อไวน์พรีเมียมของเราแล้วสร้างคำสั่งซื้อแรก</p>
                <a href="product.php" class="btn text-white px-4 py-2 fw-semibold" style="background-color: #58111A; border-radius: 8px;">
                    <i class="fa-solid fa-store me-1"></i> ไปเลือกซื้อสินค้า
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>