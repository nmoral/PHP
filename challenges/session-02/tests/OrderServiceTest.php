<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests pour la classe OrderService avec injection de dépendances
 * 
 * Ces tests doivent passer après la refactorisation avec DI
 * Ils guident l'implémentation de l'injection de dépendances
 */
class OrderServiceTest extends TestCase
{
    private $orderService;
    private $emailService;
    private $paymentGateway;
    private $database;
    private $logger;
    
    protected function setUp(): void
    {
        // Mock des dépendances
        $this->emailService = $this->createMock(EmailServiceInterface::class);
        $this->paymentGateway = $this->createMock(PaymentGatewayInterface::class);
        $this->database = $this->createMock(DatabaseInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        // Injection des dépendances dans OrderService
        $this->orderService = new OrderService(
            $this->emailService,
            $this->paymentGateway,
            $this->database,
            $this->logger
        );
    }
    
    /**
     * Test de traitement de commande avec succès
     */
    public function testProcessOrderSuccess(): void
    {
        // Arrange
        $orderData = [
            'customer_id' => 123,
            'customer_email' => 'customer@example.com',
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'items' => [
                ['product_id' => 1, 'quantity' => 2, 'price' => 10.50],
                ['product_id' => 2, 'quantity' => 1, 'price' => 25.00]
            ]
        ];
        
        $expectedTotal = 46.00; // (2 * 10.50) + (1 * 25.00)
        $orderId = 456;
        $paymentId = 'pay_789';
        
        // Mock des comportements attendus
        $this->paymentGateway
            ->expects($this->once())
            ->method('processPayment')
            ->with([
                'amount' => $expectedTotal,
                'card_number' => $orderData['card_number'],
                'expiry_date' => $orderData['expiry_date'],
                'cvv' => $orderData['cvv']
            ])
            ->willReturn(['success' => true, 'payment_id' => $paymentId]);
            
        $this->database
            ->expects($this->once())
            ->method('insert')
            ->with('orders', [
                'customer_id' => $orderData['customer_id'],
                'total' => $expectedTotal,
                'status' => 'paid',
                'created_at' => $this->isType('string')
            ])
            ->willReturn($orderId);
            
        $this->database
            ->expects($this->exactly(2))
            ->method('insert')
            ->with('order_items', $this->isType('array'));
            
        $this->emailService
            ->expects($this->once())
            ->method('sendEmail')
            ->with($this->callback(function($emailData) use ($orderId) {
                return $emailData['to'] === 'customer@example.com' 
                    && strpos($emailData['subject'], "Confirmation de commande #{$orderId}") !== false;
            }));
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('order_processed', [
                'order_id' => $orderId,
                'customer_id' => $orderData['customer_id'],
                'total' => $expectedTotal,
                'payment_id' => $paymentId
            ]);
        
        // Act
        $result = $this->orderService->processOrder($orderData);
        
        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($orderId, $result['order_id']);
        $this->assertEquals($expectedTotal, $result['total']);
        $this->assertEquals($paymentId, $result['payment_id']);
    }
    
    /**
     * Test d'échec de paiement
     */
    public function testProcessOrderPaymentFailed(): void
    {
        // Arrange
        $orderData = [
            'customer_id' => 123,
            'customer_email' => 'customer@example.com',
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'items' => [['product_id' => 1, 'quantity' => 1, 'price' => 10.00]]
        ];
        
        $this->paymentGateway
            ->expects($this->once())
            ->method('processPayment')
            ->willReturn(['success' => false, 'error' => 'Insufficient funds']);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('order_error', $this->isType('array'));
        
        // Act & Assert
        $this->expectException(PaymentException::class);
        $this->expectExceptionMessage('Payment failed: Insufficient funds');
        
        $this->orderService->processOrder($orderData);
    }
    
    /**
     * Test d'annulation de commande avec succès
     */
    public function testCancelOrderSuccess(): void
    {
        // Arrange
        $orderId = 456;
        $reason = 'Customer request';
        $order = [
            'id' => $orderId,
            'customer_id' => 123,
            'total' => 50.00,
            'payment_id' => 'pay_789',
            'customer_email' => 'customer@example.com'
        ];
        $refundId = 'ref_123';
        
        $this->database
            ->expects($this->once())
            ->method('select')
            ->with('orders', ['id' => $orderId])
            ->willReturn($order);
            
        $this->paymentGateway
            ->expects($this->once())
            ->method('refund')
            ->with([
                'payment_id' => $order['payment_id'],
                'amount' => $order['total']
            ])
            ->willReturn(['success' => true, 'refund_id' => $refundId]);
            
        $this->database
            ->expects($this->once())
            ->method('update')
            ->with('orders', 
                ['status' => 'cancelled', 'cancelled_at' => $this->isType('string')],
                ['id' => $orderId]
            );
            
        $this->emailService
            ->expects($this->once())
            ->method('sendEmail')
            ->with($this->callback(function($emailData) use ($orderId) {
                return strpos($emailData['subject'], "Annulation de commande #{$orderId}") !== false;
            }));
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('order_cancelled', [
                'order_id' => $orderId,
                'reason' => $reason,
                'refund_id' => $refundId
            ]);
        
        // Act
        $result = $this->orderService->cancelOrder($orderId, $reason);
        
        // Assert
        $this->assertTrue($result);
    }
    
    /**
     * Test d'annulation de commande inexistante
     */
    public function testCancelOrderNotFound(): void
    {
        // Arrange
        $orderId = 999;
        
        $this->database
            ->expects($this->once())
            ->method('select')
            ->with('orders', ['id' => $orderId])
            ->willReturn(null);
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('order_cancel_error', $this->isType('array'));
        
        // Act & Assert
        $this->expectException(OrderNotFoundException::class);
        $this->expectExceptionMessage('Order not found');
        
        $this->orderService->cancelOrder($orderId, 'Test reason');
    }
    
    /**
     * Test de récupération des commandes client
     */
    public function testGetCustomerOrders(): void
    {
        // Arrange
        $customerId = 123;
        $expectedOrders = [
            ['id' => 1, 'customer_id' => $customerId, 'total' => 25.00],
            ['id' => 2, 'customer_id' => $customerId, 'total' => 50.00]
        ];
        
        $this->database
            ->expects($this->once())
            ->method('selectAll')
            ->with('orders', 
                ['customer_id' => $customerId],
                ['created_at' => 'DESC']
            )
            ->willReturn($expectedOrders);
        
        // Act
        $result = $this->orderService->getCustomerOrders($customerId);
        
        // Assert
        $this->assertEquals($expectedOrders, $result);
    }
    
    /**
     * Test de validation des données de commande
     */
    public function testProcessOrderInvalidData(): void
    {
        // Arrange
        $invalidOrderData = [
            'customer_id' => 123,
            // customer_email manquant
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'items' => [['product_id' => 1, 'quantity' => 1, 'price' => 10.00]]
        ];
        
        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Customer email is required');
        
        $this->orderService->processOrder($invalidOrderData);
    }
    
    /**
     * Test de gestion d'erreur de base de données
     */
    public function testProcessOrderDatabaseError(): void
    {
        // Arrange
        $orderData = [
            'customer_id' => 123,
            'customer_email' => 'customer@example.com',
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'items' => [['product_id' => 1, 'quantity' => 1, 'price' => 10.00]]
        ];
        
        $this->paymentGateway
            ->expects($this->once())
            ->method('processPayment')
            ->willReturn(['success' => true, 'payment_id' => 'pay_123']);
            
        $this->database
            ->expects($this->once())
            ->method('insert')
            ->willThrowException(new RuntimeException('Database connection failed'));
            
        $this->logger
            ->expects($this->once())
            ->method('log')
            ->with('order_error', $this->isType('array'));
        
        // Act & Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database connection failed');
        
        $this->orderService->processOrder($orderData);
    }
}
