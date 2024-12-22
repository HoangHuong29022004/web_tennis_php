<?php
session_start();
require_once 'config/database.php';

// Kiểm tra giỏ hàng có trống không, nếu trống thì chuyển về trang giỏ hàng
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Xử lý đặt hàng khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        $conn->beginTransaction();

        // 1. Thêm thông tin người dùng vào bảng users
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['fullname'],  // Họ tên từ form
            $_POST['email'],     // Email từ form
            $_POST['phone'],     // Số điện thoại từ form
            $_POST['address']    // Địa chỉ từ form
        ]);
        $user_id = $conn->lastInsertId(); // Lấy ID của user vừa thêm

        // 2. Tính tổng tiền đơn hàng
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        // 3. Thêm đơn hàng vào bảng orders
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, phone) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $user_id,
            $total_amount,
            $_POST['address'],
            $_POST['phone']
        ]);
        $order_id = $conn->lastInsertId(); // Lấy ID của đơn hàng vừa thêm

        // 4. Thêm chi tiết từng sản phẩm vào bảng order_details
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) 
                              VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt->execute([
                $order_id,
                $product_id,
                $item['quantity'],
                $item['price']
            ]);
        }

        // Commit transaction nếu mọi thứ OK
        $conn->commit();
        
        // Xóa giỏ hàng sau khi đặt hàng thành công
        unset($_SESSION['cart']);
        
        // Chuyển đến trang cảm ơn với order_id
        header('Location: thank-you.php?order_id=' . $order_id);
        exit();

    } catch (Exception $e) {
        // Rollback nếu có lỗi xảy ra
        $conn->rollBack();
        $error = "Có lỗi xảy ra, vui lòng thử lại!";
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4">
    <h1>Thanh toán</h1>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Form nhập thông tin đặt hàng -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin đặt hàng</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <!-- Input họ tên -->
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" id="fullname" 
                                   name="fullname" required>
                        </div>
                        <!-- Input email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   name="email" required>
                        </div>
                        <!-- Input số điện thoại -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" 
                                   name="phone" required>
                        </div>
                        <!-- Input địa chỉ -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng</label>
                            <textarea class="form-control" id="address" name="address" 
                                      rows="3" required></textarea>
                        </div>
                        <!-- Nút đặt hàng -->
                        <button type="submit" class="btn btn-primary">Đặt hàng</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hiển thị thông tin đơn hàng -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <!-- Hiển thị từng sản phẩm trong đơn hàng -->
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <?php echo $item['name']; ?> x <?php echo $item['quantity']; ?>
                        </div>
                        <div><?php echo number_format($subtotal); ?>đ</div>
                    </div>
                    <?php endforeach; ?>
                    <!-- Hiển thị tổng tiền -->
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong><?php echo number_format($total); ?>đ</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 