// Sepet ekranına geçiş
function showCart() {
  menuContainer.style.display = "none";
  productContainer.style.display = "none";
  cartContainer.style.display = "flex";
  renderCart();
}

// Sepet üzerindeki bildirim sayısını (badge) güncelleme
function updateBadge() {
  const totalItems = cart.reduce((total, item) => total + item.quantity, 0);

  if (totalItems > 0) {
    badge.style.display = "flex";
    badge.innerText = totalItems;
  } else {
    badge.style.display = "none";
  }
}

// Global Sepet Dizisi ve Modal için Geçici Ürün Tutucu
let cart = [];
let currentSelectedProduct = null;

// Modal Açma Fonksiyonu
function openCustomModal(productId) {
  const product = dbProducts.find(
    (p) => parseInt(p.ProductID) === parseInt(productId),
  );
  if (!product) return;

  // Sadece Sıcak (1) ve Soğuk (2) İçeceklerde modal aç. Diğerlerini direkt sepete gönder.
  if (
    parseInt(product.CategoryID) !== 1 &&
    parseInt(product.CategoryID) !== 2
  ) {
    addToCartFinal(product, []);
    return;
  }

  currentSelectedProduct = product;
  document.getElementById("modal-product-name").innerText =
    product.ProductName + " Özelleştir";

  const container = document.getElementById("modal-options-container");
  container.innerHTML = "";

  for (const [type, options] of Object.entries(dbCustomizations)) {
    let html = `<div><h4 style="color:var(--darkGreen-color); margin-bottom:5px;">${type}</h4>`;

    const inputType = type === "Syrup" ? "checkbox" : "radio";
    const nameAttr = `custom_${type}`;

    options.forEach((opt) => {
      const priceText =
        parseFloat(opt.ExtraPrice) > 0 ? ` (+${opt.ExtraPrice} TL)` : "";
      html += `
              <label style="display:block; margin-bottom:5px; color:#333; cursor:pointer;">
                  <input type="${inputType}" name="${nameAttr}" value="${opt.CustomID}" data-price="${opt.ExtraPrice}" data-name="${opt.Value}">
                  ${opt.Value}${priceText}
              </label>
          `;
    });
    html += `</div>`;
    container.innerHTML += html;
  }

  document.getElementById("custom-modal").style.display = "flex";
}

// Modal İptal İşlemi
document.getElementById("modal-cancel-btn").addEventListener("click", () => {
  document.getElementById("custom-modal").style.display = "none";
  currentSelectedProduct = null;
});

// Modal Onay İşlemi (Seçimleri Sepete Gönderme)
document.getElementById("modal-add-btn").addEventListener("click", () => {
  if (!currentSelectedProduct) return;

  const selectedCustoms = [];
  const inputs = document.querySelectorAll(
    "#modal-options-container input:checked",
  );

  inputs.forEach((input) => {
    selectedCustoms.push({
      CustomID: parseInt(input.value),
      Value: input.getAttribute("data-name"),
      ExtraPrice: parseFloat(input.getAttribute("data-price")),
    });
  });

  addToCartFinal(currentSelectedProduct, selectedCustoms);

  document.getElementById("custom-modal").style.display = "none";
  currentSelectedProduct = null;
});

// Benzersiz Kimlikle Asıl Sepete Ekleme
function addToCartFinal(product, customizations) {
  const customKey = customizations
    .map((c) => c.CustomID)
    .sort()
    .join("_");
  const cartItemId = `${product.ProductID}_${customKey}`;

  const existingItem = cart.find((item) => item.cartItemId === cartItemId);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      cartItemId: cartItemId,
      ProductID: product.ProductID,
      ProductName: product.ProductName,
      Price: parseFloat(product.Price),
      quantity: 1,
      customizations: customizations,
    });
  }

  updateBadge();
  renderCart();
}

// Sepeti Ekrana Çizme
function renderCart() {
  if (!cartGrid) return;
  let total = 0;

  if (cart.length === 0) {
    cartGrid.innerHTML = '<p class="information">Sepetiniz şu an boş.</p>';
    if (totalDisplay) {
      totalDisplay.innerText = "Total: 0 TL";
    }
    updateBadge();
    return;
  }

  cartGrid.innerHTML = cart
    .map((item) => {
      const customTotal = item.customizations.reduce(
        (sum, c) => sum + c.ExtraPrice,
        0,
      );
      const unitTotalPrice = item.Price + customTotal;
      total += unitTotalPrice * item.quantity;

      const customText =
        item.customizations.length > 0
          ? `<span style="font-size: 14px; color: var(--text-muted); display:block; margin-bottom:10px;">` +
            item.customizations.map((c) => c.Value).join(", ") +
            `</span>`
          : "";

      return `
        <div class="item">
            <h3 class="ItemName">${item.ProductName} (x${item.quantity})</h3>
            ${customText}
            <div id="product-info">
                <p>${unitTotalPrice * item.quantity} TL</p>
                <div class="quantityUpdating"> 
                <button class="action-button" onclick="increaseTheItem('${item.cartItemId}')"><img src="images/add-icon.png" alt="addition" />
                </button>
                <button class="action-button" onclick="removeFromCart('${item.cartItemId}')"><img src="images/subtraction-icon.png" alt="subtraction" />
                </button>
                </div>
            </div>
        </div>
    `;
    })
    .join("");

  if (totalDisplay) {
    totalDisplay.innerText = `Total: ${total} TL`;
  }

  updateBadge();
}

// Ürün Silme / Eksiltme (Benzersiz ID ile)
function removeFromCart(cartItemId) {
  const itemIndex = cart.findIndex((item) => item.cartItemId === cartItemId);

  if (itemIndex !== -1) {
    if (cart[itemIndex].quantity > 1) {
      cart[itemIndex].quantity -= 1;
    } else {
      cart.splice(itemIndex, 1);
    }
  }

  renderCart();
  updateBadge();
}

// Ürün Artırma (Benzersiz ID ile)
function increaseTheItem(cartItemId) {
  const itemIndex = cart.findIndex((item) => item.cartItemId === cartItemId);
  if (itemIndex !== -1) {
    cart[itemIndex].quantity += 1;
    renderCart();
    updateBadge();
  }
}
