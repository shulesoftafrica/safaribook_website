<?php

// Renders stage.blade.php to static HTML for each language into marketing/video/.build/
// Run from the project root:  php artisan tinker --execute="include 'marketing/video/render.php';"
$dir = base_path('marketing/video');
@mkdir($dir . '/.build', 0777, true);
foreach (['en', 'sw'] as $lang) {
    file_put_contents("$dir/.build/stage_$lang.html", view()->file("$dir/stage.blade.php", ['lang' => $lang])->render());
}
echo "rendered\n";
