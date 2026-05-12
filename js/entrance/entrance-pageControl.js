document.addEventListener("DOMContentLoaded", () => {
  // Arayüz Elementleri
  const chooseIdentity = document.querySelector(".chooseIdenty");
  const studentLogin = document.getElementById("student-login");
  const personnelLogin = document.getElementById("personnel-login");
  const studentRegister = document.getElementById("student-register");
  const personnelRegister = document.getElementById("personnel-register");
  const backBtn = document.querySelector(".back-btn");

  const studentBtn = document.getElementById("student-btn");
  const personnelBtn = document.getElementById("personnel-btn");
  const studentRegBtn = document.querySelector(".student-register-btn");
  const personnelRegBtn = document.querySelector(".personnel-register-btn");

  // Tüm inputların içini zorla boşaltan fonksiyon
  function clearInputs() {
    document.querySelectorAll("input").forEach((input) => (input.value = ""));
  }

  // Sadece rakam girilmesini sağlayan kısıtlama
  document.querySelectorAll('input[inputmode="numeric"]').forEach((input) => {
    if (input) {
      input.addEventListener("input", function () {
        this.value = this.value.replace(/[^0-9]/g, "");
      });
    }
  });

  // Ekranları temizleme fonksiyonu
  function hideAll() {
    [
      studentLogin,
      personnelLogin,
      studentRegister,
      personnelRegister,
      chooseIdentity,
    ].forEach((div) => {
      if (div) div.style.display = "none";
    });
  }

  // Yönlendirme Butonları Event Listener'ları
  if (studentBtn) {
    studentBtn.addEventListener("click", () => {
      clearInputs();
      hideAll();
      studentLogin.style.display = "block";
      backBtn.style.display = "block";
    });
  }

  if (personnelBtn) {
    personnelBtn.addEventListener("click", () => {
      clearInputs();
      hideAll();
      personnelLogin.style.display = "block";
      backBtn.style.display = "block";
    });
  }

  if (studentRegBtn) {
    studentRegBtn.addEventListener("click", () => {
      clearInputs();
      hideAll();
      studentRegister.style.display = "block";
    });
  }

  if (personnelRegBtn) {
    personnelRegBtn.addEventListener("click", () => {
      clearInputs();
      hideAll();
      personnelRegister.style.display = "block";
    });
  }

  if (backBtn) {
    backBtn.addEventListener("click", () => {
      clearInputs();
      hideAll();
      chooseIdentity.style.display = "flex";
      backBtn.style.display = "none";
    });
  }
});
