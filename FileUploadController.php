<?php

class FileUploadController {
    
    // VULNERABLE: Path traversal and unrestricted file upload
    public function downloadFile($filename) {
        $basePath = '/var/www/uploads/';
        // VULNERABLE: No path traversal protection :cite[7]
        return file_get_contents($basePath . $filename);
    }
    
    // VULNERABLE: Unrestricted file upload
    public function uploadFile($file) {
        $uploadDir = '/var/www/uploads/';
        // VULNERABLE: No file type validation :cite[7]
        move_uploaded_file($file['tmp_name'], $uploadDir . $file['name']);
        return "File uploaded successfully";
    }
    
    // VULNERABLE: CVE-2022-31091 - Guzzle SSRF
    public function fetchUrl($url) {
        $client = new \GuzzleHttp\Client();
        // VULNERABLE: No URL validation for SSRF, SSL verification disabled :cite[3]
        $response = $client->request('GET', $url, ['verify' => false]);
        return $response->getBody();
    }
}