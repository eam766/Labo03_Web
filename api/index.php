<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Méthodes autorisées
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Headers autorisés
header("Content-type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


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
    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/produits'):
        $produits = $controllerProduit->getAllProduits();
        echo json_encode($produits);
        break;
    
    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/produitsExclusifs'):
        $produits = $controllerProduit->getProduitsExclusifs();
        echo json_encode($produits);
        break;
    
    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/produitsSummerCollection'):
        $produits = $controllerProduit->getProduitsSummerCollection();
        echo json_encode($produits);
        break;

    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/produits\/[1-9]/', $uri)):
        $id = end($segments);
        $produit = $controllerProduit->getProduitById($id);
        echo json_encode($produit);
        break;

    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/abonnements'):
        $abonnements = $controllerAbonnement->getAllAbonnements();
        echo json_encode($abonnements);
        break;

    case ($method == 'POST' && $uri == '/Labo3_Web_EA_AV/api/abonnements'):
        $data = json_decode(file_get_contents('php://input'), true);
        $courriel = $controllerAbonnement->createAbonnement($data['courriel']);
        if ($courriel) {
            echo json_encode(["success" => true, "message" => "Abonnement créé avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la création de l'abonnement"]);
        }
        break;

    case ($method == 'POST' && $uri == '/Labo3_Web_EA_AV/api/utilisateurs'):
        $data = $_POST;
        $produit = $controllerUtilisateur->createUtilisateur($data['nom'], $data['prenom'], $data['password'], $data['courriel']);
        if ($produit) {
            echo json_encode(["success" => true, "message" => "Utilisateur créé avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la création de l'utilisateur"]);
        }
        break;

    case ($method == 'PUT' && preg_match('/\/Labo3_Web_EA_AV\/api\/utilisateurs\/[1-9]/', $uri)):
        $id = end($segments);
        parse_str(file_get_contents('php://input'), $_PUT);
        $data = $_PUT;
        $produit = $controllerUtilisateur->updateUtilisateur($id, $data['nom'], $data['prenom'], $data['password'], $data['courriel']);
        if ($produit) {
            echo json_encode(["success" => true, "message" => "Les données de l'utilisateur #{$id} ont été mise à jour"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la mise à jour de l'utilisateur"]);
        }
        break;

    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/utilisateurs\/[1-9]/', $uri)):  
        $id = end($segments);
        $utilisateur = $controllerUtilisateur->getUtilisateurById($id);
        echo json_encode($utilisateur);
        break;

    default:
        echo "Erreur : Chemin non reconnu ou non pris en charge";
        echo "\nURI: " . $uri;
        echo "\nMETHODE: " . $method;
}