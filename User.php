<?php

namespace VulnerableApp\Model;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $username;

    /**
     * @Column(type="string")
     */
    private $password;

    // Getters and setters
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function setUsername($username) { $this->username = $username; }
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
}