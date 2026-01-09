<?php
session_start();

// --- Configuration BDD ---
const DB_HOST = 'localhost';
const DB_NAME = 'cv_generator_db';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
try { $pdo = new PDO($dsn, DB_USER, DB_PASS, $options); } 
catch (PDOException $e) { die("Erreur BDD : " . htmlspecialchars($e->getMessage())); }

// --- CSRF token ---
if(!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// --- Déconnexion ---
if(isset($_GET['logout'])) { session_destroy(); header('Location: '.$_SERVER['PHP_SELF']); exit; }

// --- Connexion ---
$login_msg = '';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['connexion'])){
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        $login_msg='<div class="alert alert-danger">Erreur CSRF.</div>';
    } else {
        $email=trim($_POST['email']??'');
        $pass=$_POST['mot_de_passe']??'';
        if(!$email||!$pass) $login_msg='<div class="alert alert-danger">Tous les champs sont obligatoires.</div>';
        else{
            $stmt=$pdo->prepare("SELECT id, nom, prenom, mot_de_passe, role FROM utilisateurs WHERE email=?");
            $stmt->execute([$email]);
            $user=$stmt->fetch();
            if($user && password_verify($pass, $user['mot_de_passe'])){
                $_SESSION['utilisateur_id']=$user['id'];
                $_SESSION['nom_complet']=$user['prenom'].' '.$user['nom'];
                $_SESSION['role']=$user['role'];
                header('Location: '.$_SERVER['PHP_SELF']);
                exit;
            } else $login_msg='<div class="alert alert-danger">Identifiants incorrects.</div>';
        }
    }
}

// --- Génération CV ---
$cv_msg=''; $cv=null;
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['generer_cv']) && isset($_SESSION['utilisateur_id'])){
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        $cv_msg='<div class="alert alert-danger">Erreur CSRF.</div>';
    } else {
        $nom=trim($_POST['nom']??''); $prenom=trim($_POST['prenom']??''); $email=trim($_POST['email']??'');
        $telephone=trim($_POST['telephone']??''); $titre=trim($_POST['titre']??'');
        $experience=trim($_POST['experience']??''); $competences=trim($_POST['competences']??'');
        $formation=trim($_POST['formation']??'');
        if(!$nom||!$prenom||!$email||!$telephone||!$titre){
            $cv_msg='<div class="alert alert-danger">Veuillez remplir tous les champs obligatoires.</div>';
        } elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $cv_msg='<div class="alert alert-danger">Email invalide.</div>';
        } else {
            $stmt=$pdo->prepare("INSERT INTO cvs (utilisateur_id, titre, poste, profil, telephone, adresse) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$_SESSION['utilisateur_id'],$titre,'',$experience,$telephone,'']);
            $cv_msg='<div class="alert alert-success">CV créé avec succès !</div>';
            $cv=compact('nom','prenom','email','telephone','titre','experience','competences','formation');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Générateur de CV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body{background:#f8f9fa; font-family:Arial,sans-serif;}
.container{max-width:900px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
input,textarea{width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:4px;}
button{padding:12px 20px; font-size:16px; border:none; border-radius:4px; cursor:pointer; transition:0.3s;}
.btn-primary{background:#007bff; color:#fff;}
.btn-primary:hover{background:#0056b3;}
.btn-success{background:#28a745; color:#fff;}
.btn-success:hover{background:#1e7e34;}
fieldset{border:1px solid #28a745; padding:15px; margin-bottom:20px; border-radius:4px;}
legend{color:#28a745; font-weight:bold;}
.cv-container{margin-top:30px; border:1px solid #333; padding:20px; background:#fff; border-radius:8px;}
.cv-header{text-align:center;margin-bottom:20px;}
.section{margin-bottom:15px;}
.section h3{margin-bottom:5px;border-bottom:2px solid #28a745;padding-bottom:3px;color:#28a745;}
.text-danger{color:red;font-weight:bold;}
.d-flex{display:flex;gap:10px;}
.flex-fill{flex:1;}
</style>
</head>
<body>
<div class="container">
<?php if(!isset($_SESSION['utilisateur_id'])): ?>
<h2>Connexion</h2>
<?php echo $login_msg; ?>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
<input type="hidden" name="connexion" value="1">
<label>Email <span class="text-danger">*</span></label>
<input type="email" name="email" required>
<label>Mot de passe <span class="text-danger">*</span></label>
<input type="password" name="mot_de_passe" required>
<div class="d-flex">
<button type="submit" class="btn btn-primary flex-fill">Se connecter</button>
<button type="reset" class="btn btn-secondary">Réinitialiser</button>
</div>
<p>Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
</form>
<?php else: ?>
<div class="d-flex justify-content-end"><a href="?logout=1">Déconnexion</a></div>
<h2>Bienvenue <?php echo $_SESSION['nom_complet']; ?></h2>
<fieldset>
<legend>Générateur de CV</legend>
<?php echo $cv_msg; ?>
<form method="POST">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
<input type="hidden" name="generer_cv" value="1">
<label>Nom <span class="text-danger">*</span></label>
<input type="text" name="nom" required>
<label>Prénom <span class="text-danger">*</span></label>
<input type="text" name="prenom" required>
<label>Email <span class="text-danger">*</span></label>
<input type="email" name="email" required>
<label>Téléphone <span class="text-danger">*</span></label>
<input type="tel" name="telephone" required>
<label>Titre du CV / Poste recherché <span class="text-danger">*</span></label>
<input type="text" name="titre" required>
<label>Expériences professionnelles</label>
<textarea name="experience" rows="4"></textarea>
<label>Compétences</label>
<textarea name="competences" rows="3"></textarea>
<label>Formation</label>
<textarea name="formation" rows="3"></textarea>
<div class="d-flex">
<button type="submit" class="btn btn-success flex-fill">Générer le CV</button>
<button type="reset" class="btn btn-secondary">Réinitialiser</button>
</div>
</form>
</fieldset>

<?php if($cv): ?>
<div class="cv-container">
<div class="cv-header">
<h1><?php echo htmlspecialchars($cv['prenom'].' '.$cv['nom']); ?></h1>
<p><?php echo htmlspecialchars($cv['titre']); ?></p>
<p><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($cv['email']); ?> | <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($cv['telephone']); ?></p>
</div>
<?php if(!empty($cv['experience'])): ?>
<div class="section">
<h3>Expériences professionnelles</h3>
<p><?php echo nl2br(htmlspecialchars($cv['experience'])); ?></p>
</div>
<?php endif; ?>
<?php if(!empty($cv['competences'])): ?>
<div class="section">
<h3>Compétences</h3>
<p><?php echo nl2br(htmlspecialchars($cv['competences'])); ?></p>
</div>
<?php endif; ?>
<?php if(!empty($cv['formation'])): ?>
<div class="section">
<h3>Formation</h3>
<p><?php echo nl2br(htmlspecialchars($cv['formation'])); ?></p>
</div>
<?php endif; ?>
</div>
<?php endif; ?>

<?php endif; ?>
</div>
</body>
</html>


