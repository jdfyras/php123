<?php
class CaptchaHelper {
    public static function generateCaptcha() {
        // Generate a random string of 6 characters
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $captchaString = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Store the captcha in session
        $_SESSION['captcha'] = $captchaString;
        
        // Create the image
        $image = imagecreatetruecolor(150, 50);
        
        // Colors
        $bg = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, 150, 50, $bg);
        
        // Add noise (dots)
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($image, rand(150, 200), rand(150, 200), rand(150, 200));
            imagesetpixel($image, rand(0, 150), rand(0, 50), $color);
        }
        
        // Add noise (lines)
        for ($i = 0; $i < 4; $i++) {
            $color = imagecolorallocate($image, rand(100, 150), rand(100, 150), rand(100, 150));
            imageline($image, rand(0, 150), rand(0, 50), rand(0, 150), rand(0, 50), $color);
        }
        
        // Use absolute path for font file
        $fontPath = $_SERVER['DOCUMENT_ROOT'] . '/event_management/public/fonts/arial.ttf';
        
        // Verify font file exists
        if (!file_exists($fontPath)) {
            error_log("Font file not found at: " . $fontPath);
            return '';
        }
        
        // Add the text
        imagettftext($image, 20, rand(-10, 10), 30, 35, $textColor, $fontPath, $captchaString);
        
        // Output the image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        
        // Clean up
        imagedestroy($image);
        
        return base64_encode($imageData);
    }
    
    public static function validateCaptcha($userInput) {
        if (!isset($_SESSION['captcha'])) {
            return false;
        }
        
        $result = strtoupper($userInput) === $_SESSION['captcha'];
        unset($_SESSION['captcha']); // Clear the captcha after validation
        return $result;
    }
} 