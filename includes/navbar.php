<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-shop me-2"></i>Tennis Shop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-house-door me-1"></i>Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">
                        <i class="bi bi-grid me-1"></i>Sản phẩm
                    </a>
                </li>
            </ul>
            <form class="d-flex me-3" action="products.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="search" placeholder="Tìm kiếm..." 
                           aria-label="Search">
                    <button class="btn btn-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            <a href="cart.php" class="btn btn-outline-light position-relative">
                <i class="bi bi-cart3"></i> Giỏ hàng
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>
                </span>
            </a>
        </div>
    </div>
</nav> 