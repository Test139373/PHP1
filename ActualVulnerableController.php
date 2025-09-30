<?php

namespace VulnerableApp\Controller;

use Symfony\Component\Yaml\Yaml;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use GuzzleHttp\Client;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Extension\SandboxExtension;
use Twig\Sandbox\SecurityPolicy;
use Doctrine\ORM\EntityManager;

class ActualVulnerableController
{
    private $entityManager;
    
    public function __construct(EntityManager $entityManager = null)
    {
        $this->entityManager = $entityManager;
    }
    
    // VULNERABLE: CVE-2022-24894 - Symfony YAML deserialization RCE
    public function symfonyYamlUnsafe($yamlInput)
    {
        // VULNERABLE: Using Yaml::parse with object forcing (enables PHP object deserialization)
        return Yaml::parse($yamlInput, Yaml::PARSE_OBJECT | Yaml::PARSE_OBJECT_FORCE);
    }
    
    // VULNERABLE: CVE-2021-21424 - Symfony security component bypass
    public function symfonySecurityBypass($username, $credentials, $roles)
    {
        // VULNERABLE: Using deprecated/unsafe security token creation
        $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
            $username, 
            $credentials, 
            'main', 
            $roles
        );
        return $token;
    }
    
    // VULNERABLE: CVE-2022-31090 - Monolog log path injection
    public function monologPathInjection($logPath, $message)
    {
        $logger = new Logger('vulnerable');
        // VULNERABLE: User-controlled log file path
        $handler = new StreamHandler($logPath);
        $logger->pushHandler($handler);
        $logger->info($message);
        return "Logged to: " . $logPath;
    }
    
    // VULNERABLE: CVE-2021-41075 - Monolog log injection via formatter
    public function monologLogInjection($message)
    {
        $logger = new Logger('vulnerable');
        $handler = new StreamHandler('app.log');
        
        // VULNERABLE: Using LineFormatter without proper sanitization
        $formatter = new LineFormatter(null, null, true, true);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        
        $logger->info($message); // User input can inject log entries
        return "Message logged";
    }
    
    // VULNERABLE: CVE-2022-31091 - Guzzle SSRF with redirects
    public function guzzleSSRF($url)
    {
        $client = new Client();
        // VULNERABLE: Following redirects without validation
        $response = $client->request('GET', $url, [
            'allow_redirects' => [
                'max' => 10,
                'strict' => false,
                'referer' => true,
                'protocols' => ['http', 'https']
            ],
            'verify' => false // VULNERABLE: Disabled SSL verification
        ]);
        return $response->getBody()->getContents();
    }
    
    // VULNERABLE: CVE-2023-29197 - Guzzle header injection
    public function guzzleHeaderInjection($url, $customHeader)
    {
        $client = new Client();
        // VULNERABLE: User-controlled headers
        $response = $client->request('GET', $url, [
            'headers' => [
                'X-Forwarded-For' => $customHeader,
                'User-Agent' => $customHeader
            ]
        ]);
        return $response->getBody()->getContents();
    }
    
    // VULNERABLE: CVE-2022-23614 - Twig sandbox escape
    public function twigSandboxBypass($templateContent)
    {
        $loader = new ArrayLoader(['template' => $templateContent]);
        $twig = new Environment($loader);
        
        // VULNERABLE: Weak security policy allowing dangerous methods
        $policy = new SecurityPolicy(
            ['isset'], // Allowed tags
            [], // Allowed filters  
            [], // Allowed methods
            [], // Allowed properties
            []  // Allowed functions (empty = none, but can be bypassed)
        );
        $twig->addExtension(new SandboxExtension($policy, true));
        
        return $twig->render('template');
    }
    
    // VULNERABLE: CVE-2021-32076 - Twig template injection
    public function twigTemplateInjection($userInput)
    {
        $loader = new ArrayLoader();
        $twig = new Environment($loader);
        
        // VULNERABLE: Creating template from user input
        $template = $twig->createTemplate($userInput);
        return $template->render();
    }
    
    // VULNERABLE: CVE-2020-13246 - Doctrine DQL injection
    public function doctrineDQLInjection($userId)
    {
        if (!$this->entityManager) {
            return "EntityManager not available";
        }
        
        // VULNERABLE: Direct DQL concatenation
        $dql = "SELECT u FROM VulnerableApp\Model\User u WHERE u.id = " . $userId;
        $query = $this->entityManager->createQuery($dql);
        return $query->getResult();
    }
    
    // VULNERABLE: CVE-2021-21434 - Doctrine SQL injection in paginator
    public function doctrinePaginatorInjection($searchTerm)
    {
        if (!$this->entityManager) {
            return "EntityManager not available";
        }
        
        // VULNERABLE: Unsafe DQL for paginator
        $dql = "SELECT u FROM VulnerableApp\Model\User u WHERE u.username LIKE '%" . $searchTerm . "%'";
        $query = $this->entityManager->createQuery($dql);
        
        // VULNERABLE: Using paginator with unsafe query
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        return iterator_to_array($paginator);
    }
}