# Rapport de tests — DiscordClone

## Résumé

- Tests unitaires: **7/7** passés
- Tests fonctionnels (WebTestCase): **2/2** passés
- Tests E2E Chrome MCP: **non exécutables dans cet environnement** (limite API atteinte côté agent navigateur)
- Fallback Panther: **1 scénario lancé, 1 échec infra/app à diagnostiquer**
- Coverage: **non calculable ici** (driver de couverture manquant dans l'environnement CLI)
- Bugs trouvés et corrigés: **2**
- Statut final: **⚠️ Non prêt pour livraison complète des 12 scénarios E2E**

## Détail tests unitaires

- `tests/Service/MarkdownRendererTest.php` ✅
- `tests/Service/MercurePublisherTest.php` ✅
- `tests/Service/MessageServiceTest.php` ✅
- `tests/Service/InvitationServiceTest.php` ✅
- `tests/Service/ServerServiceTest.php` ✅

Résultat global `php bin/phpunit`: **7 tests, 16 assertions**

## Détail tests fonctionnels

- `tests/Controller/SecurityControllerTest.php`
  - GET `/login` ✅
  - POST `/login` invalide (redirect + retour page login) ✅

Résultat `php bin/phpunit tests/Controller`: **2 tests, 6 assertions**

## Détail E2E

### Cible demandée: Chrome MCP (12 scénarios)

- Bloqué par l'environnement d'exécution agent: erreur `API usage limit reached` sur l'agent navigateur.

### Fallback exécuté: Symfony Panther

- Préparation effectuée:
  - `symfony/panther` installé
  - `dbrekelmans/bdi` installé
  - `chromedriver` installé dans `drivers/chromedriver`
- Test exécuté:
  - `tests/E2E/LoginPantherTest.php` ❌
  - Échec observé: page rendue `"Symfony Exception"` au lieu de `"Connexion"`

## Bugs trouvés et corrigés

| # | Zone | Bug | Fix |
|---|---|---|---|
| 1 | Tests fonctionnels | Initialisation DB test incompatible avec `createClient()` (kernel déjà booté) | Refactor vers `ControllerTestCase` + init DB après `createClient()` |
| 2 | Mapping Doctrine | Dépréciation sur contrainte unique `ServerMember` | Migration vers `#[ORM\UniqueConstraint(...)]` |

## Limites de cette exécution

1. **Chrome MCP indisponible** dans l'environnement de run agent (quota API navigateur).
2. **Couverture non mesurable**: aucune extension de couverture installée (`Xdebug`/`PCOV` absente).
3. Le projet applicatif ne couvre pas encore toutes les features requises par le plan E2E (DM complets, présence temps réel complète, etc.), donc les 12 scénarios ne peuvent pas tous passer à ce stade.

## Captures

- Aucune capture MCP disponible (blocage d’accès navigateur agent).
