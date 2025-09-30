<?php

class InsecureCorsMiddleware {
    public function handle($request, $next) {
        $response = $next($request);
        
        // VULNERABLE: Overly permissive CORS :cite[3]
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', '*');
        $response->headers->set('Access-Control-Allow-Headers', '*');
        
        // VULNERABLE: Missing security headers :cite[6]:cite[10]
        // Intentionally NOT setting X-Frame-Options, Content-Security-Policy, etc.
        
        return $response;
    }
}