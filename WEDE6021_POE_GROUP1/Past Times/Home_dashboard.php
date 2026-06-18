
<?php

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PasTimes - Second-Hand Fashion Marketplace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f9fafb;
        }

        /* Header */
        header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            height: 60px;
            cursor: pointer;
        }

        .search-bar {
            flex: 1;
            max-width: 600px;
            margin: 0 2rem;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 1rem;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .header-actions {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .icon-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            color: #4b5563;
            padding: 0.5rem;
        }

        .icon-btn:hover {
            color: #2563eb;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* Categories */
        .categories {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            overflow-x: auto;
        }

        .categories-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0.5rem 1.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .category-btn {
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            color: #4b5563;
            transition: all 0.3s;
        }

        .category-btn:hover {
            color: #2563eb;
            background: #f3f4f6;
        }

        .category-btn.active {
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
        }

        /* Main Content */
        .container {
            max-width: 1280px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Filters */
        .filters {
            background: white;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }

        .filters h3 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4b5563;
            font-weight: 500;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .filter-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-secondary {
            background: white;
            color: #2563eb;
            border: 1px solid #2563eb;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-secondary:hover {
            background: #eff6ff;
        }

        /* Products Grid */
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .product-image {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .favorite-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,0.9);
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 4px;
        }

        .favorite-btn.active {
            color: #ef4444;
        }

        .condition-badge {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            background: rgba(255,255,255,0.9);
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 4px;
        }

        .product-info {
            padding: 1rem;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .star {
            color: #fbbf24;
            font-size: 0.875rem;
        }

        .star.empty {
            color: #e5e7eb;
        }

        .product-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #1f2937;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        .product-size {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 20px rgba(0,0,0,0.1);
            transition: right 0.3s;
            z-index: 2000;
            display: flex;
            flex-direction: column;
        }

        .cart-sidebar.open {
            right: 0;
        }

        .cart-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .cart-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-title {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .cart-item-price {
            font-weight: 600;
            color: #1f2937;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .quantity-btn {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 4px;
        }

        .cart-footer {
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1999;
            display: none;
        }

        .overlay.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-bar {
                display: none;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }

            .cart-sidebar {
                width: 100%;
                right: -100%;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Utilities */
        .hidden {
            display: none;
        }

        .text-center {
            text-align: center;
        }

        .mt-2 {
            margin-top: 2rem;
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #6b7280;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            color: #d1d5db;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <img src="logo.jpeg" alt="PasTimes Logo" class="logo" onclick="location.reload()">

            <div class="search-bar">
                <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="searchInput" placeholder="Search for items..." onkeyup="filterProducts()">
            </div>

            <div class="header-actions">
                
                
                <button class="icon-btn" onclick="alert('Favorites feature - Add items to view later!')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
                <button class="icon-btn" onclick="alert('Messages - Chat with sellers!')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </button>
                <button class="icon-btn" id="cartButton" onclick="toggleCart()">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="badge" id="cartCount">0</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Categories -->
    <nav class="categories">
        <div class="categories-container">
            <button class="category-btn active" onclick="filterByCategory('All')">All</button>
            <button class="category-btn" onclick="filterByCategory('Women')">Women</button>
            <button class="category-btn" onclick="filterByCategory('Men')">Men</button>
            <button class="category-btn" onclick="filterByCategory('Kids')">Kids</button>
            <button class="category-btn" onclick="filterByCategory('Sports')">Sports</button>
            <button class="category-btn" onclick="filterByCategory('Accessories')">Accessories</button>
            <button class="category-btn" onclick="filterByCategory('Others')">Others</button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Filters -->
        <div class="filters" id="filtersPanel">
            <h3>Advanced Filters</h3>
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Condition</label>
                    <select id="conditionFilter" onchange="filterProducts()">
                        <option value="All">All</option>
                        <option value="Excellent">Excellent</option>
                        <option value="Like New">Like New</option>
                        <option value="Very Good">Very Good</option>
                        <option value="Good">Good</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Size</label>
                    <select id="sizeFilter" onchange="filterProducts()">
                        <option value="All">All</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Min Price (R)</label>
                    <input type="number" id="minPrice" value="0" onchange="filterProducts()">
                </div>
                <div class="filter-group">
                    <label>Max Price (R)</label>
                    <input type="number" id="maxPrice" value="5000" onchange="filterProducts()">
                </div>
            </div>
            <div class="filter-actions">
                <button class="btn-secondary" onclick="clearFilters()">Clear All Filters</button>
                <select id="sortBy" onchange="sortProducts()">
                    <option value="featured">Featured</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="newest">Newest</option>
                </select>
            </div>
        </div>

        <!-- Products -->
        <div class="products-header">
            <h2 id="productsCount">12 items available</h2>
            <button class="btn-secondary" onclick="toggleFilters()">Toggle Filters</button>
        </div>

        <div class="products-grid" id="productsGrid">
            <!-- Products will be inserted here by JavaScript -->
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h2>Shopping Cart</h2>
            <button class="icon-btn" onclick="toggleCart()">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p>Your cart is empty</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotal">R0.00</span>
            </div>
            <button class="btn-primary" style="width: 100%;" onclick="checkout()">Proceed to Checkout</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="toggleCart()"></div>

    <script>
        // Products Data
        const products = [
            { id: 1, title: "Vintage Leather Jacket", price: 1799.99, image: "https://images.unsplash.com/photo-1760533091973-1262bf57d244?w=400", category: "Men", condition: "Excellent", size: "M" },
            { id: 2, title: "Red & White Classic Sneakers", price: 1299.00, image: "https://images.unsplash.com/photo-1656944227480-98180d2a5155?w=400", category: "Sports", condition: "Like New", size: "9" },
            { id: 3, title: "Black & Blue Athletic Shoes", price: 1099.00, image: "https://images.unsplash.com/photo-1678802910315-b1bf6ca9f6a6?w=400", category: "Sports", condition: "Good", size: "10" },
            { id: 4, title: "White Canvas Sneakers", price: 899.00, image: "https://images.unsplash.com/photo-1689357642277-65228ee23680?w=400", category: "Women", condition: "Excellent", size: "8" },
            { id: 5, title: "Green Retro Sneakers", price: 1399.00, image: "https://images.unsplash.com/photo-1662037132011-1c403fd1705c?w=400", category: "Men", condition: "Very Good", size: "11" },
            { id: 6, title: "Black & White High-Top Sneakers", price: 1199.00, image: "https://images.unsplash.com/photo-1663179081594-a574d6e944d7?w=400", category: "Sports", condition: "Good", size: "9" },
            { id: 7, title: "Classic Black Converse", price: 799.00, image: "https://images.unsplash.com/photo-1605973828881-d32d26aaf2ae?w=400", category: "Sports", condition: "Good", size: "10" },
            { id: 8, title: "Red Statement Sneakers", price: 1499.00, image: "https://images.unsplash.com/photo-1650320079970-b4ee8f0dae33?w=400", category: "Women", condition: "Like New", size: "7" },
            { id: 9, title: "Elegant Women's Outfit", price: 1899.00, image: "https://images.unsplash.com/photo-1717852359279-ff4d14b62097?w=400", category: "Women", condition: "Excellent", size: "S" },
            { id: 10, title: "Kids Casual Wear", price: 699.00, image: "https://images.unsplash.com/photo-1717852360340-fd6bf12a7b1c?w=400", category: "Kids", condition: "Good", size: "10" },
            { id: 11, title: "Sports Athletic Gear", price: 1099.00, image: "https://images.unsplash.com/photo-1634579335989-769a493be0ef?w=400", category: "Sports", condition: "Very Good", size: "L" },
            { id: 12, title: "Vintage Accessories", price: 499.00, image: "https://images.unsplash.com/photo-1689357642277-65228ee23680?w=400", category: "Accessories", condition: "Excellent", size: "M" }
        ];

        let cart = [];
        let currentCategory = 'All';
        let filteredProducts = [...products];

        // Initialize
        renderProducts(products);

        function renderProducts(productsToRender) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';

            productsToRender.forEach(product => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                    <div class="product-image">
                        <img src="${product.image}" alt="${product.title}">
                        <button class="favorite-btn" onclick="toggleFavorite(event, ${product.id})">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                        <div class="condition-badge">${product.condition}</div>
                    </div>
                    <div class="product-info">
                        <div class="product-rating">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star empty">★</span>
                            <span style="font-size: 0.75rem; color: #6b7280; margin-left: 0.5rem;">(24)</span>
                        </div>
                        <h3 class="product-title">${product.title}</h3>
                        <div class="product-footer">
                            <span class="product-price">R${product.price.toFixed(2)}</span>
                            <span class="product-size">Size ${product.size}</span>
                        </div>
                        <button class="btn-primary" style="width: 100%; margin-top: 1rem;" onclick="addToCart(${product.id})">
                            Add to Cart
                        </button>
                    </div>
                `;
                grid.appendChild(card);
            });

            document.getElementById('productsCount').textContent = `${productsToRender.length} items available`;
        }

        function filterByCategory(category) {
            currentCategory = category;
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            filterProducts();
        }

        function filterProducts() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const condition = document.getElementById('conditionFilter').value;
            const size = document.getElementById('sizeFilter').value;
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || 10000;

            filteredProducts = products.filter(product => {
                const matchCategory = currentCategory === 'All' || product.category === currentCategory;
                const matchSearch = product.title.toLowerCase().includes(searchQuery);
                const matchCondition = condition === 'All' || product.condition === condition;
                const matchSize = size === 'All' || product.size === size;
                const matchPrice = product.price >= minPrice && product.price <= maxPrice;

                return matchCategory && matchSearch && matchCondition && matchSize && matchPrice;
            });

            sortProducts();
        }

        function sortProducts() {
            const sortBy = document.getElementById('sortBy').value;

            switch(sortBy) {
                case 'price-low':
                    filteredProducts.sort((a, b) => a.price - b.price);
                    break;
                case 'price-high':
                    filteredProducts.sort((a, b) => b.price - a.price);
                    break;
                case 'newest':
                    filteredProducts.sort((a, b) => b.id - a.id);
                    break;
            }

            renderProducts(filteredProducts);
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('conditionFilter').value = 'All';
            document.getElementById('sizeFilter').value = 'All';
            document.getElementById('minPrice').value = '0';
            document.getElementById('maxPrice').value = '5000';
            currentCategory = 'All';
            filterProducts();
        }

        function toggleFilters() {
            const filters = document.getElementById('filtersPanel');
            filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
        }

        function toggleFavorite(event, productId) {
            event.stopPropagation();
            event.target.closest('.favorite-btn').classList.toggle('active');
            alert('Product added to favorites!');
        }

        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ ...product, quantity: 1 });
            }

            updateCart();
            alert(`${product.title} added to cart!`);
        }

        function updateCart() {
            const cartItemsDiv = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');
            const cartTotal = document.getElementById('cartTotal');

            if (cart.length === 0) {
                cartItemsDiv.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <p>Your cart is empty</p>
                    </div>
                `;
                cartCount.textContent = '0';
                cartTotal.textContent = 'R0.00';
                return;
            }

            let html = '';
            let total = 0;
            let itemCount = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                itemCount += item.quantity;

                html += `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.title}">
                        <div class="cart-item-info">
                            <div class="cart-item-title">${item.title}</div>
                            <div class="cart-item-price">R${item.price.toFixed(2)}</div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <span>${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                                <button class="btn-secondary" style="margin-left: auto;" onclick="removeFromCart(${item.id})">Remove</button>
                            </div>
                        </div>
                    </div>
                `;
            });

            cartItemsDiv.innerHTML = html;
            cartCount.textContent = itemCount;

            const shipping = 150;
            const vat = total * 0.15;
            const grandTotal = total + shipping + vat;

            cartTotal.textContent = `R${grandTotal.toFixed(2)}`;
        }

        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    updateCart();
                }
            }
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        function toggleCart() {
            const sidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const shipping = 150;
            const vat = total * 0.15;
            const grandTotal = total + shipping + vat;

            alert(`Checkout\n\nSubtotal: R${total.toFixed(2)}\nShipping: R${shipping.toFixed(2)}\nVAT (15%): R${vat.toFixed(2)}\n\nTotal: R${grandTotal.toFixed(2)}\n\nThis is a demo. In a real app, you would proceed to payment.`);
        }
    </script>
</body>
</html>