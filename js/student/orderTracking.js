async function trackStudentOrders() {
  try {
    // Önbelleği kapatarak taze veri istiyoruz
    const response = await fetch("php/get_student_orders.php", {
      cache: "no-store",
    });
    const data = await response.json();

    if (data.success) {
      const trackingList = document.getElementById("student-orders-list");
      if (!trackingList) return; // HTML div'i henüz yüklenmediyse patlamayı önle

      if (data.orders.length === 0) {
        trackingList.innerHTML =
          "<p style='text-align:center;'>Aktif siparişiniz bulunmuyor.</p>";
        return;
      }

      trackingList.innerHTML = ""; // Listeyi temizle

      data.orders.forEach((order) => {
        let status = order.Status.toLowerCase();
        let displayStatus = status.charAt(0).toUpperCase() + status.slice(1);

        // Duruma göre renk kodlaması (UX prensibi)
        let statusColor = "#666"; // Varsayılan
        if (status === "pending") statusColor = "#f39c12"; // Turuncu
        if (status === "preparing") statusColor = "#3498db"; // Mavi
        if (status === "completed") statusColor = "#2ecc71"; // Yeşil

        trackingList.innerHTML += `
                    <div style="background: white; padding: 10px; margin-bottom: 8px; border-radius: 5px; border-left: 5px solid ${statusColor};">
                        <strong>Sipariş #${order.OrderID}</strong>
                        <span style="float: right; color: ${statusColor}; font-weight: bold;">${displayStatus}</span>
                    </div>
                `;
      });
    }
  } catch (error) {
    console.error("Sipariş takibi başarısız:", error);
  } finally {
    // Döngü: 10 saniyede bir veritabanını yokla
    setTimeout(trackStudentOrders, 2000);
  }
}

// Sayfa yüklendiğinde takip motorunu çalıştır
document.addEventListener("DOMContentLoaded", trackStudentOrders);

//sipariş durum paneli açma kapama
document.addEventListener("DOMContentLoaded", () => {
  const trackingArea = document.getElementById("order-tracking-area");
  const toggleBtn = document.getElementById("tracking-toggle-btn");

  if (toggleBtn && trackingArea) {
    console.log("Panel butonu başarıyla bağlandı."); // Kontrol mesajı
    toggleBtn.addEventListener("click", () => {
      // "open" class'ını ekleyip çıkararak paneli kaydırır
      trackingArea.classList.toggle("open");
    });
  } else {
    console.error("Hata: Panel veya Buton ID'si bulunamadı!");
  }
});
