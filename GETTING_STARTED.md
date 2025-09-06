# 🚀 Guide de Démarrage Rapide

## 📋 Prérequis

- Docker et Docker Compose installés
- Git installé
- Éditeur de code (VS Code, PhpStorm, etc.)

## 🏃‍♂️ Démarrage en 3 étapes

### 1. Cloner et démarrer l'environnement

```bash
# Cloner le repository
git clone <url-du-repo>
cd PHP

# Démarrer l'environnement de développement
docker-compose up -d

# Entrer dans le container
docker-compose exec php bash
```

### 2. Installer les dépendances

```bash
# Dans le container
composer install
```

### 3. Lancer les tests

```bash
# Tester l'environnement
composer test

# Voir la couverture de code
composer test-coverage
```

## 🎯 Challenge 1 : Refactoring

### Objectif
Refactoriser la classe `UserManager` qui viole le Single Responsibility Principle.

### Fichiers à modifier
- `challenges/session-01/src/UserManager.php` - Classe à refactoriser
- Créer les nouvelles classes dans `challenges/session-01/src/`

### Tests à faire passer
```bash
# Lancer les tests du challenge 1
./vendor/bin/phpunit challenges/session-01/tests/UserManagerTest.php

# Voir la couverture
./vendor/bin/phpunit challenges/session-01/tests/UserManagerTest.php --coverage-html coverage
```

## 🛠️ Outils disponibles

### Tests
```bash
composer test                    # Lancer tous les tests
composer test-coverage          # Tests avec couverture
./vendor/bin/phpunit --help     # Aide PHPUnit
```

### Qualité du code
```bash
composer phpstan               # Analyse statique
composer cs-check              # Vérifier le style de code
composer cs-fix                # Corriger automatiquement le style
composer quality               # Tous les contrôles de qualité
```

### Docker
```bash
docker-compose up -d           # Démarrer l'environnement
docker-compose down            # Arrêter l'environnement
docker-compose exec php bash   # Entrer dans le container
docker-compose logs php        # Voir les logs
```

## 📁 Structure du projet

```
challenges/
├── session-01/                # Challenge 1 : Refactoring
│   ├── README.md             # Instructions du challenge
│   ├── src/                  # Code source à modifier
│   │   └── UserManager.php   # Classe à refactoriser
│   └── tests/                # Tests unitaires
│       └── UserManagerTest.php
├── session-02/               # Challenge 2 : DI Container
└── ...

data/                         # Base de données SQLite
logs/                         # Fichiers de logs
coverage/                     # Rapports de couverture
```

## 🎓 Conseils pour réussir

1. **Lisez les tests** : Ils vous guident vers la solution
2. **Une classe à la fois** : Refactorisez progressivement
3. **Testez souvent** : Vérifiez que les tests passent à chaque étape
4. **Respectez les interfaces** : Les tests définissent les contrats
5. **Documentez** : Commentez vos décisions architecturales

## ⚠️ **IMPORTANT : Comprendre l'objectif des mocks**

### **Les mocks sont l'OBJECTIF, pas quelque chose à éliminer !**

- **Départ** : Code avec problèmes (dépendances hardcodées, God Classes)
- **Objectif** : Code refactorisé et testable avec mocks
- **Résultat** : Tests passent grâce aux mocks

### **Pourquoi les mocks sont la récompense :**
- ✅ **Tests isolés** : Pas d'effets de bord
- ✅ **Tests rapides** : Pas de vraies dépendances
- ✅ **Tests fiables** : Comportement déterministe
- ✅ **Code flexible** : Changement d'implémentation facile

## 🆘 Aide

- **Tests qui échouent** : Lisez les messages d'erreur attentivement
- **Problèmes Docker** : `docker-compose down && docker-compose up -d`
- **Dépendances** : `composer install` dans le container
- **Permissions** : Les fichiers sont montés depuis votre machine

## 🎯 Objectifs de qualité

- ✅ Tous les tests passent
- ✅ Couverture de code > 90%
- ✅ Aucune erreur PHPStan
- ✅ Code conforme PSR-12
- ✅ Documentation claire

---

**Bon coding ! 🚀**
