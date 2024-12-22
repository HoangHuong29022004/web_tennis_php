<?php
require_once 'config/database.php';

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_id = isset($_GET['category']) ? $_GET['category'] : '';

// Lấy danh sách danh mục
$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Câu query cơ bản
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE 1=1";
$params = [];

// Thêm điều kiện tìm kiếm
if ($search) {
    $query .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}
if ($category_id) {
    $query .= " AND p.category_id = ?";
    $params[] = $category_id;
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar lọc -->
        <div class="col-md-3">
            <h4>Danh mục</h4>
            <div class="list-group">
                <a href="products.php" class="list-group-item list-group-item-action <?php echo !$category_id ? 'active' : ''; ?>">
                    Tất cả sản phẩm
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="products.php?category=<?php echo $category['id']; ?>"
                        class="list-group-item list-group-item-action <?php echo $category_id == $category['id'] ? 'active' : ''; ?>">
                        <?php echo $category['name']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-md-9">
            <h2 class="mb-4">Sản phẩm</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="assets/images/products/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text small text-muted"><?php echo $product['category_name']; ?></p>
                                <?php if ($product['sale_price']): ?>
                                    <p class="card-text">
                                        <span class="text-decoration-line-through"><?php echo number_format($product['price']); ?>đ</span>
                                        <span class="text-danger"><?php echo number_format($product['sale_price']); ?>đ</span>
                                    </p>
                                <?php else: ?>
                                    <p class="card-text"><?php echo number_format($product['price']); ?>đ</p>
                                <?php endif; ?>
                                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Chi tiết</a>
                                <button class="btn btn-success btn-sm add-to-cart" data-id="<?php echo $product['id']; ?>">
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