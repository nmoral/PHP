<?php

namespace Session04Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Session04\PricingService;
use Session04\PricingStrategyInterface;
use Session04\PercentageDiscountStrategy;
use Session04\FixedDiscountStrategy;
use Session04\VolumeDiscountStrategy;
use Session04\BulkDiscountStrategy;
use Session04\SeasonalDiscountStrategy;
use Session04\Logger;

/**
 * Tests pour la Session 4 : Strategy Pattern pour les Calculs de Prix
 * 
 * 🎯 OBJECTIF : Faire passer tous les tests en implémentant le Strategy Pattern
 * 
 * 📋 PROCÉDURE :
 * 1. Créer l'interface PricingStrategyInterface
 * 2. Implémenter les stratégies concrètes
 * 3. Refactoriser PricingService pour utiliser les stratégies
 * 4. Faire passer tous les tests
 */
class PricingServiceTest extends TestCase
{
    private MockObject|Logger $logger;
    private PricingService $pricingService;
    
    protected function setUp(): void
    {
        $this->logger = $this->createMock(Logger::class);
        $this->pricingService = new PricingService($this->logger);
    }
    
    // ========================================
    // TESTS DES STRATÉGIES INDIVIDUELLES
    // ========================================
    
    /**
     * Test de la stratégie de remise en pourcentage
     */
    public function testPercentageDiscountStrategy(): void
    {
        $strategy = new PercentageDiscountStrategy();
        
        // Test avec 20% de remise
        $price = $strategy->calculatePrice(100.0, 2, ['discount_percent' => 20]);
        $this->assertEquals(160.0, $price); // 100 * (1 - 0.2) * 2 = 160
        
        // Test avec 0% de remise
        $price = $strategy->calculatePrice(100.0, 1, ['discount_percent' => 0]);
        $this->assertEquals(100.0, $price);
        
        // Test avec 100% de remise
        $price = $strategy->calculatePrice(100.0, 1, ['discount_percent' => 100]);
        $this->assertEquals(0.0, $price);
    }
    
    /**
     * Test de la stratégie de remise fixe
     */
    public function testFixedDiscountStrategy(): void
    {
        $strategy = new FixedDiscountStrategy();
        
        // Test avec remise de 15€
        $price = $strategy->calculatePrice(100.0, 2, ['discount_amount' => 15]);
        $this->assertEquals(170.0, $price); // (100 - 15) * 2 = 170
        
        // Test avec remise supérieure au prix
        $price = $strategy->calculatePrice(50.0, 1, ['discount_amount' => 60]);
        $this->assertEquals(0.0, $price); // Prix ne peut pas être négatif
        
        // Test avec remise de 0€
        $price = $strategy->calculatePrice(100.0, 1, ['discount_amount' => 0]);
        $this->assertEquals(100.0, $price);
    }
    
    /**
     * Test de la stratégie de remise par volume
     */
    public function testVolumeDiscountStrategy(): void
    {
        $strategy = new VolumeDiscountStrategy();
        
        $volumeThresholds = [
            100 => 0.15,  // 15% de remise pour 100+ unités
            50 => 0.10,   // 10% de remise pour 50+ unités
            20 => 0.05    // 5% de remise pour 20+ unités
        ];
        
        // Test avec 100+ unités (15% de remise)
        $price = $strategy->calculatePrice(10.0, 100, ['volume_thresholds' => $volumeThresholds]);
        $this->assertEquals(850.0, $price); // 10 * (1 - 0.15) * 100 = 850
        
        // Test avec 50+ unités (10% de remise)
        $price = $strategy->calculatePrice(10.0, 50, ['volume_thresholds' => $volumeThresholds]);
        $this->assertEquals(450.0, $price); // 10 * (1 - 0.10) * 50 = 450
        
        // Test avec moins de 20 unités (pas de remise)
        $price = $strategy->calculatePrice(10.0, 10, ['volume_thresholds' => $volumeThresholds]);
        $this->assertEquals(100.0, $price); // 10 * 1 * 10 = 100
    }
    
    /**
     * Test de la stratégie de remise par quantité (bulk)
     */
    public function testBulkDiscountStrategy(): void
    {
        $strategy = new BulkDiscountStrategy();
        
        $bulkThresholds = [
            10 => 0.20,   // 20% de remise pour 10+ unités
            5 => 0.10     // 10% de remise pour 5+ unités
        ];
        
        // Test avec 10+ unités (20% de remise)
        $price = $strategy->calculatePrice(10.0, 10, ['bulk_thresholds' => $bulkThresholds]);
        $this->assertEquals(80.0, $price); // 10 * (1 - 0.20) * 10 = 80
        
        // Test avec 5+ unités (10% de remise)
        $price = $strategy->calculatePrice(10.0, 5, ['bulk_thresholds' => $bulkThresholds]);
        $this->assertEquals(45.0, $price); // 10 * (1 - 0.10) * 5 = 45
        
        // Test avec moins de 5 unités (pas de remise)
        $price = $strategy->calculatePrice(10.0, 3, ['bulk_thresholds' => $bulkThresholds]);
        $this->assertEquals(30.0, $price); // 10 * 1 * 3 = 30
    }
    
