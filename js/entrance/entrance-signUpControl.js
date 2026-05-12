document.addEventListener("DOMContentLoaded", () => {
  const signUpBtns = document.querySelectorAll(".signUp-btn");
  const studentRegister = document.getElementById("student-register");
  const personnelRegister = document.getElementById("personnel-register");

  // Şifre Güvenlik Kontrolü
  function isPasswordSecure(password) {
    const securePattern =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{6,}$/;
    return securePattern.test(password);
  }

  signUpBtns.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
      e.preventDefault();
      e.stopImmediatePropagation();

      let role = "";
      let idNo = "";
      let email = "";
      let password = "";

      // --- PERSONEL KAYDI ---
      if (personnelRegister && personnelRegister.style.display === "block") {
        role = "Personnel";

        // HTML'deki güncel ID'ler (RegPersonnelPassword kullanıldı)
        email = personnelRegister
          .querySelector('[id="personnel-Email"]')
          .value.trim();
        password = personnelRegister.querySelector(
          '[id="RegPersonnelPassword"]',
        ).value;

        if (!email.endsWith("@karabuk.edu.tr")) {
          alert("Personel emaili @karabuk.edu.tr ile bitmelidir.");
          return;
        }

        if (!isPasswordSecure(password)) {
          alert(
            "Şifre en az 6 karakter olmalı; büyük harf, küçük harf, sayı ve özel karakter (@$!%*?&.) içermelidir.",
          );
          return;
        }

        // 4 Haneli Rastgele Personel ID Üretimi
        idNo = Math.floor(1000 + Math.random() * 9000).toString();

        try {
          const response = await fetch("php/register.php", {
            method: "POST",
            body: JSON.stringify({ role, idNo, email, password }),
          });
          const result = await response.json();

          if (result.success) {
            personnelRegister.innerHTML = `
              <div style="text-align: center; padding: 20px;">
                  <p style="color: #333; margin-bottom: 15px;">
                      Personnel Registration Successful!<br>
                      Your ID: <strong style="color: #e67e22; font-size: 1.4rem;">${idNo}</strong>
                  </p>
                  <button id="final-confirm-btn" class="logIn-btn" style="width: 100%;">Confirm & Login</button>
              </div>
            `;
            document
              .getElementById("final-confirm-btn")
              .addEventListener("click", () => {
                window.location.href = "entrance.php";
              });
          } else {
            alert("Hata: " + result.error);
          }
        } catch (error) {
          console.error("Kayıt Hatası:", error);
        }
      }

      // --- ÖĞRENCİ KAYDI ---
      else if (studentRegister && studentRegister.style.display === "block") {
        role = "Student";

        // HTML'deki güncel ID'ler (RegStudentNo ve RegStudentPassword kullanıldı)
        idNo = studentRegister
          .querySelector('[id="RegStudentNo"]')
          .value.trim();
        email = studentRegister
          .querySelector('[id="student-Email"]')
          .value.trim();
        password = studentRegister.querySelector(
          '[id="RegStudentPassword"]',
        ).value;

        if (idNo.length !== 10) {
          alert(
            `Hata: Öğrenci numaranız ${idNo.length} haneli. Tam olarak 10 haneli olmalıdır.`,
          );
          return;
        }

        if (!email.endsWith("@ogrenci.karabuk.edu.tr")) {
          alert("Öğrenci emaili @ogrenci.karabuk.edu.tr ile bitmelidir.");
          return;
        }

        if (!isPasswordSecure(password)) {
          alert(
            "Şifre en az 6 karakter olmalı; büyük harf, küçük harf, sayı ve özel karakter (@$!%*?&.) içermelidir.",
          );
          return;
        }

        try {
          const response = await fetch("php/register.php", {
            method: "POST",
            body: JSON.stringify({ role, idNo, email, password }),
          });
          const result = await response.json();

          if (result.success) {
            alert("Kayıt başarılı! Şimdi giriş yapabilirsiniz.");
            window.location.href = "entrance.php";
          } else {
            alert("Hata: " + result.error);
          }
        } catch (error) {
          console.error("Kayıt Hatası:", error);
        }
      }
    });
  });
});
