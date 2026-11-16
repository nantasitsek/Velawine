<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'employee' || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'CEO')) {
    header("Location: login.php");
    exit();
}
// เพิ่มสินค้า
if (isset($_POST['add_product'])) {
    $wname = $_POST['wname'];
    $country = $_POST['country'];
    $region = $_POST['region'] ?? '';
    $year = $_POST['year'] ?? '';
    $wtype = $_POST['wtype'] ?? '';
    $wstyle = $_POST['wstyle'] ?? '';
    $grape = $_POST['grape'] ?? '';
    $bottlclosure = $_POST['bottlclosure'] ?? '';
    $abv = $_POST['abv'] ?? '';
    $price = $_POST['price'];
    $cost = $_POST['cost'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;

    $wimg = 'no-image.png';

    if (isset($_FILES['wimg']) && $_FILES['wimg']['error'] == 0) {
        $ext = pathinfo($_FILES['wimg']['name'], PATHINFO_EXTENSION);
        $wimg = $wname . '.' . $ext;
        if (!is_dir('images')) {
            mkdir('images', 0777, true);
        }
        move_uploaded_file($_FILES['wimg']['tmp_name'], 'images/' . $wimg);
    }

    $stmt = mysqli_prepare($con, "INSERT INTO winetbl (wname, country, region, year, wtype, wstyle, grape, bottlclosure, `%abv`, price, cost, quantity, wimg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssssssddss", $wname
    , $country, $region, $year, $wtype, $wstyle, $grape, $bottlclosure,
     $abv, $price, $cost, $quantity, $wimg);
    mysqli_stmt_execute($stmt);
    header("Location: manage_product.php");
    exit();
}

// แก้ไขสินค้า
if (isset($_POST['update_product'])) {
    $wid = (int)$_POST['wid'];
    $wname = $_POST['wname'];
    $country = $_POST['country'];
    $region = $_POST['region'];
    $year = $_POST['year'];
    $wtype = $_POST['wtype'];
    $wstyle = $_POST['wstyle'];
    $grape = $_POST['grape'];
    $bottlclosure = $_POST['bottlclosure'];
    $abv = $_POST['abv'];
    $price = $_POST['price'];
    $cost = $_POST['cost'];
    $quantity = $_POST['quantity'];

    if (isset($_FILES['wimg']) && $_FILES['wimg']['error'] == 0) {
        $ext = pathinfo($_FILES['wimg']['name'], PATHINFO_EXTENSION);
        $wimg = $wname . '.' . $ext;
        move_uploaded_file($_FILES['wimg']['tmp_name'], 'images/' . $wimg);
        $stmt = mysqli_prepare($con, "UPDATE winetbl SET wname=?, country=?, region=?, year=?, wtype=?, wstyle=?, grape=?, bottlclosure=?, `%abv`=?, price=?, cost=?, quantity=?, wimg=? WHERE wid=?");
        mysqli_stmt_bind_param($stmt, "sssssssssdddsi", $wname, $country, $region, $year, $wtype, $wstyle, $grape, $bottlclosure, $abv, $price, $cost, $quantity, $wimg, $wid);
    } else {
        $stmt = mysqli_prepare($con, "UPDATE winetbl SET wname=?, country=?, region=?, year=?, wtype=?, wstyle=?, grape=?, bottlclosure=?, `%abv`=?, price=?, cost=?, quantity=? WHERE wid=?");
        mysqli_stmt_bind_param($stmt, "sssssssssdddi", $wname, $country, $region, $year, $wtype, $wstyle, $grape, $bottlclosure, $abv, $price, $cost, $quantity, $wid);
    }
    mysqli_stmt_execute($stmt);
    header("Location: manage_product.php");
    exit();
}

// ลบสินค้า
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($con, "DELETE FROM winetbl WHERE wid = $id");
    header("Location: manage_product.php");
    exit();
}

