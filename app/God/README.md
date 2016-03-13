// Modify&append below to "app/Http/routes.php" file:

$GodPath = __DIR__.'/../God/routes.php';
if (file_exists($GodPath)) {
    include_once $GodPath;
}
