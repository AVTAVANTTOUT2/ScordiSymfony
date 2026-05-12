#!/bin/bash
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$PROJECT_DIR"

echo "📦 Installation DiscordClone (mode MAMP strict)"

if [ ! -d "/Applications/MAMP" ]; then
  echo "❌ MAMP introuvable dans /Applications/MAMP"
  exit 1
fi

LATEST_PHP_BIN="$(ls -d /Applications/MAMP/bin/php/php*/bin 2>/dev/null | sort -V | tail -n 1)"
if [ -z "${LATEST_PHP_BIN:-}" ]; then
  echo "❌ Aucun binaire PHP MAMP trouvé dans /Applications/MAMP/bin/php/"
  exit 1
fi
export PATH="$LATEST_PHP_BIN:$PATH"
echo "✅ PHP MAMP détecté: $(php -v | head -n 1)"

if ! command -v composer >/dev/null 2>&1; then
  echo "❌ Composer non trouvé dans le PATH"
  exit 1
fi

if ! command -v npm >/dev/null 2>&1; then
  echo "❌ npm non trouvé. Installe Node.js pour compiler Tailwind une fois."
  exit 1
fi

echo "➡️ Installation dépendances PHP"
composer install --no-interaction

echo "➡️ Installation dépendances JS et build CSS"
npm install
npm run build

echo "➡️ Création de la base MySQL MAMP"
MYSQL_BIN="$(echo /Applications/MAMP/Library/bin/mysql*/bin/mysql | awk '{print $NF}')"
"$MYSQL_BIN" -h 127.0.0.1 -P 8889 -u root -proot \
  -e "CREATE DATABASE IF NOT EXISTS discord_clone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "➡️ Migrations + fixtures"
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction

echo "➡️ Permissions locales"
mkdir -p var public/uploads public/build
chmod -R 777 var public/uploads

echo "✅ Installation terminée"
echo "👉 URL: http://localhost:8888/discord-clone/public/"
echo "👉 Comptes de test: alice@test.com / password | bob@test.com / password"
