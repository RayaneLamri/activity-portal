# Activity Portal

Activity Portal est une version simplifiee et reconstruite d'une application metier developpee dans un contexte professionnel pour un organisme de colonies de vacances.

Le besoin initial etait de permettre a des moniteurs de consulter les camps disponibles, d'indiquer leurs preferences de lieu, de periode et de tranche d'age, puis de demander une inscription. Cote administration, l'objectif etait de centraliser le suivi des demandes, d'inviter les moniteurs les plus pertinents selon leurs preferences, de valider ou refuser les inscriptions, et d'exporter les futurs camps pour le suivi operationnel.

Stack principale : Laravel 12, PHP 8.2+, Breeze, Blade, Vite, Alpine.js, SQLite, Maatwebsite Excel.

## Besoins metier et choix techniques

- **Deux espaces distincts** : Laravel Breeze fournit l'authentification, completee par des roles `admin` / `moniteur` et des middlewares dedies.
- **Recherche de camps pertinents** : les moniteurs peuvent filtrer les camps par ville, periode et tranche d'age, puis enregistrer des preferences reutilisees dans l'application.
- **Regles d'inscription centralisees** : les demandes, invitations, acceptations, refus, doublons, capacites et profils visibles sont geres dans un service metier dedie.
- **Administration des demandes** : l'admin peut filtrer les inscriptions, consulter les preferences des moniteurs, accepter/refuser une demande et envoyer des invitations ciblees.
- **Export operationnel** : les futurs camps peuvent etre exportes au format Excel avec les participants acceptes.
- **Demo locale rapide** : SQLite et les seeders fournissent des donnees couvrant plusieurs cas metier : camps disponibles, complets, inscriptions en attente, invitations et preferences variees.

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build
php artisan serve
```

L'application est accessible sur :

```text
http://127.0.0.1:8000
```

## Comptes de demonstration

Tous les comptes utilisent le mot de passe :

```text
password
```

Admin :

```text
admin@example.com
```

Moniteurs :

```text
marion@example.com
antoine@example.com
enzo@example.com
maxime@example.com
```

## Parcours de demonstration

### Parcours moniteur

Se connecter avec `marion@example.com`.

1. Consulter la liste des camps disponibles.
2. Filtrer les camps par ville, periode ou tranche d'age.
3. Enregistrer des preferences.
4. Appliquer les preferences pour voir les camps correspondants.
5. Envoyer une demande d'inscription.
6. Consulter ses inscriptions et verifier le statut.

<!-- ![Liste des camps cote moniteur](docs/screenshots/monitor-activities.png) -->

### Parcours administrateur

Se connecter avec `admin@example.com`.

1. Ouvrir la vue admin des inscriptions.
2. Filtrer les camps ou les demandes.
3. Accepter ou refuser une demande.
4. Ouvrir la page des moniteurs.
5. Consulter les preferences d'un moniteur.
6. Envoyer une invitation vers un camp pertinent.
7. Exporter les futurs camps au format Excel.

<!-- ![Vue admin des inscriptions](docs/screenshots/admin-registrations.png) -->
<!-- ![Invitation ciblee](docs/screenshots/admin-invite.png) -->

## Tests

```bash
composer test
```

## Ameliorations possibles

- ajouter davantage de tests metier automatises
- utiliser des Policies Laravel pour affiner les autorisations
- envoyer les notifications via queue
- enrichir l'historique des decisions admin
- ajouter une CI pour lancer formatage, tests et build