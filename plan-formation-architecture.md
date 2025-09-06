# Programme de Formation : Architecture Logicielle pour Développeurs PHP
## 🕐 Sessions de 2h par semaine - Exemples Concrets & Applicables

## 🎯 Objectifs du Programme

Ce programme vise à faire progresser les développeurs PHP dans l'architecture logicielle via des sessions de 2h par semaine, avec des exemples concrets directement applicables dans leurs projets actuels.

## 📅 Planning des Sessions (16 semaines)

### 🏗️ **SEMAINE 1-4 : Fondamentaux Applicables**

#### **Session 1 (2h) : Refactoring d'une classe "God Class"**
- **Problème concret** : Classe `UserManager` qui fait tout (validation, email, base de données, logs)
- **Solution** : Séparer en `UserValidator`, `EmailService`, `UserRepository`, `Logger`
- **Application immédiate** : Refactoring de classes existantes dans vos projets
- **Tests fournis** : Tests unitaires pour chaque nouvelle classe
- **Quick Win** : Améliorer la maintenabilité d'une classe complexe existante

#### **Session 2 (2h) : Injection de Dépendances avec Container**
- **Problème concret** : `OrderService` qui instancie directement `EmailService`, `PaymentGateway`, `Database`
- **Solution** : Utiliser un DI Container (PSR-11) pour injecter les dépendances
- **Application immédiate** : Remplacer les `new` par de l'injection dans vos services
- **Tests fournis** : Tests avec mocks pour isoler les dépendances
- **Quick Win** : Rendre vos classes testables et flexibles

#### **Session 3 (2h) : Factory Pattern pour les Notifications**
- **Problème concret** : Code avec des `if/else` pour créer différents types de notifications
- **Solution** : `NotificationFactory` avec `EmailNotification`, `SmsNotification`, `PushNotification`
- **Application immédiate** : Système de notifications dans vos applications
- **Tests fournis** : Tests pour chaque type de notification
- **Quick Win** : Ajouter facilement de nouveaux canaux de notification

#### **Session 4 (2h) : Strategy Pattern pour les Calculs**
- **Problème concret** : Calculs de prix avec différents types de remises (pourcentage, fixe, volume)
- **Solution** : `PricingStrategy` avec `PercentageDiscount`, `FixedDiscount`, `VolumeDiscount`
- **Application immédiate** : Système de pricing flexible dans vos e-commerce
- **Tests fournis** : Tests pour chaque stratégie de calcul
- **Quick Win** : Ajouter de nouveaux types de remises sans modifier le code existant

### 🏢 **SEMAINE 5-8 : Architecture en Couches**

#### **Session 5 (2h) : Repository Pattern pour l'Accès aux Données**
- **Problème concret** : SQL directement dans les contrôleurs ou services
- **Solution** : `UserRepository`, `OrderRepository` avec interface commune
- **Application immédiate** : Séparer la logique métier de l'accès aux données
- **Tests fournis** : Tests avec repository en mémoire pour les tests unitaires
- **Quick Win** : Changer de base de données sans impacter la logique métier

#### **Session 6 (2h) : Service Layer Pattern**
- **Problème concret** : Logique métier éparpillée dans les contrôleurs
- **Solution** : `UserService`, `OrderService` avec logique métier centralisée
- **Application immédiate** : Créer une couche service dans vos applications
- **Tests fournis** : Tests unitaires pour la logique métier
- **Quick Win** : Réutiliser la logique métier dans différents contextes (API, CLI, etc.)

#### **Session 7 (2h) : DTO (Data Transfer Objects)**
- **Problème concret** : Arrays associatifs partout, pas de validation des données
- **Solution** : `UserDTO`, `OrderDTO` avec validation et type safety
- **Application immédiate** : Structurer les données dans vos APIs
- **Tests fournis** : Tests de validation des DTOs
- **Quick Win** : Améliorer la sécurité et la lisibilité de vos APIs

#### **Session 8 (2h) : Command Pattern pour les Actions**
- **Problème concret** : Méthodes longues avec beaucoup de paramètres
- **Solution** : `CreateUserCommand`, `UpdateOrderCommand` avec handlers
- **Application immédiate** : Structurer les actions complexes dans vos applications
- **Tests fournis** : Tests pour chaque command et handler
- **Quick Win** : Rendre les actions auditable et rejouable

### 🎯 **SEMAINE 9-12 : Patterns Avancés**

#### **Session 9 (2h) : Observer Pattern pour les Événements**
- **Problème concret** : Code couplé pour les actions après création d'utilisateur
- **Solution** : `UserCreatedEvent` avec `SendWelcomeEmailListener`, `CreateUserProfileListener`
- **Application immédiate** : Découpler les actions secondaires dans vos applications
- **Tests fournis** : Tests d'événements et de listeners
- **Quick Win** : Ajouter de nouvelles actions sans modifier le code existant

