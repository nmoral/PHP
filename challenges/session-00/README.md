# Session 0 : Initiation aux Tests Unitaires avec PHPUnit

## 🎯 Objectif de la Session

Apprendre les **fondamentaux des tests unitaires** en PHP avec PHPUnit, comprendre les bonnes pratiques et être capable d'écrire des tests efficaces pour guider le développement.

## 📋 Problème à Résoudre

### Situation Initiale

Vous avez une classe `Calculator` avec des méthodes à tester, mais **aucun test n'existe encore**. Vous devez apprendre à :

1. **Écrire vos premiers tests unitaires**
2. **Comprendre la structure d'un test PHPUnit**
3. **Utiliser les assertions appropriées**
4. **Tester les cas limites et les erreurs**
5. **Organiser vos tests de manière claire**

### Code à Tester

```php
// Classe Calculator avec des méthodes à tester
class Calculator
{
    public function add(float $a, float $b): float
    {
        return $a + $b;
    }
    
    public function divide(float $a, float $b): float
    {
        if ($b === 0.0) {
            throw new DivisionByZeroError("Division by zero is not allowed");
        }
        return $a / $b;
    }
    
    public function calculateDiscount(float $price, float $discountPercent): float
    {
        if ($price < 0) {
            throw new InvalidArgumentException("Price cannot be negative");
        }
        if ($discountPercent < 0 || $discountPercent > 100) {
            throw new InvalidArgumentException("Discount must be between 0 and 100");
        }
        return $price * (1 - $discountPercent / 100);
    }
}
```

## 🎯 Objectif du Challenge

### Tests à Écrire

Vous devez créer un fichier `CalculatorTest.php` avec les tests suivants :

1. **Tests de la méthode `add()`**
   - Test avec des nombres positifs
   - Test avec des nombres négatifs
   - Test avec des nombres décimaux
   - Test avec zéro

2. **Tests de la méthode `divide()`**
   - Test de division normale
   - Test de division par zéro (doit lever une exception)
   - Test avec des nombres décimaux

3. **Tests de la méthode `calculateDiscount()`**
   - Test avec un prix et une remise valides
   - Test avec un prix négatif (doit lever une exception)
   - Test avec une remise négative (doit lever une exception)
   - Test avec une remise supérieure à 100% (doit lever une exception)
   - Test avec une remise de 0%
   - Test avec une remise de 100%

## 🧪 Structure d'un Test PHPUnit

### Template de Base

```php
<?php

namespace Session00Tests;

use PHPUnit\Framework\TestCase;
use Session00\Calculator;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;
    
    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }
    
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
}
```

## 📚 Concepts Clés à Maîtriser

### 1. Structure AAA (Arrange, Act, Assert)
- **Arrange** : Préparer les données de test
- **Act** : Exécuter la méthode à tester
- **Assert** : Vérifier le résultat

### 2. Assertions PHPUnit
- `assertEquals($expected, $actual)` - Vérifier l'égalité
- `assertSame($expected, $actual)` - Vérifier l'identité (type + valeur)
- `assertTrue($condition)` - Vérifier qu'une condition est vraie
- `assertFalse($condition)` - Vérifier qu'une condition est fausse
- `assertNull($value)` - Vérifier qu'une valeur est null
- `assertNotNull($value)` - Vérifier qu'une valeur n'est pas null

### 3. Tests d'Exceptions
```php
public function testDivideByZeroThrowsException(): void
{
    $this->expectException(DivisionByZeroError::class);
    $this->expectExceptionMessage("Division by zero is not allowed");
    
    $this->calculator->divide(10.0, 0.0);
}
```

### 4. Méthodes de Test
- `setUp()` - Exécutée avant chaque test
- `tearDown()` - Exécutée après chaque test
- `setUpBeforeClass()` - Exécutée une fois avant tous les tests
- `tearDownAfterClass()` - Exécutée une fois après tous les tests

### 5. Nommage des Tests
- **Convention** : `testMethodNameWithScenario()`
- **Exemple** : `testAddWithPositiveNumbers()`
- **Lisibilité** : Le nom doit décrire ce qui est testé

## 🛠️ Procédure Détaillée pour les Développeurs

