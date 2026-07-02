# Makefile — ChronoSite API
#
# Commandes principales :
#   make setup   → Installation complète (première fois uniquement)
#   make start   → Démarrer les conteneurs (tous les jours)
#   make stop    → Arrêter les conteneurs
#
# Tape "make" sans argument pour voir toutes les commandes.

.DEFAULT_GOAL := help

# ── Variables ──────────────────────────────────────────────────────────────────
PHP     = docker compose exec php
CONSOLE = $(PHP) php bin/console

# ── Aide ───────────────────────────────────────────────────────────────────────

help: ## Afficher toutes les commandes disponibles
	@echo ""
	@echo "  ChronoSite API — commandes disponibles"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-22s\033[0m %s\n", $$1, $$2}'
	@echo ""

# ── Commandes principales ──────────────────────────────────────────────────────

setup: ## 🚀 Installation complète (à lancer une seule fois)
	@echo "\n  Installation de ChronoSite API...\n"
	docker compose build
	docker compose up -d
	$(PHP) composer install
	$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	@echo "\n  ✅ Prêt ! API disponible sur http://localhost:8080\n"

start: ## ▶️  Démarrer les conteneurs (commande du quotidien)
	docker compose up -d
	@echo "\n  ✅ ChronoSite API démarré sur http://localhost:8080\n"

stop: ## ⏹  Arrêter les conteneurs
	docker compose down
	@echo "\n  Conteneurs arrêtés.\n"

restart: stop start ## 🔄 Redémarrer les conteneurs

# ── Docker ─────────────────────────────────────────────────────────────────────

build: ## Reconstruire les images Docker (après modification du Dockerfile)
	docker compose build --no-cache

ps: ## État des conteneurs
	docker compose ps

logs: ## Logs de tous les conteneurs en temps réel (Ctrl+C pour quitter)
	docker compose logs -f

logs-php: ## Logs PHP uniquement
	docker compose logs -f php

logs-nginx: ## Logs Nginx uniquement
	docker compose logs -f nginx

# ── Accès aux conteneurs ────────────────────────────────────────────────────────

shell: ## Ouvrir un terminal dans le conteneur PHP
	docker compose exec php sh

shell-db: ## Ouvrir psql dans PostgreSQL
	docker compose exec postgres psql -U chronosite -d chronosite

# ── Composer ───────────────────────────────────────────────────────────────────

install: ## composer install
	$(PHP) composer install

require: ## Ajouter un package   ex: make require PKG="symfony/orm-pack"
	$(PHP) composer require $(PKG)

require-dev: ## Ajouter un package dev   ex: make require-dev PKG="symfony/test-pack"
	$(PHP) composer require --dev $(PKG)

# ── Base de données ────────────────────────────────────────────────────────────

db-create: ## Créer la base de données
	$(CONSOLE) doctrine:database:create --if-not-exists

db-drop: ## Supprimer la base de données
	$(CONSOLE) doctrine:database:drop --force --if-exists

migration: ## Générer une migration depuis les entités modifiées
	$(CONSOLE) make:migration

migrate: ## Appliquer toutes les migrations en attente
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

db-reset: ## ⚠️  Remettre la base à zéro (supprime toutes les données)
	$(CONSOLE) doctrine:database:drop --force --if-exists
	$(CONSOLE) doctrine:database:create
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	@echo "\n  Base de données réinitialisée.\n"

db-validate: ## Vérifier la cohérence entités / schéma SQL
	$(CONSOLE) doctrine:schema:validate

# ── Symfony ────────────────────────────────────────────────────────────────────

cc: ## Vider le cache Symfony
	$(CONSOLE) cache:clear

routes: ## Lister toutes les routes définies
	$(CONSOLE) debug:router

# ── Tests ──────────────────────────────────────────────────────────────────────

test: ## Lancer tous les tests
	$(PHP) php bin/phpunit

.PHONY: help setup start stop restart build ps logs logs-php logs-nginx \
        shell shell-db install require require-dev \
        db-create db-drop migration migrate db-reset db-validate \
        cc routes test
