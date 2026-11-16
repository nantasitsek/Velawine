<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'employee' || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'CEO')) {
    header("Location: login.php");
    exit();
}

// เพิ่มพนักงาน
if (isset($_POST['add_employee'])) {
    $ename = $_POST['ename'];
    $esurname = $_POST['esurname'];
    $eusername = $_POST['eusername'];
    $epassword = $_POST['epassword'];
    $role = $_POST['role'];

    $stmt = mysqli_prepare($con, "INSERT INTO employee (ename, esurname, eusername, epassword, role) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $ename, $esurname, $eusername, $epassword, $role);
    mysqli_stmt_execute($stmt);
    header("Location: manage_employee.php");
    exit();
}

// แก้ไขพนักงาน
if (isset($_POST['update_employee'])) {
    $eid = (int)$_POST['eid'];
    $ename = $_POST['ename'];
    $esurname = $_POST['esurname'];
    $eusername = $_POST['eusername'];
    $epassword = $_POST['epassword'];
    $role = $_POST['role'];

    $stmt = mysqli_prepare($con, "UPDATE employee SET ename=?, esurname=?, eusername=?, epassword=?, role=? WHERE eid=?");
    mysqli_stmt_bind_param($stmt, "sssssi", $ename, $esurname, $eusername, $epassword, $role, $eid);
    mysqli_stmt_execute($stmt);
    header("Location: manage_employee.php");
    exit();
}

// ลบพนักงาน
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $eid = (int)$_GET['id'];
    mysqli_query($con, "DELETE FROM employee WHERE eid = $eid");
    header("Location: manage_employee.php");
    exit();
}

$employees = mysqli_query($con, "SELECT * FROM employee");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการข้อมูลผู้ดูแล - Velawine</title>
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
        <h2 class="mb-4 fw-bold" style="color: #58111A;"><i class="fa-solid fa-user-tie me-2"></i> จัดการข้อมูลผู้ดูแล</h2>

        <!-- ฟอร์มเพิ่มผู้ดูแล -->
        <div class="card shadow-sm p-4 mb-5 border-0 rounded-4 bg-white">
            <h4 class="mb-3 text-secondary"><i class="fa-solid fa-user-plus me-2" style="color: #8B1338;"></i> เพิ่มผู้ดูแลใหม่</h4>
            <form action="manage_employee.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ชื่อ (ename)</label>
                        <input type="text" name="ename" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">นามสกุล (esurname)</label>
                        <input type="text" name="esurname" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Username (eusername)</label>
                        <input type="text" name="eusername" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Password (epassword)</label>
                        <input type="text" name="epassword" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ระดับสิทธิ์ (role)</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">admin</option>
                            <option value="staff">staff</option>
                            <option value="CEO">CEO</option>
                        </select>
                    </div>
                    <div class="col-12 text-end mt-4">
                        <button type="submit" name="add_employee" class="btn text-white px-5 py-2 fw-semibold" style="background-color: #58111A;">
                            <i class="fa-solid fa-plus me-1"></i> เพิ่มผู้ดูแล
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ตารางแสดงรายชื่อผู้ดูแล -->
        <div class="card shadow-sm p-4 border-0 rounded-4 bg-white">
            <h4 class="mb-3 text-secondary">รายชื่อผู้ดูแลทั้งหมด</h4>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead style="background-color: #58111A; color: white;">
                        <tr>
                            <th class="py-3 ps-3">EID</th>
                            <th class="py-3">ชื่อ - นามสกุล</th>
                            <th class="py-3">Username</th>
                            <th class="py-3">Role</th>
                            <th class="text-center py-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($emp = mysqli_fetch_assoc($employees)): ?>
                        <tr>
                            <td class="ps-3"><?php echo $emp['eid']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($emp['ename'] . ' ' . $emp['esurname']); ?></td>
                            <td><?php echo htmlspecialchars($emp['eusername']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($emp['role']); ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-yellow" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $emp['eid']; ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                </button>
                                <a href="manage_employee.php?action=delete&id=<?php echo $emp['eid']; ?>" class="btn btn-sm btn-red" onclick="return confirm('ยืนยันลบผู้ดูแลคนนี้?');">
                                    <i class="fa-solid fa-trash"></i> ลบ
                                </a>
                            </td>
                        </tr>

                        <!-- แก้ไขผู้ดูแล -->
                        <div class="modal fade" id="editModal<?php echo $emp['eid']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="manage_employee.php" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">แก้ไขผู้ดูแล: <?php echo htmlspecialchars($emp['ename']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <input type="hidden" name="eid" value="<?php echo $emp['eid']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">ชื่อ (ename)</label>
                                                <input type="text" name="ename" class="form-control" value="<?php echo htmlspecialchars($emp['ename']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">นามสกุล (esurname)</label>
                                                <input type="text" name="esurname" class="form-control" value="<?php echo htmlspecialchars($emp['esurname']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Username (eusername)</label>
                                                <input type="text" name="eusername" class="form-control" value="<?php echo htmlspecialchars($emp['eusername']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Password (epassword)</label>
                                                <input type="text" name="epassword" class="form-control" value="<?php echo htmlspecialchars($emp['epassword']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select name="role" class="form-select" required>
                                                    <option value="admin" <?php if($emp['role']=='admin') echo 'selected'; ?>>admin</option>
                                                    <option value="staff" <?php if($emp['role']=='staff') echo 'selected'; ?>>staff</option>
                                                    <option value="CEO" <?php if($emp['role']=='CEO') echo 'selected'; ?>>CEO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" name="update_employee" class="btn btn-save">บันทึกการแก้ไข</button>
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