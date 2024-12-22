document.addEventListener('DOMContentLoaded', function() {
    // Xử lý chức năng thêm vào giỏ hàng
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Lấy ID sản phẩm từ data attribute
            const productId = this.dataset.id;
            // Lấy số lượng từ input (nếu có) hoặc mặc định là 1
            const quantity = document.getElementById('quantity') ? 
                           document.getElementById('quantity').value : 1;

            // Gửi request AJAX đến add-to-cart.php
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công
                    alert('Đã thêm sản phẩm vào giỏ hàng!');
                    // Cập nhật số lượng trong badge giỏ hàng
                    document.querySelector('.badge').textContent = data.cart_count;
                } else {
                    alert('Có lỗi xảy ra!');
                }
            });
        });
    });

    // Thêm hiệu ứng fade-in cho sản phẩm
    const products = document.querySelectorAll('.card');
    products.forEach(product => {
        product.classList.add('fade-in');
    });

    // Tạo nút cuộn lên đầu trang
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollTopBtn.className = 'btn btn-primary scroll-top-btn';
    document.body.appendChild(scrollTopBtn);

    // Xử lý sự kiện click nút cuộn lên
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'  // Cuộn mượt
        });
    });

    // Hiển thị/ẩn nút cuộn lên khi scroll
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {  // Khi cuộn xuống 300px
            scrollTopBtn.style.display = 'block';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    });

    // Thêm hiệu ứng loading khi thêm vào giỏ
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Lưu text gốc của nút
            const originalText = this.innerHTML;
            // Thay thế bằng spinner
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang thêm...';
            this.disabled = true;

            // Sau 1 giây, khôi phục trạng thái nút
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        });
    });

    // Hiệu ứng zoom ảnh trong trang chi tiết sản phẩm
    const productImage = document.querySelector('.product-image');
    if (productImage) {
        // Xử lý sự kiện di chuột
        productImage.addEventListener('mousemove', function(e) {
            // Tính toán vị trí con trỏ chuột
            const x = e.clientX - this.offsetLeft;
            const y = e.clientY - this.offsetTop;
            
            // Áp dụng hiệu ứng zoom
            this.style.transformOrigin = `${x}px ${y}px`;
            this.style.transform = 'scale(1.5)';
        });

        // Khôi phục khi rời chuột
        productImage.addEventListener('mouseleave', function() {
            this.style.transformOrigin = 'center center';
            this.style.transform = 'scale(1)';
        });
    }
});

// Thêm CSS cho nút cuộn lên đầu trang
const style = document.createElement('style');
style.textContent = `
    .scroll-top-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        z-index: 99;
        padding: 10px 15px;
        border-radius: 50%;
        opacity: 0.8;
    }
    .scroll-top-btn:hover {
        opacity: 1;
    }
`;
document.head.appendChild(style); 

// Hàm đổi ảnh chính trong gallery
function changeMainImage(src) {
    document.querySelector('.main-image').src = src;
}

// CSS cho gallery ảnh
const galleryStyle = `
    .gallery-thumb {
        cursor: pointer;
        transition: opacity 0.3s ease;
    }
    .gallery-thumb:hover {
        opacity: 0.8;
    }
    .main-image {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
`;

// Thêm CSS gallery vào head
const styleSheet = document.createElement("style");
styleSheet.textContent = galleryStyle;
document.head.appendChild(styleSheet); 