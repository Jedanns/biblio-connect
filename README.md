# BiblioConnect - Plateforme de gestion de bibliothèque

Projet Symfony 8.0 - Plateforme de gestion moderne pour une bibliothèque avec espace usagers et interface d'administration.

## Prérequis

- PHP 8.3+ avec extensions : intl, mbstring, pdo_sqlite, curl, openssl, zip, gd, fileinfo
- Composer 2.x
- Symfony CLI (optionnel, pour `symfony serve`)

## Installation

```bash
# 1. Cloner le projet
git clone <url-du-repo>
cd biblio-connect

# 2. Installer les dépendances
composer install

# 3. Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction

# 4. Charger les fixtures (données de test)
php bin/console doctrine:fixtures:load --no-interaction

# 5. Installer les assets JavaScript
php bin/console importmap:install

# 6. Lancer le serveur
symfony serve
# ou
php -S localhost:8000 -t public/
```

## Identifiants de test

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| **Admin** | admin@biblioconnect.fr | admin123 |
| **Bibliothécaire** | librarian1@biblioconnect.fr | librarian123 |
| **Utilisateur** | user1@biblioconnect.fr | user123 |

10 utilisateurs sont disponibles : user1@ à user10@biblioconnect.fr (mot de passe : user123)

## Lancer les tests

```bash
# Préparer la base de test
php bin/console doctrine:schema:create --env=test
php bin/console doctrine:fixtures:load --env=test --no-interaction

# Exécuter les tests
php bin/phpunit
```

## Fonctionnalités

### Usagers (ROLE_USER)
- Inscription avec validation et double vérification du mot de passe
- Connexion / déconnexion
- Recherche d'ouvrages (par titre, auteur, catégorie)
- Réservation de livres avec choix de dates
- Ajout de livres en favoris
- Consultation du profil (réservations + favoris)
- Notation et commentaire des livres (système 1-5 étoiles)

### Bibliothécaires (ROLE_LIBRARIAN)
- Gestion du catalogue : ajout/modification de livres, auteurs, catégories
- Upload d'images de couverture
- Consultation de l'historique des réservations

### Administrateurs (ROLE_ADMIN)
- Tout ce que peut faire un bibliothécaire
- Gestion des rôles et profils utilisateurs
- Modération des commentaires (approuver/rejeter)
- Suivi des stocks et réservations en attente
- Activation des réservations et gestion des retours
- Historique global des réservations

## Architecture

- **8 entités** : User, Book, Author, Category, Language, Reservation, Review, Favorite
- **Symfony 8.0** avec Asset Mapper, Bootstrap 5, Twig
- **SQLite** par défaut (modifiable via `DATABASE_URL` dans `.env.local`)
- **Sécurité** : form_login, CSRF, rôles hiérarchiques, access_control
- **Tests** : PHPUnit avec DAMA DoctrineTestBundle pour isolation par transaction

## Structure du projet

```
src/
├── Controller/
│   ├── Admin/AdminController.php
│   ├── Librarian/CatalogController.php
│   ├── BookController.php
│   ├── FavoriteController.php
│   ├── HomeController.php
│   ├── ProfileController.php
│   ├── ReservationController.php
│   ├── ReviewController.php
│   └── SecurityController.php
├── Entity/          (8 entités Doctrine)
├── Form/            (8 FormTypes)
├── Repository/      (8 repositories avec requêtes custom)
├── Service/         (FileUploader, ReservationService)
└── DataFixtures/    (8 fixtures avec données réalistes)
```
