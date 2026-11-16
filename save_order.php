
<?php
session_start();
require 'dbconnect.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ตรวจสอบว่าลูกค้าล็อกอินอยู่
if (!isset($_SESSION['cid'])) {
    header("Location: login.php");
    exit();
}

$cid = (int)$_SESSION['cid'];

$payment_method = $_POST['payment_method'] ?? 'QR PromptPay';
$qtys = $_POST['qty'] ?? [];

// ตรวจสอบวิธีชำระเงิน
$allowed_methods = [
    'QR PromptPay',
    'Bank Transfer',
    'Cash on Delivery'
];

if (!in_array($payment_method, $allowed_methods, true)) {
    die("วิธีชำระเงินไม่ถูกต้อง");
}

if (empty($qtys)) {
    header("Location: product.php");
    exit();
}

$stmt_c = mysqli_prepare(
    $con,
    "SELECT * FROM customer WHERE cid = ?"
);

mysqli_stmt_bind_param($stmt_c, "i", $cid);
mysqli_stmt_execute($stmt_c);

$customer_result = mysqli_stmt_get_result($stmt_c);
$customer = mysqli_fetch_assoc($customer_result);

if (!$customer) {
    die("ไม่พบข้อมูลลูกค้า");
}

$cart_items = [];
$total_amount = 0;

$stmt_p = mysqli_prepare(
    $con,
    "SELECT wid, wname, price FROM winetbl WHERE wid = ?"
);

foreach ($qtys as $wid => $qty) {

    $wid = (int)$wid;
    $qty = (int)$qty;

    if ($wid <= 0 || $qty <= 0) {
        continue;
    }

    mysqli_stmt_bind_param($stmt_p, "i", $wid);
    mysqli_stmt_execute($stmt_p);

    $product_result = mysqli_stmt_get_result($stmt_p);
    $product = mysqli_fetch_assoc($product_result);

    if (!$product) {
        die("ไม่พบสินค้า รหัส: " . $wid);
    }

    $unit_price = (float)$product['price'];
    $subtotal = $unit_price * $qty;

    $total_amount += $subtotal;

    $cart_items[] = [
        'wid' => $wid,
        'wname' => $product['wname'],
        'quantity' => $qty,
        'unit_price' => $unit_price,
        'subtotal' => $subtotal
    ];
}

if (empty($cart_items)) {
    header("Location: product.php");
    exit();
}

try {

    mysqli_begin_transaction($con);

    $sql_order = "
        INSERT INTO orders (cid, total_amount, payment_method, status, order_date)
        VALUES (?, ?, ?, 'รอดำเนินการ', NOW())
    ";
    $stmt_order = mysqli_prepare($con, $sql_order);
    mysqli_stmt_bind_param(
        $stmt_order,
        "ids",
        $cid,
        $total_amount,
        $payment_method
    );
    mysqli_stmt_execute($stmt_order);

    $order_id = mysqli_insert_id($con);

    $sql_detail = "
        INSERT INTO order_details
        (order_id, wid, quantity, unit_price, subtotal)
        VALUES (?, ?, ?, ?, ?)
    ";

    $stmt_detail = mysqli_prepare($con, $sql_detail);

    foreach ($cart_items as $item) {

        $wid = $item['wid'];
        $quantity = $item['quantity'];
        $unit_price = $item['unit_price'];
        $subtotal = $item['subtotal'];

        mysqli_stmt_bind_param(
            $stmt_detail,
            "iiidd",
            $order_id,
            $wid,
            $quantity,
            $unit_price,
            $subtotal
        );

        mysqli_stmt_execute($stmt_detail);
    }

    mysqli_commit($con);

    unset($_SESSION['cart']);

} catch (Throwable $e) {
    mysqli_rollback($con);
    die("เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ: " . $e->getMessage());
}

$sql_items = "
    SELECT
        od.*,
        w.wname
    FROM order_details od
    JOIN winetbl w ON od.wid = w.wid
    WHERE od.order_id = ?
";

