<?php
session_start();
require_once 'config/database.php';

// Nhận dữ liệu JSON
$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? 0;
$quantity = $data['quantity'] ?? 1;

// Kiểm tra sản phẩm tồn tại
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm hoặc cập nhật số lượng trong giỏ hàng
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

    // Tính tổng số sản phẩm trong giỏ hàng
    $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));

    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count
    ]);
} else {
    echo json_encode(['success' => false]);
} 