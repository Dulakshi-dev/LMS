<?php

require_once '../../core/logger.php'; 

// Abstract base controller class to provide common utility methods for all controllers
abstract class Controller
{
    // Check if the current HTTP request is a POST request
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Send a JSON response and terminate script execution
    // $data: associative array of data to send
    // $success: boolean indicating success/failure status
    protected function jsonResponse(array $data, bool $success = true): void
    {
        echo json_encode(array_merge(['success' => $success], $data));
        exit;
    }

    // Sanitize a string to prevent XSS and trim extra whitespace
    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value));
    }

    // Get a value from $_POST safely with optional default
    // Sanitizes the value before returning
    protected function getPost(string $key, $default = ''): string
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    // Get a value from $_GET safely with optional default
    // Sanitizes the value before returning
    protected function getGet(string $key, $default = ''): string
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }
}
