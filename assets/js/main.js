document.addEventListener('DOMContentLoaded', function() {
    // Xử lý thêm vào giỏ hàng
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const quantity = document.getElementById('quantity') ? 
                           document.getElementById('quantity').value : 1;

            // Gửi request thêm vào giỏ hàng
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
                    alert('Đã thêm sản phẩm vào giỏ hàng!');
                    // Cập nhật số lượng trong giỏ hàng trên navbar
                    document.querySelector('.badge').textContent = data.cart_count;
                } else {
                    alert('Có lỗi xảy ra!');
                }
            });
        });
    });

    // Add fade-in effect to products
    const products = document.querySelectorAll('.card');
    products.forEach(product => {
        product.classList.add('fade-in');
    });

    // Smooth scroll to top
    const scrollTopBtn = document.createElement('button');
    scrollTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    scrollTopBtn.className = 'btn btn-primary scroll-top-btn';
    document.body.appendChild(scrollTopBtn);

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Show/hide scroll to top button
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollTopBtn.style.display = 'block';
        } else {
            scrollTopBtn.style.display = 'none';
        }
    });

    // Add loading spinner when adding to cart
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang thêm...';
            this.disabled = true;

            // Existing add to cart logic...
            // After success:
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        });
    });

    // Image zoom effect on product detail page
    const productImage = document.querySelector('.product-image');
    if (productImage) {
        productImage.addEventListener('mousemove', function(e) {
            const x = e.clientX - this.offsetLeft;
            const y = e.clientY - this.offsetTop;
            
            this.style.transformOrigin = `${x}px ${y}px`;
            this.style.transform = 'scale(1.5)';
        });

        productImage.addEventListener('mouseleave', function() {
            this.style.transformOrigin = 'center center';
            this.style.transform = 'scale(1)';
        });
    }
});

// Add CSS for scroll to top button
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

function changeMainImage(src) {
    document.querySelector('.main-image').src = src;
}

// Thêm style cho gallery
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

// Thêm style vào head
const styleSheet = document.createElement("style");
styleSheet.textContent = galleryStyle;
document.head.appendChild(styleSheet); 