#### **Session 10 (2h) : Decorator Pattern pour les Services**
- **Problème concret** : Ajouter logging, cache, validation à un service existant
- **Solution** : `LoggingDecorator`, `CachingDecorator`, `ValidationDecorator`
- **Application immédiate** : Enrichir vos services existants sans les modifier
- **Tests fournis** : Tests pour chaque décorateur
- **Quick Win** : Ajouter des fonctionnalités transversales (cache, logs) facilement

#### **Session 11 (2h) : Adapter Pattern pour les APIs Externes**
- **Problème concret** : Intégrer différents fournisseurs de paiement avec des APIs différentes
- **Solution** : `PaymentAdapter` avec `StripeAdapter`, `PayPalAdapter`
- **Application immédiate** : Intégrer facilement de nouveaux fournisseurs
- **Tests fournis** : Tests pour chaque adaptateur
- **Quick Win** : Changer de fournisseur sans impacter le code métier

#### **Session 12 (2h) : Facade Pattern pour les Services Complexes**
- **Problème concret** : Client doit appeler plusieurs services pour une action simple
- **Solution** : `OrderFacade` qui orchestre `PaymentService`, `InventoryService`, `ShippingService`
- **Application immédiate** : Simplifier l'utilisation de vos APIs complexes
- **Tests fournis** : Tests d'intégration pour la façade
- **Quick Win** : Améliorer l'expérience développeur de vos APIs

### 🚀 **SEMAINE 13-16 : Architecture Moderne**

#### **Session 13 (2h) : CQRS Simple (Command Query Separation)**
- **Problème concret** : Même modèle pour lecture et écriture, performance dégradée
- **Solution** : `UserCommand` pour écriture, `UserQuery` pour lecture
- **Application immédiate** : Optimiser les performances de vos applications
- **Tests fournis** : Tests séparés pour commands et queries
- **Quick Win** : Améliorer les performances des lectures

#### **Session 14 (2h) : Event Sourcing Simple**
- **Problème concret** : Perdre l'historique des changements d'état
- **Solution** : Stocker les événements `UserCreated`, `UserUpdated`, `UserDeleted`
- **Application immédiate** : Auditer et rejouer les changements dans vos applications
- **Tests fournis** : Tests de reconstruction d'état à partir des événements
- **Quick Win** : Ajouter un audit trail à vos entités importantes

#### **Session 15 (2h) : Hexagonal Architecture (Ports & Adapters)**
- **Problème concret** : Code métier couplé à la base de données et aux APIs externes
- **Solution** : Ports (interfaces) et Adapters (implémentations)
- **Application immédiate** : Isoler votre logique métier des détails techniques
- **Tests fournis** : Tests isolés du domaine métier
- **Quick Win** : Rendre votre code métier indépendant des technologies

#### **Session 16 (2h) : Projet Final - Refactoring Complet**
- **Problème concret** : Application legacy avec tous les problèmes vus précédemment
- **Solution** : Application des 15 patterns vus dans un refactoring complet
- **Application immédiate** : Plan de refactoring pour vos applications existantes
- **Tests fournis** : Suite complète de tests pour valider le refactoring
- **Quick Win** : Roadmap de modernisation de vos applications

## 🧪 Stratégie de Tests

### Structure des Tests
```
tests/
├── Unit/           # Tests unitaires par classe
├── Integration/    # Tests d'intégration entre composants
├── Contract/       # Tests contractuels entre services
├── EndToEnd/       # Tests end-to-end
└── Fixtures/       # Données de test
```

### Outils de Test
- **PHPUnit** : Framework de tests principal
- **Mockery** : Création de mocks
- **Laravel Testing** : Tests d'API et base de données
- **Codeception** : Tests d'acceptation

### Métriques de Qualité
- **Couverture de code** : Minimum 80% par challenge
- **Complexité cyclomatique** : Maximum 10 par méthode
- **Standards PSR** : Respect des standards PHP
- **Documentation** : README et documentation technique

## 📚 Ressources d'Apprentissage

### Livres Recommandés
1. "Clean Architecture" - Robert C. Martin
2. "Domain-Driven Design" - Eric Evans
3. "Patterns of Enterprise Application Architecture" - Martin Fowler
4. "Building Microservices" - Sam Newman

### Articles et Blogs
- Symfony Best Practices
- Laravel Architecture Patterns
- PHP The Right Way
- Clean Code PHP

## 🎓 Évaluation et Progression

### Critères d'Évaluation
1. **Qualité du Code** (30%)
   - Respect des principes SOLID
   - Lisibilité et maintenabilité
   - Standards de codage

2. **Architecture** (40%)
   - Choix des patterns appropriés
   - Séparation des responsabilités
   - Évolutivité de la solution

3. **Tests** (20%)
   - Couverture de tests
   - Qualité des tests
   - Tests d'intégration

4. **Documentation** (10%)
   - Documentation technique
   - README complet
   - Diagrammes d'architecture

