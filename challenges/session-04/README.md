# Session 4 : Strategy Pattern pour les Calculs de Prix

## 🎯 Objectif de la Session

Apprendre à implémenter le **Strategy Pattern** pour gérer différents types de calculs de prix et remises de manière flexible et extensible.

## 📋 Problème à Résoudre

### Code de Départ (Problématique)

Le `PricingService` actuel utilise des `if/else` pour gérer différents types de remises :

```php
// ❌ Code problématique avec if/else
public function calculatePrice($basePrice, $discountType, $discountValue, $quantity = 1)
{
    if ($discountType === 'percentage') {
        return $basePrice * (1 - $discountValue / 100) * $quantity;
    } elseif ($discountType === 'fixed') {
        return max(0, ($basePrice - $discountValue) * $quantity);
    } elseif ($discountType === 'volume') {
        if ($quantity >= 10) {
            return $basePrice * 0.8 * $quantity; // 20% de remise
        } elseif ($quantity >= 5) {
            return $basePrice * 0.9 * $quantity; // 10% de remise
        }
        return $basePrice * $quantity;
    }
    // ... plus de conditions
}
```

### Problèmes Identifiés

1. **Violation du Principe Ouvert/Fermé** : Ajouter un nouveau type de remise nécessite de modifier le code existant
2. **Responsabilité Multiple** : La classe connaît tous les types de remises
3. **Difficile à Tester** : Tous les cas de test sont dans une seule méthode
4. **Code Rigide** : Impossible de combiner plusieurs stratégies

## 🎯 Objectif du Refactoring

### Solution Cible (Strategy Pattern)

```php
// ✅ Code cible avec Strategy Pattern
interface PricingStrategyInterface
{
    public function calculatePrice(float $basePrice, int $quantity, array $parameters = []): float;
}

class PercentageDiscountStrategy implements PricingStrategyInterface
{
    public function calculatePrice(float $basePrice, int $quantity, array $parameters = []): float
    {
        $discountPercent = $parameters['discount_percent'] ?? 0;
        return $basePrice * (1 - $discountPercent / 100) * $quantity;
    }
}

class PricingService
{
    private PricingStrategyInterface $strategy;
    
    public function __construct(PricingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }
    
    public function calculatePrice(float $basePrice, int $quantity, array $parameters = []): float
    {
        return $this->strategy->calculatePrice($basePrice, $quantity, $parameters);
    }
}
```

## 🧪 Tests à Faire Passer

### Tests Fournis

Les tests suivants sont fournis et doivent passer après le refactoring :

1. **Test des Stratégies Individuelles**
   - `testPercentageDiscountStrategy()` - Remise en pourcentage
   - `testFixedDiscountStrategy()` - Remise fixe
   - `testVolumeDiscountStrategy()` - Remise par volume
   - `testBulkDiscountStrategy()` - Remise par quantité
   - `testSeasonalDiscountStrategy()` - Remise saisonnière

2. **Test du Service Principal**
   - `testPricingServiceWithPercentageStrategy()` - Service avec stratégie pourcentage
   - `testPricingServiceWithFixedStrategy()` - Service avec stratégie fixe
   - `testPricingServiceWithVolumeStrategy()` - Service avec stratégie volume

3. **Test des Cas Limites**
   - `testNegativePriceHandling()` - Gestion des prix négatifs
   - `testZeroQuantityHandling()` - Gestion des quantités nulles
   - `testInvalidParametersHandling()` - Gestion des paramètres invalides

4. **Test de Flexibilité**
   - `testStrategySwitching()` - Changement de stratégie
   - `testMultipleStrategiesCombination()` - Combinaison de stratégies

## 📚 Concepts Clés à Maîtriser

### Strategy Pattern
- **Définition** : Permet de définir une famille d'algorithmes, les encapsuler et les rendre interchangeables
- **Avantages** : Extensibilité, testabilité, respect du principe Ouvert/Fermé
- **Utilisation** : Quand plusieurs algorithmes peuvent être utilisés pour résoudre un problème

