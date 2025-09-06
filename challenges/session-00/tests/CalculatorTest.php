<?php

namespace Session00Tests;

use PHPUnit\Framework\TestCase;
use Session00\Calculator;

/**
 * Tests pour la Session 0 : Initiation aux Tests Unitaires
 * 
 * 🎯 OBJECTIF : Apprendre à écrire des tests unitaires avec PHPUnit
 * 
 * 📋 PROCÉDURE :
 * 1. Analyser le code de la classe Calculator
 * 2. Identifier tous les scénarios à tester
 * 3. Écrire des tests complets pour chaque méthode
 * 4. Utiliser la structure AAA (Arrange, Act, Assert)
 * 5. Tester les cas limites et les exceptions
 * 
 * 💡 CONSEILS :
 * - Commencez par les cas simples
 * - Testez les cas limites (zéro, négatifs, valeurs extrêmes)
 * - Testez les exceptions avec expectException()
 * - Nommez vos tests de manière descriptive
 * - Utilisez setUp() pour initialiser les objets
 */
class CalculatorTest extends TestCase
{
    private Calculator $calculator;
    
    /**
     * Méthode exécutée avant chaque test
     * Permet d'initialiser un objet Calculator propre pour chaque test
     */
    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }
    
    // ========================================
    // TESTS DE LA MÉTHODE add()
    // ========================================
    
    /**
     * Test de l'addition avec des nombres positifs
     */
    public function testAddWithPositiveNumbers(): void
    {
        // Arrange (Préparer)
        $a = 5.0;
        $b = 3.0;
        $expected = 8.0;
        
        // Act (Agir)
        $result = $this->calculator->add($a, $b);
        
        // Assert (Vérifier)
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test de l'addition avec des nombres négatifs
     */
    public function testAddWithNegativeNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $a = -5.0, $b = -3.0, $expected = -8.0
        // Act: Appeler $this->calculator->add($a, $b)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test de l'addition avec des nombres décimaux
     */
    public function testAddWithDecimalNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $a = 2.5, $b = 1.3, $expected = 3.8
        // Act: Appeler $this->calculator->add($a, $b)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test de l'addition avec zéro
     */
    public function testAddWithZero(): void
    {
        // TODO: Écrire ce test
        // Arrange: $a = 5.0, $b = 0.0, $expected = 5.0
        // Act: Appeler $this->calculator->add($a, $b)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    // ========================================
    // TESTS DE LA MÉTHODE divide()
    // ========================================
    
    /**
     * Test de la division avec des nombres valides
     */
    public function testDivideWithValidNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $a = 10.0, $b = 2.0, $expected = 5.0
        // Act: Appeler $this->calculator->divide($a, $b)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test de la division par zéro (doit lever une exception)
     */
    public function testDivideByZeroThrowsException(): void
    {
        // TODO: Écrire ce test
        // Arrange: $a = 10.0, $b = 0.0
        // Act & Assert: Utiliser expectException() pour vérifier que DivisionByZeroError est levée
        // $this->expectException(DivisionByZeroError::class);
        // $this->expectExceptionMessage("Division by zero is not allowed");
        // $this->calculator->divide($a, $b);
    }
    
    /**
     * Test de la division avec des nombres décimaux
     */
    public function testDivideWithDecimalNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $a = 7.5, $b = 2.5, $expected = 3.0
        // Act: Appeler $this->calculator->divide($a, $b)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    // ========================================
    // TESTS DE LA MÉTHODE calculateDiscount()
    // ========================================
    
    /**
     * Test du calcul de remise avec des valeurs valides
     */
    public function testCalculateDiscountWithValidValues(): void
    {
        // TODO: Écrire ce test
        // Arrange: $price = 100.0, $discountPercent = 20.0, $expected = 80.0
        // Act: Appeler $this->calculator->calculateDiscount($price, $discountPercent)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test du calcul de remise avec un prix négatif (doit lever une exception)
     */
    public function testCalculateDiscountWithNegativePriceThrowsException(): void
    {
        // TODO: Écrire ce test
        // Arrange: $price = -100.0, $discountPercent = 20.0
        // Act & Assert: Utiliser expectException() pour vérifier que InvalidArgumentException est levée
        // $this->expectException(InvalidArgumentException::class);
        // $this->expectExceptionMessage("Price cannot be negative");
        // $this->calculator->calculateDiscount($price, $discountPercent);
    }
    
    /**
     * Test du calcul de remise avec une remise négative (doit lever une exception)
     */
    public function testCalculateDiscountWithNegativeDiscountThrowsException(): void
    {
        // TODO: Écrire ce test
        // Arrange: $price = 100.0, $discountPercent = -10.0
        // Act & Assert: Utiliser expectException() pour vérifier que InvalidArgumentException est levée
        // $this->expectException(InvalidArgumentException::class);
        // $this->expectExceptionMessage("Discount must be between 0 and 100");
        // $this->calculator->calculateDiscount($price, $discountPercent);
    }
    
    /**
     * Test du calcul de remise avec une remise supérieure à 100% (doit lever une exception)
     */
    public function testCalculateDiscountWithDiscountOver100ThrowsException(): void
    {
        // TODO: Écrire ce test
        // Arrange: $price = 100.0, $discountPercent = 150.0
        // Act & Assert: Utiliser expectException() pour vérifier que InvalidArgumentException est levée
        // $this->expectException(InvalidArgumentException::class);
        // $this->expectExceptionMessage("Discount must be between 0 and 100");
        // $this->calculator->calculateDiscount($price, $discountPercent);
    }
    
    /**
     * Test du calcul de remise avec une remise de 0%
     */
    public function testCalculateDiscountWithZeroDiscount(): void
    {
        // TODO: Écrire ce test
        // Arrange: $price = 100.0, $discountPercent = 0.0, $expected = 100.0
        // Act: Appeler $this->calculator->calculateDiscount($price, $discountPercent)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test du calcul de remise avec une remise de 100%
     */
    public function testCalculateDiscountWithFullDiscount(): void
    {
        // TODO: Écrire ce test
        // Arrange: $price = 100.0, $discountPercent = 100.0, $expected = 0.0
        // Act: Appeler $this->calculator->calculateDiscount($price, $discountPercent)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    // ========================================
    // TESTS BONUS - MÉTHODES SUPPLÉMENTAIRES
    // ========================================
    
    /**
     * Test du calcul de moyenne avec des nombres valides
     */
    public function testCalculateAverageWithValidNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $numbers = [1, 2, 3, 4, 5], $expected = 3.0
        // Act: Appeler $this->calculator->calculateAverage($numbers)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test du calcul de moyenne avec un tableau vide (doit lever une exception)
     */
    public function testCalculateAverageWithEmptyArrayThrowsException(): void
    {
        // TODO: Écrire ce test
        // Arrange: $numbers = []
        // Act & Assert: Utiliser expectException() pour vérifier que InvalidArgumentException est levée
        // $this->expectException(InvalidArgumentException::class);
        // $this->expectExceptionMessage("Cannot calculate average of empty array");
        // $this->calculator->calculateAverage($numbers);
    }
    
    /**
     * Test de la recherche du maximum
     */
    public function testFindMaxWithValidNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $numbers = [1, 5, 3, 9, 2], $expected = 9.0
        // Act: Appeler $this->calculator->findMax($numbers)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test de la recherche du minimum
     */
    public function testFindMinWithValidNumbers(): void
    {
        // TODO: Écrire ce test
        // Arrange: $numbers = [5, 2, 8, 1, 9], $expected = 1.0
        // Act: Appeler $this->calculator->findMin($numbers)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test de vérification si un nombre est pair
     */
    public function testIsEvenWithEvenNumber(): void
    {
        // TODO: Écrire ce test
        // Arrange: $number = 4
        // Act: Appeler $this->calculator->isEven($number)
        // Assert: Vérifier que le résultat est true avec assertTrue()
    }
    
    /**
     * Test de vérification si un nombre est impair
     */
    public function testIsEvenWithOddNumber(): void
    {
        // TODO: Écrire ce test
        // Arrange: $number = 5
        // Act: Appeler $this->calculator->isEven($number)
        // Assert: Vérifier que le résultat est false avec assertFalse()
    }
    
    /**
     * Test de vérification si un nombre est premier
     */
    public function testIsPrimeWithPrimeNumber(): void
    {
        // TODO: Écrire ce test
        // Arrange: $number = 7
        // Act: Appeler $this->calculator->isPrime($number)
        // Assert: Vérifier que le résultat est true avec assertTrue()
    }
    
    /**
     * Test de vérification si un nombre n'est pas premier
     */
    public function testIsPrimeWithNonPrimeNumber(): void
    {
        // TODO: Écrire ce test
        // Arrange: $number = 8
        // Act: Appeler $this->calculator->isPrime($number)
        // Assert: Vérifier que le résultat est false avec assertFalse()
    }
    
    /**
     * Test du calcul de factorielle
     */
    public function testFactorialWithValidNumber(): void
    {
        // TODO: Écrire ce test
        // Arrange: $number = 5, $expected = 120
        // Act: Appeler $this->calculator->factorial($number)
        // Assert: Vérifier que le résultat est égal à $expected
    }
    
    /**
     * Test du calcul de factorielle avec un nombre négatif (doit lever une exception)
     */
    public function testFactorialWithNegativeNumberThrowsException(): void
    {
        // TODO: Écrire ce test
        // Arrange: $number = -5
        // Act & Assert: Utiliser expectException() pour vérifier que InvalidArgumentException est levée
        // $this->expectException(InvalidArgumentException::class);
        // $this->expectExceptionMessage("Cannot calculate factorial of negative number");
        // $this->calculator->factorial($number);
    }
}
