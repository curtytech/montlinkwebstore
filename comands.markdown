# Como rodar as migrations e seeders do Laravel

Para preparar o banco de dados do seu projeto, siga os passos abaixo no terminal, dentro da pasta do projeto:

## 1. Rodar as migrations

Este comando cria todas as tabelas necessárias no banco de dados:

```bash
php artisan migrate
```

## 2. Rodar os seeders

Este comando irá popular o banco de dados com os usuários de exemplo definidos no seeder:

```bash
php artisan db:seed
```

---

### Dica: Resetar e popular tudo de uma vez
Se quiser apagar todas as tabelas, recriá-las e rodar os seeders em seguida, use:

```bash
php artisan migrate:fresh --seed
```

---

Após esses comandos, você poderá acessar o sistema usando:
- **E-mail:** client@gmail.com / **Senha:** 12345678
- **E-mail:** admin@admin.com / **Senha:** 12345678