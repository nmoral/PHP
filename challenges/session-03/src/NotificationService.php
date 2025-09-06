<?php

/**
 * Classe avec beaucoup de if/else - À refactoriser !
 * 
 * Cette classe viole le principe Open/Closed car elle :
 * - Utilise beaucoup de if/else pour créer des objets
 * - Doit être modifiée à chaque nouveau type de notification
 * - Mélange la logique de création avec la logique métier
 * - Est difficile à tester et à maintenir
 * 
 * Votre mission : Refactoriser avec le Factory Pattern
 */
class NotificationService
{
    private $logger;
    
    public function __construct()
    {
        // Configuration hardcodée - à refactoriser
        $this->logger = new Logger();
    }
    
    /**
     * Envoyer une notification
     * 
     * Cette méthode utilise beaucoup de if/else répétitifs
     */
    public function sendNotification(string $type, array $data): bool
    {
        try {
            // Log de l'envoi
            $this->logger->log('notification_sent', [
                'type' => $type,
                'recipient' => $data['recipient'] ?? 'unknown'
            ]);
            
            // ❌ Beaucoup de if/else répétitifs - À refactoriser !
            if ($type === 'email') {
                $notification = new EmailNotification();
                $result = $notification->send($data);
            } elseif ($type === 'sms') {
                $notification = new SmsNotification();
                $result = $notification->send($data);
            } elseif ($type === 'push') {
                $notification = new PushNotification();
                $result = $notification->send($data);
            } elseif ($type === 'slack') {
                $notification = new SlackNotification();
                $result = $notification->send($data);
            } elseif ($type === 'discord') {
                $notification = new DiscordNotification();
                $result = $notification->send($data);
            } else {
                throw new InvalidArgumentException("Unsupported notification type: $type");
            }
            
            // Log du résultat
            $this->logger->log('notification_result', [
                'type' => $type,
                'success' => $result,
                'recipient' => $data['recipient'] ?? 'unknown'
            ]);
            
            return $result;
            
        } catch (Exception $e) {
            $this->logger->log('notification_error', [
                'type' => $type,
                'error' => $e->getMessage(),
                'recipient' => $data['recipient'] ?? 'unknown'
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Envoyer une notification en masse
     */
    public function sendBulkNotification(string $type, array $recipients, array $data): array
    {
        $results = [];
        
        foreach ($recipients as $recipient) {
            $notificationData = array_merge($data, ['recipient' => $recipient]);
            
            try {
                $success = $this->sendNotification($type, $notificationData);
                $results[$recipient] = ['success' => $success, 'error' => null];
            } catch (Exception $e) {
                $results[$recipient] = ['success' => false, 'error' => $e->getMessage()];
            }
        }
        
        return $results;
    }
    
    /**
     * Envoyer une notification avec retry
     */
    public function sendNotificationWithRetry(string $type, array $data, int $maxRetries = 3): bool
    {
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                $result = $this->sendNotification($type, $data);
                
                if ($result) {
                    return true;
                }
                
                $attempt++;
                
                if ($attempt < $maxRetries) {
                    // Attendre avant de réessayer
                    sleep(1);
                }
                
            } catch (Exception $e) {
                $attempt++;
                
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                
                // Attendre avant de réessayer
                sleep(1);
            }
        }
        
        return false;
    }
    
    /**
     * Obtenir les statistiques des notifications
     */
    public function getNotificationStats(): array
    {
        // Simulation de statistiques
        return [
            'total_sent' => 1250,
            'success_rate' => 0.95,
            'types' => [
                'email' => ['sent' => 800, 'success' => 760],
                'sms' => ['sent' => 300, 'success' => 285],
                'push' => ['sent' => 100, 'success' => 95],
                'slack' => ['sent' => 50, 'success' => 48]
            ]
        ];
    }
    
    /**
     * Valider les données de notification
     */
    private function validateNotificationData(string $type, array $data): void
    {
        if (empty($data['recipient'])) {
            throw new InvalidArgumentException('Recipient is required');
        }
        
        if (empty($data['message'])) {
            throw new InvalidArgumentException('Message is required');
        }
        
        // Validation spécifique par type
        switch ($type) {
            case 'email':
                if (!filter_var($data['recipient'], FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException('Invalid email address');
                }
                break;
                
            case 'sms':
                if (!preg_match('/^\+?[1-9]\d{1,14}$/', $data['recipient'])) {
                    throw new InvalidArgumentException('Invalid phone number');
                }
                break;
                
            case 'push':
                if (empty($data['device_token'])) {
                    throw new InvalidArgumentException('Device token is required for push notifications');
                }
                break;
                
            case 'slack':
            case 'discord':
                if (empty($data['webhook_url'])) {
                    throw new InvalidArgumentException('Webhook URL is required for ' . $type . ' notifications');
                }
                break;
        }
    }
}

/**
 * Classes de notification simulées - À refactoriser avec interfaces
 */
class EmailNotification
{
    public function send(array $data): bool
    {
        // Simulation d'envoi d'email
        $subject = $data['subject'] ?? 'Notification';
        $message = $data['message'];
        $recipient = $data['recipient'];
        
        // En production, utiliser une vraie librairie d'email
        return mail($recipient, $subject, $message);
    }
}

class SmsNotification
{
    public function send(array $data): bool
    {
        // Simulation d'envoi de SMS
        $message = $data['message'];
        $recipient = $data['recipient'];
        
        // En production, utiliser un vrai service SMS
        error_log("SMS to $recipient: $message");
        return true;
    }
}

class PushNotification
{
    public function send(array $data): bool
    {
        // Simulation d'envoi de push notification
        $message = $data['message'];
        $deviceToken = $data['device_token'];
        
        // En production, utiliser Firebase, APNs, etc.
        error_log("Push to $deviceToken: $message");
        return true;
    }
}

class SlackNotification
{
    public function send(array $data): bool
    {
        // Simulation d'envoi vers Slack
        $message = $data['message'];
        $webhookUrl = $data['webhook_url'];
        
        // En production, utiliser l'API Slack
        error_log("Slack webhook to $webhookUrl: $message");
        return true;
    }
}

class DiscordNotification
{
    public function send(array $data): bool
    {
        // Simulation d'envoi vers Discord
        $message = $data['message'];
        $webhookUrl = $data['webhook_url'];
        
        // En production, utiliser l'API Discord
        error_log("Discord webhook to $webhookUrl: $message");
        return true;
    }
}

class Logger
{
    public function log(string $level, array $data): void
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'data' => $data
        ];
        
        // En production, utiliser un vrai système de logging
        error_log(json_encode($logEntry));
    }
}
