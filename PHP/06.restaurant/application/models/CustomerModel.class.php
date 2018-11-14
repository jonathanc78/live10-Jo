<?php


class CustomerModel {

    function create($firstName, $lastName, $phone, $email, $password, $address, $city, $zipCode) {

        if ($this->userExists($email))
            throw new DomainException("Cet email à déjà été utilisé");

        $sql = "INSERT INTO customers (FirstName, LastName, Phone, Email, Password, Address, City, ZipCode, RegisterDate) 
                VALUES (?,?,?,?,?,?,?,?,NOW())";

        $db = new Database();

        // cryptage du mot de passe
        $password = $this->hashPassword($password);

        // grace à PDO->lastInsertId(), la fonction me retourne l'id de la dernière requête effectué,
        // on peut ainsi connaitre l'ID du nouvel utilisateur et le renvoyer pour créer la session
        $customerId = $db->executeSql($sql, [$firstName, $lastName, $phone, $email, $password, $address, $city, $zipCode]);

        return $customerId;
    }

    function userExists($email) {
        $db = new Database();
        $customer = $db->queryOne("SELECT Id FROM customers WHERE Email = ?", [$email]);

        // si queryOne ne renvoi pas false alors l'utilisateur existe
        return $customer != false;
    }

    function hashPassword($password) {
        /*
               * Génération du sel, nécessite l'extension PHP OpenSSL pour fonctionner.
               *
               * openssl_random_pseudo_bytes() va renvoyer n'importe quel type de caractères.
               * Or le chiffrement en blowfish nécessite un sel avec uniquement les caractères
               * a-z, A-Z ou 0-9.
               *
               * On utilise donc bin2hex() pour convertir en une chaîne hexadécimale le résultat,
               * qu'on tronque ensuite à 22 caractères pour être sûr d'obtenir la taille
               * nécessaire pour construire le sel du chiffrement en blowfish.
               */
        $salt = '$2y$11$' . substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);


        return crypt($password, $salt);
    }

    function login($email, $password) {

        $sql = "SELECT Id, FirstName,LastName, Password FROM customers WHERE Email = ?";
        $db = new Database();
        $customer = $db->queryOne($sql, [$email]);

        // si l'email à été trouvé dans la base ou que le mot de passe est différent
        if (count($customer) == 0 OR !$this->verifyPassword($password, $customer['Password']))
            throw new DomainException('Mauvais login ou mot de passe');

        return $customer;
    }

    private function verifyPassword($password, $hashedPassword) {
        // Si le mot de passe en clair est le même que la version hachée alors renvoie true.
        return crypt($password, $hashedPassword) == $hashedPassword;
    }

    public function getUser ($id){
        $db = new Database();
        $sql = "SELECT FirstName, LastName, Address, Phone, City, ZipCode FROM customers WHERE Id = ?";
        $customer = $db->queryOne($sql, [$id]);

        return $customer;
    }

    public function updateUser($firstName, $lastName, $address, $phone,  $city, $zipCode, $id) {
        $db = new Database;
        $sql = "UPDATE customers 
                SET     FirstName = ?, 
                        LastName = ?, 
                        Address = ?, 
                        Phone = ?, 
                        City = ?, 
                        ZipCode = ?
            WHERE Id = ?";
        $db->executeSql($sql, [$firstName, $lastName, $address, $phone, $city, $zipCode, $id]);
    }
}
