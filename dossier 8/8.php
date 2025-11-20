<?php
session_start();


$dbFile = __DIR__ . DIRECTORY_SEPARATOR . 'users.json';


$users = [];
if (is_readable($dbFile)) {
	$data = file_get_contents($dbFile);
	$decoded = json_decode($data, true);
	if (is_array($decoded)) {
		$users = $decoded;
	}
}

$errors = [];
$messages = [];


if (isset($_GET['deleted'])) {
	$messages[] = 'Compte supprimé.';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	if ($action === 'register') {
		$username = trim($_POST['reg_username'] ?? '');
		$password = trim($_POST['reg_password'] ?? '');

		if ($username === '') {
			$errors[] = 'Le champ username de l\'inscription est vide';
		}
		if ($password === '') {
			$errors[] = 'Le champ password de l\'inscription est vide';
		}


		if (empty($errors)) {
			if (array_key_exists($username, $users)) {
				$errors[] = 'Le username est déjà présent en base';
			} else {

				$users[$username] = password_hash($password, PASSWORD_DEFAULT);
				file_put_contents($dbFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
				$messages[] = 'Inscription réussie. Vous pouvez maintenant vous connecter.';
			}
		}

	} elseif ($action === 'login') {
		$username = trim($_POST['login_username'] ?? '');
		$password = trim($_POST['login_password'] ?? '');

		if ($username === '') {
			$errors[] = 'Le champ username de la connexion est vide';
		}
		if ($password === '') {
			$errors[] = 'Le champ password de la connexion est vide';
		}

		if (empty($errors)) {
			if (!array_key_exists($username, $users)) {
				$errors[] = 'Le username n\'existe pas dans la base de données';
			} else {
				$hash = $users[$username];
				if (!password_verify($password, $hash)) {
					$errors[] = 'Le mot de passe est invalide';
				} else {
					
					$_SESSION['username'] = $username;
					header('Location: ' . $_SERVER['PHP_SELF']);
					exit;
				}
			}
		}
	} elseif ($action === 'delete_user') {
		
		$target = trim($_POST['del_username'] ?? '');
		$current = $_SESSION['username'] ?? '';
		if ($target === '' || $current === '' || $target !== $current) {
			$errors[] = 'Opération non autorisée.';
		} else {
			if (array_key_exists($target, $users)) {
				unset($users[$target]);
				file_put_contents($dbFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
			}
			
			$_SESSION = [];
			if (ini_get('session.use_cookies')) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params['path'], $params['domain'], $params['secure'], $params['httponly']
				);
			}
			session_destroy();
			header('Location: ' . $_SERVER['PHP_SELF'] . '?deleted=1');
			exit;
		}
	} elseif ($action === 'logout') {
		
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
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>TP : Formulaire de login</title>
	<style>
		body { font-family: Arial, sans-serif; max-width: 700px; margin: 20px; }
		form { margin-bottom: 16px; padding: 12px; border: 1px solid #ddd; }
		.errors { color: #b00020; }
		.messages { color: #006600; }
		label { display:block; margin-top:8px; }
	</style>
</head>
<body>
	<h1>TP : Formulaire de login</h1>

	<?php if (!empty($errors)): ?>
		<div class="errors">
			<ul>
				<?php foreach ($errors as $e): ?>
					<li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if (!empty($messages)): ?>
		<div class="messages">
			<ul>
				<?php foreach ($messages as $m): ?>
					<li><?= htmlspecialchars($m, ENT_QUOTES, 'UTF-8') ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<?php if (empty($_SESSION['username'])): ?>
		<h2>Inscription</h2>
		<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
			<input type="hidden" name="action" value="register">
			<label for="reg_username">username</label>
			<input type="text" id="reg_username" name="reg_username">
			<label for="reg_password">password</label>
			<input type="password" id="reg_password" name="reg_password">
			<button type="submit">S'inscrire</button>
		</form>

		<h2>Connexion</h2>
		<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
			<input type="hidden" name="action" value="login">
			<label for="login_username">username</label>
			<input type="text" id="login_username" name="login_username">
			<label for="login_password">password</label>
			<input type="password" id="login_password" name="login_password">
			<button type="submit">Se connecter</button>
		</form>

	<?php else: ?>
		<p>Connecté en tant que <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong></p>
		<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
			<input type="hidden" name="action" value="logout">
			<button type="submit">Déconnexion</button>
		</form>

		<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
			<input type="hidden" name="action" value="delete_user">
			<input type="hidden" name="del_username" value="<?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>">
			<button type="submit" style="color: #fff; background:#b00020; border:none; padding:6px 10px; cursor:pointer;">Supprimer mon compte</button>
		</form>
	<?php endif; ?>

</body>
</html>

