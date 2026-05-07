#!/bin/bash

# Cores para saída
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # Sem cor

echo -e "${BLUE}===> Iniciando Deploy do Sistema (Versão ARM + Postgres) <===${NC}"

# 0. Ajuste de segurança do Git
git config --global --add safe.directory /home/ubuntu/Eternizar/AlbumCompartilhado

# 1. Sincronizar código com o GitHub
echo -e "${BLUE}1. Sincronizando código com GitHub...${NC}"
git fetch origin main
git reset --hard origin/main

# 2. Construir a imagem localmente (Obrigatório para ARM e para o Driver Postgres)
echo -e "${BLUE}2. Construindo imagem local (isso pode demorar uns minutos)...${NC}"
docker compose build --no-cache

# 3. Reiniciar os containers
echo -e "${BLUE}3. Subindo containers...${NC}"
docker compose up -d --remove-orphans

# 4. Aguardar o banco e o app estabilizarem
echo -e "${BLUE}4. Aguardando estabilização (5s)...${NC}"
sleep 5

# 5. Rodar migrações e limpar caches
echo -e "${BLUE}5. Executando comandos internos do Laravel...${NC}"
docker exec eternizar-app php artisan migrate --force
docker exec eternizar-app php artisan config:clear
docker exec eternizar-app php artisan view:clear
docker exec eternizar-app php artisan cache:clear

# 6. Permissões de Storage e Cache
echo -e "${BLUE}6. Ajustando permissões de storage e cache...${NC}"
chmod -R 777 storage bootstrap/cache

# 7. Limpeza de disco
echo -e "${BLUE}7. Limpando imagens antigas...${NC}"
docker system prune -f

echo -e "${GREEN}===> DEPLOY FINALIZADO COM SUCESSO! <===${NC}"
echo -e "${GREEN}Site online em: https://eternizar.gguise.com.br${NC}"