### Principe Ouvert/Fermé (Open/Closed Principle)
- **Ouvert** : Pour l'extension (ajouter de nouvelles stratégies)
- **Fermé** : Pour la modification (ne pas modifier le code existant)

### Interface Segregation Principle (ISP)
- **Définition** : Les clients ne doivent pas dépendre d'interfaces qu'ils n'utilisent pas
- **Application** : Interface `PricingStrategyInterface` simple et focalisée

## 🛠️ Procédure Détaillée pour les Développeurs

### Étape 1 : Analyser le Code de Départ
1. **Identifier les responsabilités** : Le `PricingService` gère tous les types de remises
2. **Repérer les violations** : Principe Ouvert/Fermé violé par les `if/else`
3. **Comprendre les tests** : Les tests échouent car les classes/interfaces n'existent pas encore

### Étape 2 : Créer l'Interface de Stratégie
1. **Définir l'interface** : `PricingStrategyInterface` avec méthode `calculatePrice()`
2. **Paramètres flexibles** : Utiliser un tableau `$parameters` pour la flexibilité
3. **Signature claire** : `calculatePrice(float $basePrice, int $quantity, array $parameters = []): float`

### Étape 3 : Implémenter les Stratégies Concrètes
1. **PercentageDiscountStrategy** : Remise en pourcentage
2. **FixedDiscountStrategy** : Remise fixe
3. **VolumeDiscountStrategy** : Remise par volume
4. **BulkDiscountStrategy** : Remise par quantité
5. **SeasonalDiscountStrategy** : Remise saisonnière

### Étape 4 : Refactoriser le Service Principal
1. **Injecter la stratégie** : `PricingService` reçoit une `PricingStrategyInterface`
2. **Déléguer le calcul** : Le service délègue à la stratégie
3. **Supprimer les if/else** : Plus de logique conditionnelle dans le service

### Étape 5 : Faire Passer les Tests
1. **Tests individuels** : Chaque stratégie testée séparément
2. **Tests d'intégration** : Service avec différentes stratégies
3. **Tests de flexibilité** : Changement de stratégie à l'exécution

## 🎯 Résultats Attendus

### Avant le Refactoring
- ❌ Tests échouent (classes manquantes)
- ❌ Code rigide avec if/else
- ❌ Difficile à étendre
- ❌ Tests complexes

### Après le Refactoring
- ✅ Tous les tests passent
- ✅ Code flexible et extensible
- ✅ Facile d'ajouter de nouvelles stratégies
- ✅ Tests simples et focalisés
- ✅ Respect des principes SOLID

## 🚀 Application Immédiate

### Dans vos Projets
1. **E-commerce** : Système de pricing flexible
2. **Calculs de commissions** : Différentes stratégies de calcul
3. **Système de remises** : Gestion des promotions
4. **Calculs de taxes** : Différents régimes fiscaux

### Quick Wins
- **Ajouter une nouvelle remise** : Créer une nouvelle stratégie sans modifier le code existant
- **Tester facilement** : Chaque stratégie testée indépendamment
- **Changer de stratégie** : À l'exécution selon le contexte
- **Combiner des stratégies** : Utiliser plusieurs stratégies ensemble

## 📖 Ressources Complémentaires

- [Strategy Pattern - Refactoring Guru](https://refactoring.guru/design-patterns/strategy)
- [Open/Closed Principle - SOLID Principles](https://en.wikipedia.org/wiki/Open%E2%80%93closed_principle)
- [Interface Segregation Principle - SOLID Principles](https://en.wikipedia.org/wiki/Interface_segregation_principle)

---

## 🎯 Défi Final

**Objectif** : Faire passer tous les tests en implémentant le Strategy Pattern

**Contraintes** :
- Ne pas modifier les tests fournis
- Respecter les principes SOLID
- Code propre et lisible
- Documentation des stratégies

**Temps estimé** : 2 heures

**Bonne chance ! 🚀**
