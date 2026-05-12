// Ürünleri Listeleme
window.loadProducts = async function () {
  const productListBody = document.getElementById("product-list-body");
  try {
    // cache: "no-store" ekleyerek her seferinde güncel veriyi zorunlu kılıyoruz
    const response = await fetch("php/get_products.php", { cache: "no-store" });
    const products = await response.json();
    if (!productListBody) return;
    productListBody.innerHTML = "";

    const categoryMap = {
      1: "Hot Beverages",
      2: "Cold Beverages",
      3: "Bakery",
      4: "Soft Drinks",
      5: "Desserts",
    };

    products.forEach((p) => {
      const catName = categoryMap[p.CategoryID] || "Unknown";
      productListBody.innerHTML += `
                <tr>
                    <td>${p.ProductName}</td> 
                    <td>${catName}</td> 
                    <td><input type="number" value="${p.Price}" id="price-${p.ProductID}" style="width:70px"></td>
                    <td><button class="product-list-btns" onclick="updateProductPrice(${p.ProductID})">Fiyatı Güncelle</button></td>
                </tr>`;
    });
  } catch (error) {
    console.error("Yükleme hatası:", error);
  }
};

// Ürün Ekleme (CREATE)
document.addEventListener("DOMContentLoaded", () => {
  const addProductBtn = document.getElementById("add-product-btn");
  if (addProductBtn) {
    addProductBtn.addEventListener("click", async () => {
      const name = document.getElementById("new-p-name").value;
      const price = document.getElementById("new-p-price").value;
      const category = document.getElementById("new-p-category").value;

      if (!name || !price) return alert("Lütfen boş alan bırakmayın!");

      const response = await fetch("php/add_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, price, category }),
      });

      const res = await response.json();
      if (res.success) {
        alert("Ürün eklendi!");
        loadProducts();
        document.getElementById("new-p-name").value = "";
        document.getElementById("new-p-price").value = "";
      }
    });
  }
});

// Fiyat Güncelleme
window.updateProductPrice = async function (id) {
  const newPrice = document.getElementById(`price-${id}`).value;
  const response = await fetch("php/update_product_price.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, price: newPrice }),
  });
  const res = await response.json();
  if (res.success) {
    alert("Fiyat başarıyla güncellendi!");
    loadProducts();
  } else alert("Bir hata oluştu: " + res.error);
};
