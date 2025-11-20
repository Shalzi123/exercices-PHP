<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params['path'], $params['domain'], $params['secure'], $params['httponly']
		);
	}
	session_destroy();
	header('Location: ' . $_SERVER['PHP_SELF']);
	exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
	$username = trim($_POST['username']);
	$_SESSION['username'] = $username;

	header('Location: ' . $_SERVER['PHP_SELF']);
	exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>TP : Variable de session</title>
</head>
<body>
<?php if (empty($_SESSION['username'])): ?>
	<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
		<label for="username">username</label>
		<input type="text" id="username" name="username" required>
		<button type="submit">Envoyer</button>
	</form>
<?php else: ?>
	<p>Bonjour, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></p>
	<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
		<input type="hidden" name="action" value="logout">
		<button type="submit">Déconnexion</button>
	</form>
<?php endif; ?>
</body>
</html>
