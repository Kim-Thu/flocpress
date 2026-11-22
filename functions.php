<?php
declare(strict_types=1);

defined('ABSPATH') || exit;

// 1. Kiểm tra PHP
if (PHP_VERSION_ID < 80200) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-error"><p>FlocPress yêu cầu PHP 8.2+.</p></div>';
    });
    return;
}

// 2. Kiểm tra Autoloader
$autoload = __DIR__ . '/vendor/autoload.php';
if (! is_readable($autoload)) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-error"><p><strong>Lỗi:</strong> Không tìm thấy <code>vendor/autoload.php</code>.<br>Hãy mở Site Shell và chạy: <code>composer install</code></p></div>';
    });
    return;
}

require_once $autoload;

// 3. Kiểm tra từng Class một trước khi gọi
$errors = [];
if (! class_exists('FlocPress\Support\ThemeContext')) {
    $errors[] = 'Class <code>FlocPress\Support\ThemeContext</code> không tồn tại. (Kiểm tra lại tên file hoặc chạy composer dump-autoload)';
}
if (! class_exists('FlocPress\Kernel')) {
    $errors[] = 'Class <code>FlocPress\Kernel</code> không tồn tại. (File app/Kernel.php chưa được tạo)';
}

if (! empty($errors)) {
    add_action('admin_notices', static function () use ($errors): void {
        echo '<div class="notice notice-error"><p><strong>Lỗi FlocPress:</strong></p><ul><li>' . implode('</li><li>', $errors) . '</li></ul></div>';
    });
    return;
}

$theme = wp_get_theme();
$context = new FlocPress\Support\ThemeContext(
    path:       get_stylesheet_directory(),
    uri:        get_stylesheet_directory_uri(),
    version:    (string) $theme->get('Version'),
    textDomain: (string) $theme->get('TextDomain'),
    isDev:      defined('FLOCPRESS_VITE_DEV') && FLOCPRESS_VITE_DEV,
);

FlocPress\Kernel::boot($context);