# ⚡ Conhecimento é poder — Nivelamento Informática Básica

Um sistema de quiz gamificado e responsivo, desenvolvido para ambientes escolares, focado no nivelamento de conceitos de Informática Básica. O projeto utiliza uma estética *Cyberpunk/Futurista* para engajar os alunos e oferece um motor de perguntas dinâmico que suporta múltiplos formatos (Atalhos, Hardware, Software, Comandos do Windows, etc.).

## 🚀 Diferenciais Pedagógicos

- **Motor de Questões Dinâmico:** As perguntas e opções de resposta não são mais fixas. Cada item possui seus próprios botões de escolha e feedbacks detalhados, permitindo testar diversos temas de forma flexível.
- **Pausa Pedagógica:** O cronômetro para automaticamente quando o aluno responde uma questão. Isso permite que ele leia a explicação detalhada sem a pressão do tempo, focando no **aprendizado real** antes de prosseguir.
- **Dossiê de Evolução:** O professor tem acesso a um relatório que cruza o que o aluno *acha* que sabe (via Forms inicial) com o que ele *realmente* demonstra no quiz.
- **Fluxo Simplificado:** Com o Importador Mestre, o professor cadastra a turma e o diagnóstico inicial em um único clique, fazendo o upload do CSV exportado do Google Forms.
- **Validação de Acesso Blindada:** O sistema exige identificação pré-cadastrada e valida a turma via parâmetro de URL, impedindo que alunos entrem em turmas erradas ou refaçam o desafio.

## 🛠️ Funcionalidades Técnicas

- **Importador Mestre (`importar_diagnostico.php`):** Ferramenta unificada para cadastrar alunos e perfis iniciais através do **upload direto** do CSV do Google Forms.
- **Relatório de Evolução (`relatorio.php`):** Dashboard para o professor acompanhar médias de acertos, temas concluídos e visualizar o "Dossiê" individual de cada aluno.
- **Validação via URL:** O campo de turma no login é bloqueado (`readonly`) se o parâmetro `?turma=...` estiver presente na URL.
- **Anti-Duplicidade no Banco:** A verificação de "já realizado" é feita diretamente no servidor via ID único do aluno.
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
3. Configure o arquivo `db.php` com as credenciais do seu banco de dados (use `db.example.php` como base).

## 👨‍🏫 Guia do Professor (Ciclo de Uso)

1. **Importar Dados Mestre:** No Google Forms, exporte as respostas como **CSV**. No `importar_diagnostico.php`, defina o código da turma (ex: `T01`) e faça o upload.
2. **Distribuir o Link:** Envie o link para os alunos incluindo a turma na URL: `index.html?tema=geral&turma=T01`.
3. **Analisar Evolução:** Acesse `relatorio.php` para ver o desempenho real.

## 🔄 Fluxo do Aluno

1. **Identificação:** O aluno insere seu Nome. A Turma já vem preenchida via URL. O sistema valida o acesso.
2. **Desafio:** Responde as questões com feedback imediato e explicações pedagógicas personalizadas.
3. **Ranking:** Visualiza sua posição em tempo real no ranking da turma.

## 🤖 Prompt para IA (Geração de Conteúdo sob Demanda)

O motor do quiz é flexível. Você pode usar o prompt abaixo em qualquer IA (ChatGPT, Gemini, Claude) para gerar novas questões de **qualquer disciplina**. Basta preencher os campos entre colchetes:

```text
Atue como um especialista em [INSIRA A DISCIPLINA OU TEMA] e Educação. Gere [QUANTIDADE] novas questões para um quiz no formato JSON.
Cada objeto deve seguir esta estrutura exata:
{
  "question": "Texto da pergunta aqui?",
  "icon": "Emoji relacionado ao tema da questão",
  "options": [
    { "label": "Opção A", "isCorrect": true/false, "feedback": "Explicação pedagógica detalhada para esta escolha" },
    { "label": "Opção B", "isCorrect": true/false, "feedback": "Explicação pedagógica detalhada para esta escolha" }
  ]
}
As questões devem focar em: [DESCREVA OS SUB-TEMAS OU NÍVEL DE DIFICULDADE].
Mantenha um tom encorajador e educativo nos feedbacks, garantindo que o aluno aprenda mesmo se errar a opção.
```

---
**Desenvolvido para fins educacionais · Diagnóstico e Nivelamento de Informática**
---