### Étape 1 : Analyser le Code à Tester
1. **Identifier les méthodes** à tester dans `Calculator`
2. **Comprendre le comportement** de chaque méthode
3. **Identifier les cas limites** et les erreurs possibles
4. **Définir les scénarios** de test pour chaque méthode

### Étape 2 : Créer la Structure de Test
1. **Créer la classe** `CalculatorTest` qui étend `TestCase`
2. **Ajouter la propriété** `$calculator` privée
3. **Implémenter `setUp()`** pour initialiser le calculator
4. **Importer les classes** nécessaires

### Étape 3 : Écrire les Tests de la Méthode `add()`
1. **Test avec nombres positifs** : `testAddWithPositiveNumbers()`
2. **Test avec nombres négatifs** : `testAddWithNegativeNumbers()`
3. **Test avec nombres décimaux** : `testAddWithDecimalNumbers()`
4. **Test avec zéro** : `testAddWithZero()`

### Étape 4 : Écrire les Tests de la Méthode `divide()`
1. **Test de division normale** : `testDivideWithValidNumbers()`
2. **Test de division par zéro** : `testDivideByZeroThrowsException()`
3. **Test avec nombres décimaux** : `testDivideWithDecimalNumbers()`

### Étape 5 : Écrire les Tests de la Méthode `calculateDiscount()`
1. **Test avec valeurs valides** : `testCalculateDiscountWithValidValues()`
2. **Test avec prix négatif** : `testCalculateDiscountWithNegativePriceThrowsException()`
3. **Test avec remise négative** : `testCalculateDiscountWithNegativeDiscountThrowsException()`
4. **Test avec remise > 100%** : `testCalculateDiscountWithDiscountOver100ThrowsException()`
5. **Test avec remise 0%** : `testCalculateDiscountWithZeroDiscount()`
6. **Test avec remise 100%** : `testCalculateDiscountWithFullDiscount()`

### Étape 6 : Exécuter et Valider les Tests
1. **Lancer les tests** avec `./vendor/bin/phpunit`
2. **Vérifier que tous les tests passent**
3. **Analyser la couverture** de code si possible
4. **Refactoriser** si nécessaire

## 🎯 Résultats Attendus

### Avant d'écrire les tests
- ❌ Aucun test n'existe
- ❌ Pas de validation du comportement
- ❌ Risque de régression

### Après avoir écrit les tests
- ✅ **12+ tests** couvrant tous les scénarios
- ✅ **Tests d'exceptions** pour les cas d'erreur
- ✅ **Tests de cas limites** (zéro, valeurs négatives)
- ✅ **Code lisible** et bien organisé
- ✅ **Confiance** dans le comportement du code

## 🚀 Application Immédiate

### Dans vos Projets
1. **Tester les nouvelles fonctionnalités** avant de les implémenter (TDD)
2. **Ajouter des tests** aux classes existantes
3. **Valider les cas limites** et les erreurs
4. **Documenter le comportement** via les tests

### Quick Wins
- **Détecter les bugs** avant la production
- **Refactoriser en sécurité** avec des tests
- **Documenter le comportement** attendu
- **Améliorer la qualité** du code

## 📖 Ressources Complémentaires

- [PHPUnit Documentation](https://phpunit.readthedocs.io/)
- [PHPUnit Assertions](https://phpunit.readthedocs.io/en/9.5/assertions.html)
- [Test-Driven Development (TDD)](https://fr.wikipedia.org/wiki/Test_driven_development)
- [AAA Pattern](https://blog.cleancoder.com/uncle-bob/2017/05/05/TestDefinitions.html)

## 🎯 Défi Final

**Objectif** : Écrire tous les tests pour la classe `Calculator`

**Contraintes** :
- Utiliser la structure AAA (Arrange, Act, Assert)
- Tester tous les cas limites et erreurs
- Nommer les tests de manière descriptive
- Organiser le code de manière claire

**Temps estimé** : 2 heures

**Bonne chance ! 🚀**

---

## 💡 Conseils pour Réussir

1. **Commencez simple** : Un test à la fois
2. **Nommez clairement** : Le nom doit décrire le scénario
3. **Testez les erreurs** : Les exceptions sont importantes
4. **Organisez vos tests** : Groupez par méthode
5. **Lisez les messages d'erreur** : Ils vous guident vers la solution
