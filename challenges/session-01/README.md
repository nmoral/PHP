# Session 1 : Refactoring d'une classe "God Class"

## 🎯 Objectif

Apprendre à identifier et refactoriser une classe qui viole le **Single Responsibility Principle (SRP)** en la séparant en plusieurs classes spécialisées.

## 📋 Contexte

Vous avez une classe `UserManager` qui fait **trop de choses** :
- Validation des données utilisateur
- Envoi d'emails
- Gestion de la base de données
- Logging des actions
- Génération de mots de passe

Cette classe est difficile à tester, maintenir et faire évoluer.

## 🚀 Challenge

### Étape 1 : Analyser le code existant
Examinez la classe `UserManager` dans `src/UserManager.php` et identifiez les responsabilités multiples.

### Étape 2 : Faire passer les tests
Les tests unitaires sont déjà écrits dans `tests/UserManagerTest.php`. Votre objectif est de refactoriser le code pour que **tous les tests passent**.

### Étape 3 : Séparer les responsabilités
Créez les classes suivantes :
- `UserValidator` : Validation des données utilisateur
- `EmailService` : Envoi d'emails
- `UserRepository` : Accès à la base de données
- `Logger` : Logging des actions
- `PasswordGenerator` : Génération de mots de passe

## 🧪 Tests à faire passer

```bash
./vendor/bin/phpunit tests/UserManagerTest.php
```

**Objectif :** 100% des tests doivent passer avec une couverture de code > 90%.

## 📋 Procédure détaillée pour les développeurs

### ⚠️ **IMPORTANT : Les mocks sont l'OBJECTIF, pas quelque chose à éliminer !**

### **Étape 1 : Code de départ (problématique)**
```php
// UserManager.php - AVANT refactorisation
class UserManager 
{
    public function createUser(array $userData): array 
    {
        // ❌ Toutes les responsabilités dans une seule classe
        // - Validation
        // - Génération de mot de passe  
        // - Sauvegarde en base
        // - Envoi d'email
        // - Logging
    }
}
```

### **Étape 2 : Tests échouent (normal)**
```php
// UserManagerTest.php - Les tests échouent car :
$this->userValidator = $this->createMock(UserValidator::class); // ❌ Classe n'existe pas
// Erreur : Class or interface "UserValidator" does not exist
```

### **Étape 3 : Refactorisation (votre travail)**
```php
// 1. Créer les classes spécialisées
class UserValidator 
{
    public function validate(array $userData): bool { /* ... */ }
}

class EmailService 
{
    public function sendWelcomeEmail(string $email, string $name, string $password): void { /* ... */ }
}

// 2. Modifier UserManager pour utiliser l'injection
class UserManager 
{
    public function __construct(
        UserValidator $userValidator,     // ✅ Responsabilité séparée
        EmailService $emailService,       // ✅ Responsabilité séparée
        UserRepository $userRepository,   // ✅ Responsabilité séparée
        Logger $logger,                   // ✅ Responsabilité séparée
        PasswordGenerator $passwordGenerator // ✅ Responsabilité séparée
    ) {
        $this->userValidator = $userValidator;
        $this->emailService = $emailService;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
        $this->passwordGenerator = $passwordGenerator;
    }
}
```

### **Étape 4 : Tests passent avec mocks (objectif final)**
```php
// UserManagerTest.php - Les tests passent maintenant
$this->userValidator = $this->createMock(UserValidator::class); // ✅ Classe mockable
$this->userValidator->expects($this->once())->method('validate');
```

## 🎯 **Pourquoi les mocks sont l'objectif :**

1. **Testabilité** : Tester chaque classe indépendamment
2. **Maintenabilité** : Chaque classe a une seule responsabilité
3. **Flexibilité** : Changer d'implémentation sans impacter les autres
4. **Réutilisabilité** : Classes réutilisables dans d'autres contextes

## 📚 **Exemple concret :**

### **AVANT (problématique) :**
```php
// Impossible de tester la validation sans toucher à la base de données
$userManager = new UserManager(); // Fait tout
$result = $userManager->createUser($data); // Valide + sauvegarde + envoie email !
```

### **APRÈS (objectif) :**
```php
// Tests isolés et rapides
$mockValidator = $this->createMock(UserValidator::class);
$mockValidator->expects($this->once())->method('validate');

$userManager = new UserManager($mockValidator, $mockEmail, $mockRepo, $mockLogger, $mockPassword);
$result = $userManager->createUser($data); // Seule la logique métier est testée !
```

## 🎯 **Résumé de la procédure :**

1. **Départ** : Une classe qui fait tout (God Class)
2. **Objectif** : Séparer en classes spécialisées (Single Responsibility)
3. **Résultat** : Tests passent grâce aux mocks

**Les mocks sont la RÉCOMPENSE du refactoring, pas quelque chose à éliminer !**

## 📚 Concepts à appliquer

- **Single Responsibility Principle (SRP)** : Une classe = une responsabilité
- **Dependency Injection** : Injecter les dépendances au lieu de les créer
- **Interface Segregation** : Créer des interfaces spécifiques
- **Composition over Inheritance** : Utiliser la composition

## 🎯 Critères de réussite

- [ ] Tous les tests passent
- [ ] Couverture de code > 90%
- [ ] Chaque classe a une seule responsabilité
- [ ] Les dépendances sont injectées
- [ ] Le code est lisible et maintenable

## 💡 Conseils

1. **Commencez par les tests** : Ils vous guideront dans la refactorisation
2. **Une classe à la fois** : Refactorisez progressivement
3. **Gardez la compatibilité** : L'interface publique doit rester la même
4. **Testez à chaque étape** : Vérifiez que les tests passent toujours

## 🚀 Application immédiate

Cette technique peut être appliquée immédiatement dans vos projets existants :
- Identifiez les classes qui font trop de choses
- Séparez-les en classes spécialisées
- Améliorez la testabilité et la maintenabilité

## 📖 Ressources

- [Single Responsibility Principle](https://en.wikipedia.org/wiki/Single-responsibility_principle)
- [Refactoring: Improving the Design of Existing Code](https://martinfowler.com/books/refactoring.html)
- [Clean Code - Robert C. Martin](https://www.oreilly.com/library/view/clean-code/9780136083238/)
