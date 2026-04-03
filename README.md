# BiblioConnect

Plateforme de gestion de bibliothèque — Symfony 8.0

## Installation

```bash
git clone https://github.com/Jedanns/biblio-connect.git
cd biblio-connect
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
php bin/console importmap:install
symfony serve
```

## Identifiants

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Admin | admin@biblioconnect.fr | admin123 |
| Bibliothécaire | librarian1@biblioconnect.fr | librarian123 |
| Utilisateur | user1@biblioconnect.fr à user10@ | user123 |

## Tests

```bash
php bin/console doctrine:schema:create --env=test
php bin/console doctrine:fixtures:load --env=test --no-interaction
php bin/phpunit
```

## Stack

- **Symfony 8.0** / PHP 8.5
- **Doctrine ORM** avec SQLite
- **Twig** + Bootstrap 5 via Asset Mapper
- **PHPUnit** + DAMA DoctrineTestBundle

## Fonctionnalités

### Usagers
- Inscription avec double vérification du mot de passe
- Recherche d'ouvrages par titre, auteur ou catégorie
- Réservation de livres avec choix de dates
- Favoris et avis (notation 1-5 étoiles + commentaire)
- Espace profil avec historique des réservations et favoris

### Bibliothécaires
- Gestion du catalogue : CRUD livres, auteurs, catégories
- Upload d'images de couverture
- Historique des réservations

### Administrateurs
- Gestion des utilisateurs et de leurs rôles
- Modération des avis (approbation / rejet)
- Suivi des stocks et réservations en attente
- Activation des réservations et gestion des retours

## Architecture

```
src/
├── Controller/          9 controllers (Home, Book, Security, Profile,
│                        Reservation, Favorite, Review, Librarian, Admin)
├── Entity/              8 entités (User, Book, Author, Category,
│                        Language, Reservation, Review, Favorite)
├── Repository/          8 repositories avec requêtes DQL
├── Form/                8 FormTypes
├── Service/             ReservationService, FileUploader
└── DataFixtures/        8 fixtures (30 livres, 15 auteurs, 13 users)
```

## Sécurité

Trois rôles hiérarchiques : `ROLE_USER` < `ROLE_LIBRARIAN` < `ROLE_ADMIN`.
Authentification par formulaire avec CSRF, remember-me, et hashage automatique des mots de passe.
Contrôle d'accès par route (`access_control`) et par attribut (`#[IsGranted]`).
