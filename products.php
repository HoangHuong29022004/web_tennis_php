<?php
require_once 'config/database.php';

// Xử lý tìm kiếm và lọc sản phẩm
$search = isset($_GET['search']) ? $_GET['search'] : '';        // Từ khóa tìm kiếm
$category_id = isset($_GET['category']) ? $_GET['category'] : ''; // ID danh mục cần lọc

// Lấy danh sách danh mục để hiển thị sidebar
$stmt = mysqli_prepare($conn, "SELECT * FROM categories");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Query lấy sản phẩm với JOIN để lấy thêm tên danh mục
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE 1=1";

if ($search) {
    $query .= " AND p.name LIKE ?";
    $search_param = "%$search%";
}

if ($category_id) {
    $query .= " AND p.category_id = ?";
}

$stmt = mysqli_prepare($conn, $query);

if ($search && $category_id) {
    mysqli_stmt_bind_param($stmt, "si", $search_param, $category_id);
} elseif ($search) {
    mysqli_stmt_bind_param($stmt, "s", $search_param);
} elseif ($category_id) {
    mysqli_stmt_bind_param($stmt, "i", $category_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- Container chính -->
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar lọc danh mục bên trái -->
        <div class="col-md-3">
            <h4>Danh mục</h4>
            <!-- Danh sách danh mục dạng list group -->
            <div class="list-group">
                <!-- Link "Tất cả sản phẩm" - active nếu không có category_id -->
                <a href="products.php" 
                   class="list-group-item list-group-item-action <?php echo !$category_id ? 'active' : ''; ?>">
                    Tất cả sản phẩm
                </a>
                <!-- Hiển thị các danh mục -->
                <?php foreach ($categories as $category): ?>
                    <a href="products.php?category=<?php echo $category['id']; ?>"
                       class="list-group-item list-group-item-action 
                              <?php echo $category_id == $category['id'] ? 'active' : ''; ?>">
                        <?php echo $category['name']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Phần hiển thị sản phẩm bên phải -->
        <div class="col-md-9">
            <h2 class="mb-4">Sản phẩm</h2>
            <!-- Grid hiển thị sản phẩm -->
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <!-- Mỗi sản phẩm chiếm 1/3 chiều rộng (col-md-4) -->
                    <div class="col-md-4 mb-4">
                        <!-- Card sản phẩm -->
                        <div class="card h-100">
                            <!-- Ảnh sản phẩm -->
                            <img src="assets/images/products/<?php echo $product['image']; ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo $product['name']; ?>">
                            
                            <div class="card-body">
                                <!-- Tên sản phẩm -->
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <!-- Tên danh mục -->
                                <p class="card-text small text-muted">
                                    <?php echo $product['category_name']; ?>
                                </p>
                                
                                <!-- Hiển thị giá -->
                                <?php if ($product['sale_price']): ?>
                                    <p class="card-text">
                                        <!-- Giá gốc gạch ngang -->
                                        <span class="text-decoration-line-through">
                                            <?php echo number_format($product['price']); ?>đ
                                        </span>
                                        <!-- Giá khuyến mãi -->
                                        <span class="text-danger">
                                            <?php echo number_format($product['sale_price']); ?>đ
                                        </span>
                                    </p>
                                <?php else: ?>
                                    <!-- Chỉ hiển thị giá gốc -->
                                    <p class="card-text">
                                        <?php echo number_format($product['price']); ?>đ
                                    </p>
                                <?php endif; ?>

                                <!-- Các nút chức năng -->
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" 
                                   class="btn btn-primary btn-sm">Chi tiết</a>
                                <!-- Nút thêm vào giỏ với data-id để JavaScript xử lý -->
                                <button class="btn btn-success btn-sm add-to-cart" 
                                        data-id="<?php echo $product['id']; ?>">
                                    Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>