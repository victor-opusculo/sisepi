# SisEPI
Sistema de Informações on-line desenvolvido para a Escola do Parlamento da Câmara Municipal de Itapevi (SP, Brasil)

A Câmara Municipal de Itapevi é a sede do poder legislativo municipal da cidade de Itapevi, localizada no estado de São Paulo, Brasil. A Escola do Parlamento de Itapevi é um setor da Câmara que oferece cursos, palestras e outras atividades aos servidores públicos e à população do município. O SisEPI foi desenvolvido para aumentar a eficiência dos trabalhos da Escola por meio da automação de alguns de seus processos. O sistema gerencia a Bilbioteca Legislativa, os eventos (cursos e outros), o museu de arte, o mailing e tem um cadastro básico de docentes dos eventos promovidos.

De todo o sistema, o módulo de eventos é o maior e mais complexo, pois ele gerencia os checklists, as listas de inscrições, as listas de presença e também permite ao aluno que cumpriu a carga horária requerida gerar seu próprio certificado. O segundo maior e mais complexo é da Biblioteca Legislativa, que mantém o cadastro do acervo de publicações, dos usuários, dos empréstimos de publicações e de reservas para empréstimo.

O sistema é divido em dois sites: O site privado, protegido por usuário e senha e de uso da equipe da Escola do Parlamento e o site público, que pode ser acessado por qualquer pessoa pela internet e é através deste que os alunos se inscrevem para os eventos, assinam as listas de presença e podem gerar seus próprios certificados. Este site público também coleta os e-mails de alunos (caso concordem em receber) para envio em massa (atualmente manual) de e-mails divulgando eventos da Escola. Os certificados gerados podem ter sua autenticidade verificada em página específica nesse mesmo site. 

O sistema pode ser visto em funcionamento pelo seguinte link (site público): http://v-opus.kinghost.net/sisepi/public/

Eu, Victor Opusculo Oliveira Ventura de Almeida, sou servidor da Câmara de Itapevi desde 2017 e em 2021 fui realocado para a Escola do Parlamento para ajudar nos trabalhos do setor. Como sou programador hobbista há mais de 15 anos, decidi desenvolver este sistema para reduzir o trabalho manual da Escola e torná-la mais eficiente.

# Informações do SisEPI
Tipo: Sistema web  
Versão atual: 1.7.4  
Linguagens de programação usadas: PHP e Javascript  
Bibliotecas PHP usadas: tFPDF (para geração de certificados em PDF), PHPMailer, Phinx, E-ChartsPHP, SimpleExcel
Banco de dados usado: MySQL 8.0  
Versão do PHP requerida: Mínimo 7.4  


# Instalação e funcionamento
Todos os arquivos e diretórios do branch "master" devem ser copiados para um diretório nomeado "sisepi" dentro da pasta acessível via localhost de seu servidor HTTP (neste caso, usou-se o IIS do Windows 10). O PHP deve estar instalado e configurado com as seguintes extensões habilitadas: fileinfo, intl, mbstring, mysqli, openssl. O banco de dados MySQL deve ser criado com as definições e dados de exemplo contidos no arquivo "sisepi_mysql.sql", presente no diretório base do branch "master".

No diretório base do seu servidor HTTP (fora do diretório sisepi), deve ser criado o arquivo de configuração com os dados para acesso ao banco de dados e a chave de criptografia (dados pessoais são criptografados ao serem salvos no banco). O arquivo deve se chamar "sisepi_config.ini", com o conteúdo abaixo, modificado as informações para seu caso:

<pre>
[database]  
servername="127.0.0.1"  
username="sisepi_admin"       ;usuário do MySQL com permissões para SELECT, UPDATE, DELETE e INSERT  
password="Lt8Xn.NE05YyzAh("   ;senha do usuário do MySQL  
dbname="sisepi"               ;nome do banco de dados MySQL  
crypto="a1f15167b75cf1ae7e74a72bb454e536"    ;chave de criptografia  
  
[regularmail]  
host="smtp.example.net"       ;Host SMTP para o envio de e-mail  
port=587                      ;Porta SMTP  
username="v-opus@example.net" ;Nome de usuário do servidor SMTP    
password="12345678"           ;Senha
sender="v-opus@example.net"
replyto="v-opus@example.net"  ;Endereço de resposta quando necessário
  
[urls]  
usefriendly=0                 ;Usar (1) ou não (0) URLs amigáveis. Esta configuração é lida pela classe de geração de URL
</pre>

O sistema em produção tem o acesso direto ao arquivo de configuração restringido. 

# Informações de contato
Desenvolvedor solo: Victor Opusculo  
E-mail: victor.ventura@uol.com.br