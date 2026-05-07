#!/bin/bash

# Cores para saída
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # Sem cor

echo -e "${BLUE}===> Iniciando Deploy do Sistema <===${NC}"

# 1. Puxar a imagem mais recente do GitHub Container Registry
echo -e "${BLUE}1. Baixando imagem mais recente...${NC}"
docker compose pull

# 2. Reiniciar os containers com a nova imagem
echo -e "${BLUE}2. Reiniciando containers...${NC}"
docker compose up -d

# 3. Rodar migrações do banco de dados (caso haja novos campos)
echo -e "${BLUE}3. Rodando migrações do banco...${NC}"
docker exec laravel-app php artisan migrate --force

# 4. Limpar cache (opcional, ajuda a evitar bugs de config antiga)
echo -e "${BLUE}4. Limpando caches...${NC}"
docker exec laravel-app php artisan config:clear
docker exec laravel-app php artisan view:clear

# 5. Limpar imagens antigas (ajuda a não encher o disco do servidor)
echo -e "${BLUE}5. Limpando imagens órfãs...${NC}"
docker image prune -f

echo -e "${GREEN}===> DEPLOY FINALIZADO COM SUCESSO! <===${NC}"
echo -e "${GREEN}Site online em: https://eternizar.gguise.com.br${NC}"