$stmt_items = mysqli_prepare($con, $sql_items);
mysqli_stmt_bind_param($stmt_items, "i", $order_id);
mysqli_stmt_execute($stmt_items);

$items_res = mysqli_stmt_get_result($stmt_items);
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จรับเงิน / ใบกำกับภาษี - Velawine</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Prompt:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <header class="py-4 mb-4 bg-white border-bottom shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="text-decoration-none">
                <h1 class="brand mb-0">
                    <i class="fa-solid fa-wine-glass-empty me-2" style="color: #8B1338;"></i>Velawine
                </h1>
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="index.php" class="btn btn-outline-secondary" style="padding: 9px 20px;">
                    <i class="fa-solid fa-house me-1"></i> หน้าหลัก
                </a>

                <a href="order_history.php" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" style="border-radius: 20px;">
                    <i class="fa-solid fa-clock-rotate-left me-1"></i> ประวัติการสั่งซื้อ
                </a>

                <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
            </div>
        </div>
    </header>

<div class="container py-5">

    <div class="card shadow p-5 mx-auto bg-white" style="max-width: 800px;">
        
        <!-- Header ใบเสร็จ -->
        <div class="d-flex justify-content-between border-bottom pb-4 mb-4">
            <div>
                <h2 class="fw-bold" style="color: #58111A;"><i class="fa-solid fa-wine-glass-empty me-2"></i>Velawine</h2>
                <p class="text-muted mb-0">ร้านเวลาไวน์ ผู้จำหน่ายไวน์นำเข้า</p>
            </div>
            <div class="text-end">
                <h4 class="fw-bold text-secondary">ใบเสร็จรับเงิน / ใบกำกับสินค้า</h4>
                <p class="mb-0"><strong>เลขที่ออเดอร์:</strong> #<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></p>
                <p class="mb-0"><strong>วันที่:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </div>

        <!-- ข้อมูลลูกค้าและวิธีชำระเงิน -->
        <div class="row mb-4">
            <div class="col-sm-6">
                <h6 class="text-secondary fw-bold">ข้อมูลผู้รับสินค้า:</h6>
                <p class="mb-1"><strong>ชื่อ:</strong> <?php echo htmlspecialchars($customer['cname'].' '.$customer['csurname']); ?></p>
                <p class="mb-1"><strong>ที่อยู่:</strong> <?php echo htmlspecialchars($customer['address']
                .' '.$customer['soi'].' '.$customer['street'].' '.$customer['sub_district'].' '.$customer['district']
                .' '.$customer['province'].' ' .$customer['post_id']); ?></p>
                <p class="mb-0"><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($customer['tel'] ?? '-'); ?></p>
            </div>

            <div class="col-sm-6 text-sm-end">
                <h6 class="text-secondary fw-bold">การชำระเงิน:</h6>
                <p class="mb-1"><strong>วิธีชำระ:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
                <p class="mb-0 text-success"><strong>สถานะ:</strong> บันทึกคำสั่งซื้อสำเร็จ</p>
            </div>
        </div>

        <!-- ตารางรายการสินค้า -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-dark" style="background-color: #58111A !important;">
                    <tr>
                        <th>สินค้า</th>
                        <th class="text-center">จำนวน</th>
                        <th class="text-end">ราคาต่อหน่วย</th>
                        <th class="text-end">ราคารวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($items_res)): 
                        $total_row = $item['subtotal'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['wname']); ?></td>
                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                        <td class="text-end">฿<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td class="text-end">฿<?php echo number_format($total_row, 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold fs-5">ยอดชำระสุทธิ:</td>
                        <td class="text-end fw-bold fs-5" style="color: #8B1338;">฿<?php echo number_format($total_amount, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print();" class="btn btn-outline-secondary me-2">
                <i class="fa-solid fa-print me-1"></i> พิมพ์ใบเสร็จ
            </button>
            <a href="product.php" class="btn btn-wine px-4">
                <i class="fa-solid fa-store me-1"></i> เลือกซื้อสินค้าต่อ
            </a>
        </div>

    </div>
</div>
</body>
</html>