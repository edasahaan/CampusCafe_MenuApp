document.addEventListener("DOMContentLoaded", () => {
  const logInButtons = document.querySelectorAll(".logIn-btn");
  const studentLogin = document.getElementById("student-login");
  const personnelLogin = document.getElementById("personnel-login");

  logInButtons.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      // Dinamik kayıt onayı butonunu (final-confirm-btn) tetiklememek için
      if (e.target.id === "final-confirm-btn") return;

      e.preventDefault();
      e.stopImmediatePropagation();

      let role = "";
      let idNo = "";
      let password = "";

      // --- PERSONEL GİRİŞİ ---
      if (personnelLogin && personnelLogin.style.display === "block") {
        role = "Personnel";
        idNo = personnelLogin
          .querySelector('[id="RegistrationNum"]')
          .value.trim();
        password = personnelLogin.querySelector(
          '[id="PersonnelPassword"]',
        ).value;

        if (idNo.length !== 4 || password.length < 6) {
          alert(
            "Geçersiz! Personel numarası 4 haneli ve şifre en az 6 karakter olmalıdır.",
          );
          return;
        }
      }
      // --- ÖĞRENCİ GİRİŞİ ---
      else if (studentLogin && studentLogin.style.display === "block") {
        role = "Student";
        idNo = studentLogin.querySelector('[id="StudentNo"]').value.trim();
        password = studentLogin.querySelector('[id="StudentPassword"]').value;

        if (idNo.length !== 10 || password.length < 8) {
          alert(
            "Geçersiz! Öğrenci numarası 10 haneli ve şifre en az 8 karakter olmalıdır.",
          );
          return;
        }
      }

      if (idNo === "" || password === "") {
        alert("Kimlik numarası ve şifre boş bırakılamaz.");
        return;
      }

      try {
        const response = await fetch("php/login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ role, idNo, password }),
        });

        const result = await response.json();

        if (result.success) {
          if (result.role === "Student") {
            localStorage.setItem("studentNo", idNo);
            window.location.href = "menu.php";
          } else {
            localStorage.setItem("personnelNo", idNo);
            window.location.href = "personnelSide.php";
          }
        } else {
          alert("Giriş başarısız: " + result.error);
        }
      } catch (error) {
        console.error("Giriş sırasında hata:", error);
        alert("Sunucuya bağlanılamadı.");
      }
    });
  });
});
