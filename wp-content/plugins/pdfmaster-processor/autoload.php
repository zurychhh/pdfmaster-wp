<?php
/**
 * PSR-4 autoloader for PDFMaster Processor plugin.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

if (! defined('ABSPATH')) {
    exit;
}

spl_autoload_register(static function (string $class): void {
    $prefix = __NAMESPACE__ . '\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relative_class = substr($class, strlen($prefix));
    if ($relative_class === '') {
        return;
    }

    $segments = explode('\\', $relative_class);
    $class_name = array_pop($segments);

    $transform = static function (string $part): string {
        $part = preg_replace('/([a-z])([A-Z])/', '$1-$2', $part);
        $part = str_replace('_', '-', (string) $part);

        return strtolower($part);
    };

    $path = '';
    if ($segments !== []) {
        $path = implode('/', array_map($transform, $segments)) . '/';
    }

    $file = __DIR__ . '/includes/' . $path . 'class-' . $transform($class_name) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
