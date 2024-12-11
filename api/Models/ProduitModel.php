<?php
class ProduitModel
{
        private $db;

        public function __construct($database)
        {
                $this->db = $database;
        }
        public function getAllProduits()
        {
            $sql = "SELECT * FROM `produits` ORDER BY `produits`.`type` DESC;";
            $result = $this->db->query($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        //???
        public function getProduitByType($type){
            $sql = "SELECT * FROM `produits` WHERE `type` = :type;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
            // truc exclusif  A MODIFIER les ids etc...
        public function getProduitsExclusifs(){
            $ids = [4, 8, 7, 14, 17, 18, 13];
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT * FROM `produits` WHERE `id` IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        //a modifier les ids
        public function getProduitsSummerCollection(){
            $ids = [9, 10, 11, 12, 13, 14];
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT * FROM `produits` WHERE `id` IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            foreach ($ids as $index => $id) {
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getProduitsFiltrer($type,$prixMin,$prixMax,$taille,$couleur){
            $sql ="SELECT * FROM produits WHERE type LIKE :type AND (prix BETWEEN :prixMin AND :prixMax) AND couleur LIKE :couleur AND taille LIKE :taille ORDER BY `produits`.`type` DESC;";;
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
            $stmt->bindParam(':prixMin', $prixMin, PDO::PARAM_STR);
            $stmt->bindParam(':prixMax', $prixMax, PDO::PARAM_STR);
            $stmt->bindParam(':taille', $taille, PDO::PARAM_STR);
            $stmt->bindParam(':couleur', $couleur, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
        public function getProduitById($id){
            $sql = "SELECT * FROM `produits` WHERE `id` = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function createProduit($type, $nom, $image, $description, $prix, $couleur)
        {
                $sql = "INSERT INTO produits (type, nom, image, description,prix,taille,couleur) VALUES (:type, :nom, :image, :description, :prix, :taille, :couleur)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':type', $type, PDO::PARAM_STR);
                $stmt->bindValue(':type', $nom, PDO::PARAM_STR);
                $stmt->bindValue(':image', $image, PDO::PARAM_STR);
                $stmt->bindValue(':description', $description, PDO::PARAM_STR);
                $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
                
                $stmt->bindValue(':couleur', $couleur, PDO::PARAM_STR);
                return $stmt->execute();
        }

        public function updateProduit($id, $type, $nom, $image, $description, $prix, $couleur)
        {
            $sql = "UPDATE produits SET type=:type, nom=:nom, image=:image, description= :description, prix = :prix, taille = :taille, couleur = :couleur WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindValue(':image', $image, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
            $stmt->bindValue(':couleur', $couleur, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();

        }

        public function deleteProduit($id)
        {
            $sql = "DELETE FROM produits WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            return $stmt->execute();
        }


        //Partie pour les tailles

    public function getTaille($id){
        $sql = "SELECT * FROM tailles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Partie pour les quantity 

    public function getAllQuantity(){
        $sql = "SELECT * FROM quantity";
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuantity($id, $quantity){
        $sql = "UPDATE quantity SET quantity = :quantity WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        return $stmt->execute();
    }

     //possiblement pas utile mais peut servir un jours
     public function deleteQuantity($id) {
        $sql = "DELETE FROM quantity WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
        
    }

    public function getQuantityByIdProduct($idProduct){
        $sql = "SELECT * FROM quantity WHERE idProduct = :idProduct";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':idProduct', $idProduct, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctColumnValues($table, $column) {
        $sql = "SELECT DISTINCT $column FROM $table ORDER BY $column";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    


}