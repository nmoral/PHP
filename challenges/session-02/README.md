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
