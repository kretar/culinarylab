<?php
// Create a simple screenshot image with PHP
header('Content-Type: image/png');

// Create the image
$width = 1200;
$height = 900;
$image = imagecreatetruecolor($width, $height);

// Define colors
$background = imagecolorallocate($image, 231, 76, 60); // Red background
$textColor = imagecolorallocate($image, 255, 255, 255); // White text
$accentColor = imagecolorallocate($image, 241, 196, 15); // Yellow accent

// Fill the background
imagefill($image, 0, 0, $background);

// Draw some decoration elements
imagefilledrectangle($image, 0, $height - 200, $width, $height, $accentColor);

// Add the theme name
$font = 5; // Use built-in font
$text = "MASTER CHEF";
$textWidth = imagefontwidth($font) * strlen($text);
$textHeight = imagefontheight($font);
$x = ($width - $textWidth) / 2;
$y = ($height - $textHeight) / 2;

imagestring($image, $font, $x, $y, $text, $textColor);

// Add a subtitle
$subtitle = "WordPress Theme";
$subtitleWidth = imagefontwidth($font - 1) * strlen($subtitle);
$x = ($width - $subtitleWidth) / 2;
$y = $y + $textHeight + 10;

imagestring($image, $font - 1, $x, $y, $subtitle, $textColor);

// Output the image
imagepng($image);
imagedestroy($image);
?>