# 🐳 Docker - Comandos Básicos

Esta documentação contém os comandos essenciais para trabalhar com o ambiente Docker do projeto Laravel.

## 🚀 Comandos Iniciais

### **Iniciar o ambiente**
```bash
# Construir e iniciar containers
docker compose up --build -d

# Apenas iniciar (sem rebuild)
docker compose up -d
```

### **Parar o ambiente**
```bash
# Parar containers
docker compose down

# Parar e remover volumes (CUIDADO: apaga dados)
docker compose down -v
```

## 🛠️ Comandos de Desenvolvimento

### **Acessar o container**
```bash
# Entrar no shell do container
docker compose exec app bash

# Executar comando específico
docker compose exec app php artisan migrate
docker compose exec app composer install
```

### **Comandos Laravel**
```bash
# Gerar chave da aplicação
docker compose exec app php artisan key:generate

# Executar migrações
docker compose exec app php artisan migrate

# Limpar cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear

# Criar controller
docker compose exec app php artisan make:controller Api/UserController

# Ver rotas
docker compose exec app php artisan route:list
```

### **Comandos Composer**
```bash
# Instalar dependências
docker compose exec app composer install

# Atualizar dependências
docker compose exec app composer update

# Adicionar pacote
docker compose exec app composer require package/name
```

## 📊 Monitoramento

### **Ver status dos containers**
```bash
# Containers do projeto atual
docker compose ps

# Todos os containers
docker ps -a
```

### **Ver logs**
```bash
# Logs em tempo real
docker compose logs -f app

# Logs de todos os serviços
docker compose logs -f

# Últimas 50 linhas
docker compose logs --tail=50 app
```

### **Uso de recursos**
```bash
# Estatísticas de uso
docker stats

# Estatísticas sem stream
docker stats --no-stream
```

## 🔧 Manutenção

### **Build e rebuild**
```bash
# Build forçado (sem cache)
docker compose build --no-cache

# Rebuild e iniciar
docker compose up -d --build

# Recriar containers
docker compose up -d --force-recreate
```

### **Limpeza**
```bash
# Remover containers parados
docker container prune

# Remover imagens não utilizadas
docker image prune

# Limpeza completa (CUIDADO)
docker system prune -a
```

## 🌐 Acesso à Aplicação

- **URL da aplicação**: http://localhost:9090
- **Container**: starter-pack-app
- **Serviço**: app
- **Porta**: 9090

## 📁 Estrutura do Projeto

```
docker/
├── README.md           # Esta documentação
└── php/
    ├── php-local.ini   # Configurações PHP
    └── xdebug.ini      # Configurações Xdebug
```

## 🐛 Troubleshooting

### **Container não inicia**
```bash
# Ver logs de erro
docker compose logs app

# Verificar status
docker compose ps

# Recriar container
docker compose up -d --force-recreate app
```

### **Problemas de permissão**
```bash
# Verificar usuário dentro do container
docker compose exec app whoami

# Verificar permissões
docker compose exec app ls -la /var/www
```

### **Porta em uso**
```bash
# Verificar portas em uso
ss -tulpn | grep :9090

# Parar outros containers que usam a porta
docker stop $(docker ps -q --filter "publish=9090")
```

## 💡 Dicas

- Use `docker compose exec app bash` para desenvolvimento diário
- Sempre use `docker compose` (não `docker-compose`) para comandos
- O projeto usa a porta 9090 para evitar conflitos
- Xdebug está configurado para debugging na porta 9003
- Configurações PHP estão em `docker/php/php-local.ini`

## 🔗 Links Úteis

- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Documentation](https://laravel.com/docs)
- [PHP Docker Hub](https://hub.docker.com/_/php)
