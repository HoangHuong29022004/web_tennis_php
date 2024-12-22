<?php
require_once 'config/database.php';

// Lấy id sản phẩm từ URL parameter
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

// Lấy thông tin sản phẩm và tên danh mục thông qua JOIN
$stmt = $conn->prepare("SELECT p.*, c.name as category_name 
                       FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy sản phẩm, chuyển hướng về trang sản phẩm
if (!$product) {
    header('Location: products.php');
    exit();
}

// Lấy 4 sản phẩm liên quan cùng danh mục, ngoại trừ sản phẩm hiện tại
$stmt = $conn->prepare("SELECT * FROM products 
                       WHERE category_id = ? AND id != ? 
                       LIMIT 4");
$stmt->execute([$product['category_id'], $product_id]);
$related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4">
    <!-- Breadcrumb - Đường dẫn điều hướng -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="products.php">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $product['name']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Cột trái - Hình ảnh sản phẩm -->
        <div class="col-md-6">
            <div class="product-gallery card">
                <img src="assets/images/products/<?php echo $product['image']; ?>" 
                     class="img-fluid main-image product-image" 
                     alt="<?php echo $product['name']; ?>">
            </div>
        </div>

        <!-- Cột phải - Thông tin sản phẩm -->
        <div class="col-md-6">
            <div class="product-details card">
                <div class="card-body">
                    <!-- Tên sản phẩm -->
                    <h1 class="card-title"><?php echo $product['name']; ?></h1>
                    
                    <!-- Link danh mục -->
                    <p class="text-muted mb-3">
                        Danh mục: <a href="products.php?category=<?php echo $product['category_id']; ?>">
                            <?php echo $product['category_name']; ?>
                        </a>
                    </p>
                    
                    <!-- Phần giá và khuyến mãi -->
                    <div class="pricing mb-4">
                        <?php if ($product['sale_price']): ?>
                            <!-- Badge giảm giá -->
                            <div class="sale-badge mb-2">Giảm giá!</div>
                            <div class="price-wrapper">
                                <!-- Giá gốc gạch ngang -->
                                <span class="h4 text-decoration-line-through text-muted">
                                    <?php echo number_format($product['price']); ?>đ
                                </span>
                                <!-- Giá khuyến mãi -->
                                <span class="h3 text-danger">
                                    <?php echo number_format($product['sale_price']); ?>đ
                                </span>
                            </div>
                            <!-- Hiển thị số tiền tiết kiệm -->
                            <div class="saving mt-1">
                                Tiết kiệm: <?php echo number_format($product['price'] - $product['sale_price']); ?>đ
                            </div>
                        <?php else: ?>
                            <!-- Chỉ hiển thị giá gốc nếu không có khuyến mãi -->
                            <span class="h3"><?php echo number_format($product['price']); ?>đ</span>
                        <?php endif; ?>
                    </div>

                    <!-- Hiển thị tình trạng kho hàng -->
                    <div class="stock-info mb-4">
                        <span class="badge <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
                        </span>
                        <?php if ($product['stock'] > 0): ?>
                            <span class="text-muted ms-2">
                                (<?php echo $product['stock']; ?> sản phẩm có sẵn)
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Phần chọn số lượng -->
                    <div class="mb-4">
                        <label for="quantity" class="form-label">Số lượng:</label>
                        <div class="input-group" style="width: 150px;">
                            <!-- Nút giảm số lượng -->
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(-1)">-</button>
                            <!-- Input số lượng -->
                            <input type="number" class="form-control text-center" id="quantity" 
                                   value="1" min="1" max="<?php echo $product['stock']; ?>">
                            <!-- Nút tăng số lượng -->
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(1)">+</button>
                        </div>
                    </div>

                    <!-- Nút thêm vào giỏ hàng -->
                    <button class="btn btn-primary btn-lg add-to-cart w-100 mb-3" 
                            data-id="<?php echo $product['id']; ?>"
                            <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                    </button>

                    <!-- Mô tả sản phẩm -->
                    <div class="description mt-4">
                        <h4>Mô tả sản phẩm</h4>
                        <div class="card">
                            <div class="card-body">
                                <?php echo nl2br($product['description']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần sản phẩm liên quan -->
    <?php if ($related_products): ?>
    <div class="related-products mt-5">
        <h3 class="mb-4">Sản phẩm liên quan</h3>
        <div class="row">
            <?php foreach ($related_products as $related): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <!-- Ảnh sản phẩm liên quan -->
                    <img src="assets/images/products/<?php echo $related['image']; ?>" 
                         class="card-img-top" 
                         alt="<?php echo $related['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $related['name']; ?></h5>
                        <!-- Hiển thị giá sản phẩm liên quan -->
                        <?php if ($related['sale_price']): ?>
                            <p class="card-text">
                                <span class="text-decoration-line-through">
                                    <?php echo number_format($related['price']); ?>đ
                                </span>
                                <span class="text-danger">
                                    <?php echo number_format($related['sale_price']); ?>đ
                                </span>
                            </p>
                        <?php else: ?>
                            <p class="card-text"><?php echo number_format($related['price']); ?>đ</p>
                        <?php endif; ?>
                        <a href="product-detail.php?id=<?php echo $related['id']; ?>" 
                           class="btn btn-primary btn-sm">Chi tiết</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- JavaScript để xử lý tăng giảm số lượng -->
<script>
function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.max);
    const newValue = currentValue + change;
    
    if (newValue >= 1 && newValue <= maxValue) {
        input.value = newValue;
    }
}
</script>

<?php include 'includes/footer.php'; ?> 