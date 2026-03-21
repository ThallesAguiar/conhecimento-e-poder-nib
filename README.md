# ⚡ Conhecimento é poder — Nivelamento Informática Básica

Um sistema de quiz gamificado e responsivo, desenvolvido para ambientes escolares, focado no nivelamento de conceitos de Informática Básica (como Hardware vs. Software, Dispositivos de Entrada vs. Saída, etc.). O projeto utiliza uma estética *Cyberpunk/Futurista* para engajar os alunos e oferece um ecossistema completo de **Diagnóstico Pedagógico**.

## 🚀 Diferenciais Pedagógicos

- **Pausa Pedagógica:** O cronômetro para automaticamente quando o aluno responde uma questão. Isso permite que ele leia a explicação detalhada sem a pressão do tempo, focando no **aprendizado real** antes de prosseguir.
- **Dossiê de Evolução:** O professor tem acesso a um relatório que cruza o que o aluno *acha* que sabe (via Forms inicial) com o que ele *realmente* demonstra no quiz.
- **Fluxo Simplificado:** Com o Importador Mestre, o professor cadastra a turma e o diagnóstico inicial em um único clique, fazendo o upload do CSV exportado do Google Forms.
- **Validação de Acesso Blindada:** O sistema exige identificação pré-cadastrada e valida a turma via parâmetro de URL, impedindo que alunos entrem em turmas erradas ou refaçam o desafio.

## 🛠️ Funcionalidades Técnicas

- **Importador Mestre (`importar_diagnostico.php`):** Ferramenta unificada para cadastrar alunos e perfis iniciais através do **upload direto** do CSV do Google Forms. Permite definir uma "Turma de Destino" (ex: T01) para o quiz, separando-a do curso acadêmico do aluno.
- **Relatório de Evolução (`relatorio.php`):** Dashboard para o professor acompanhar médias de acertos, temas concluídos e visualizar o "Dossiê" individual de cada aluno.
- **Validação via URL:** O campo de turma no login é bloqueado (`readonly`) se o parâmetro `?turma=...` estiver presente na URL, garantindo que o aluno responda no grupo correto.
- **Anti-Duplicidade no Banco:** A verificação de "já realizado" é feita diretamente no servidor via ID único do aluno, tornando o bloqueio infalível contra limpeza de cache ou troca de navegador.
- **Registro Detalhado:** Grava cada resposta individual (correta ou errada) para análise posterior de pontos críticos da turma.

## 💻 Tecnologias Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.
- **Bibliotecas JS:** Axios (API), SweetAlert2 (Modais).
- **Backend:** PHP 8.x (PDO).
- **Banco de Dados:** MySQL / MariaDB (Relacional).

## 📦 Como Instalar

1. Clone o repositório em sua pasta `htdocs`:
   ```bash
   git clone https://github.com/ThallesAguiar/quiz-nib.git
   ```
2. Importe o arquivo `setup.sql` no seu PHPMyAdmin.
3. Configure o arquivo `db.php` com as credenciais do seu banco de dados.

## 👨‍🏫 Guia do Professor (Ciclo de Uso)

1. **Importar Dados Mestre:** No Google Forms, exporte as respostas como **CSV**. No `importar_diagnostico.php`, defina o código da turma que você quer usar (ex: `T01`) e faça o upload do arquivo.
2. **Distribuir o Link:** Envie o link para os alunos incluindo a turma na URL: `index.html?tema=geral&turma=T01`.
3. **Analisar Evolução:** Acesse `relatorio.php` para ver o desempenho real comparado ao diagnóstico inicial.

## 🔄 Fluxo do Aluno

1. **Identificação:** O aluno insere seu Nome. A Turma já vem preenchida e travada via URL. O sistema valida se ele existe naquela turma e se já completou o tema.
2. **Desafio:** Responde as questões com feedback imediato e explicações pedagógicas.
3. **Ranking:** Visualiza sua posição em tempo real no ranking da turma.

---
**Desenvolvido para fins educacionais · Diagnóstico e Nivelamento de Informática**
---