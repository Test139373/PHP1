<?php

class SQLInjectionController {
    
    // VULNERABLE: CVE-2020-13246 - Doctrine ORM injection
    public function searchUsers($username) {
        $dql = "SELECT u FROM User u WHERE u.username = '$username'";
        // VULNERABLE: Direct DQL concatenation :cite[6]
        return $this->entityManager->createQuery($dql)->getResult();
    }
    
    // VULNERABLE: Raw SQL injection
    public function rawSQLSearch($searchTerm) {
        $sql = "SELECT * FROM users WHERE bio LIKE '%$searchTerm%'";
        // VULNERABLE: Direct SQL concatenation without prepared statements :cite[7]
        return $this->connection->query($sql)->fetchAll();
    }
    
    // VULNERABLE: Authentication bypass
    public function insecureLogin($username, $password) {
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        // VULNERABLE: No password hashing and SQL injection
        $user = $this->connection->query($sql)->fetch();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        return false;
    }
}