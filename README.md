# Programa Calmamente

Repositório do código do Programa Calmamente em Docker

# Getting Started

- Verifique se você tem o Composer e o Node instalado na máquina e se os comandos funcionam no shell `composer -v` e `npm -v`
- Derrube os containers em seu PC que estejam rodando na porta 80
- Clone esse projeto
- Vá para a pasta do tema `cd wp-content/themes/calmamente` e rode os comandos:
  - `composer install`;
  - `npm install`;
  - `npm run build`;
- Com tudo pronto, vá para a pasta raíz do repo e execute `docker-compose up -d` e aguarde. O procedimento de instalação vai rodar e o wordpress vai ser instalado junto com os plugins.
- Algumas informações serão postadas na tela, sente e tome um café pois o WP está sendo configurado e os plugins instalados
- Se tudo ocorreu como o esperado, você já pode acessar a área administrativa WP do projeto em `http://localhost/wp-admin`

- A partir daqui, para continuar será necessário importar a imagem do DB de ambiente DEV no formato `.wpress` para que o projeto rode. Caso você ainda não tenha este arquivo, deverá solicitar ao <b>Eder Silva</b> ou outro administrador do projeto.
- Com o arquivo `.wpress` em mãos, faça login no wp-admin, e no menu lateral procure o submenu do plugin `WP All import` e clique na opção `Import`. Faça o upload do arquivo de imagem e aguarde até a conclusão.

> Você deve saber que seus dados de acesso ao admin se encontram no `.env`

# Development

- Abra o workspace do tema em sua IDE preferida.
- Rode o comando `npm run dev` e bom desenvolvimento!

# Database

- Acesse pelo endereço `http://localhost:8080`
- Usuário: `root`
- Senha: `password`