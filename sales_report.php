<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'employee' || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'CEO')) {
    header("Location: login.php");
    exit();
}

$sql_total = "
    SELECT
        COALESCE(SUM(od.subtotal), 0) AS total_sales,
        COALESCE(SUM(od.quantity), 0) AS total_quantity
    FROM orders o
    JOIN order_details od ON o.order_id = od.order_id
";
$result_total = mysqli_query($con, $sql_total);
$total_data = mysqli_fetch_assoc($result_total);

$sql_products = "
    SELECT
        w.wid,
        w.wname,
        SUM(od.quantity) AS total_quantity,
        SUM(od.subtotal) AS total_sales
    FROM order_details od
    JOIN winetbl w ON od.wid = w.wid
    JOIN orders o ON od.order_id = o.order_id
    GROUP BY w.wid, w.wname
    ORDER BY total_quantity DESC, total_sales DESC
";
$result_products = mysqli_query($con, $sql_products);

$sql_top3 = "
    SELECT
        w.wid,
        w.wname,
        SUM(od.quantity) AS total_quantity,
        SUM(od.subtotal) AS total_sales
    FROM order_details od
    JOIN winetbl w ON od.wid = w.wid
    JOIN orders o ON od.order_id = o.order_id
    GROUP BY w.wid, w.wname
    ORDER BY total_quantity DESC, total_sales DESC
    LIMIT 3
";
$result_top3 = mysqli_query($con, $sql_top3);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สรุปข้อมูลยอดขาย - Velawine</title>
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
                <a href="index_for_staff.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> กลับหน้าหลักหลังบ้าน</a>
                <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
            </div>
        </div>
    </header>

    <div class="container py-4">
        <h2 class="mb-4 fw-bold" style="color: #58111A;"><i class="fa-solid fa-chart-line me-2" style="color: #8B1338;"></i> สรุปข้อมูลยอดขาย</h2>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                    <p class="text-muted mb-2">ยอดขายรวมทั้งหมด</p>
                    <h2 class="fw-bold" style="color:#8B1338;">฿<?php echo number_format($total_data['total_sales'], 2); ?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
                    <p class="text-muted mb-2">จำนวนสินค้าที่ขายได้</p>
                    <h2 class="fw-bold" style="color:#8B1338;"><?php echo number_format($total_data['total_quantity']); ?> ขวด</h2>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4 mb-4 rounded-4 bg-white">
            <h5 class="fw-bold mb-4" style="color:#58111A;"><i class="fa-solid fa-trophy me-2 text-warning"></i> Top 3 Best-Selling Products</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead style="background-color: #58111A; color: white;">
                        <tr>
                            <th class="text-center py-3 ps-3">อันดับ</th>
                            <th class="text-center py-3">รหัสไวน์</th>
                            <th class="py-3">ชื่อไวน์</th>
                            <th class="text-center py-3">จำนวนที่ขายได้</th>
                            <th class="text-end py-3 pe-3">ยอดขายรวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rank = 1;
                        if ($result_top3 && mysqli_num_rows($result_top3) > 0):
                            while ($product = mysqli_fetch_assoc($result_top3)):
                        ?>
                        <tr>
                            <td class="text-center ps-3 fw-bold text-danger"><?php echo $rank++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($product['wid']); ?></td>
                            <td class="fw-semibold"><?php echo htmlspecialchars($product['wname']); ?></td>
                            <td class="text-center"><?php echo number_format($product['total_quantity']); ?></td>
                            <td class="text-end fw-bold pe-3" style="color: #8B1338;">฿<?php echo number_format($product['total_sales'], 2); ?></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีข้อมูลยอดขาย</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-4 rounded-4 bg-white">
            <h5 class="fw-bold mb-4" style="color:#58111A;"><i class="fa-solid fa-wine-bottle me-2" style="color: #8B1338;"></i> Product Sales Summary</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center py-3 ps-3">รหัสสินค้า</th>
                            <th class="text-center py-3">ชื่อสินค้า</th>
                            <th class="text-center py-3">จำนวนที่ขายได้</th>
                            <th class="text-center py-3 pe-3">ยอดขายรวม (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_products && mysqli_num_rows($result_products) > 0): ?>
                            <?php while ($product = mysqli_fetch_assoc($result_products)): ?>
                            <tr>
                                <td class="text-center ps-3"><?php echo htmlspecialchars($product['wid']); ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($product['wname']); ?></td>
                                <td class="text-center"><?php echo number_format($product['total_quantity']); ?></td>
                                <td class="text-end fw-bold pe-3" style="color: #8B1338;">฿<?php echo number_format($product['total_sales'], 2); ?></td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">ยังไม่มีข้อมูลยอดขาย</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>