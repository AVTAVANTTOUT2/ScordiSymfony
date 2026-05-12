# DiscordClone (Symfony 7.1, mode MAMP strict)

Application type Discord construite en MVC strict sur Symfony 7.1, compatible **MAMP uniquement** (Apache + MySQL de MAMP).

## Contraintes d’exécution

- **Aucun Docker**
- **Aucun `symfony serve`**
- **Aucun service externe obligatoire**
- Apache MAMP (port `8888`)
- MySQL MAMP (port `8889`, `root/root`)

URL principale: `http://localhost:8888/discord-clone/public/`

## Stack

- PHP MAMP (8.2+)
- Symfony 7.1
- Doctrine ORM 3 + Migrations
- Twig 3
- Stimulus (polling AJAX)
- TailwindCSS (build statique)
- PHPUnit

## Installation (1 commande)

1. Cloner dans `/Applications/MAMP/htdocs/discord-clone`
2. Démarrer MAMP (Apache + MySQL)
3. Lancer:

```bash
cd /Applications/MAMP/htdocs/discord-clone
./install-mamp.sh
```

## Vérification en 30 secondes

1. Ouvre `http://localhost:8888/discord-clone/public/`
2. Connecte-toi avec `alice@test.com / password`
3. Ouvre un serveur puis un salon `#general`
4. Poste un message dans l’input
5. Ouvre un second onglet avec `bob@test.com / password`, même salon
6. Vérifie que les nouveaux messages apparaissent automatiquement (polling ~2s)

## Fonctionnalités disponibles

- Auth: inscription, connexion, déconnexion, remember me
- Serveurs: création + salon `general` par défaut
- Salons texte: navigation serveur/salon
- Messages: envoi + rafraîchissement quasi temps réel (polling AJAX)
- Historique: chargement de messages plus anciens au scroll haut
- Présence: heartbeat utilisateur via endpoint API
- Invitations: génération + join par code
- DM: recherche utilisateur, création de thread, messagerie 1-1
- Profil: édition bio
- Permissions: voters `SERVER_MANAGE`, `MEMBER_KICK`, `CHANNEL_VIEW`, `CHANNEL_MANAGE`, `MESSAGE_DELETE_ANY`

## Architecture MVC

- **Model**: `src/Entity`, `src/Repository`, `src/Service`, `src/Security/Voter`
- **View**: `templates/`
- **Controller**: `src/Controller` + `src/Controller/Api`

## Endpoints polling

- `GET /api/channel/{id}/poll?since=TIMESTAMP`
- `GET /api/channel/{id}/history?before=MESSAGE_ID`
- `POST /api/presence/heartbeat`

## Comptes de test (fixtures)

- `alice@test.com / password`
- `bob@test.com / password`
- `admin@test.com / password`

## Bonus: VirtualHost MAMP (optionnel)

Tu peux créer un VirtualHost pour une URL propre (`http://discord.local`) pointant vers:

`/Applications/MAMP/htdocs/discord-clone/public`

Sinon le mode sous-dossier est déjà prêt avec `public/.htaccess`.

