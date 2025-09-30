<?php

class DeserializationController {
    
    // VULNERABLE: CVE-2022-24894 - Symfony unserialize RCE
    public function unsafeUnserialize($input) {
        // VULNERABLE: Using unserialize with user input :cite[10]
        return unserialize($input);
    }
    
    // VULNERABLE: CVE-2021-21424 - Symfony YAML deserialization
    public function parseYaml($yamlContent) {
        $parser = new \Symfony\Component\Yaml\Parser();
        // VULNERABLE: Unsafe YAML parsing
        return $parser->parse($yamlContent);
    }
    
    // VULNERABLE: Monolog log injection - CVE-2022-31090
    public function logUserInput($userInput) {
        $logger = new \Monolog\Logger('vulnerable');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('app.log'));
        // VULNERABLE: Logging unsanitized user input
        $logger->info("User input: " . $userInput);
    }
}