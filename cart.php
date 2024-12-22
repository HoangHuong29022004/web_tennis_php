<?php
session_start();
require_once 'config/database.php';

// Xử lý cập nhật số lượng sản phẩm trong giỏ
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- Container chính -->
<div class="container mt-4">
    <h1>Giỏ hàng</h1>

    <!-- Hiển thị thông báo nếu giỏ hàng trống -->
    <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">
        Giỏ hàng trống. <a href="products.php">Tiếp tục mua sắm</a>
    </div>
    <?php else: ?>

    <!-- Form cập nhật giỏ hàng -->
    <form method="post">
        <!-- Bảng hiển thị sản phẩm trong giỏ -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th width="150">Số lượng</th>
                        <th>Tổng</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $product_id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <!-- Thông tin sản phẩm -->
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- Ảnh sản phẩm -->
                                <img src="assets/images/products/<?php echo $item['image']; ?>" 
                                     alt="<?php echo $item['name']; ?>"
                                     class="img-thumbnail me-2" style="width: 100px">
                                <!-- Tên sản phẩm -->
                                <span><?php echo $item['name']; ?></span>
                            </div>
                        </td>
                        <!-- Giá sản phẩm -->
                        <td><?php echo number_format($item['price']); ?>đ</td>
                        <!-- Input số lượng -->
                        <td>
                            <input type="number" 
                                   name="quantity[<?php echo $product_id; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   class="form-control" 
                                   min="0">
                        </td>
                        <!-- Tổng tiền của sản phẩm -->
                        <td><?php echo number_format($subtotal); ?>đ</td>
                        <!-- Nút xóa -->
                        <td>
                            <a href="cart.php?remove=<?php echo $product_id; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <!-- Footer bảng hiển thị tổng tiền -->
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">
                            <strong>Tổng cộng:</strong>
                        </td>
                        <td>
                            <strong><?php echo number_format($total); ?>đ</strong>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Các nút chức năng -->
        <div class="d-flex justify-content-between">
            <!-- Nút tiếp tục mua sắm -->
            <a href="products.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
            </a>
            <div>
                <!-- Nút cập nhật giỏ hàng -->
                <button type="submit" name="update_cart" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Cập nhật giỏ hàng
                </button>
                <!-- Nút thanh toán -->
                <a href="checkout.php" class="btn btn-success">
                    Thanh toán <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?> 