<?php

/**
 * Service de calcul de prix avec différents types de remises
 * 
 * ❌ PROBLÈME : Cette classe viole le principe Ouvert/Fermé
 * - Utilise des if/else pour gérer différents types de remises
 * - Difficile à étendre avec de nouveaux types de remises
 * - Responsabilité multiple : connaît tous les types de remises
 * - Difficile à tester : tous les cas dans une seule méthode
 * 
 * 🎯 OBJECTIF : Refactoriser avec le Strategy Pattern
 * - Créer une interface PricingStrategyInterface
 * - Implémenter des stratégies concrètes pour chaque type de remise
 * - Le service délègue le calcul à la stratégie injectée
 * - Respecter le principe Ouvert/Fermé
 */
class PricingService
{
    private Logger $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Calcule le prix avec différents types de remises
     * 
     * @param float $basePrice Prix de base
     * @param string $discountType Type de remise (percentage, fixed, volume, bulk, seasonal)
     * @param float $discountValue Valeur de la remise
     * @param int $quantity Quantité
     * @param array $parameters Paramètres supplémentaires
     * @return float Prix final
     */
    public function calculatePrice(
        float $basePrice, 
        string $discountType, 
        float $discountValue, 
        int $quantity = 1, 
        array $parameters = []
    ): float {
        $this->logger->info("Calcul du prix", [
            'base_price' => $basePrice,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'quantity' => $quantity,
            'parameters' => $parameters
        ]);
        
        // ❌ PROBLÈME : Logique conditionnelle complexe
        if ($discountType === 'percentage') {
            $finalPrice = $this->calculatePercentageDiscount($basePrice, $discountValue, $quantity);
        } elseif ($discountType === 'fixed') {
            $finalPrice = $this->calculateFixedDiscount($basePrice, $discountValue, $quantity);
        } elseif ($discountType === 'volume') {
            $finalPrice = $this->calculateVolumeDiscount($basePrice, $quantity, $parameters);
        } elseif ($discountType === 'bulk') {
            $finalPrice = $this->calculateBulkDiscount($basePrice, $quantity, $parameters);
        } elseif ($discountType === 'seasonal') {
            $finalPrice = $this->calculateSeasonalDiscount($basePrice, $parameters);
        } else {
            $finalPrice = $basePrice * $quantity;
        }
        
        // Validation du prix final
        if ($finalPrice < 0) {
            $this->logger->warning("Prix négatif calculé", ['final_price' => $finalPrice]);
            $finalPrice = 0;
        }
        
        $this->logger->info("Prix calculé", ['final_price' => $finalPrice]);
        
        return $finalPrice;
    }
    
    /**
     * Calcule une remise en pourcentage
     */
    private function calculatePercentageDiscount(float $basePrice, float $discountPercent, int $quantity): float
    {
        if ($discountPercent < 0 || $discountPercent > 100) {
            throw new InvalidArgumentException("Le pourcentage de remise doit être entre 0 et 100");
        }
        
        return $basePrice * (1 - $discountPercent / 100) * $quantity;
    }
    
    /**
     * Calcule une remise fixe
     */
    private function calculateFixedDiscount(float $basePrice, float $discountAmount, int $quantity): float
    {
        if ($discountAmount < 0) {
            throw new InvalidArgumentException("Le montant de remise ne peut pas être négatif");
        }
        
        $discountedPrice = max(0, $basePrice - $discountAmount);
        return $discountedPrice * $quantity;
    }
    
    /**
     * Calcule une remise par volume
     */
    private function calculateVolumeDiscount(float $basePrice, int $quantity, array $parameters): float
    {
        $volumeThresholds = $parameters['volume_thresholds'] ?? [
            100 => 0.15,  // 15% de remise pour 100+ unités
            50 => 0.10,   // 10% de remise pour 50+ unités
            20 => 0.05    // 5% de remise pour 20+ unités
        ];
        
        $discountPercent = 0;
        foreach ($volumeThresholds as $threshold => $discount) {
            if ($quantity >= $threshold) {
                $discountPercent = $discount;
                break;
            }
        }
        
        return $basePrice * (1 - $discountPercent) * $quantity;
    }
    
    /**
     * Calcule une remise par quantité (bulk)
     */
    private function calculateBulkDiscount(float $basePrice, int $quantity, array $parameters): float
    {
        $bulkThresholds = $parameters['bulk_thresholds'] ?? [
            10 => 0.20,   // 20% de remise pour 10+ unités
            5 => 0.10     // 10% de remise pour 5+ unités
        ];
        
        $discountPercent = 0;
        foreach ($bulkThresholds as $threshold => $discount) {
            if ($quantity >= $threshold) {
                $discountPercent = $discount;
                break;
            }
        }
        
        return $basePrice * (1 - $discountPercent) * $quantity;
    }
    
    /**
     * Calcule une remise saisonnière
     */
    private function calculateSeasonalDiscount(float $basePrice, array $parameters): float
    {
        $currentMonth = $parameters['current_month'] ?? date('n');
        $seasonalDiscounts = $parameters['seasonal_discounts'] ?? [
            12 => 0.25,   // 25% de remise en décembre (Noël)
            1 => 0.20,    // 20% de remise en janvier (soldes)
            7 => 0.15,    // 15% de remise en juillet (été)
            8 => 0.15     // 15% de remise en août (été)
        ];
        
        $discountPercent = $seasonalDiscounts[$currentMonth] ?? 0;
        
        return $basePrice * (1 - $discountPercent);
    }
    
    /**
     * Obtient les statistiques de calcul
     */
    public function getCalculationStats(): array
    {
        return [
            'total_calculations' => $this->logger->getLogCount(),
            'supported_discount_types' => ['percentage', 'fixed', 'volume', 'bulk', 'seasonal'],
            'last_calculation_time' => $this->logger->getLastLogTime()
        ];
    }
}

/**
 * Classe Logger simple pour les tests
 */
class Logger
{
    private array $logs = [];
    private int $logCount = 0;
    
    public function info(string $message, array $context = []): void
    {
        $this->logs[] = [
            'level' => 'info',
            'message' => $message,
            'context' => $context,
            'timestamp' => time()
        ];
        $this->logCount++;
    }
    
    public function warning(string $message, array $context = []): void
    {
        $this->logs[] = [
            'level' => 'warning',
            'message' => $message,
            'context' => $context,
            'timestamp' => time()
        ];
        $this->logCount++;
    }
    
    public function getLogCount(): int
    {
        return $this->logCount;
    }
    
    public function getLastLogTime(): ?int
    {
        if (empty($this->logs)) {
            return null;
        }
        
        return end($this->logs)['timestamp'];
    }
    
    public function getLogs(): array
    {
        return $this->logs;
    }
}
