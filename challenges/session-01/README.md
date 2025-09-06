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
