# Session 3 : Factory Pattern pour les Notifications

## 🎯 Objectif

Apprendre à utiliser le **Factory Pattern** pour éliminer les `if/else` répétitifs et centraliser la création d'objets complexes, rendant le code plus maintenable et extensible.

## 📋 Contexte

Vous avez un système de notifications avec beaucoup de `if/else` pour créer différents types de notifications :
- `if ($type === 'email')` → `new EmailNotification()`
- `if ($type === 'sms')` → `new SmsNotification()`
- `if ($type === 'push')` → `new PushNotification()`

Cette approche rend le code difficile à maintenir et à étendre.

## 🚀 Challenge

### Étape 1 : Analyser le code existant
Examinez la classe `NotificationService` dans `src/NotificationService.php` et identifiez les `if/else` répétitifs.

### Étape 2 : Faire passer les tests
Les tests unitaires sont déjà écrits dans `tests/NotificationServiceTest.php`. Votre objectif est de refactoriser le code pour que **tous les tests passent**.

### Étape 3 : Implémenter le Factory Pattern
1. **Créer une interface** `NotificationInterface` commune
2. **Créer les classes** `EmailNotification`, `SmsNotification`, `PushNotification`
3. **Créer une factory** `NotificationFactory` pour centraliser la création
4. **Refactoriser** `NotificationService` pour utiliser la factory
5. **Ajouter facilement** de nouveaux types de notifications

## 🧪 Tests à faire passer

```bash
./vendor/bin/phpunit challenges/session-03/tests/NotificationServiceTest.php
```

**Objectif :** 100% des tests doivent passer avec une couverture de code > 90%.

## 📋 Procédure détaillée pour les développeurs

### ⚠️ **IMPORTANT : Les mocks sont l'OBJECTIF, pas quelque chose à éliminer !**

### **Étape 1 : Code de départ (problématique)**
```php
// NotificationService.php - AVANT refactorisation
class NotificationService 
{
    public function sendNotification(string $type, array $data): bool 
    {
        // ❌ Beaucoup de if/else répétitifs
        if ($type === 'email') {
            $notification = new EmailNotification();
            return $notification->send($data);
        } elseif ($type === 'sms') {
            $notification = new SmsNotification();
            return $notification->send($data);
        } elseif ($type === 'push') {
            $notification = new PushNotification();
            return $notification->send($data);
        }
        // Difficile d'ajouter de nouveaux types
    }
}
```

### **Étape 2 : Tests échouent (normal)**
```php
// NotificationServiceTest.php - Les tests échouent car :
$this->notificationFactory = $this->createMock(NotificationFactory::class); // ❌ Classe n'existe pas
// Erreur : Class or interface "NotificationFactory" does not exist
```

### **Étape 3 : Refactorisation (votre travail)**
```php
// 1. Créer l'interface commune
interface NotificationInterface 
{
    public function send(array $data): bool;
}

// 2. Créer les classes de notification
class EmailNotification implements NotificationInterface 
{
    public function send(array $data): bool { /* ... */ }
}

// 3. Créer la factory
class NotificationFactory 
{
    public function create(string $type): NotificationInterface 
    {
        return match($type) {
            'email' => new EmailNotification(),
            'sms' => new SmsNotification(),
            'push' => new PushNotification(),
            default => throw new InvalidArgumentException("Unknown notification type: $type")
        };
    }
}

// 4. Refactoriser NotificationService
class NotificationService 
{
    public function __construct(NotificationFactory $factory) 
    {
        $this->factory = $factory;
    }
    
    public function sendNotification(string $type, array $data): bool 
    {
        $notification = $this->factory->create($type); // ✅ Factory centralisée
        return $notification->send($data);
    }
}
```

### **Étape 4 : Tests passent avec mocks (objectif final)**
```php
// NotificationServiceTest.php - Les tests passent maintenant
$this->notificationFactory = $this->createMock(NotificationFactory::class); // ✅ Factory mockable
$mockNotification = $this->createMock(NotificationInterface::class);
$this->notificationFactory->expects($this->once())->method('create')->willReturn($mockNotification);
```

## 🎯 **Pourquoi les mocks sont l'objectif :**

1. **Testabilité** : Tester la logique sans créer de vraies notifications
2. **Maintenabilité** : Centraliser la création d'objets
3. **Extensibilité** : Ajouter facilement de nouveaux types
4. **Flexibilité** : Changer d'implémentation sans modifier le code client

## 📚 **Exemple concret :**

### **AVANT (problématique) :**
```php
// Difficile d'ajouter de nouveaux types
if ($type === 'email') { /* ... */ }
elseif ($type === 'sms') { /* ... */ }
elseif ($type === 'push') { /* ... */ }
// Il faut modifier le code à chaque nouveau type !
```

### **APRÈS (objectif) :**
```php
// Facile d'ajouter de nouveaux types
$notification = $this->factory->create($type); // ✅ Factory gère tout
// Pour ajouter un nouveau type, il suffit de modifier la factory !
```

## 🎯 **Résumé de la procédure :**

1. **Départ** : Code avec beaucoup de `if/else` répétitifs
2. **Objectif** : Factory Pattern pour centraliser la création
3. **Résultat** : Tests passent grâce aux mocks

**Les mocks sont la RÉCOMPENSE du refactoring, pas quelque chose à éliminer !**

## 📚 Concepts à appliquer

- **Factory Pattern** : Centraliser la création d'objets
- **Interface Segregation** : Interface commune pour tous les types
- **Open/Closed Principle** : Ouvert à l'extension, fermé à la modification
- **Dependency Injection** : Injecter la factory

## 🎯 Critères de réussite

- [ ] Tous les tests passent
- [ ] Couverture de code > 90%
- [ ] Aucun `if/else` pour la création d'objets
- [ ] Factory centralisée et extensible
- [ ] Interface commune pour tous les types
- [ ] Code testable avec mocks

## 💡 Conseils

1. **Commencez par l'interface** : Définissez le contrat commun
2. **Créez les classes** : Implémentez chaque type de notification
3. **Créez la factory** : Centralisez la création
4. **Refactorisez progressivement** : Une étape à la fois
5. **Testez souvent** : Vérifiez que les tests passent

## 🚀 Application immédiate

Cette technique peut être appliquée immédiatement dans vos projets existants :
- Remplacer les `if/else` par des factories
- Centraliser la création d'objets complexes
- Faciliter l'ajout de nouveaux types
- Améliorer la testabilité

## 📖 Ressources

- [Factory Pattern](https://en.wikipedia.org/wiki/Factory_method_pattern)
- [Design Patterns - Gang of Four](https://en.wikipedia.org/wiki/Design_Patterns)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

## 🔧 Outils utilisés

- **Factory Pattern** : Création centralisée d'objets
- **Interface** : Contrat commun
- **PHPUnit Mocks** : Tests isolés
- **Match Expression** : Syntaxe moderne PHP 8+
