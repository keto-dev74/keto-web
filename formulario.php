
<?php
$message = ""; // Texto da notificação
$type = "";    // "success" ou "error"

try {
    $connexion = new PDO(
        'mysql:host=localhost;dbname=senkou',
        'senkou',
        'eA)U)dloot4X-aaW'
    );
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $message = "Erro na conexão com o banco de dados!";
    $type = "error";
}

if (isset($_POST["enviar"])) {
    if (!empty($_POST['nome']) && !empty($_POST['sobre_nome']) && !empty($_POST['email']) && !empty($_POST['empresa'])) {
        $nome = htmlspecialchars($_POST['nome']);
        $sobre_nome = htmlspecialchars($_POST['sobre_nome']);
        $email = htmlspecialchars($_POST['email']);
        $empresa = htmlspecialchars($_POST['empresa']);

        if (strlen($nome) > 50 || strlen($sobre_nome) > 50) {
            $message = "⚠ O seu nome ou sobrenome é muito longo.";
            $type = "error";
        } else {
            $verif = $connexion->prepare("SELECT id FROM users WHERE email = ?");
            $verif->execute([$email]);

            if ($verif->rowCount() > 0) {
                $message = "❌ Desculpe, este e-mail já foi utilizado.";
                $type = "error";
            } else {
                $insertion = $connexion->prepare("INSERT INTO users (nome, sobre_nome, email, empresa) VALUES (?, ?, ?, ?)");
                $insertion->execute([$nome, $sobre_nome, $email, $empresa]);
                $message = "✅ A sua mensagem foi enviada com sucesso!";
                $type = "success";
            }
        }
    } else {
        $message = "⚠ Por favor, preencha todos os campos.";
        $type = "error";
    }
}
?>

<!-- Conteneur pour afficher la notification -->
<div id="notif"></div>

<style>
  #notif {
    position: fixed;
    top: 25px;
    left: 50%;
    transform: translateX(-50%) translateY(-30px);
    min-width: 400px;
    max-width: 600px;
    padding: 20px 25px;
    border-radius: 12px;
    font-family: Arial, sans-serif;
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.4);
    opacity: 0;
    transition: opacity 0.6s ease, transform 0.6s ease;
    z-index: 9999;
  }
  #notif.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
  #notif.success {
    background: linear-gradient(135deg, #28a745, #218838);
  }
  #notif.error {
    background: linear-gradient(135deg, #dc3545, #a71d2a);
  }
</style>

<script>
  const notif = document.getElementById('notif');
  <?php if($message !== ""): ?>
    notif.innerText = <?php echo json_encode($message); ?>;
    notif.className = 'show <?php echo $type; ?>';

    // Disparaît après 4 secondes
    setTimeout(() => {
      notif.classList.remove('show');
      setTimeout(() => notif.innerText = "", 500);
    }, 4000);
  <?php endif; ?>
</script>