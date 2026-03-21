# ⚡ Conhecimento é poder — Nivelamento Informática Básica

Um sistema de quiz gamificado e responsivo, desenvolvido para ambientes escolares, focado no nivelamento de conceitos de Informática Básica (como Hardware vs. Software, Dispositivos de Entrada vs. Saída, etc.). O projeto utiliza uma estética *Cyberpunk/Futurista* para engajar os alunos e oferece um sistema de ranking dinâmico e ferramentas de diagnóstico pedagógico.

## 🚀 Diferenciais Pedagógicos

- **Pausa Pedagógica:** O cronômetro para automaticamente quando o aluno responde uma questão. Isso permite que ele leia a explicação detalhada sem a pressão do tempo, focando no **aprendizado real** antes de prosseguir.
- **Validação de Acesso:** O sistema permite que o professor pré-cadastre os alunos. Isso evita que pessoas externas respondam ao quiz e garante que os dados coletados sejam apenas dos alunos da turma.
- **Rastreamento de Aprendizado (Diagnóstico):** O sistema agora grava **cada resposta individual** do aluno (quais questões ele acertou ou errou). Isso permite que o professor realize um diagnóstico inicial e compare com o desempenho ao final do curso para medir a evolução.
- **Ranking por Turma:** Permite filtrar os resultados por turma, facilitando a visualização do desempenho de grupos específicos pelo professor.

## 🛠️ Funcionalidades Técnicas

- **Importador de Alunos:** Script utilitário (`importar_alunos.php`) para o professor cadastrar rapidamente listas de alunos via cópia/cola (formato Nome;Turma).
- **Endpoint de Validação:** API em PHP para validar se o nome e turma inseridos pelo aluno existem no banco de dados antes de iniciar o desafio.
- **Persistência de Respostas:** Nova estrutura de banco de dados que vincula pontuações a IDs de alunos e armazena o histórico detalhado de cada tentativa.
- **Multi-Temas:** Suporta múltiplos quizes através de parâmetros na URL, organizando os rankings de forma independente.
- **Ranking Vivo:** Atualização automática a cada 30 segundos com filtros dinâmicos.
- **Sistema Anti-Cheat:** Utiliza trava por `localStorage` vinculada ao tema e à turma para evitar re-tentativas não autorizadas.

## 💻 Tecnologias Utilizadas

- **Frontend:** HTML5, CSS3 (Vanilla), JavaScript (ES6+), Bootstrap 5.
- **Bibliotecas JS:** Axios (API), SweetAlert2 (Modais), Google Fonts.
- **Backend:** PHP 8.x (PDO).
- **Banco de Dados:** MySQL / MariaDB (com chaves estrangeiras e índices).

## 📦 Como Instalar

1. Clone o repositório em sua pasta `htdocs` (se usar XAMPP):
   ```bash
   git clone https://github.com/ThallesAguiar/conhecimento-e-poder-nib.git
   ```
2. Importe o arquivo `setup.sql` no seu PHPMyAdmin para criar o banco de dados e as tabelas (`alunos`, `pontuacoes`, `respostas_detalhadas`).
3. Configure o arquivo `db.php` com as credenciais do seu banco de dados.
4. **Passo do Professor:** Acesse `importar_alunos.php` e cole a lista de nomes e turmas dos seus alunos para liberar o acesso ao quiz.
5. **Acesso do Aluno:** Distribua o link do `index.html` (ex: `http://localhost/quiz/index.html?tema=geral&turma=INFO-1A`).

## 🔄 Fluxo do Jogo Atualizado

1.  **Entrada:** O sistema lê o tema e a turma da URL.
2.  **Instruções e Identificação:** O aluno lê as regras e, em seguida, deve inserir seu **Nome Completo** e **Turma**.
3.  **Validação:** O sistema consulta o banco de dados. Se o aluno estiver cadastrado, o quiz é liberado.
4.  **Desafio com Pausa:** O cronômetro conta apenas o tempo de raciocínio. Ao responder, ele para para que o aluno leia o feedback explicativo.
5.  **Registro Automático:** Ao final das 15 questões, os resultados (incluindo o que foi respondido em cada pergunta) são enviados automaticamente ao servidor vinculados ao ID do aluno.
6.  **Ranking:** O aluno é levado ao ranking para ver sua posição em relação aos colegas.

---
**Desenvolvido para fins educacionais · Diagnóstico e Nivelamento de Informática**
---