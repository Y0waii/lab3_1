<?php
namespace App;

class User {
    public static function register($login, $password, $bg_color = '#ffffff', $font_color = '#000000') {
        $pdo = Database::getConnection();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (login, password, bg_color, font_color) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$login, $hashed, $bg_color, $font_color]);
    }
    
    public static function login($login, $password) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'login' => $user['login'],
                'bg_color' => $user['bg_color'],
                'font_color' => $user['font_color']
            ];
        }
        return false;
    }
    
    public static function getUserSettings($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT bg_color, font_color FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: ['bg_color' => '#ffffff', 'font_color' => '#000000'];
    }
}
