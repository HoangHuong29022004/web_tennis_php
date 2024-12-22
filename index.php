<?php
require_once 'config/database.php';

// Lấy sản phẩm mới nhất
$stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$stmt->execute();
$latest_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-4">
    <div id="carouselMain" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/banners/banner_01.jpg" class="d-block w-100" alt="Tennis Banner 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Bộ sưu tập Wilson Pro Staff 2023</h5>
                    <p>Khám phá bộ sưu tập vợt Wilson Pro Staff mới nhất</p>
                    <a href="products.php?category=1" class="btn btn-light">Xem thêm</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/banners/banner_02.jpg" class="d-block w-100" alt="Tennis Banner 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Giày Tennis Nike Mới Nhất</h5>
                    <p>Trải nghiệm công nghệ mới nhất từ Nike</p>
                    <a href="products.php?category=2" class="btn btn-light">Xem thêm</a>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselMain" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselMain" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <h2 class="mb-4">Sản phẩm mới</h2>
    <div class="row">
        <?php foreach ($latest_products as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="assets/images/products/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <?php if ($product['sale_price']): ?>
                            <p class="card-text">
                                <span class="text-decoration-line-through"><?php echo number_format($product['price']); ?>đ</span>
                                <span class="text-danger"><?php echo number_format($product['sale_price']); ?>đ</span>
                            </p>
                        <?php else: ?>
                            <p class="card-text"><?php echo number_format($product['price']); ?>đ</p>
                        <?php endif; ?>
                        <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                        <button class="btn btn-success add-to-cart" data-id="<?php echo $product['id']; ?>">
                            Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>