<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe NotificationService avec Factory Pattern
 * 
 * Ces tests doivent passer après la refactorisation avec Factory Pattern
 * Ils guident l'implémentation du pattern Factory
 */
class NotificationServiceTest extends TestCase
{
    private $notificationService;
    private $notificationFactory;
    private $logger;
    
    protected function setUp(): void
    {
        // Mock des dépendances
        $this->notificationFactory = $this->createMock(NotificationFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        // Injection des dépendances dans NotificationService
        $this->notificationService = new NotificationService(
            $this->notificationFactory,
            $this->logger
        );
    }
    
    /**
     * Test d'envoi d'email avec succès
     */
    public function testSendEmailNotificationSuccess(): void
    {
        // Arrange
        $type = 'email';
        $data = [
            'recipient' => 'user@example.com',
            'message' => 'Test email message',
            'subject' => 'Test Subject'
        ];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        // Mock des comportements attendus
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('notification_sent', [
                'type' => $type,
                'recipient' => $data['recipient']
            ]);
            
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('notification_result', [
                'type' => $type,
                'success' => true,
                'recipient' => $data['recipient']
            ]);
        
        // Act
        $result = $this->notificationService->sendNotification($type, $data);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'envoi de SMS avec succès
     */
    public function testSendSmsNotificationSuccess(): void
    {
        // Arrange
        $type = 'sms';
        $data = [
            'recipient' => '+33123456789',
            'message' => 'Test SMS message'
        ];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);
        
        // Act
        $result = $this->notificationService->sendNotification($type, $data);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'envoi de push notification avec succès
     */
    public function testSendPushNotificationSuccess(): void
    {
        // Arrange
        $type = 'push';
        $data = [
            'recipient' => 'user123',
            'message' => 'Test push message',
            'device_token' => 'device_token_123'
        ];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);
        
        // Act
        $result = $this->notificationService->sendNotification($type, $data);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'envoi de notification Slack
     */
    public function testSendSlackNotificationSuccess(): void
    {
        // Arrange
        $type = 'slack';
        $data = [
            'recipient' => 'channel',
            'message' => 'Test Slack message',
            'webhook_url' => 'https://hooks.slack.com/test'
        ];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);
        
        // Act
        $result = $this->notificationService->sendNotification($type, $data);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'envoi de notification Discord
     */
    public function testSendDiscordNotificationSuccess(): void
    {
        // Arrange
        $type = 'discord';
        $data = [
            'recipient' => 'channel',
            'message' => 'Test Discord message',
            'webhook_url' => 'https://discord.com/api/webhooks/test'
        ];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(true);
        
        // Act
        $result = $this->notificationService->sendNotification($type, $data);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'échec d'envoi de notification
     */
    public function testSendNotificationFailure(): void
    {
        // Arrange
        $type = 'email';
        $data = [
            'recipient' => 'user@example.com',
            'message' => 'Test message'
        ];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->once())
            ->method('send')
            ->with($data)
            ->willReturn(false);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('notification_result', [
                'type' => $type,
                'success' => false,
                'recipient' => $data['recipient']
            ]);
        
        // Act
        $result = $this->notificationService->sendNotification($type, $data);
        
        // Assert
        $this->assertFalse($result);
    }
    
    /**
     * Test d'exception lors de la création de notification
     */
    public function testSendNotificationFactoryException(): void
    {
        // Arrange
        $type = 'unknown_type';
        $data = [
            'recipient' => 'user@example.com',
            'message' => 'Test message'
        ];
        
        $this->notificationFactory
            ->expects($this->once())
            ->method('create')
            ->with($type)
            ->willThrowException(new InvalidArgumentException("Unknown notification type: $type"));
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('notification_error', [
                'type' => $type,
                'error' => "Unknown notification type: $type",
                'recipient' => $data['recipient']
            ]);
        
        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown notification type: $type");
        
        $this->notificationService->sendNotification($type, $data);
    }
    
    /**
     * Test d'envoi en masse de notifications
     */
    public function testSendBulkNotification(): void
    {
        // Arrange
        $type = 'email';
        $recipients = ['user1@example.com', 'user2@example.com'];
        $data = ['message' => 'Bulk message'];
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn(true);
        
        // Act
        $results = $this->notificationService->sendBulkNotification($type, $recipients, $data);
        
        // Assert
        $this->assertCount(2, $results);
        $this->assertTrue($results['user1@example.com']['success']);
        $this->assertTrue($results['user2@example.com']['success']);
        $this->assertNull($results['user1@example.com']['error']);
        $this->assertNull($results['user2@example.com']['error']);
    }
    
    /**
     * Test d'envoi avec retry
     */
    public function testSendNotificationWithRetry(): void
    {
        // Arrange
        $type = 'email';
        $data = [
            'recipient' => 'user@example.com',
            'message' => 'Test message'
        ];
        $maxRetries = 3;
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturnOnConsecutiveCalls(false, true); // Échec puis succès
        
        // Act
        $result = $this->notificationService->sendNotificationWithRetry($type, $data, $maxRetries);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'échec après tous les retry
     */
    public function testSendNotificationWithRetryFailure(): void
    {
        // Arrange
        $type = 'email';
        $data = [
            'recipient' => 'user@example.com',
            'message' => 'Test message'
        ];
        $maxRetries = 2;
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn(false); // Toujours en échec
        
        // Act
        $result = $this->notificationService->sendNotificationWithRetry($type, $data, $maxRetries);
        
        // Assert
        $this->assertFalse($result);
    }
    
    /**
     * Test d'exception lors du retry
     */
    public function testSendNotificationWithRetryException(): void
    {
        // Arrange
        $type = 'email';
        $data = [
            'recipient' => 'user@example.com',
            'message' => 'Test message'
        ];
        $maxRetries = 2;
        
        $mockNotification = $this->createMock(NotificationInterface::class);
        
        $this->notificationFactory
            ->expects($this->exactly(2))
            ->method('create')
            ->with($type)
            ->willReturn($mockNotification);
            
        $mockNotification
            ->expects($this->exactly(2))
            ->method('send')
            ->willThrowException(new RuntimeException('Network error'));
        
        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Network error');
        
        $this->notificationService->sendNotificationWithRetry($type, $data, $maxRetries);
    }
    
    /**
     * Test de récupération des statistiques
     */
    public function testGetNotificationStats(): void
    {
        // Act
        $stats = $this->notificationService->getNotificationStats();
        
        // Assert
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_sent', $stats);
        $this->assertArrayHasKey('success_rate', $stats);
        $this->assertArrayHasKey('types', $stats);
        $this->assertGreaterThan(0, $stats['total_sent']);
        $this->assertGreaterThan(0, $stats['success_rate']);
    }
}
