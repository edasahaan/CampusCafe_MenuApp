// Siparişleri Çekme ve Ekrana Basma
let pollingTimer = null;

window.fetchOrders = async function () {
  try {
    const response = await fetch("php/get_orders_personnel.php", {
      cache: "no-store",
    }); // YOL GÜNCELLENDİ
    const orders = await response.json();

    if (orders.error) return console.error("Veritabanı Hatası:", orders.error);

    const lists = {
      pending: document.getElementById("pending-list"),
      preparing: document.getElementById("preparing-list"),
      completed: document.getElementById("completed-list"),
    };

    if (!lists.pending) return;

    Object.values(lists).forEach((list) => (list.innerHTML = "")); // Temizle

    orders.forEach((order) => {
      let status = order.Status ? order.Status.toLowerCase().trim() : "pending";
      let displayStatus = status.charAt(0).toUpperCase() + status.slice(1);

      const cardHTML = `
            <div class="order-card">
                <div class="order-card-header">
                    <strong>Sipariş #${order.OrderID}</strong>
                    <span class="order-status-text">${displayStatus}</span>
                </div>
                <hr>
                <p class="order-details-text">${order.Details}</p>
                <div class="order-card-footer">
                    <input type="checkbox" class="${status}-checkbox" value="${order.OrderID}" id="chk-${order.OrderID}"> 
                    <label for="chk-${order.OrderID}">Seç</label>
                </div>
            </div>
            `;
      if (lists[status]) lists[status].innerHTML += cardHTML;
    });
  } catch (error) {
    console.error("Siparişler çekilemedi:", error);
  } finally {
    if (pollingTimer) clearTimeout(pollingTimer);
    pollingTimer = setTimeout(fetchOrders, 7000);
  }
};

// Durum Güncelleme (Kanban Taşıma)
window.moveOrders = async function (currentStatus, targetStatus) {
  const checkboxes = document.querySelectorAll(
    `.${currentStatus}-checkbox:checked`,
  );
  const selectedIds = Array.from(checkboxes).map((cb) => cb.value);

  if (selectedIds.length === 0)
    return alert("Lütfen taşımak için en az bir sipariş seçin.");

  try {
    const response = await fetch("php/update_order_status.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ orderIds: selectedIds, newStatus: targetStatus }),
    });

    const result = await response.json();
    if (result.success) {
      if (pollingTimer) clearTimeout(pollingTimer);
      fetchOrders();
    } else alert("Güncelleme hatası: " + result.error);
  } catch (error) {
    console.error("Güncelleme başarısız:", error);
  }
};

// İlk yükleme
document.addEventListener("DOMContentLoaded", fetchOrders);
