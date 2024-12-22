<?php
session_start();
require_once 'config/database.php';

// Xử lý cập nhật số lượng
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: cart.php');
    exit();
}

// Xử lý xóa sản phẩm
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php');
    exit();
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4">
    <h1>Giỏ hàng</h1>

    <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">
        Giỏ hàng trống. <a href="products.php">Tiếp tục mua sắm</a>
    </div>
    <?php else: ?>

    <form method="post">
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
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="assets/images/products/<?php echo $item['image']; ?>" 
                                     alt="<?php echo $item['name']; ?>"
                                     class="img-thumbnail me-2" style="width: 100px">
                                <span><?php echo $item['name']; ?></span>
                            </div>
                        </td>
                        <td><?php echo number_format($item['price']); ?>đ</td>
                        <td>
                            <input type="number" name="quantity[<?php echo $product_id; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   class="form-control" min="0">
                        </td>
                        <td><?php echo number_format($subtotal); ?>đ</td>
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
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                        <td><strong><?php echo number_format($total); ?>đ</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between">
            <a href="products.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
            </a>
            <div>
                <button type="submit" name="update_cart" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Cập nhật giỏ hàng
                </button>
                <a href="checkout.php" class="btn btn-success">
                    Thanh toán <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?> 