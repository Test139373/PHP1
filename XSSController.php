<?php

class XSSController {
    
    // VULNERABLE: CVE-2022-23614 - Twig SSTI
    public function renderTemplate($templateContent) {
        $loader = new \Twig\Loader\ArrayLoader([
            'template' => $templateContent
        ]);
        $twig = new \Twig\Environment($loader);
        // VULNERABLE: User-controlled template rendering :cite[7]
        return $twig->render('template', ['user_input' => $_GET['input']]);
    }
    
    // VULNERABLE: Reflected XSS
    public function search($query) {
        // VULNERABLE: No output encoding :cite[6]:cite[7]
        echo "Search results for: " . $query;
    }
    
    // VULNERABLE: Stored XSS
    public function saveComment($comment) {
        // VULNERABLE: Storing unsanitized user input
        file_put_contents('comments.txt', $comment . PHP_EOL, FILE_APPEND);
        return "Comment saved";
    }
}