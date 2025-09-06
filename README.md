# 🏗️ Programme de Formation : Architecture Logicielle PHP

## 📋 Description

Ce repository contient un programme de formation complet pour faire progresser les développeurs PHP dans l'architecture logicielle. Le programme est conçu pour des sessions de 2h par semaine avec des exemples concrets directement applicables dans les projets existants.

## 🎯 Objectifs

- **Apprendre l'architecture logicielle** via des exemples pratiques
- **Améliorer le code existant** avec des quick wins immédiats
- **Maîtriser les patterns** de conception les plus utiles
- **Progresser par la pratique** avec des tests unitaires comme guide

## 📅 Structure du Programme

### 🏗️ **SEMAINE 1-4 : Fondamentaux Applicables**
- Session 1 : Refactoring d'une classe "God Class"
- Session 2 : Injection de Dépendances avec Container
- Session 3 : Factory Pattern pour les Notifications
- Session 4 : Strategy Pattern pour les Calculs

### 🏢 **SEMAINE 5-8 : Architecture en Couches**
- Session 5 : Repository Pattern pour l'Accès aux Données
- Session 6 : Service Layer Pattern
- Session 7 : DTO (Data Transfer Objects)
- Session 8 : Command Pattern pour les Actions

### 🎯 **SEMAINE 9-12 : Patterns Avancés**
- Session 9 : Observer Pattern pour les Événements
- Session 10 : Decorator Pattern pour les Services
- Session 11 : Adapter Pattern pour les APIs Externes
- Session 12 : Facade Pattern pour les Services Complexes

### 🚀 **SEMAINE 13-16 : Architecture Moderne**
- Session 13 : CQRS Simple (Command Query Separation)
- Session 14 : Event Sourcing Simple
- Session 15 : Hexagonal Architecture (Ports & Adapters)
- Session 16 : Projet Final - Refactoring Complet

## 🚀 Démarrage Rapide

### Prérequis
- PHP 8.1+
- Composer
- PHPUnit
- Git

### Installation
```bash
# Cloner le repository
git clone <url-du-repo>
cd PHP

# Installer les dépendances
composer install

# Lancer les tests
./vendor/bin/phpunit
```

## 📚 Organisation du Repository

```
├── plan-formation-architecture.md  # Plan détaillé du programme
├── challenges/                     # Dossiers des challenges
│   ├── session-01/                # Challenge 1 : Refactoring
│   ├── session-02/                # Challenge 2 : DI Container
│   └── ...
├── examples/                      # Exemples de code
├── tests/                         # Tests unitaires
└── docs/                          # Documentation
```

## 🧪 Approche par Tests

Chaque challenge commence par des **tests unitaires déjà écrits**. Les développeurs doivent implémenter le code de production pour faire passer ces tests.

### Structure des Tests
```
tests/
├── Unit/           # Tests unitaires par classe
├── Integration/    # Tests d'intégration
├── Contract/       # Tests contractuels
└── Fixtures/       # Données de test
```

## 🎓 Évaluation

### Critères d'Évaluation
1. **Qualité du Code** (30%) - SOLID, lisibilité, standards
2. **Architecture** (40%) - Patterns, séparation, évolutivité
3. **Tests** (20%) - Couverture, qualité des tests
4. **Documentation** (10%) - README, diagrammes

### Système de Badges
- 🥉 **Bronze** : Score > 70%
- 🥈 **Silver** : Score > 85%
- 🥇 **Gold** : Score > 95%

## 🛠️ Outils Utilisés

- **PHPUnit** : Tests unitaires
- **Mockery** : Création de mocks
- **PHPStan** : Analyse statique
- **PHP CS Fixer** : Formatage de code
- **Docker** : Environnement de développement

## 📖 Ressources

- [Plan de Formation Détaillé](plan-formation-architecture.md)
- [Documentation PHP](https://www.php.net/docs.php)
- [PHPUnit Documentation](https://phpunit.readthedocs.io/)
- [PSR Standards](https://www.php-fig.org/psr/)

## 🤝 Contribution

Ce programme est conçu pour être adaptatif. Les challenges peuvent être ajustés selon le niveau et les besoins spécifiques de chaque développeur.

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

*Formation créée pour améliorer les compétences en architecture logicielle des développeurs PHP via une approche pratique et progressive.*
