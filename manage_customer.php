<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'employee' || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'CEO')) {
    header("Location: login.php");
    exit();
}
// เพิ่มลูกค้า
if (isset($_POST['add_customer'])) {
    $cusername = $_POST['cusername'];
    $cpassword = $_POST['cpassword'];
    $cname = $_POST['cname'];
    $csurname = $_POST['csurname'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $soi = $_POST['soi'];
    $street = $_POST['street'];
    $sub_district = $_POST['sub_district'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $post_id = $_POST['post_id'];

    $stmt = mysqli_prepare($con, "INSERT INTO customer (cusername, cpassword, cname, csurname, tel, address, soi, street, sub_district, district, province, post_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssssssssss", $cusername, $cpassword, $cname, $csurname, $tel, $address, $soi, $street, $sub_district, $district, $province, $post_id);
    mysqli_stmt_execute($stmt);
    header("Location: manage_customer.php");
    exit();
}

// แก้ไขลูกค้า
if (isset($_POST['update_customer'])) {
    $cid = (int)$_POST['cid'];
    $cusername = $_POST['cusername'];
    $cpassword = $_POST['cpassword'];
    $cname = $_POST['cname'];
    $csurname = $_POST['csurname'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $soi = $_POST['soi'];
    $street = $_POST['street'];
    $sub_district = $_POST['sub_district'];
    $district = $_POST['district'];
    $province = $_POST['province'];
    $post_id = $_POST['post_id'];

    $stmt = mysqli_prepare($con, "UPDATE customer SET cusername=?, cpassword=?, cname=?, csurname=?, tel=?, address=?, soi=?, street=?, sub_district=?, district=?, province=?, post_id=? WHERE cid=?");
    mysqli_stmt_bind_param($stmt, "ssssssssssssi", $cusername, $cpassword, $cname, $csurname, $tel, $address, $soi, $street, $sub_district, $district, $province, $post_id, $cid);
    mysqli_stmt_execute($stmt);
    header("Location: manage_customer.php");
    exit();
}

// ลบลูกค้า
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $cid = (int)$_GET['id'];
    mysqli_query($con, "DELETE FROM customer WHERE cid = $cid");
    header("Location: manage_customer.php");
    exit();
}

$customers = mysqli_query($con, "SELECT * FROM customer");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อมูลลูกค้า - Velawine</title>
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
        <h2 class="mb-4 fw-bold" style="color: #58111A;"><i class="fa-solid fa-users me-2"></i> จัดการข้อมูลลูกค้า</h2>

        <!-- ฟอร์มเพิ่มลูกค้า -->
        <div class="card shadow-sm p-4 mb-5 border-0 rounded-4 bg-white">
            <h4 class="mb-3 text-secondary"><i class="fa-solid fa-user-plus me-2" style="color: #8B1338;"></i> เพิ่มข้อมูลลูกค้าใหม่</h4>
            <form action="manage_customer.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="cusername" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="text" name="cpassword" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ชื่อ </label>
                        <input type="text" name="cname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">นามสกุล </label>
                        <input type="text" name="csurname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">เบอร์โทรศัพท์ </label>
                        <input type="text" name="tel" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">บ้านเลขที่</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ซอย </label>
                        <input type="text" name="soi" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ถนน</label>
                        <input type="text" name="street" class="form-control">
                    </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">เขต/อำเภอ</label>
                        <input type="text" name="district" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">แขวง/ตำบล </label>
                        <input type="text" name="sub_district" class="form-control">
                    </div>
                   
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">จังหวัด </label>
                        <input type="text" name="province" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">รหัสไปรษณีย์ </label>
                        <input type="text" name="post_id" class="form-control">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="add_customer" class="btn text-white px-5 py-2 fw-semibold" style="background-color: #58111A;">
                            <i class="fa-solid fa-plus me-1"></i> เพิ่มข้อมูลลูกค้า
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ตารางแสดงรายชื่อลูกค้า -->
        <div class="card shadow-sm p-4 border-0 rounded-4 bg-white">
            <h4 class="mb-3 text-secondary">รายชื่อลูกค้าทั้งหมด</h4>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead style="background-color: #58111A; color: white;">
                        <tr>
                            <th class="py-3 ps-3">CID</th>
                            <th class="py-3">ชื่อ - นามสกุล</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">เบอร์โทร</th>
                            <th class="text-center py-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                        <tr>
                            <td class="ps-3"><?php echo $c['cid']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($c['cname'] . ' ' . $c['csurname']); ?></td>
                            <td><?php echo htmlspecialchars($c['cusername']); ?></td>
                            <td><?php echo htmlspecialchars($c['tel']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-yellow" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $c['cid']; ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                </button>
                                <a href="manage_customer.php?action=delete&id=<?php echo $c['cid']; ?>" class="btn btn-sm btn-red" onclick="return confirm('ยืนยันลบลูกค้านี้?');">
                                    <i class="fa-solid fa-trash"></i> ลบ
                                </a>
                            </td>
                        </tr>

                        <!-- แก้ไขลูกค้า -->
                        <div class="modal fade" id="editModal<?php echo $c['cid']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="manage_customer.php" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">แก้ไขข้อมูลลูกค้า: <?php echo htmlspecialchars($c['cname']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <input type="hidden" name="cid" value="<?php echo $c['cid']; ?>">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" name="cusername" class="form-control" value="<?php echo htmlspecialchars($c['cusername']); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Password</label>
                                                    <input type="text" name="cpassword" class="form-control" value="<?php echo htmlspecialchars($c['cpassword']); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">ชื่อ </label>
                                                    <input type="text" name="cname" class="form-control" value="<?php echo htmlspecialchars($c['cname']); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">นามสกุล </label>
                                                    <input type="text" name="csurname" class="form-control" value="<?php echo htmlspecialchars($c['csurname']); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">เบอร์โทร</label>
                                                    <input type="text" name="tel" class="form-control" value="<?php echo htmlspecialchars($c['tel'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">บ้านเลขที่</label>
                                                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($c['address'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">ซอย</label>
                                                    <input type="text" name="soi" class="form-control" value="<?php echo htmlspecialchars($c['soi'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">ถนน</label>
                                                    <input type="text" name="street" class="form-control" value="<?php echo htmlspecialchars($c['street'] ?? ''); ?>">
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label">เขต/อำเภอ</label>
                                                    <input type="text" name="district" class="form-control" value="<?php echo htmlspecialchars($c['district'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">แขวง/ตำบล</label>
                                                    <input type="text" name="sub_district" class="form-control" value="<?php echo htmlspecialchars($c['sub_district'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">จังหวัด</label>
                                                    <input type="text" name="province" class="form-control" value="<?php echo htmlspecialchars($c['province'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">รหัสไปรษณีย์</label>
                                                    <input type="text" name="post_id" class="form-control" value="<?php echo htmlspecialchars($c['post_id'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" name="update_customer" class="btn btn-save">บันทึกการแก้ไข</button>
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