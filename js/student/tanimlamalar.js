const menuContainer = document.getElementById("menu-container");
const productContainer = document.getElementById("product-container");
const categoryTitle = document.getElementById("category-title");
const productsGrid = document.getElementById("products-grid");
const backBtn = document.querySelector(".back-btn");

//SEPET OLAYI------------------
const shopBtn = document.getElementById("shop-btn");
const cartContainer = document.getElementById("cart-container");
const orderContainer = document.getElementById("order-container");
const orderGrid = document.getElementById("order-grid");
const badge = document.getElementById("cart-badge");

const cartGrid = document.getElementById("cart-grid");
const totalDisplay = document.getElementById("cart-total"); // ID komtrolü

//finalizeorder
const orderTotal = document.getElementById("order-total"); // ID komtrolü
const orderNumDisplay = document.getElementById("orderNum");

const categoryNames = {
  1: "Hot Beverages",
  2: "Cold Beverages",
  3: "Bakery",
  4: "Soft Drinks",
  5: "Desserts",
};

let dbProducts = []; // mockProducts yerine bu kullanılacak

// Sunucudan ürünleri çekmek için asenkron fonksiyon
async function fetchProducts() {
  try {
    const response = await fetch("php/get_products.php");
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    dbProducts = await response.json();
    console.log("Veritabanından gelen ürünler:", dbProducts);
  } catch (error) {
    console.error("Ürünler çekilirken hata oluştu:", error);
  }
}

// Sayfa yüklendiğinde ürünleri hazırla
document.addEventListener("DOMContentLoaded", () => {
  fetchProducts();
});

let dbCustomizations = {}; // Özelleştirmeleri tutacağımız obje

// Sunucudan özelleştirmeleri çekmek için fonksiyon
async function fetchCustomizations() {
  try {
    const response = await fetch("php/get_customizations.php");
    if (!response.ok) throw new Error("Ağ hatası");
    dbCustomizations = await response.json();
    console.log("Veritabanından gelen özelleştirmeler:", dbCustomizations);
  } catch (error) {
    console.error("Özelleştirmeler çekilirken hata:", error);
  }
}

// Mevcut DOMContentLoaded eventinin içini şu şekilde güncelle ki ikisini de çeksin:
document.addEventListener("DOMContentLoaded", () => {
  fetchProducts();
  fetchCustomizations();
});