    /**
     * Test de la stratégie de remise saisonnière
     */
    public function testSeasonalDiscountStrategy(): void
    {
        $strategy = new SeasonalDiscountStrategy();
        
        $seasonalDiscounts = [
            12 => 0.25,   // 25% de remise en décembre
            1 => 0.20,    // 20% de remise en janvier
            7 => 0.15,    // 15% de remise en juillet
            8 => 0.15     // 15% de remise en août
        ];
        
        // Test avec remise de décembre (25%)
        $price = $strategy->calculatePrice(100.0, 1, [
            'current_month' => 12,
            'seasonal_discounts' => $seasonalDiscounts
        ]);
        $this->assertEquals(75.0, $price); // 100 * (1 - 0.25) = 75
        
        // Test avec remise de janvier (20%)
        $price = $strategy->calculatePrice(100.0, 1, [
            'current_month' => 1,
            'seasonal_discounts' => $seasonalDiscounts
        ]);
        $this->assertEquals(80.0, $price); // 100 * (1 - 0.20) = 80
        
        // Test sans remise saisonnière
        $price = $strategy->calculatePrice(100.0, 1, [
            'current_month' => 3,
            'seasonal_discounts' => $seasonalDiscounts
        ]);
        $this->assertEquals(100.0, $price); // 100 * 1 = 100
    }
    
    // ========================================
    // TESTS DU SERVICE PRINCIPAL
    // ========================================
    
    /**
     * Test du service avec stratégie de remise en pourcentage
     */
    public function testPricingServiceWithPercentageStrategy(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->with(100.0, 2, ['discount_percent' => 20])
            ->willReturn(160.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $price = $service->calculatePrice(100.0, 2, ['discount_percent' => 20]);
        $this->assertEquals(160.0, $price);
    }
    
    /**
     * Test du service avec stratégie de remise fixe
     */
    public function testPricingServiceWithFixedStrategy(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->with(100.0, 1, ['discount_amount' => 15])
            ->willReturn(85.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $price = $service->calculatePrice(100.0, 1, ['discount_amount' => 15]);
        $this->assertEquals(85.0, $price);
    }
    
    /**
     * Test du service avec stratégie de remise par volume
     */
    public function testPricingServiceWithVolumeStrategy(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->with(10.0, 100, ['volume_thresholds' => ['100' => 0.15]])
            ->willReturn(850.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $price = $service->calculatePrice(10.0, 100, ['volume_thresholds' => ['100' => 0.15]]);
        $this->assertEquals(850.0, $price);
    }
    
    // ========================================
    // TESTS DES CAS LIMITES
    // ========================================
    
    /**
     * Test de gestion des prix négatifs
     */
    public function testNegativePriceHandling(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(-10.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $price = $service->calculatePrice(100.0, 1, []);
        $this->assertEquals(0.0, $price); // Prix négatif doit être ramené à 0
    }
    
    /**
     * Test de gestion des quantités nulles
     */
    public function testZeroQuantityHandling(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->with(100.0, 0, [])
            ->willReturn(0.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $price = $service->calculatePrice(100.0, 0, []);
        $this->assertEquals(0.0, $price);
    }
    
    /**
     * Test de gestion des paramètres invalides
     */
    public function testInvalidParametersHandling(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->with(100.0, 1, ['invalid_param' => 'value'])
            ->willReturn(100.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $price = $service->calculatePrice(100.0, 1, ['invalid_param' => 'value']);
        $this->assertEquals(100.0, $price);
    }
    
    // ========================================
    // TESTS DE FLEXIBILITÉ
    // ========================================
    
    /**
     * Test de changement de stratégie
     */
    public function testStrategySwitching(): void
    {
        $percentageStrategy = $this->createMock(PricingStrategyInterface::class);
        $percentageStrategy->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(80.0);
        
        $fixedStrategy = $this->createMock(PricingStrategyInterface::class);
        $fixedStrategy->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(85.0);
        
        $service = new PricingService($this->logger);
        
        // Test avec stratégie pourcentage
        $service->setStrategy($percentageStrategy);
        $price1 = $service->calculatePrice(100.0, 1, []);
        $this->assertEquals(80.0, $price1);
        
        // Test avec stratégie fixe
        $service->setStrategy($fixedStrategy);
        $price2 = $service->calculatePrice(100.0, 1, []);
        $this->assertEquals(85.0, $price2);
    }
    
    /**
     * Test de combinaison de plusieurs stratégies
     */
    public function testMultipleStrategiesCombination(): void
    {
        $strategy1 = $this->createMock(PricingStrategyInterface::class);
        $strategy1->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(80.0);
        
        $strategy2 = $this->createMock(PricingStrategyInterface::class);
        $strategy2->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(70.0);
        
        $service = new PricingService($this->logger);
        
        // Test avec première stratégie
        $service->setStrategy($strategy1);
        $price1 = $service->calculatePrice(100.0, 1, []);
        $this->assertEquals(80.0, $price1);
        
        // Test avec deuxième stratégie
        $service->setStrategy($strategy2);
        $price2 = $service->calculatePrice(100.0, 1, []);
        $this->assertEquals(70.0, $price2);
    }
    
    /**
     * Test des statistiques de calcul
     */
    public function testGetCalculationStats(): void
    {
        $strategy = $this->createMock(PricingStrategyInterface::class);
        $strategy->expects($this->once())
            ->method('calculatePrice')
            ->willReturn(100.0);
        
        $service = new PricingService($this->logger);
        $service->setStrategy($strategy);
        
        $service->calculatePrice(100.0, 1, []);
        
        $stats = $service->getCalculationStats();
        $this->assertArrayHasKey('total_calculations', $stats);
        $this->assertArrayHasKey('supported_discount_types', $stats);
        $this->assertArrayHasKey('last_calculation_time', $stats);
    }
}
