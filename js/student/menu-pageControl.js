//tanımlamalar
//Geri butonu
//mockProducts
//Kategori seçilincw ürün listesi gelsin
// Ana menüyü gizlenecek
// Ürünleri filtrele ve HTML oluştur
// Başlık güncellenecek
//Sepete ekleme (Şimdilik konsola yazdırılıyor)

//back buttons---------------------
function BackToCategories() {
  productContainer.style.display = "none";
  menuContainer.style.display = "flex";
}

function BackToProducts() {
  cartContainer.style.display = "none";
  menuContainer.style.display = "none";
  orderContainer.style.display = "none";
  productContainer.style.display = "flex";
}

function showUser() {
  const userDisplay = document.getElementById("user-info-display");
  const loggedInNo = document.getElementById("logged-in-no");

  if (
    userDisplay.style.display === "none" ||
    userDisplay.style.display === ""
  ) {
    // 1. Sorunun Çözümü: ID'yi LocalStorage'dan kesin olarak alıyoruz
    const studentId = localStorage.getItem("studentNo") || "Unknown";
    loggedInNo.innerText = "Account ID: " + studentId;

    // Menüyü görünür yap (içindeki butonla alt alta durması için flex kullanıyoruz)
    userDisplay.style.display = "flex";

    // Kullanıcının Log Out butonuna basmaya vakti olması için süreyi 5 saniyeye çıkarıyoruz
    setTimeout(() => {
      userDisplay.style.display = "none";
    }, 5000);
  } else {
    userDisplay.style.display = "none";
  }
}

//Log Out Butonu İşlevi
document.addEventListener("DOMContentLoaded", () => {
  const logoutBtn = document.getElementById("logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      // Tarayıcıdaki ID kayıtlarını tamamen temizle
      localStorage.clear();
      // PHP tarafında oturumu (session) kapatmak için logout.php'ye yönlendir
      window.location.href = "php/logout.php";
    });
  }
});

//Showing products after selected a category
function showProducts(CategoryID) {
  menuContainer.style.display = "none";
  productContainer.style.display = "flex";

  // mockProducts yerine dbProducts kullanılıyor.
  // API'den CategoryID string gelebileceği için == kullanıyoruz (veya parseInt yapıyoruz).
  const filtered = dbProducts.filter(
    (p) => parseInt(p.CategoryID) === CategoryID,
  );

  console.log("Filtrelenmiş Ürün Sayısı:", filtered.length);

  categoryTitle.innerHTML = categoryNames[CategoryID].toUpperCase();

  productsGrid.innerHTML = filtered
    .map(
      (product) => `
        <div class="item">
            <h3 class="ItemName">${product.ProductName}</h3>
           <div id="product-info">
            <p>${product.Price} TL</p> 
            <button class="action-button" id="addToCardBtn" onclick="openCustomModal(${product.ProductID})">Add to Cart</button>
            </div>
        </div>
    `,
    )
    .join("");
}
