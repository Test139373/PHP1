<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable Search</title>
</head>
<body>
    <h1>Search Results</h1>
    <!-- VULNERABLE: XSS through unescaped output :cite[7] -->
    <div>You searched for: <?= $_GET['q'] ?? '' ?></div>
    
    <form method="post" action="/upload" enctype="multipart/form-data">
        <!-- VULNERABLE: No CSRF protection :cite[7] -->
        <input type="file" name="file">
        <input type="submit" value="Upload">
    </form>
</body>
</html>