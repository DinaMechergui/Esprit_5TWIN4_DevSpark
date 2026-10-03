# Alerte Canicule — plateforme d'alertes (canicule & coupures de courant)

Application Laravel 12 (Blade, Breeze) en français pour diffuser les alertes de canicule
et les coupures de courant, avec un front office public et un back office réservé aux
administrateurs.

- **Front office** : template `VaultEdge` (Bootstrap 4.1.3).
- **Back office** : template `Sneat` (Bootstrap 5).
- **Module à venir** : « Points de fraîcheur » (`PointFraicheur` lié à `Quartier`).

## Prérequis

- PHP >= 8.2 (ex. XAMPP : `C:\xampp\php`)
- Composer 2.x
- MySQL 8 (ou MariaDB)
- Node.js >= 18 (uniquement pour reconstruire les assets, non requis au démarrage)

## Installation

```bash
composer install
cp .env.example .env        # sous Windows : Copy-Item .env.example .env
php artisan key:generate
```

1. Créer la base de données `alerte_canicule` (UTF-8).
2. Ajuster `DB_*` dans `.env` si nécessaire (hôte, utilisateur, mot de passe).
3. Lancer le schéma et les données de démonstration :

```bash
php artisan migrate:fresh --seed
```

4. Démarrer le serveur :

```bash
php artisan serve           # http://127.0.0.1:8000
```

Optionnel (reconstruction des assets, sans usage dans les layouts) :

```bash
npm install
npm run build
```

## Comptes créés par le seed

| Rôle           | E-mail              | Mot de passe |
|----------------|---------------------|--------------|
| Administrateur | `admin@example.com` | `password`   |
| Utilisateur    | `user@example.com`  | `password`   |

10 utilisateurs supplémentaires sont générés (données de démonstration).

---

## Arborescence (front office / back office séparés)

```
C:\laravel project\
│
├── app/                                    CODE PHP — 2 espaces distincts
│   ├── BackOffice/                         ══ BACK OFFICE (réservé aux admins)
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php     /admin — statistiques & activité
│   │   │   └── UserController.php          CRUD /admin/users
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php         alias « admin » → 403 si non-admin
│   │   └── Requests/
│   │       ├── StoreUserRequest.php        validation création
│   │       └── UpdateUserRequest.php       validation modification
│   │
│   ├── FrontOffice/                        ══ FRONT OFFICE (public + espace user)
│   │   ├── Controllers/
│   │   │   ├── Auth/                       connexion, inscription, mots de passe
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── ConfirmablePasswordController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── EmailVerificationPromptController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   ├── HomeController.php          page d'accueil /
│   │   │   └── ProfileController.php       /profile
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php       validation connexion
│   │       └── ProfileUpdateRequest.php    validation profil
│   │
│   ├── Http/Controllers/Controller.php     contrôleur de base PARTAGÉ
│   ├── Models/User.php                     modèle PARTAGÉ (rôle admin/user)
│   ├── Providers/AppServiceProvider.php
│   └── Support/AuthRedirect.php            redirections post-login PARTAGÉES
│
├── bootstrap/app.php                       alias middleware « admin »
│
├── routes/                                 ROUTES — 1 fichier par espace
│   ├── web.php                             point d'entrée : charge front + back
│   ├── front.php                           FRONT : /, /profile, module, auth
│   ├── auth.php                            FRONT : login, register, mdp, vérif.
│   ├── back.php                            BACK : /admin, /admin/users, module
│   ├── console.php
│   └── channels.php
│
├── resources/views/
│   ├── front/                              ══ VUES DU FRONT OFFICE
│   │   ├── layouts/front.blade.php         gabarit général (sans @vite)
│   │   ├── partials/
│   │   │   ├── header.blade.php            barre de navigation
│   │   │   ├── footer.blade.php
│   │   │   └── flash-messages.blade.php
│   │   ├── pages/
│   │   │   └── home.blade.php              page d'accueil
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   ├── forgot-password.blade.php
│   │   │   ├── reset-password.blade.php
│   │   │   ├── verify-email.blade.php
│   │   │   └── confirm-password.blade.php
│   │   ├── profile/
│   │   │   ├── edit.blade.php
│   │   │   └── partials/
│   │   │       ├── update-profile-information-form.blade.php
│   │   │       ├── update-password-form.blade.php
│   │   │       └── delete-user-form.blade.php
│   │   └── points-fraicheur/               module à créer (index, show)
│   │
│   ├── back/                               ══ VUES DU BACK OFFICE
│   │   ├── layouts/back.blade.php          gabarit général (sans @vite)
│   │   ├── partials/
│   │   │   ├── sidebar.blade.php           menu latéral
│   │   │   ├── topbar.blade.php            barre supérieure
│   │   │   ├── flash-messages.blade.php
│   │   │   └── footer.blade.php
│   │   ├── pages/
│   │   │   ├── dashboard.blade.php         tableau de bord
│   │   │   └── users/
│   │   │       ├── index.blade.php         liste + recherche
│   │   │       ├── create.blade.php
│   │   │       ├── edit.blade.php
│   │   │       └── show.blade.php
│   │   └── points-fraicheur/               module à créer (CRUD)
│   │
│   ├── components/                         COMPOSANTS PARTAGÉS (front + back)
│   │   ├── alert.blade.php
│   │   ├── card.blade.php
│   │   ├── form-input.blade.php
│   │   ├── form-select.blade.php
│   │   ├── page-header.blade.php
│   │   └── pagination.blade.php
│   │
│   └── errors/                             ERREURS PARTAGÉES (résolues par Laravel)
│       ├── 403.blade.php
│       ├── 404.blade.php
│       └── 500.blade.php
│
├── public/
│   ├── assets/front/                       templates front (VaultEdge) : css, js, img, fonts
│   ├── assets/back/                        templates back (Sneat) : css, js, img, vendor
│   └── build/                              sortie Vite (non utilisée par les layouts)
│
├── lang/fr/                                traductions françaises (laravel-lang)
│   ├── fr.json
│   └── {auth,pagination,passwords,validation,...}.php
│
├── database/
│   ├── migrations/                         schéma (+ rôle dans users)
│   ├── factories/UserFactory.php           états user / admin
│   └── seeders/DatabaseSeeder.php          comptes de démonstration
│
├── tests/
│   ├── Feature/
│   │   ├── AdminAccessTest.php             403 back office, accès admin
│   │   ├── PagesRenderTest.php             rendu de toutes les pages
│   │   ├── ProfileTest.php
│   │   └── Auth/{Authentication,Registration,EmailVerification,...}Test.php
│   └── Unit/
│
├── .env / .env.example                     fr, Europe/Paris, MySQL alerte_canicule
├── phpunit.xml                             tests en SQLite mémoire
├── vite.config.js, package.json
└── README.md
```

