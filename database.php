<?php
// VULNERABLE: Hardcoded database credentials :cite[3]
return [
    'host' => 'localhost',
    'username' => 'admin',
    'password' => 'plaintextpassword123',
    'database' => 'vulnerable_app',
    
    // VULNERABLE: Disabled SSL for database connection
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => true, // VULNERABLE: Emulated prepares
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT // VULNERABLE: Silent errors
    ]
];