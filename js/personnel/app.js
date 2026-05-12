//arayüz ve genel ayarlar

document.addEventListener("DOMContentLoaded", () => {
  // Personel ID Gösterimi
  const idDisplay = document.getElementById("personnel-id-display");
  if (idDisplay) {
    const personnelId = localStorage.getItem("personnelNo") || "Unknown";
    idDisplay.innerText = "Personel ID: " + personnelId;
  }

  // Ekranlar arası geçiş
  const orderScreen = document.querySelector(".orderScreen");
  const productScreen = document.querySelector(".productScreen");
  const toggleBtn = document.getElementById("product-btn");

  if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
      if (orderScreen.style.display !== "none") {
        orderScreen.style.display = "none";
        productScreen.style.display = "block";
        toggleBtn.innerText = "Orders";
        if (window.loadProducts) loadProducts();
      } else {
        orderScreen.style.display = "block";
        productScreen.style.display = "none";
        toggleBtn.innerText = "Products";
      }
    });
  }

  // Logout İşlemi
  const logoutBtn = document.getElementById("logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      localStorage.clear();
      window.location.href = "php/logout.php"; // YOL GÜNCELLENDİ
    });
  }

  // Kasayı Kapat (Gün Sonu)
  const closeRegisterBtn = document.getElementById("close-register-btn");
  if (closeRegisterBtn) {
    closeRegisterBtn.addEventListener("click", async () => {
      if (!confirm("Gün sonu kapanışı yapıyorsunuz. Onaylıyor musunuz?"))
        return;
      try {
        const response = await fetch("php/close_register.php", {
          method: "POST",
        }); // YOL GÜNCELLENDİ
        const result = await response.json();
        if (result.success) {
          alert("Kasa kapatıldı!");
          if (window.fetchOrders) window.fetchOrders(); // Kanban'ı tazele
        }
      } catch (error) {
        console.error("Hata:", error);
      }
    });
  }
});
