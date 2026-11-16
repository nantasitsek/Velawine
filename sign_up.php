<?php
require 'dbconnect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $cname = trim($_POST['cname']);
    $csurname = trim($_POST['csurname']);
    $tel = trim($_POST['tel']);
    $address = trim($_POST['address']);
    $soi = trim($_POST['soi']);
    $street = trim($_POST['street']);
    $sub_district = trim($_POST['sub_district']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $post_id = trim($_POST['post_id']);

    if ($password !== $confirm_password) {
        $error = "รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน";
    }
    else {

        // ตรวจสอบ username ซ้ำ
        $check = mysqli_prepare($con,
            "SELECT cid FROM customer WHERE cusername = ?"
        );

        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Username นี้ถูกใช้งานแล้ว";
        }
        else {

            // เข้ารหัสผ่านก่อนบันทึก
            $hashed_password = $password;

            // เพิ่มข้อมูลลูกค้า
            $sql = "INSERT INTO customer
            (cusername, cpassword, cname, csurname, tel,
             address, soi, street, sub_district, district,province, post_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?)";

            $stmt = mysqli_prepare($con, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssssssssss",
                $username,
                $hashed_password,
                $cname,
                $csurname,
                $tel,
                $address,
                $soi,
                $street,
                $sub_district,
                $district,
                $province,
                $post_id
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "สมัครสมาชิกเรียบร้อยแล้ว";
            } else {
                $error = "ไม่สามารถสมัครสมาชิกได้ กรุณาลองใหม่";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velawine - Register</title>
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
               <a href="index.php" class="btn btn-outline-secondary" style="padding: 9px 20px;">
                    <i class="fa-solid fa-house me-1"></i> หน้าหลัก
                </a>
            </div>
        </div>
    </header>

    <!-- Register Form -->
    <div class="container pb-5">

        <div class="register-card mx-auto">

            <h2 class="login-title">Create Account</h2>
            <p class="login-subtitle">สมัครสมาชิก Velawine</p>

            <?php if ($error != "") { ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <?php if ($success != "") { ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                    <a href="login.php">เข้าสู่ระบบ</a>
                </div>
            <?php } ?>

            <?php if ($success == "") { ?>

            <form method="POST" action="sign_up.php" class="was-validated">

                <!-- Account Information -->
                <h5 class="section-title mb-3">ข้อมูลบัญชีผู้ใช้</h5>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username"
                        class="form-control" placeholder="Username หรือรหัสลูกค้า" required>
                    <div class="valid-feedback">Username ถูกต้อง</div>
                    <div class="invalid-feedback">ยังไม่ได้กรอกUsername หรือ Username ไม่ถูกต้อง</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control" minlength="8" placeholder="โปรดกรอกรหัสผ่านอย่างน้อย 8 ตัวอักษร" required>
                        <div class="valid-feedback">รหัสผ่านถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกรหัสผ่านหรือรหัสผ่านไม่ถูกต้อง</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password"
                            class="form-control" minlength="8" placeholder="ยืนยันรหัสผ่าน" required>
                        <div class="valid-feedback">รหัสผ่านถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกรหัสผ่านหรือรหัสผ่านไม่ถูกต้อง</div>
                    </div>
                </div>

                <hr>

                <!-- Personal Information -->
                <h5 class="section-title mb-3">ข้อมูลส่วนตัว</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ชื่อ</label>
                        <input type="text" name="cname"
                            class="form-control" placeholder="ชื่อจริง" required>
                        <div class="valid-feedback">ชื่อผู้ใช้ถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกชื่อผู้ใช้หรือชื่อผู้ใช้ไม่ถูกต้อง</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">นามสกุล</label>
                        <input type="text" name="csurname"
                            class="form-control" placeholder="นามสกุล" required>
                        <div class="valid-feedback">นามสกุลถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกนามสกุลหรือนามสกุลไม่ถูกต้อง</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">เบอร์โทรศัพท์</label>
                    <input type="tel" name="tel"
                        class="form-control" minlength="8" maxlength="10" placeholder="เบอร์โทรศัพท์" required>
                    <div class="valid-feedback">เบอร์โทรศัพท์ถูกต้อง</div>
                    <div class="invalid-feedback">ยังไม่ได้กรอกเบอร์โทรศัพท์หรือเบอร์โทรศัพท์ไม่ถูกต้อง</div>
                </div>

                <hr>

                <!-- Address Information -->
                <h5 class="section-title mb-3">ข้อมูลที่อยู่</h5>

                <div class="mb-3">
                    <label class="form-label">บ้านเลขที่</label>
                    <input type="text" name="address"
                        class="form-control" placeholder="บ้านเลขที่ อาคาร และรายละเอียดอื่น ๆ " required>
                    <div class="valid-feedback">บ้านเลขที่ถูกต้อง</div>
                    <div class="invalid-feedback">ยังไม่ได้กรอกบ้านเลขที่หรือบ้านเลขที่ไม่ถูกต้อง</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ซอย</label>
                        <input type="text" name="soi"
                            class="form-control" placeholder="ซอย หากไม่มีกรุณาเว้นว่าง">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">ถนน</label>
                        <input type="text" name="street"
                            class="form-control" placeholder="ถนน หากไม่มีกรุณาเว้นว่าง">
                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">เขต/อำเภอ</label>
                        <input type="text" name="district"
                            class="form-control" placeholder="เขตหรืออำเภอ" required>
                        <div class="valid-feedback">เขต/อำเภอถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกเขต/อำเภอหรือเขต/อำเภอไม่ถูกต้อง</div>
                    </div>
                    
                      <div class="col-md-6 mb-3">
                        <label class="form-label">แขวง/ตำบล</label>
                        <input type="text" name="sub_district"
                            class="form-control" placeholder="แขวงหรือตำบล" required>
                        <div class="valid-feedback">แขวง/ตำบลถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกแขวง/ตำบลหรือแขวง/ตำบลไม่ถูกต้อง</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">จังหวัด</label>
                        <input type="text" name="province"
                            class="form-control" placeholder="จังหวัด" required>
                        <div class="valid-feedback">จังหวัดถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกจังหวัดหรือจังหวัดไม่ถูกต้อง</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">รหัสไปรษณีย์</label>
                        <input type="text" name="post_id"
                            class="form-control" minlength="5" maxlength="5" placeholder="รหัสไปรษณีย์" required>
                        <div class="valid-feedback">รหัสไปรษณีย์ถูกต้อง</div>
                        <div class="invalid-feedback">ยังไม่ได้กรอกรหัสไปรษณีย์หรือรหัสไปรษณีย์ไม่ถูกต้อง</div>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    สมัครสมาชิก
                </button>

                <p class="text-center mt-4">
                    มีบัญชีอยู่แล้ว?
                    <a href="login.php" style="color:#8B1338;">
                        เข้าสู่ระบบ
                    </a>
                </p>

            </form>

            <?php } ?>

        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-auto">
        <div class="container">
            <p class="mb-0">© Velawine. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
