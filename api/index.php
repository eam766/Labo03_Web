<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");

require_once 'Controllers/AbonnementController.php';
require_once 'Controllers/ProduitController.php';
require_once 'Controllers/UtilisateurController.php';
require_once "Database.php";


$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

//connexion base de données
$db = Database::getInstance();
$conn = $db->getConnection();

$controllerProduit = new ProduitController($conn);
$controllerAbonnement = new AbonnementController($conn);
$controllerUtilisateur = new UtilisateurController($conn);

$segments = explode('/', $uri);

switch ($method | $uri) {
    case ($method == 'GET' && $uri == '/api/produits'):
        $produits = $controllerProduit->getAllProduits();
        echo json_encode($produits);
        break;

    case ($method == 'POST' && $uri == '/api/abonnements'):
        $data = $_POST;
        $result = $controllerAbonnement->createAbonnement($data['courriel']);
        if ($result) {
            echo json_encode(["success" => true, "message" => "Abonnement créé avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la création de l'abonnement"]);
        }
        break;

    case ($method == 'POST' && $uri == '/api/utilisateurs'):
        $data = $_POST;
        $result = $controllerUtilisateur->createUtilisateur($data['nom'], $data['prenom'], $data['password'], $data['courriel']);
        if ($result) {
            echo json_encode(["success" => true, "message" => "Utilisateur créé avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la création de l'utilisateur"]);
        }
        break;

    case ($method == 'PUT' && preg_match('/\/api\/utilisateurs\/[1-9]/', $uri)):
        $id = end($segments);
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        $result = $controllerUtilisateur->updateUtilisateur($id, $data['nom'], $data['prenom'], $data['password'], $data['courriel']);
        if ($result) {
            echo json_encode(["success" => true, "message" => "Les données de l'utilisateur #{$id} ont été mise à jour"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la mise à jour de l'utilisateur"]);
        }
        break;

    case ($method == 'GET' && preg_match('/\/api\/utilisateurs\/[1-9]/', $uri)):  
        $id = end($segments);
        $utilisateur = $controllerUtilisateur->getUtilisateurById($id);
        echo json_encode($utilisateur);
        break;

    default:
        echo "Erreur : Chemin non reconnu ou non pris en charge";
        echo "\nURI: " . $uri;
        echo "\nMETHODE: " . $method;
}