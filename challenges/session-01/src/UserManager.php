<?php

/**
 * Classe "God Class" - Fait trop de choses !
 * 
 * Cette classe viole le Single Responsibility Principle car elle :
 * - Valide les données utilisateur
 * - Envoie des emails
 * - Gère la base de données
 * - Log les actions
 * - Génère des mots de passe
 * 
 * Votre mission : Refactoriser cette classe en respectant le SRP
 */
class UserManager
{
    private $database;
    private $emailConfig;
    
    public function __construct()
    {
        // Configuration hardcodée - à refactoriser
        $this->database = new PDO('sqlite::memory:');
        $this->emailConfig = [
            'smtp_host' => 'localhost',
            'smtp_port' => 587,
            'smtp_user' => 'admin@example.com',
            'smtp_pass' => 'password'
        ];
    }
    
    /**
     * Créer un nouvel utilisateur
     * 
     * Cette méthode fait trop de choses :
     * 1. Valide les données
     * 2. Génère un mot de passe
     * 3. Sauvegarde en base
     * 4. Envoie un email
     * 5. Log l'action
     */
    public function createUser(array $userData): array
    {
        // 1. Validation des données (responsabilité 1)
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Email is required');
        }
        
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        
        if (empty($userData['name'])) {
            throw new InvalidArgumentException('Name is required');
        }
        
        if (strlen($userData['name']) < 2) {
            throw new InvalidArgumentException('Name must be at least 2 characters');
        }
        
        // 2. Génération du mot de passe (responsabilité 2)
        $password = $this->generatePassword();
        
        // 3. Sauvegarde en base de données (responsabilité 3)
        $stmt = $this->database->prepare("
            INSERT INTO users (name, email, password, created_at) 
            VALUES (?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $userData['name'],
            $userData['email'],
            password_hash($password, PASSWORD_DEFAULT),
            date('Y-m-d H:i:s')
        ]);
        
        if (!$result) {
            throw new RuntimeException('Failed to save user to database');
        }
        
        $userId = $this->database->lastInsertId();
        
        // 4. Envoi d'email (responsabilité 4)
        $this->sendWelcomeEmail($userData['email'], $userData['name'], $password);
        
        // 5. Logging (responsabilité 5)
        $this->logAction('user_created', [
            'user_id' => $userId,
            'email' => $userData['email'],
            'name' => $userData['name']
        ]);
        
        return [
            'id' => $userId,
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $password
        ];
    }
    
    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(int $userId, array $userData): array
    {
        // Validation
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Email is required');
        }
        
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        
        if (empty($userData['name'])) {
            throw new InvalidArgumentException('Name is required');
        }
        
        // Mise à jour en base
        $stmt = $this->database->prepare("
            UPDATE users 
            SET name = ?, email = ?, updated_at = ? 
            WHERE id = ?
        ");
        
        $result = $stmt->execute([
            $userData['name'],
            $userData['email'],
            date('Y-m-d H:i:s'),
            $userId
        ]);
        
        if (!$result) {
            throw new RuntimeException('Failed to update user');
        }
        
        // Logging
        $this->logAction('user_updated', [
            'user_id' => $userId,
            'email' => $userData['email'],
            'name' => $userData['name']
        ]);
        
        return [
            'id' => $userId,
            'name' => $userData['name'],
            'email' => $userData['email']
        ];
    }
    
    /**
     * Supprimer un utilisateur
     */
    public function deleteUser(int $userId): bool
    {
        // Suppression en base
        $stmt = $this->database->prepare("DELETE FROM users WHERE id = ?");
        $result = $stmt->execute([$userId]);
        
        if (!$result) {
            throw new RuntimeException('Failed to delete user');
        }
        
        // Logging
        $this->logAction('user_deleted', ['user_id' => $userId]);
        
        return true;
    }
    
    /**
     * Génération de mot de passe (responsabilité séparée)
     */
    private function generatePassword(): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < 12; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }
    
    /**
     * Envoi d'email de bienvenue (responsabilité séparée)
     */
    private function sendWelcomeEmail(string $email, string $name, string $password): void
    {
        $subject = 'Bienvenue sur notre plateforme !';
        $message = "Bonjour $name,\n\n";
        $message .= "Votre compte a été créé avec succès.\n";
        $message .= "Votre mot de passe temporaire est : $password\n\n";
        $message .= "Merci de vous connecter et de changer votre mot de passe.\n\n";
        $message .= "Cordialement,\nL'équipe";
        
        // Simulation d'envoi d'email
        $headers = "From: {$this->emailConfig['smtp_user']}\r\n";
        $headers .= "Reply-To: {$this->emailConfig['smtp_user']}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // En production, utiliser une vraie librairie d'email
        mail($email, $subject, $message, $headers);
    }
    
    /**
     * Logging des actions (responsabilité séparée)
     */
    private function logAction(string $action, array $data): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'data' => $data
        ];
        
        // En production, utiliser un vrai système de logging
        error_log(json_encode($logEntry));
    }
}
