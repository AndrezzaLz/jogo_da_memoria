-------------------------------------------------------------------
PROJETO YASH - JOGO DA MEMÓRIA (WEB DEVELOPMENT)
Guia de Instalação e Execução Local
-------------------------------------------------------------------

Este arquivo contém o passo a passo para configurar o ambiente, 
o banco de dados e executar o projeto em uma máquina local usando XAMPP.

-------------------------------------------------------------------
1. PRÉ-REQUISITOS
-------------------------------------------------------------------
Certifique-se de ter instalado em sua máquina:
- XAMPP (Apache + MariaDB/MySQL + PHP)
- Um navegador web moderno (Chrome, Firefox, Edge)

-------------------------------------------------------------------
2. INSTALAÇÃO DOS ARQUIVOS
-------------------------------------------------------------------
1. Localize a pasta de instalação do XAMPP (geralmente 'C:\xampp').
2. Entre na pasta 'htdocs'.
3. Cole a pasta deste projeto dentro de 'htdocs'.
   
   Caminho final sugerido: C:\xampp\htdocs\jogo_da_memoria

-------------------------------------------------------------------
3. CONFIGURAÇÃO DO BANCO DE DADOS
-------------------------------------------------------------------
O projeto requer um banco de dados para funcionar (login, ranking, histórico).

1. Abra o XAMPP Control Panel.
2. Inicie os módulos 'Apache' e 'MySQL' (botão Start).
3. No navegador, acesse: http://localhost/phpmyadmin
4. Clique na aba "Importar" (Import) no menu superior.
5. Clique em "Escolher arquivo" e selecione o arquivo SQL localizado 
   dentro deste projeto em:
   
   > database/init.sql

6. Clique no botão "Executar" (Go) no final da página.

*O script irá criar automaticamente o banco 'yash_db', as tabelas necessárias
e um usuário administrador para testes.*

-------------------------------------------------------------------
4. CONFIGURAÇÃO DE CREDENCIAIS (Opcional)
-------------------------------------------------------------------
O projeto já vem configurado para o padrão do XAMPP:
- Host: localhost
- Usuário: root
- Senha: (vazio)

Caso seu MySQL tenha senha, edite o arquivo:
> config/database.php

-------------------------------------------------------------------
5. COMO ACESSAR O JOGO
-------------------------------------------------------------------
1. Certifique-se de que o Apache e MySQL continuam rodando.
2. Abra seu navegador.
3. Acesse o seguinte endereço (ajuste o nome da pasta se necessário):

   http://localhost/jogo_da_memoria/public/

   *Nota: Você deve ser redirecionado automaticamente para a tela de Login.*

-------------------------------------------------------------------
6. USUÁRIOS PARA TESTE
-------------------------------------------------------------------
Você pode criar uma conta nova clicando em "Cadastre-se", ou usar
o usuário administrador já criado pelo script de banco de dados:

Login: admin
Senha: 123456

-------------------------------------------------------------------
Desenvolvido para a disciplina de Programação para a Web.
-------------------------------------------------------------------
