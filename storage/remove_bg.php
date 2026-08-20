<?php
$rawPath = __DIR__ . '/../public/images/logo-bpbd-raw.png';
$outputPath = __DIR__ . '/../public/images/logo-bpbd.png';

$url = 'https://bpbd.probolinggokab.go.id/wp-content/uploads/2021/04/logo-bpbd.png';
if (!is_dir(dirname($rawPath))) {
    mkdir(dirname($rawPath), 0777, true);
}

$ctx = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
    "http" => [
        "user_agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    ]
]);
$imageData = @file_get_contents($url, false, $ctx);
if ($imageData) {
    file_put_contents($rawPath, $imageData);
}

if (!file_exists($rawPath)) {
    echo "Raw image not found.\n";
    exit(1);
}

$src = @imagecreatefromstring(file_get_contents($rawPath));
if (!$src) {
    echo "Failed to load image.\n";
    exit(1);
}

$w = imagesx($src);
$h = imagesy($src);

// Create truecolor image with alpha channel
$dst = imagecreatetruecolor($w, $h);
imagealphablending($dst, false);
imagesavealpha($dst, true);

$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
imagefill($dst, 0, 0, $transparent);

// Convert white/near-white pixels to transparent OR crop strictly to circle
$cx = $w / 2;
$cy = $h / 2;
$radius = min($w, $h) / 2 - 2; // radius of inner logo circle

for ($x = 0; $x < $w; $x++) {
    for ($y = 0; $y < $h; $y++) {
        $dist = sqrt(pow($x - $cx, 2) + pow($y - $cy, 2));
        $rgb = imagecolorat($src, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        // If pixel is outside circular logo boundary OR is pure white/near white background
        if ($dist > $radius || ($r > 235 && $g > 235 && $b > 235 && $dist > ($radius - 10))) {
            imagesetpixel($dst, $x, $y, $transparent);
        } else {
            imagesetpixel($dst, $x, $y, $rgb);
        }
    }
}

imagepng($dst, $outputPath);
echo "SUCCESS_TRANSPARENT_LOGO_CREATED\n";
