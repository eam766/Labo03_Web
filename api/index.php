<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Méthodes autorisées
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
$segments = explode('/', $uri);

//connexion base de données
$db = Database::getInstance();
$conn = $db->getConnection();

$controllerProduit = new ProduitController($conn);
$controllerAbonnement = new AbonnementController($conn);
$controllerUtilisateur = new UtilisateurController($conn);

$segments = explode('/', $uri);

switch ($method | $uri) {
    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/produits/', $uri)):
        $type = $_GET['type'] ?? '';
        $couleur = $_GET['couleur'] ?? '';
        $taille = $_GET['taille'] ?? '';
        $prixMin = $_GET['prix_min'] ?? 0;
        $prixMax = $_GET['prix_max'] ?? 1000;

        $produits = $controllerProduit->getProduitsFiltrer($type, $prixMin, $prixMax, $taille, $couleur);
    
        echo json_encode($produits);
        break;
    
    
    
    
    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/produitExclusifs'):
        $produits = $controllerProduit->getProduitsExclusifs();
        echo json_encode($produits);
        break;
    
    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/produitSummerCollection'):
        $produits = $controllerProduit->getProduitsSummerCollection();
        echo json_encode($produits);
        break;

    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/produit\/[1-9]/', $uri)):
        $id = end($segments);
        $utilisateur = $controllerProduit->getProduitById($id);
        echo json_encode($utilisateur);
        break;

    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/quantityProduits\/[1-9]/', $uri)):
        $id = end($segments);
        $quantity = $controllerProduit->getQuantityByIdProduct($id);
        echo json_encode($quantity);
        break;

    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/abonnements'):
        $abonnements = $controllerAbonnement->getAllAbonnements();
        echo json_encode($abonnements);
        break;

    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/abonnements\/(.+)/', $uri)):
        $courriel = urldecode(end($segments));
        $abonnement = $controllerAbonnement->getAbonnement($courriel);

        echo json_encode($abonnement ? [$abonnement] : []);
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
        $data = json_decode(file_get_contents('php://input'), true);
        $utilisateur = $controllerUtilisateur->createUtilisateur($data['nom'], $data['prenom'], $data['password'], $data['courriel']);
        if ($utilisateur) {
            echo json_encode(["success" => true, "message" => "Utilisateur créé avec succès"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la création de l'utilisateur"]);
        }
        break;
    
    case ($method == 'POST' && $uri == '/Labo3_Web_EA_AV/api/login'):
        $data = json_decode(file_get_contents('php://input'), true);

        if(!empty($data['courriel']) && !empty($data['password'])){
            $response = $controllerUtilisateur->login($data['courriel'], $data['password']);
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Courriel et mot de passe requis"]);
        }
        break;

    case ($method == 'PUT' && preg_match('/\/Labo3_Web_EA_AV\/api\/utilisateurs\/[1-9]/', $uri)):
        $id = end($segments);
        $data = json_decode(file_get_contents('php://input'), true);
        $utilisateur = $controllerUtilisateur->updateUtilisateur($id, $data['nom'], $data['prenom'], $data['password'], $data['courriel']);
        if ($utilisateur) {
            echo json_encode(["success" => true, "message" => "Les données de l'utilisateur #{$id} ont été mise à jour"]);
        } else {
            echo json_encode(["success" => false, "message" => "Échec de la mise à jour de l'utilisateur"]);
        }
        break;


    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/utilisateurs\/courriel\/(.+)/', $uri)):
        $courriel = urldecode(end($segments));
        $utilisateur = $controllerUtilisateur->getUtilisateurByCourriel($courriel);

        echo json_encode($utilisateur ? [$utilisateur] : []);
        break;
           

    case ($method == 'GET' && preg_match('/\/Labo3_Web_EA_AV\/api\/utilisateurs\/[1-9]/', $uri)):  
        $id = end($segments);
        $utilisateur = $controllerUtilisateur->getUtilisateurById($id);
        
        echo json_encode($utilisateur);
        break;

    case ($method == 'GET' && $uri == '/Labo3_Web_EA_AV/api/utilisateurs'):
        $utilisateurs = $controllerUtilisateur->getAllUtilisateurs();
        echo json_encode($utilisateurs);
        break;  
        
    case ($method == 'GET' && preg_match('/\/api\/distinct\/(.+)\/(.+)/', $uri)):
        $table = urldecode($segments[count($segments) - 2]);
        $column = urldecode(end($segments));
        $distinctValues = $controllerProduit->getDistinctValues($table, $column);
        echo json_encode($distinctValues);
        break;

    default:
        echo "Erreur : Chemin non reconnu ou non pris en charge";
        echo "\nURI: " . $uri;
        echo "\nMETHODE: " . $method;
}