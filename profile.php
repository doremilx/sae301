<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Connexion</h1>
    <form action="traite_login.php" method="post">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Se connecter">
    </form>

    <h1>Créer un compte</h1>
    <form action="creer_compte.php" method="POST">
        <input type="text" name="nom" id="nom" placeholder="Nom">
        <input type="text" name="prenom" id="prenom" placeholder="Prénom">
        <input type="text" name="login" id="login" placeholder="Login">
        <div style="display:flex; align-items:center; gap:5px;">
    <input type="password" name="motdepasse" id="motdepasse" placeholder="Mot de passe">
    <button type="button" id="toggleBtn" onclick="togglePassword()">👁️</button>
<button type="submit">Créer un compte</button>

</form>
    <script>
    function togglePassword() {
    let input = document.getElementById("motdepasse");
    let btn = document.getElementById("toggleBtn");

    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈";
    } else {
        input.type = "password";
        btn.textContent = "👁️";
    }
}

</script>
</body>
</html>