### Système de Badges
- 🥉 **Bronze** : Challenge réussi avec score > 70%
- 🥈 **Silver** : Challenge réussi avec score > 85%
- 🥇 **Gold** : Challenge réussi avec score > 95%

## 📅 Planning des Sessions (16 semaines)

### 🕐 **Format des Sessions**
- **Durée** : 2h par semaine
- **Format** : 30min théorie + 90min pratique
- **Approche** : Tests unitaires fournis, code de production à implémenter
- **Application** : Exemples directement utilisables dans vos projets

### 📊 **Progression par Semaine**

| Semaine | Thème | Pattern/Concept | Application Immédiate |
|---------|-------|-----------------|----------------------|
| 1 | Refactoring | Single Responsibility | Classes existantes |
| 2 | DI Container | Dependency Injection | Services actuels |
| 3 | Factory | Factory Pattern | Notifications |
| 4 | Strategy | Strategy Pattern | Calculs de prix |
| 5 | Repository | Repository Pattern | Accès aux données |
| 6 | Service Layer | Service Layer | Logique métier |
| 7 | DTO | Data Transfer Objects | APIs |
| 8 | Command | Command Pattern | Actions complexes |
| 9 | Observer | Observer Pattern | Événements |
| 10 | Decorator | Decorator Pattern | Services transversaux |
| 11 | Adapter | Adapter Pattern | APIs externes |
| 12 | Facade | Facade Pattern | APIs complexes |
| 13 | CQRS | Command Query Separation | Performance |
| 14 | Event Sourcing | Event Sourcing | Audit trail |
| 15 | Hexagonal | Ports & Adapters | Isolation métier |
| 16 | Final | Refactoring complet | Applications legacy |

## 🛠️ Outils et Environnement

### Stack Technique
- **PHP 8.1+**
- **Composer** : Gestion des dépendances
- **PHPUnit** : Tests unitaires
- **Docker** : Environnement de développement
- **Git** : Contrôle de version

### Outils de Qualité
- **PHPStan** : Analyse statique
- **PHP CS Fixer** : Formatage de code
- **Psalm** : Analyse de types
- **PHPUnit Coverage** : Couverture de tests

## 📝 Livrables Attendus

Pour chaque challenge :
1. **Code source** complet et fonctionnel
2. **Tests unitaires** avec couverture > 80%
3. **Documentation** technique (README.md)
4. **Diagrammes** d'architecture (Mermaid ou PlantUML)
5. **Démo** fonctionnelle du challenge

## 🎯 Objectifs de Compétences

À la fin du programme, les développeurs devront maîtriser :

### Compétences Techniques
- [ ] **Refactoring** : Transformer du code legacy en code maintenable
- [ ] **Patterns** : 12 patterns de conception directement applicables
- [ ] **Architecture** : Séparation des responsabilités et couches
- [ ] **Tests** : Tests unitaires avec mocks et stubs
- [ ] **DI Container** : Injection de dépendances et inversion de contrôle
- [ ] **APIs** : DTOs, Commands, et architecture REST propre

### Compétences Transversales
- [ ] **Quick Wins** : Améliorer immédiatement le code existant
- [ ] **Code Review** : Identifier les problèmes d'architecture
- [ ] **Refactoring** : Planifier et exécuter des refactorings
- [ ] **Documentation** : Documenter les décisions architecturales
- [ ] **Mentoring** : Transmettre les bonnes pratiques à l'équipe

## 🚀 Quick Wins pour Projets Existants

### Semaine 1-4 : Améliorations Immédiates
- **Refactoring** : Identifier et séparer les "God Classes"
- **DI** : Remplacer les `new` par de l'injection
- **Factory** : Centraliser la création d'objets complexes
- **Strategy** : Éliminer les `if/else` pour les calculs

### Semaine 5-8 : Architecture Propre
- **Repository** : Extraire le SQL des contrôleurs
- **Service Layer** : Centraliser la logique métier
- **DTOs** : Structurer les données d'API
- **Commands** : Encapsuler les actions complexes

### Semaine 9-12 : Patterns Avancés
- **Observer** : Découpler les actions secondaires
- **Decorator** : Ajouter des fonctionnalités transversales
- **Adapter** : Intégrer facilement de nouveaux fournisseurs
- **Facade** : Simplifier les APIs complexes

### Semaine 13-16 : Architecture Moderne
- **CQRS** : Optimiser les performances de lecture
- **Event Sourcing** : Ajouter un audit trail
- **Hexagonal** : Isoler la logique métier
- **Refactoring** : Planifier la modernisation

## 🚀 Prochaines Étapes

1. **Validation du plan** avec l'équipe
2. **Création des environnements** de développement
3. **Développement du premier challenge** (1.1)
4. **Formation des mentors** sur les concepts
5. **Lancement du programme** avec l'équipe

---

*Ce programme est conçu pour être adaptatif. Les challenges peuvent être ajustés selon le niveau et les besoins spécifiques de chaque développeur.*
