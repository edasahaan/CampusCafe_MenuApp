<?php

session_start();

if (!isset($_SESSION['student_user_id'])) {

    header("Location: entrance.php");

    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu App</title>
    <link rel="stylesheet" href="styles/menu.css" />
</head>


<!-- extras -->
<div id="custom-modal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center;">
    <div
        style="background: var(--card-bg); padding: 30px; border-radius: var(--radius); width: 90%; max-width: 400px; box-shadow: var(--shadow); backdrop-filter: blur(10px);">

        <h2 id="modal-product-name"
            style="color: var(--text-main); margin-bottom: 20px; font-size: 24px; text-align: center;">Ürün Adı</h2>

        <div id="modal-options-container"
            style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button id="modal-cancel-btn"
                style="flex: 1; background: var(--text-muted); color: white; padding: 12px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold;">İptal</button>
            <button id="modal-add-btn"
                style="flex: 1; background: var(--action-color); color: white; padding: 12px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold;">Sepete
                Ekle</button>
        </div>

    </div>
</div>

<body>
    <div class="accountSettings">
        <div id="user-info-display" style="display: none;"><span id="logged-in-no" style="font-weight: bold;"></span>
            <button id="logout-btn">
                Log Out
            </button>
        </div>

        <button id="account-btn" onclick="showUser()">
            <img src="images/account.png" alt="User">
        </button>

        <button id="shop-btn" onclick="showCart()">
            <img src="images/shoppingBag.png" alt="Cart">
            <span id="cart-badge" class="badge" style="display: none">0</span>
        </button>
    </div>

    <div id="menu-container">
        <h1>Selecet Your Category</h1>
        <div class="grid" id="category-grid">
            <div class="item">
                <button class="category-button" onclick="showProducts(1)">
                    <img class="ItemImage" src="images/HotDrinks.png" alt="Hot Beverages" />
                    <h3 class="ItemName">Hot Beverages</h3>
                </button>
            </div>

            <div class="item">
                <button class="category-button" onclick="showProducts(2)">
                    <img class="ItemImage" src="images/ColdDrinks.png" alt="Cold Beverages" />
                    <h3 class="ItemName">Cold Beverages</h3>
                </button>
            </div>

            <div class="item">
                <button class="category-button" onclick="showProducts(3)">
                    <img class="ItemImage" src="images/bakery.jpg" alt="Bakery" />
                    <span class="ItemName">Bakery</span>
                </button>
            </div>

            <div class="item">
                <button class="category-button" onclick="showProducts(4)">
                    <img class="ItemImage" src="images/mesrubatlar.jpg" alt="Soft Drinks" />
                    <span class="ItemName">Soft Drinks</span>
                </button>
            </div>

            <div class="item">
                <button class="category-button" onclick="showProducts(5)">
                    <img class="ItemImage" src="images/dessert.jpg" alt="Desserts" />
                    <h3 class="ItemName">Desserts</h3>
                </button>
            </div>
        </div>
    </div>

    <!-- PRODUCTS -->
    <div id="product-container" style="display: none">
        <button onclick="BackToCategories()" class="back-btn">
            <img src="images/backButton.png" alt="back-button" />
        </button>

        <h1 id="category-title"></h1>

        <div class="grid" id="products-grid">
            <!-- Ürünler buraya JS ile gönderilecek -->
        </div>
    </div>

    <!-- CART -->
    <div id="cart-container" style="display: none">
        <button onclick="BackToProducts()" class="back-btn">
            <img src="images/backButton.png" alt="back-button" />
        </button>
        <h1>YOUR CART</h1>
        <div class="grid" id="cart-grid">
            <!-- Ürünler buraya JS ile gönderilecek -->
        </div>
        <div class="summary">
            <h3 id="cart-total"></h3>
            <button class="action-button" id="confirm-order-btn" onclick="finalizeOrder()">
                Confirm Order
            </button>
        </div>
    </div>


    <div id="order-tracking-area">
        <button id="tracking-toggle-btn">
            <img src="images/right-arrow.png" alt="toggle">
        </button>

        <h3>Sipariş Durumu</h3>
        <div id="student-orders-list">
            <p>Aktif siparişiniz bulunmuyor.</p>
        </div>
    </div>


    <!-- ORDER -->
    <div id="order-container" style="display: none">
        <button onclick="BackToProducts()" class="back-btn">
            <img src="images/backButton.png" alt="back-button" />
        </button>
        <h1>
            ORDER HAS COMPLETED <img src="images/done-icon.png" alt="done-icon" />
        </h1>

        <div class="orderNumberBlock">
            <p>Your Order Number:</p>
            <h3 id="orderNum"></h3>
        </div>
    </div>

    <script src="js/student/tanimlamalar.js"></script>
    <script src="js/student/menu-pageControl.js"></script>
    <script src="js/student/menu-cart.js"></script>
    <script src="js/student/menu-orderControl.js"></script>
    <script src="js/student/orderTracking.js"></script>
</body>

</html>