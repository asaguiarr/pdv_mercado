# Correções no Sistema de Acesso de Usuários

## Problemas Identificados
- Modelo User sem 'role' e 'active' em $fillable, impedindo seeding.
- Seeders com inconsistências em emails e senhas.
- Possível banco não migrado/seedado.

## Passos a Executar
- [x] Atualizar modelo User para incluir 'role' e 'active' em $fillable.
- [x] Corrigir DefaultUsersSeeder para consistência.
- [x] Executar migrações do banco.
- [x] Executar seeders para criar usuários padrão.
- [x] Testar login com credenciais dos usuários seedados.
