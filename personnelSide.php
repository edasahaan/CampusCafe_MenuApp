<?php
session_start();
// Oturum yoksa Ana Dizindeki entrance.php'ye dön
if (!isset($_SESSION['personnel_user_id'])) {
    header("Location: entrance.php");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sipariş Paneli - MenuApp</title>
    <link rel="stylesheet" href="styles/personnelSide.css" />
</head>

<body>
    <div class="top-bar">
        <div class="user-info">
            <span id="personnel-id-display">Personnel ID: ...</span>
        </div>
        <div class="actions" style="display: flex; gap: 15px;">
            <button class="top-bar-btns" id="product-btn">Products</button>
            <button class="top-bar-btns" id="close-register-btn">End of Day (Close Register)</button>
            <button class="top-bar-btns" id="logout-btn">Log Out</button>
        </div>
    </div>

    <!-- ORDER CONTROL SCREEN -->
    <div class="orderScreen">
        <h1>ORDERS</h1>
        <div class="kanban-board">
            <div class="kanban-column">
                <h2>Pending</h2>
                <div id="pending-list"></div>
                <button class="action-btn" onclick="moveOrders('pending', 'preparing')">Seçilenleri Hazırlanıyor'a Al</button>
            </div>

            <div class="kanban-column">
                <h2>Preparing</h2>
                <div id="preparing-list"></div>
                <button class="action-btn" onclick="moveOrders('preparing', 'completed')">Seçilenleri Tamamlandı'ya Al</button>
            </div>

            <div class="kanban-column">
                <h2>Completed</h2>
                <div id="completed-list"></div>
            </div>
        </div>
    </div>

    <!-- PRODUCT CONTROL SCREEN -->
    <div class="productScreen" style="display: none;">
        <h1>Product Control Panel</h1>
        <div class="product-container">
            <div class="add-product-form">

                <input type="text" id="new-p-name" placeholder="Product Name">
                <input type="number" id="new-p-price" placeholder="Price">
                <select id="new-p-category">
                    <option value="1">Hot Beverages</option>
                    <option value="2">Cold Beverages</option>
                    <option value="3">Bakery</option>
                    <option value="4">Soft Drinks</option>
                    <option value="5">Desserts</option>
                </select>
                <button class="product-list-btns" id="add-product-btn" class="update-btn" style="padding: 10px 20px; font-weight: bold;">Add Product</button>
            </div>

            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price (TL)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="product-list-body">
                </tbody>
            </table>
        </div>
    </div>
    <script src="js/personnel/kanbanControl.js"></script>
    <script src="js/personnel/productControl.js"></script>
    <script src="js/personnel/app.js"></script>
</body>

</html>