$products = mysqli_query($con, "SELECT * FROM winetbl");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า - Velawine</title>
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
                <a href="index_for_staff.php" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> กลับหน้าหลักหลังบ้าน</a>
                <a href="log_out.php" class="btn btn-red btn-sm">Logout</a>
            </div>
        </div>
    </header>

    <div class="container py-4">
        <h2 class="mb-4 fw-bold" style="color: #58111A;"><i class="fa-solid fa-boxes-stacked me-2"></i> จัดการรายการสินค้า</h2>

        <!-- ฟอร์มเพิ่มสินค้า -->
        <div class="card shadow-sm p-4 mb-5 border-0 rounded-4 bg-white">
            <h4 class="mb-3 text-secondary"><i class="fa-solid fa-circle-plus me-2" style="color: #8B1338;"></i> เพิ่มรายการสินค้าใหม่</h4>
            <form action="manage_product.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ชื่อไวน์ </label>
                        <input type="text" name="wname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ประเทศ </label>
                        <input type="text" name="country" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">แคว้น/ภูมิภาค </label>
                        <input type="text" name="region" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ปีผลิต </label>
                        <input type="text" name="year" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ประเภทไวน์ </label>
                        <input type="text" name="wtype" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">สไตล์ไวน์ </label>
                        <input type="text" name="wstyle" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">พันธุ์องุ่น </label>
                        <input type="text" name="grape" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ลักษณะจุกปิด </label>
                        <input type="text" name="bottlclosure" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">แอลกอฮอล์ </label>
                        <input type="text" name="abv" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">ต้นทุน </label>
                        <input type="number" step="0.01" name="cost" class="form-control" required value="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">ราคาขาย </label>
                        <input type="number" step="0.01" name="price" class="form-control" required value="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">จำนวนสต็อก </label>
                        <input type="number" name="quantity" class="form-control" required value="10">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">รูปภาพสินค้า </label>
                        <input type="file" name="wimg" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="add_product" class="btn text-white px-5 py-2 fw-semibold" style="background-color: #58111A;">
                            <i class="fa-solid fa-plus me-1"></i> เพิ่มสินค้า
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ตารางแสดงสินค้า -->
        <div class="card shadow-sm p-4 border-0 rounded-4 bg-white">
            <h4 class="mb-3 text-secondary">รายการสินค้าทั้งหมด</h4>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead style="background-color: #58111A; color: white;">
                        <tr>
                            <th class="py-3 ps-3">รูป</th>
                            <th class="py-3">ID</th>
                            <th class="py-3">ชื่อไวน์</th>
                            <th class="py-3">ประเทศ</th>
                            <th class="py-3">ราคา</th>
                            <th class="text-center py-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = mysqli_fetch_assoc($products)): 
                            $img = !empty($p['wimg']) ? "images/" . $p['wimg'] : "images/no-image.png";
                        ?>
                        <tr>
                            <td class="ps-3"><img src="<?php echo htmlspecialchars($img); ?>" width="45" height="45" style="object-fit: contain;"></td>
                            <td class="ps-3"><?php echo $p['wid']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($p['wname']); ?></td>
                            <td><?php echo htmlspecialchars($p['country']); ?></td>
                            <td class="fw-bold" style="color: #8B1338;">฿<?php echo number_format($p['price'], 2); ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-yellow" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $p['wid']; ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                </button>
                                <a href="manage_product.php?action=delete&id=<?php echo $p['wid']; ?>" class="btn btn-sm btn-red" onclick="return confirm('ยืนยันลบสินค้ารายการนี้?');">
                                    <i class="fa-solid fa-trash"></i> ลบ
                                </a>
                            </td>
                        </tr>

                        <!-- แก้ไขสินค้า -->
                        <div class="modal fade" id="editModal<?php echo $p['wid']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="manage_product.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-header">
                                            <h5 class="modal-title">แก้ไขสินค้า: <?php echo htmlspecialchars($p['wname']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <input type="hidden" name="wid" value="<?php echo $p['wid']; ?>">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">ชื่อไวน์</label>
                                                    <input type="text" name="wname" class="form-control" value="<?php echo htmlspecialchars($p['wname']); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">ประเทศ</label>
                                                    <input type="text" name="country" class="form-control" value="<?php echo htmlspecialchars($p['country']); ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">แคว้น</label>
                                                    <input type="text" name="region" class="form-control" value="<?php echo htmlspecialchars($p['region'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">ปีผลิต</label>
                                                    <input type="text" name="year" class="form-control" value="<?php echo htmlspecialchars($p['year'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">ประเภท</label>
                                                    <input type="text" name="wtype" class="form-control" value="<?php echo htmlspecialchars($p['wtype'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">สไตล์</label>
                                                    <input type="text" name="wstyle" class="form-control" value="<?php echo htmlspecialchars($p['wstyle'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">องุ่น</label>
                                                    <input type="text" name="grape" class="form-control" value="<?php echo htmlspecialchars($p['grape'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">จุกปิด</label>
                                                    <input type="text" name="bottlclosure" class="form-control" value="<?php echo htmlspecialchars($p['bottlclosure'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">แอลกอฮอล์</label>
                                                    <input type="text" name="abv" class="form-control" value="<?php echo htmlspecialchars($p['%abv'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">ต้นทุน</label>
                                                    <input type="number" step="0.01" name="cost" class="form-control" value="<?php echo $p['cost']; ?>" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">ราคาขาย</label>
                                                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $p['price']; ?>" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">สต็อก</label>
                                                    <input type="number" name="quantity" class="form-control" value="<?php echo $p['quantity']; ?>" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">เปลี่ยนรูปภาพ</label>
                                                    <input type="file" name="wimg" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" name="update_product" class="btn btn-save">บันทึกการแก้ไข</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>