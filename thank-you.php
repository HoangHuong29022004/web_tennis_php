<?php
session_start();
require_once 'config/database.php';

$order_id = $_GET['order_id'] ?? 0;

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("
    SELECT o.*, u.fullname, u.email, u.phone
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: index.php');
    exit();
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4">
    <div class="text-center">
        <h1 class="mb-4">Cảm ơn bạn đã đặt hàng!</h1>
        <p>Mã đơn hàng của bạn là: <strong>#<?php echo $order_id; ?></strong></p>
        <p>Chúng tôi sẽ liên hệ với bạn qua số điện thoại <?php echo $order['phone']; ?> để xác nhận đơn hàng.</p>
        <a href="index.php" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 