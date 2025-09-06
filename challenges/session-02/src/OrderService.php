<?php

/**
 * Classe avec dépendances hardcodées - À refactoriser !
 * 
 * Cette classe viole le principe d'Inversion of Control car elle :
 * - Instancie directement ses dépendances avec 'new'
 * - Est couplée à des implémentations concrètes
 * - Est difficile à tester (pas de mocks possibles)
 * - Est inflexible (impossible de changer d'implémentation)
 * 
 * Votre mission : Refactoriser avec l'injection de dépendances
 */
class OrderService
{
    private $emailService;
    private $paymentGateway;
    private $database;
    private $logger;
    
    public function __construct()
    {
        // Dépendances hardcodées - À refactoriser !
        $this->emailService = new EmailService();
        $this->paymentGateway = new PaymentGateway();
        $this->database = new Database();
        $this->logger = new Logger();
    }
    
    /**
     * Traiter une commande
     * 
     * Cette méthode utilise des dépendances hardcodées
     */
    public function processOrder(array $orderData): array
    {
        try {
            // 1. Valider les données de la commande
            $this->validateOrderData($orderData);
            
            // 2. Calculer le total
            $total = $this->calculateTotal($orderData['items']);
            
            // 3. Traiter le paiement
            $paymentResult = $this->paymentGateway->processPayment([
                'amount' => $total,
                'card_number' => $orderData['card_number'],
                'expiry_date' => $orderData['expiry_date'],
                'cvv' => $orderData['cvv']
            ]);
            
            if (!$paymentResult['success']) {
                throw new PaymentException('Payment failed: ' . $paymentResult['error']);
            }
            
            // 4. Sauvegarder la commande en base
            $orderId = $this->database->insert('orders', [
                'customer_id' => $orderData['customer_id'],
                'total' => $total,
                'status' => 'paid',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // 5. Sauvegarder les articles de la commande
            foreach ($orderData['items'] as $item) {
                $this->database->insert('order_items', [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
            
            // 6. Envoyer un email de confirmation
            $this->emailService->sendEmail([
                'to' => $orderData['customer_email'],
                'subject' => 'Confirmation de commande #' . $orderId,
                'body' => $this->generateOrderConfirmationEmail($orderId, $orderData, $total)
            ]);
            
            // 7. Logger l'action
            $this->logger->log('order_processed', [
                'order_id' => $orderId,
                'customer_id' => $orderData['customer_id'],
                'total' => $total,
                'payment_id' => $paymentResult['payment_id']
            ]);
            
            return [
                'success' => true,
                'order_id' => $orderId,
                'total' => $total,
                'payment_id' => $paymentResult['payment_id']
            ];
            
        } catch (Exception $e) {
            // Logger l'erreur
            $this->logger->log('order_error', [
                'error' => $e->getMessage(),
                'order_data' => $orderData
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Annuler une commande
     */
    public function cancelOrder(int $orderId, string $reason): bool
    {
        try {
            // 1. Récupérer la commande
            $order = $this->database->select('orders', ['id' => $orderId]);
            
            if (!$order) {
                throw new OrderNotFoundException('Order not found');
            }
            
            // 2. Rembourser le paiement
            $refundResult = $this->paymentGateway->refund([
                'payment_id' => $order['payment_id'],
                'amount' => $order['total']
            ]);
            
            if (!$refundResult['success']) {
                throw new RefundException('Refund failed: ' . $refundResult['error']);
            }
            
            // 3. Mettre à jour le statut
            $this->database->update('orders', 
                ['status' => 'cancelled', 'cancelled_at' => date('Y-m-d H:i:s')],
                ['id' => $orderId]
            );
            
            // 4. Envoyer un email d'annulation
            $this->emailService->sendEmail([
                'to' => $order['customer_email'],
                'subject' => 'Annulation de commande #' . $orderId,
                'body' => $this->generateOrderCancellationEmail($orderId, $reason)
            ]);
            
            // 5. Logger l'action
            $this->logger->log('order_cancelled', [
                'order_id' => $orderId,
                'reason' => $reason,
                'refund_id' => $refundResult['refund_id']
            ]);
            
            return true;
            
        } catch (Exception $e) {
            $this->logger->log('order_cancel_error', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Récupérer les commandes d'un client
     */
    public function getCustomerOrders(int $customerId): array
    {
        // Utilise directement la base de données
        return $this->database->selectAll('orders', 
            ['customer_id' => $customerId],
            ['created_at' => 'DESC']
        );
    }
    
    /**
     * Valider les données de la commande
     */
    private function validateOrderData(array $orderData): void
    {
        if (empty($orderData['customer_id'])) {
            throw new InvalidArgumentException('Customer ID is required');
        }
        
        if (empty($orderData['customer_email'])) {
            throw new InvalidArgumentException('Customer email is required');
        }
        
        if (!filter_var($orderData['customer_email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid customer email');
        }
        
        if (empty($orderData['items']) || !is_array($orderData['items'])) {
            throw new InvalidArgumentException('Order items are required');
        }
        
        if (empty($orderData['card_number'])) {
            throw new InvalidArgumentException('Card number is required');
        }
    }
    
    /**
     * Calculer le total de la commande
     */
    private function calculateTotal(array $items): float
    {
        $total = 0;
        
        foreach ($items as $item) {
            if (!isset($item['quantity']) || !isset($item['price'])) {
                throw new InvalidArgumentException('Item quantity and price are required');
            }
            
            $total += $item['quantity'] * $item['price'];
        }
        
        return $total;
    }
    
    /**
     * Générer le contenu de l'email de confirmation
     */
    private function generateOrderConfirmationEmail(int $orderId, array $orderData, float $total): string
    {
        $email = "Bonjour,\n\n";
        $email .= "Votre commande #{$orderId} a été confirmée.\n\n";
        $email .= "Détails de la commande :\n";
        $email .= "- Total : €" . number_format($total, 2) . "\n";
        $email .= "- Date : " . date('d/m/Y H:i') . "\n\n";
        $email .= "Merci pour votre achat !\n\n";
        $email .= "Cordialement,\nL'équipe";
        
        return $email;
    }
    
    /**
     * Générer le contenu de l'email d'annulation
     */
    private function generateOrderCancellationEmail(int $orderId, string $reason): string
    {
        $email = "Bonjour,\n\n";
        $email .= "Votre commande #{$orderId} a été annulée.\n\n";
        $email .= "Raison : {$reason}\n\n";
        $email .= "Le remboursement sera traité dans les 3-5 jours ouvrés.\n\n";
        $email .= "Cordialement,\nL'équipe";
        
        return $email;
    }
}

/**
 * Exceptions personnalisées
 */
class PaymentException extends Exception {}
class OrderNotFoundException extends Exception {}
class RefundException extends Exception {}
