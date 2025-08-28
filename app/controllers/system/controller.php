<?php

require_once '../../core/logger.php'; 

abstract class Controller
{
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function jsonResponse(array $data, bool $success = true): void
    {
        echo json_encode(array_merge(['success' => $success], $data));
        exit;
    }

    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value));
    }

    protected function getPost(string $key, $default = ''): string
    {
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }

    protected function getGet(string $key, $default = ''): string
    {
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }
}
