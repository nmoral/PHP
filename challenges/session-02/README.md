# Session 2 : Injection de Dépendances avec Container

## 🎯 Objectif

Apprendre à utiliser l'**Injection de Dépendances (DI)** et un **DI Container** pour éliminer les dépendances hardcodées et rendre le code plus testable et flexible.

## 📋 Contexte

Vous avez une classe `OrderService` qui instancie directement ses dépendances :
- `new EmailService()` - Envoi d'emails
- `new PaymentGateway()` - Traitement des paiements  
- `new Database()` - Accès à la base de données
- `new Logger()` - Logging des actions

Cette approche rend le code difficile à tester et inflexible.

## 🚀 Challenge

### Étape 1 : Analyser le code existant
Examinez la classe `OrderService` dans `src/OrderService.php` et identifiez les dépendances hardcodées.

### Étape 2 : Faire passer les tests
Les tests unitaires sont déjà écrits dans `tests/OrderServiceTest.php`. Votre objectif est de refactoriser le code pour que **tous les tests passent**.

### Étape 3 : Implémenter l'injection de dépendances
1. **Créer des interfaces** pour chaque dépendance
2. **Modifier OrderService** pour accepter les dépendances via le constructeur
3. **Créer un DI Container** simple (PSR-11 compatible)
4. **Configurer le container** avec les implémentations
5. **Utiliser le container** pour résoudre les dépendances

## 🧪 Tests à faire passer

```bash
./vendor/bin/phpunit challenges/session-02/tests/OrderServiceTest.php
```

**Objectif :** 100% des tests doivent passer avec une couverture de code > 90%.

## 📋 Procédure détaillée pour les développeurs

### ⚠️ **IMPORTANT : Les mocks sont l'OBJECTIF, pas quelque chose à éliminer !**

### **Étape 1 : Code de départ (problématique)**
```php
// OrderService.php - AVANT refactorisation
class OrderService 
{
    public function __construct() 
    {
        // ❌ Dépendances hardcodées - impossible à mocker
        $this->emailService = new EmailService();
        $this->paymentGateway = new PaymentGateway();
    }
}
```

### **Étape 2 : Tests échouent (normal)**
```php
// OrderServiceTest.php - Les tests échouent car :
$this->emailService = $this->createMock(EmailService::class); // ❌ Classe concrète
// Erreur : Class or interface "EmailServiceInterface" does not exist
```

### **Étape 3 : Refactorisation (votre travail)**
```php
// 1. Créer les interfaces
interface EmailServiceInterface 
{
    public function sendEmail(array $emailData): void;
}

// 2. Modifier OrderService pour accepter les dépendances
class OrderService 
{
    public function __construct(
        EmailServiceInterface $emailService,  // ✅ Interface injectée
        PaymentGatewayInterface $paymentGateway,
        DatabaseInterface $database,
        LoggerInterface $logger
    ) {
        $this->emailService = $emailService;
        $this->paymentGateway = $paymentGateway;
        $this->database = $database;
        $this->logger = $logger;
    }
}
```

### **Étape 4 : Tests passent avec mocks (objectif final)**
```php
// OrderServiceTest.php - Les tests passent maintenant
$this->emailService = $this->createMock(EmailServiceInterface::class); // ✅ Interface mockable
$this->emailService->expects($this->once())->method('sendEmail');
```

## 🎯 **Pourquoi les mocks sont l'objectif :**

1. **Testabilité** : Les mocks permettent d'isoler les tests
2. **Flexibilité** : Changer d'implémentation sans modifier le code
3. **Performance** : Tests rapides sans vraies dépendances
4. **Fiabilité** : Tests déterministes et reproductibles

## 📚 **Exemple concret :**

### **AVANT (problématique) :**
```php
// Impossible de tester sans envoyer de vrais emails
$orderService = new OrderService(); // Instancie de vraies classes
$result = $orderService->processOrder($data); // Envoie de vrais emails !
```

### **APRÈS (objectif) :**
```php
// Tests isolés et rapides
$mockEmail = $this->createMock(EmailServiceInterface::class);
$mockEmail->expects($this->once())->method('sendEmail');

$orderService = new OrderService($mockEmail, $mockPayment, $mockDb, $mockLogger);
$result = $orderService->processOrder($data); // Aucun email envoyé !
```

## 🎯 **Résumé de la procédure :**

1. **Départ** : Code avec dépendances hardcodées (non testable)
2. **Objectif** : Code avec injection de dépendances (testable avec mocks)
3. **Résultat** : Tests passent grâce aux mocks

**Les mocks sont la RÉCOMPENSE du refactoring, pas quelque chose à éliminer !**

## 📚 Concepts à appliquer

- **Dependency Injection** : Injecter les dépendances au lieu de les créer
- **Inversion of Control (IoC)** : Inverser le contrôle des dépendances
- **DI Container** : Container pour gérer les dépendances
- **Interface Segregation** : Créer des interfaces spécifiques
- **PSR-11** : Standard pour les containers de dépendances

## 🎯 Critères de réussite

- [ ] Tous les tests passent
- [ ] Couverture de code > 90%
- [ ] Aucune dépendance hardcodée (`new` dans les méthodes)
- [ ] DI Container fonctionnel
- [ ] Configuration centralisée des dépendances
- [ ] Code testable avec mocks

## 💡 Conseils

1. **Commencez par les interfaces** : Définissez les contrats
2. **Modifiez le constructeur** : Acceptez les dépendances
3. **Créez le container** : Simple mais fonctionnel
4. **Testez progressivement** : Vérifiez à chaque étape
5. **Utilisez les mocks** : Pour isoler les tests

## 🚀 Application immédiate

Cette technique peut être appliquée immédiatement dans vos projets existants :
- Remplacer les `new` par de l'injection
- Créer un DI Container simple
- Améliorer la testabilité de vos services
- Faciliter les changements d'implémentation

## 📖 Ressources

- [PSR-11: Container Interface](https://www.php-fig.org/psr/psr-11/)
- [Dependency Injection](https://en.wikipedia.org/wiki/Dependency_injection)
- [Inversion of Control](https://en.wikipedia.org/wiki/Inversion_of_control)
- [PHP-DI Documentation](https://php-di.org/)

## 🔧 Outils utilisés

- **PSR-11 Container** : Interface standard
- **PHPUnit Mocks** : Tests isolés
- **Composer Autoloading** : Chargement automatique des classes
