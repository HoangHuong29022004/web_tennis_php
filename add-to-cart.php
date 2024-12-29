<?php
session_start();
require_once 'config/database.php';

// Nhận dữ liệu JSON từ request
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? 0;
$quantity = $data['quantity'] ?? 1;

// Kiểm tra sản phẩm tồn tại
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if ($product) {
    // Thêm vào session giỏ hàng
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Cập nhật số lượng nếu đã có trong giỏ
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['sale_price'] ?? $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }
}

// Tính tổng số sản phẩm trong giỏ hàng
$cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));

echo json_encode([
    'success' => true,
    'cart_count' => $cart_count
]); 