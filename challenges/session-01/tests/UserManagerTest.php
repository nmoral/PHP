<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe UserManager refactorisée
 * 
 * Ces tests doivent passer après la refactorisation
 * Ils guident la séparation des responsabilités
 */
class UserManagerTest extends TestCase
{
    private $userManager;
    private $userValidator;
    private $emailService;
    private $userRepository;
    private $logger;
    private $passwordGenerator;
    
    protected function setUp(): void
    {
        // Mock des dépendances
        $this->userValidator = $this->createMock(UserValidator::class);
        $this->emailService = $this->createMock(EmailService::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->logger = $this->createMock(Logger::class);
        $this->passwordGenerator = $this->createMock(PasswordGenerator::class);
        
        // Injection des dépendances dans UserManager
        $this->userManager = new UserManager(
            $this->userValidator,
            $this->emailService,
            $this->userRepository,
            $this->logger,
            $this->passwordGenerator
        );
    }
    
    /**
     * Test de création d'utilisateur avec succès
     */
    public function testCreateUserSuccess(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        $generatedPassword = 'temp_password_123';
        $userId = 1;
        
        // Mock des comportements attendus
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn(true);
            
        $this->passwordGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($generatedPassword);
            
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($userData, $generatedPassword)
            ->willReturn($userId);
            
        $this->emailService
            ->expects($this->once())
            ->method('sendWelcomeEmail')
            ->with($userData['email'], $userData['name'], $generatedPassword);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('user_created', [
                'user_id' => $userId,
                'email' => $userData['email'],
                'name' => $userData['name']
            ]);
        
        // Act
        $result = $this->userManager->createUser($userData);
        
        // Assert
        $this->assertEquals($userId, $result['id']);
        $this->assertEquals($userData['name'], $result['name']);
        $this->assertEquals($userData['email'], $result['email']);
        $this->assertEquals($generatedPassword, $result['password']);
    }
    
    /**
     * Test de création d'utilisateur avec validation échouée
     */
    public function testCreateUserValidationFailed(): void
    {
        // Arrange
        $userData = [
            'name' => 'J', // Nom trop court
            'email' => 'invalid-email'
        ];
        
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willThrowException(new InvalidArgumentException('Name must be at least 2 characters'));
        
        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name must be at least 2 characters');
        
        $this->userManager->createUser($userData);
    }
    
    /**
     * Test de mise à jour d'utilisateur
     */
    public function testUpdateUserSuccess(): void
    {
        // Arrange
        $userId = 1;
        $userData = [
            'name' => 'John Updated',
            'email' => 'john.updated@example.com'
        ];
        
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn(true);
            
        $this->userRepository
            ->expects($this->once())
            ->method('update')
            ->with($userId, $userData)
            ->willReturn(true);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('user_updated', [
                'user_id' => $userId,
                'email' => $userData['email'],
                'name' => $userData['name']
            ]);
        
        // Act
        $result = $this->userManager->updateUser($userId, $userData);
        
        // Assert
        $this->assertEquals($userId, $result['id']);
        $this->assertEquals($userData['name'], $result['name']);
        $this->assertEquals($userData['email'], $result['email']);
    }
    
    /**
     * Test de suppression d'utilisateur
     */
    public function testDeleteUserSuccess(): void
    {
        // Arrange
        $userId = 1;
        
        $this->userRepository
            ->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn(true);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('user_deleted', ['user_id' => $userId]);
        
        // Act
        $result = $this->userManager->deleteUser($userId);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test de gestion d'erreur lors de la sauvegarde
     */
    public function testCreateUserDatabaseError(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn(true);
            
        $this->passwordGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('temp_password');
            
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willThrowException(new RuntimeException('Database connection failed'));
        
        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database connection failed');
        
        $this->userManager->createUser($userData);
    }
    
    /**
     * Test de gestion d'erreur lors de l'envoi d'email
     */
    public function testCreateUserEmailError(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        $this->userValidator
            ->expects($this->once())
            ->method('validate')
            ->with($userData)
            ->willReturn(true);
            
        $this->passwordGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn('temp_password');
            
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn(1);
            
        $this->emailService
            ->expects($this->once())
            ->method('sendWelcomeEmail')
            ->willThrowException(new RuntimeException('SMTP server unavailable'));
        
        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SMTP server unavailable');
        
        $this->userManager->createUser($userData);
    }
}