### Conventions de nommage

| Élément            | Front office                  | Back office                   |
|--------------------|-------------------------------|-------------------------------|
| Namespace PHP      | `App\FrontOffice\...`         | `App\BackOffice\...`          |
| Fichier de routes  | `routes/front.php` (+ `auth.php`) | `routes/back.php`         |
| Préfixe de route   | `front.*`, `login`, `profile.*` | `admin.*` (URI `/admin`)   |
| Vues               | `resources/views/front/*`     | `resources/views/back/*`      |
| Layout             | `front.layouts.front`         | `back.layouts.back`           |
| Assets             | `public/assets/front/`        | `public/assets/back/`         |

Éléments volontairement partagés : `app/Models`, `app/Support/AuthRedirect.php`,
`app/Http/Controllers/Controller.php`, `resources/views/components/`,
`resources/views/errors/` (Laravel résout `errors.403` à cet emplacement).

---

## Routes principales

| Méthode | URI                   | Nom               | Fichier          | Description                |
|---------|-----------------------|-------------------|------------------|----------------------------|
| GET     | `/`                   | `front.home`      | `routes/front.php` | Accueil (public)         |
| GET     | `/login`, `/register` | `login`,`register`| `routes/auth.php`  | Authentification (public) |
| GET     | `/profile`            | `profile.edit`    | `routes/front.php` | Profil (connecté)         |
| GET     | `/admin`              | `admin.dashboard` | `routes/back.php`  | Tableau de bord (admin)   |
| *       | `/admin/users`        | `admin.users.*`   | `routes/back.php`  | CRUD utilisateurs (admin) |

- Le middleware `admin` (`app/BackOffice/Middleware/AdminMiddleware.php`, alias déclaré
  dans `bootstrap/app.php`) renvoie **403** aux non-administrateurs.
- Après connexion, un administrateur est dirigé vers `/admin`, un utilisateur vers `/`
  (voir `app/Support/AuthRedirect.php`).

## Points d'attention

- **Pas de `@vite`** dans les layouts : CSS/JS chargés depuis `public/assets/...`
  (aucun lien en dur, toujours `asset()` / `route()`).
- Langue `fr`, fuseau `Europe/Paris`, données de démo `fr_FR`.
- Le CRUD « Points de fraîcheur » reste à créer : routes commentées dans
  `routes/front.php` et `routes/back.php`, dossiers vides prêts dans
  `resources/views/{front,back}/points-fraicheur/`.

## Tests

```bash
php artisan test
```

34 tests — SQLite en mémoire (`phpunit.xml`).
