document.getElementById("reservaForm").addEventListener("submit", async function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  try {
    const response = await fetch("formulario.php", {
      method: "POST",
      body: formData
    });

    const text = await response.text();
    showNotification(text, text.includes("sucesso") ? "success" : "error");

    // reset form on success
    if (text.includes("sucesso")) {
      this.reset();

      // redirection après 3 secondes
      setTimeout(() => {
        window.location.href = "merci.html"; // <-- mets l'URL de ta page de remerciement
      }, 3000);
    }

  } catch (error) {
    showNotification("Erro de conexão com o servidor.", "error");
  }
});

function showNotification(message, type) {
  const notif = document.getElementById("notif");
  notif.textContent = message;
  notif.className = "notification " + type + " show";

  setTimeout(() => {
    notif.classList.remove("show");
  }, 4000);
}


 const menu = document.getElementById("menu");
  const toggle = document.getElementById("menuToggle");
  const links = menu.querySelectorAll("a");

  // ouvrir / fermer menu
  toggle.addEventListener("click", () => {
    menu.classList.toggle("open");
  });

  // fermer menu après clic sur un lien
  links.forEach(link => {
    link.addEventListener("click", () => {
      menu.classList.remove("open");
    });
  });

