# ⚡ Conhecimento é poder — Nivelamento Informática Básica

Um sistema de quiz gamificado e responsivo, desenvolvido para ambientes escolares, focado no nivelamento de conceitos de Informática Básica (como Hardware vs. Software, Dispositivos de Entrada vs. Saída, etc.). O projeto utiliza uma estética *Cyberpunk/Futurista* para engajar os alunos e oferece um ecossistema completo de **Diagnóstico Pedagógico**.

## 🚀 Diferenciais Pedagógicos

- **Pausa Pedagógica:** O cronômetro para automaticamente quando o aluno responde uma questão. Isso permite que ele leia a explicação detalhada sem a pressão do tempo, focando no **aprendizado real** antes de prosseguir.
- **Dossiê de Evolução:** O professor tem acesso a um relatório que cruza o que o aluno *acha* que sabe (via Forms inicial) com o que ele *realmente* demonstra no quiz.
- **Fluxo Simplificado:** Com o Importador Mestre, o professor cadastra a turma e o diagnóstico inicial em um único clique, fazendo o upload do CSV exportado do Google Forms.
- **Validação de Acesso:** O sistema exige identificação pré-cadastrada, garantindo a integridade dos dados e evitando usuários externos.

## 🛠️ Funcionalidades Técnicas

- **Importador Mestre (`importar_diagnostico.php`):** Ferramenta unificada para cadastrar alunos e perfis iniciais através do **upload direto** do CSV do Google Forms (ou colagem manual).
- **Relatório de Evolução (`relatorio.php`):** Dashboard para o professor acompanhar médias de acertos, temas concluídos e comparar com o diagnóstico inicial.
- **Endpoint de Validação Anti-Duplicidade:** Verifica no banco de dados se o aluno já realizou o desafio, impedindo re-tentativas mesmo em dispositivos diferentes.
- **Registro Detalhado:** Grava cada resposta individual (correta ou errada) para análise posterior de pontos críticos da turma.
- **Multi-Temas:** Suporta múltiplos quizes via URL (ex: `tema=hardware`), com rankings independentes.

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

1. **Importar Dados Mestre:** No Google Forms, exporte as respostas como **CSV**. No `importar_diagnostico.php`, defina o código da turma (ex: `T01`) e faça o upload deste arquivo. Isso cadastrará todos os alunos e seus perfis de uma só vez.
2. **Realizar o Quiz:** Distribua o link para os alunos (Ex: `index.html?tema=geral`).
3. **Analisar Evolução:** Acesse `relatorio.php` para ver o "Dossiê" de cada aluno e como o conhecimento real deles se compara ao nível que declararam no início.

## 🔄 Fluxo do Aluno

1. **Identificação:** O aluno insere Nome e Turma (Curso). O sistema valida o acesso e bloqueia se ele já tiver jogado aquele tema.
2. **Desafio:** Responde as questões com feedback imediato e explicações pedagógicas.
3. **Ranking:** Visualiza sua posição em tempo real no ranking da turma.

---
**Desenvolvido para fins educacionais · Diagnóstico e Nivelamento de Informática**
---