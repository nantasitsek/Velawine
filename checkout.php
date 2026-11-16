<?php
session_start();
require 'dbconnect.php';

// 1. ถ้าเป็นพนักงานหรือผู้บริหาร (Admin, CEO, Staff) กดเข้ามาหน้าชำระเงิน ให้เด้งเตือนและดีดกลับทันที
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'employee') {
    echo "<script>
        alert('บัญชีผู้ดูแลระบบ พนักงานและผู้บริหาร ไม่สามารถสั่งซื้อสินค้าผ่านหน้าร้านได้ กรุณาใช้บัญชีลูกค้าในการสั่งซื้อ');
        window.location.href = 'product.php';
    </script>";
    exit();
}

// 2. เช็คสิทธิ์พนักงานหลังบ้าน (ใช้ in_array เพื่อเช็คว่าอยู่ในกลุ่ม admin, CEO หรือ staff คนใดคนหนึ่ง)
$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'employee' && isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'CEO', 'staff']);

$displayName = $_SESSION['display_name'] ?? '';

// 3. ระบบอัปเดตจำนวนสินค้าอัตโนมัติทันทีที่มีการเปลี่ยนตัวเลขในช่อง Input
if (isset($_POST['qty'])) {
    foreach ($_POST['qty'] as $wid => $quantity) {
        $quantity = (int)$quantity;
        $wid = (int)$wid;
        if ($quantity > 0) {
            $_SESSION['cart'][$wid] = $quantity;
        } else {
            unset($_SESSION['cart'][$wid]); // ถ้าใส่ 0 หรือติดลบ ให้ลบออกจากตะกร้า
        }
    }
    header("Location: checkout.php");
    exit();
}

// 2. จัดการการลบสินค้าทีละรายการ (เมื่อคลิกปุ่มถังขยะ)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    unset($_SESSION['cart'][$del_id]);
    header("Location: checkout.php");
    exit();
}

// 3. ดึงข้อมูลสินค้าจาก Session Cart มาแสดงผลในตาราง
$qtys = $_SESSION['cart'] ?? [];
$cart_items = [];
$grand_total = 0;

if (!empty($qtys)) {
    foreach ($qtys as $wid => $quantity) {
        $wid = (int)$wid;
        $quantity = (int)$quantity;
        if ($quantity > 0) {
            $stmt = mysqli_prepare($con, "SELECT * FROM winetbl WHERE wid = ?");
            mysqli_stmt_bind_param($stmt, "i", $wid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($res)) {
                $subtotal = $row['price'] * $quantity;
                $grand_total += $subtotal;
                $row['order_qty'] = $quantity;
                $row['subtotal'] = $subtotal;
                $cart_items[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตะกร้าสินค้าและสรุปรายการ - Velawine</title>
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
                        <i class="fa-solid fa-user-circle me-1" style="color: #8B1338;"></i> สวัสดีคุณ <?php echo htmlspecialchars($displayName); ?>
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

    <div class="container py-4">
        <h3 class="mb-4" style="color: #58111A; font-weight: 700;">
            <i class="fa-solid fa-cart-shopping me-2"></i> ตะกร้าสินค้าที่คุณเลือกซื้อ
        </h3>

        <?php if (!empty($cart_items)): ?>
        <div class="card shadow-sm p-4 bg-white rounded-4 border-0">
            <form id="cartForm" action="checkout.php" method="POST">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead style="background-color: #58111A; color: white;">
                            <tr>
                                <th class="py-3 ps-3">สินค้า</th>
                                <th class="py-3">ราคา/ชิ้น</th>
                                <th class="text-center py-3" style="width: 150px;">จำนวน (ขวด)</th>
                                <th class="text-end py-3">ราคารวม</th>
                                <th class="text-center py-3" style="width: 100px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td class="fw-bold ps-3"><?php echo htmlspecialchars($item['wname']); ?></td>
                                <td>฿<?php echo number_format($item['price'], 2); ?></td>
                                <td class="text-center">
                                    <!-- ใส่ onchange="this.form.submit()" เพื่อให้อัปเดตทันทีเมื่อเปลี่ยนตัวเลข -->
                                    <input type="number" 
                                           name="qty[<?php echo $item['wid']; ?>]" 
                                           value="<?php echo $item['order_qty']; ?>" 
                                           min="1" 
                                           class="form-control text-center mx-auto" 
                                           style="width: 80px;"
                                           onchange="this.form.submit()">
                                </td>
                                <td class="text-end fw-bold" style="color: #8B1338;">฿<?php echo number_format($item['subtotal'], 2); ?></td>
                                <td class="text-center">
                                    <a href="checkout.php?action=delete&id=<?php echo $item['wid']; ?>" 
                                       class="btn btn-sm btn-red" 
                                       onclick="return confirm('คุณต้องการลบสินค้ารายการนี้ออกจากตะกร้าใช่หรือไม่?');"
                                       title="ลบสินค้า">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="table-light fs-5">
                                <td colspan="3" class="text-end fw-bold py-3">ยอดชำระสุทธิ:</td>
                                <td colspan="2" class="text-end fw-bold py-3" style="color: #8B1338;">฿<?php echo number_format($grand_total, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="product.php" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fa-solid fa-arrow-left me-2"></i> เลือกซื้อสินค้าต่อ
                    </a>
                    <a href="payment.php" class="btn btn-wine btn-lg px-5 py-2 text-white" style="background: linear-gradient(135deg, #58111A, #8B1338); font-weight: 600;">
                        ไปหน้าชำระเงิน <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center py-5 rounded-4 shadow-sm">
            <h4 class="mb-3">ยังไม่มีสินค้าในตะกร้าของคุณ</h4>
            <a href="product.php" class="btn btn-wine mt-2 px-4 py-2 text-white" style="background-color: #58111A;">กลับไปเลือกสินค้า</a>
        </div>
        <?php endif; ?>
    </div>

    <footer class="text-center shadow-sm mt-auto py-3 bg-white border-top">
        <div class="container">
            <small style="color: #555;">&copy; 2026 Velawine. All Rights Reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>