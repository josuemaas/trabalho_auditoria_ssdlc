# Atividade Avaliativa - SSDLC

Projeto acadêmico desenvolvido para a disciplina de Auditoria e Segurança de Sistemas.

## Objetivo

Desenvolver uma aplicação simples utilizando práticas de Secure Software Development Life Cycle (SSDLC), integrando desenvolvimento seguro, versionamento, análise de segurança, pipeline de CI/CD e publicação em ambiente AWS.

## Funcionalidades

- Autenticação de usuário
- Controle de sessão
- Cadastro de tarefas
- Consulta de tarefas
- Edição de tarefas
- Exclusão de tarefas

## Práticas de segurança

- Hash de senha
- Validação de sessão
- Prepared Statements com PDO
- Proteção contra SQL Injection
- Proteção contra XSS
- Proteção CSRF
- Validação de entradas

## Tecnologias

- PHP
- HTML
- CSS
- SQLite
- Git
- GitHub
- GitHub Actions
- Scanner de segurança
- AWS EC2

## Arquitetura

GitHub → Pipeline CI/CD → Scanner de Segurança → AWS EC2 → Aplicação PHP