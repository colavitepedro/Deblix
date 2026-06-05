# DER da Base de Dados do Deblix

![Diagrama entidade-relacionamento do Deblix](docs/der-deblix.png)

Arquivos do diagrama: [`PNG`](docs/der-deblix.png) e [`SVG`](docs/der-deblix.svg).

## Entidades

```mermaid
erDiagram
    USERS {
        BIGINT id PK
        VARCHAR name
        VARCHAR email UK
        VARCHAR foto_perfil
        BOOLEAN is_admin
        VARCHAR password
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    TOPICOS {
        INT id PK
        BIGINT user_id FK
        VARCHAR titulo
        VARCHAR categoria
        VARCHAR autor
        TEXT descricao
        DATE data_criacao
        VARCHAR imagem
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    RESPOSTAS {
        INT id PK
        BIGINT user_id FK
        INT topico_id FK
        VARCHAR usuario
        TEXT mensagem
        DATE data_resposta
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    USERS o|--o{ TOPICOS : cria
    USERS o|--o{ RESPOSTAS : publica
    TOPICOS ||--o{ RESPOSTAS : possui
```

## Relacionamentos

| Origem | Destino | Cardinalidade | Exclusão |
| --- | --- | --- | --- |
| `users.id` | `topicos.user_id` | Um usuário cria vários tópicos | O tópico permanece com `user_id` nulo |
| `users.id` | `respostas.user_id` | Um usuário publica várias respostas | A resposta permanece com `user_id` nulo |
| `topicos.id` | `respostas.topico_id` | Um tópico possui várias respostas | As respostas são excluídas em cascata |

## Regras

- `users.email` é único.
- `topicos.user_id` e `respostas.user_id` aceitam valor nulo.
- `topicos.imagem` e `users.foto_perfil` armazenam apenas o caminho do arquivo.
