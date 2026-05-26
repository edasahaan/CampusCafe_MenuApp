// Sepetteki ürünleri ve seçilen özelleştirmeleri save_order.php'ye gönderir.
async function finalizeOrder() {
  // Güvenlik kontrolü
  if (cart.length === 0) {
    alert("Sepetiniz boş!");
    return;
  }

  // Toplam fiyatı (Ürün Taban Fiyatı + Ekstra Özellikler) hesapla
  let total = 0;
  cart.forEach((item) => {
    const customTotal = item.customizations.reduce(
      (sum, c) => sum + c.ExtraPrice,
      0,
    );
    total += (item.Price + customTotal) * item.quantity;
  });

  // Backend'in (save_order.php) beklediği veri yapısını oluştur
  const orderData = {
    totalPrice: total,
    items: cart.map((item) => {
      const customTotal = item.customizations.reduce(
        (sum, c) => sum + c.ExtraPrice,
        0,
      );
      return {
        productID: item.ProductID,
        quantity: item.quantity,
        unitPrice: item.Price + customTotal, // Backend'e özelleştirilmiş birim fiyatı gönderiyoruz
        customizations: item.customizations.map((c) => c.CustomID), // Sadece ID listesini gönderiyoruz
      };
    }),
  };

  try {
    const response = await fetch("php/save_order.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(orderData),
    });

    const result = await response.json();

    if (result.success) {
      // Sipariş numarasını ekranda göster (Veritabanından dönen asıl ID)
      if (document.getElementById("orderNum")) {
        document.getElementById("orderNum").innerText = "ID: " + result.orderID;
      }

      // Arayüz geçişlerini yönet
      if (typeof menuContainer !== "undefined")
        menuContainer.style.display = "none";
      if (typeof productContainer !== "undefined")
        productContainer.style.display = "none";
      if (typeof cartContainer !== "undefined")
        cartContainer.style.display = "none";
      if (typeof orderContainer !== "undefined")
        orderContainer.style.display = "flex";

      // Sepeti temizle ve badge'i sıfırla
      cart = [];
      if (typeof updateBadge === "function") updateBadge();
      if (typeof renderCart === "function") renderCart();

      alert("Sipariş başarıyla tamamlandı!");
    } else {
      alert("Sipariş hatası: " + result.error);
    }
  } catch (error) {
    console.error("Sipariş gönderim hatası:", error);
    alert("Sunucuyla iletişim kurulamadı. Lütfen bağlantınızı kontrol edin.");
  }
}
