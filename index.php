<?php
// Kết nối đến cơ sở dữ liệu
require_once 'config/database.php';

// Truy vấn 8 sản phẩm mới nhất dựa vào thời gian tạo (created_at)
// ORDER BY created_at DESC: sắp xếp theo thời gian tạo mới nhất
// LIMIT 8: chỉ lấy 8 sản phẩm
$stmt = mysqli_prepare($conn, "SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$latest_products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Include các file header và navigation bar
include 'includes/header.php';  // Chứa các thẻ meta, CSS, JS và phần đầu HTML
include 'includes/navbar.php';  // Chứa menu điều hướng
?>

<!-- Container chính với margin-top 4 units -->
<div class="container mt-4">
    <!-- Carousel/Slideshow - Sử dụng Bootstrap Carousel -->
    <!-- data-bs-ride="carousel": tự động chạy slideshow -->
    <div id="carouselMain" class="carousel slide mb-4" data-bs-ride="carousel">
        <!-- Các nút chỉ báo slide (dots) ở dưới slideshow -->
        <div class="carousel-indicators">
            <!-- data-bs-slide-to: chỉ định slide sẽ chuyển đến khi click -->
            <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselMain" data-bs-slide-to="2"></button>
        </div>

        <!-- Phần nội dung của các slide -->
        <div class="carousel-inner">
            <!-- Slide đầu tiên - active để hiển thị đầu tiên -->
            <div class="carousel-item active">
                <!-- Banner 1 - Wilson Pro Staff -->
                <img src="assets/images/banners/banner_01.jpg" class="d-block w-100" alt="Tennis Banner 1">
                <!-- Caption cho banner - ẩn trên mobile (d-none) và hiện từ màn hình medium trở lên (d-md-block) -->
                <div class="carousel-caption d-none d-md-block">
                    <h5>Bộ sưu tập Wilson Pro Staff 2023</h5>
                    <p>Khám phá bộ sưu tập vợt Wilson Pro Staff mới nhất</p>
                    <!-- Link đến trang sản phẩm với category=1 (category của Wilson) -->
                    <a href="products.php?category=1" class="btn btn-light">Xem thêm</a>
                </div>
            </div>
            <!-- Slide thứ 2 - Nike -->
            <div class="carousel-item">
                <img src="assets/images/banners/banner_02.jpg" class="d-block w-100" alt="Tennis Banner 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Giày Tennis Nike Mới Nhất</h5>
                    <p>Trải nghiệm công nghệ mới nhất từ Nike</p>
                    <!-- Link đến trang sản phẩm với category=2 (category của Nike) -->
                    <a href="products.php?category=2" class="btn btn-light">Xem thêm</a>
                </div>
            </div>
        </div>

        <!-- Nút điều hướng Previous của carousel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselMain" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <!-- Nút điều hướng Next của carousel -->
        <button class="carousel-control-next" type="button" data-bs-target="#carouselMain" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Phần hiển thị danh sách sản phẩm mới -->
    <h2 class="mb-4">Sản phẩm mới</h2>
    <!-- Grid system của Bootstrap - row để tạo hàng -->
    <div class="row">
        <?php foreach ($latest_products as $product): ?>
            <!-- col-md-3: chiếm 1/4 chiều rộng trên màn hình medium trở lên -->
            <!-- mb-4: margin-bottom 4 units -->
            <div class="col-md-3 mb-4">
                <!-- Card Bootstrap để hiển thị thông tin sản phẩm -->
                <div class="card">
                    <!-- Ảnh sản phẩm -->
                    <img src="assets/images/products/<?php echo $product['image']; ?>" 
                         class="card-img-top" 
                         alt="<?php echo $product['name']; ?>">
                    
                    <!-- Phần thông tin sản phẩm -->
                    <div class="card-body">
                        <!-- Tên sản phẩm -->
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        
                        <!-- Hiển thị giá: kiểm tra nếu có giá khuyến mãi -->
                        <?php if ($product['sale_price']): ?>
                            <p class="card-text">
                                <!-- Giá gốc được gạch ngang -->
                                <span class="text-decoration-line-through">
                                    <?php echo number_format($product['price']); ?>đ
                                </span>
                                <!-- Giá khuyến mãi hiển thị màu đỏ -->
                                <span class="text-danger">
                                    <?php echo number_format($product['sale_price']); ?>đ
                                </span>
                            </p>
                        <?php else: ?>
                            <!-- Hiển thị giá gốc nếu không có khuyến mãi -->
                            <p class="card-text"><?php echo number_format($product['price']); ?>đ</p>
                        <?php endif; ?>

                        <!-- Các nút chức năng -->
                        <!-- Link đến trang chi tiết sản phẩm -->
                        <a href="product-detail.php?id=<?php echo $product['id']; ?>" 
                           class="btn btn-primary">Xem chi tiết</a>
                        <!-- Nút thêm vào giỏ hàng - được xử lý bởi JavaScript -->
                        <!-- data-id: lưu ID sản phẩm để JavaScript có thể lấy và xử lý -->
                        <button class="btn btn-success add-to-cart" 
                                data-id="<?php echo $product['id']; ?>">
                            Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Include footer -->
<?php include 'includes/footer.php'; ?>