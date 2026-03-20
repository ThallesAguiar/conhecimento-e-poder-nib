# ⚡ Conhecimento é poder — Nivelamento Informática Básica

Um sistema de quiz gamificado e responsivo, desenvolvido para ambientes escolares, focado no nivelamento de conceitos de Informática Básica (como Hardware vs. Software, Dispositivos de Entrada vs. Saída, etc.). O projeto utiliza uma estética *Cyberpunk/Futurista* para engajar os alunos e oferece um sistema de ranking dinâmico.

## 🚀 Diferenciais Pedagógicos

- **Pausa Pedagógica:** O cronômetro para automaticamente quando o aluno responde uma questão. Isso permite que ele leia a explicação detalhada sem a pressão do tempo, focando no **aprendizado real** antes de prosseguir.
- **Ranking por Turma:** Permite filtrar os resultados por turma, facilitando a visualização do desempenho de grupos específicos pelo professor.
- **Feedback Imediato:** Explicações claras e fundamentadas após cada resposta para reforçar o conteúdo.

## 🛠️ Funcionalidades Técnicas

- **Multi-Temas:** Suporta múltiplos quizes (ex: `tema=hardware` ou `tema=entrada_saida`) através de parâmetros na URL, organizando os rankings de forma independente.
- **Ranking Vivo:** As abas de "Ranking da Aula" e "Ranking Geral" possuem atualização automática a cada 30 segundos e um botão de atualização manual sincronizado.
- **Sistema Anti-Cheat:** Utiliza trava por `localStorage` vinculada ao tema e à turma para evitar que o aluno refaça o mesmo desafio várias vezes na mesma aula.
- **Interface Imersiva:** Desenvolvido com Bootstrap 5 e CSS customizado com animações de *scanlines* e grid 3D.
- **Backend Seguro:** PHP com PDO para proteção contra SQL Injection e banco de dados MySQL com índices otimizados.

## 💻 Tecnologias Utilizadas

- **Frontend:** HTML5, CSS3 (Vanilla), JavaScript (ES6+), Bootstrap 5.
- **Bibliotecas JS:** Axios (Requisições API), SweetAlert2 (Modais), Google Fonts.
- **Backend:** PHP 8.x.
- **Banco de Dados:** MySQL / MariaDB.

## 📦 Como Instalar

1. Clone o repositório em sua pasta `htdocs` (se usar XAMPP):
   ```bash
   git clone https://github.com/ThallesAguiar/conhecimento-e-poder-nib.git
   ```
2. Importe o arquivo `setup.sql` no seu PHPMyAdmin para criar o banco de dados `conhecimento-e-poder-nib` e a estrutura de tabelas.
3. Configure o arquivo `db.php` com as credenciais do seu servidor de banco de dados.
4. Acesse pelo navegador:
   - Ex: `http://localhost/conhecimento-e-poder-nib/index.html?tema=geral&turma=9A`

## 📖 Parâmetros de URL

Personalize a experiência do aluno passando parâmetros via GET:
- `turma`: Define a identificação da turma (Ex: `9A`, `INFO_BASICA`).
- `tema`: Define o assunto do quiz para o banco de dados (Ex: `hardware_software`, `perifericos`).

## ✨ Gerador de Questões (AI Prompt)

Para criar novos temas rapidamente, copie e cole o prompt abaixo em uma IA (ChatGPT, Gemini, Claude). Ele gerará o código pronto para substituir no seu `index.html`:

> **Prompt:** "Atue como um especialista em pedagogia e informática. Gere um quiz sobre **[INSIRA O TEMA AQUI, ex: Periféricos de Entrada vs Saída]**. 
> O formato de saída deve conter duas constantes JavaScript:
> 
> 1. `const CONFIG = { cat1: { id: 'id1', label: 'Nome1', icon: 'Emoji1' }, cat2: { id: 'id2', label: 'Nome2', icon: 'Emoji2' } };`
> 
> 2. `const ITEMS = [ { name: 'Item', icon: 'Emoji', type: 'id1', ok: '...', err: '...' }, ... ];` (Gere 15 itens)
> 
> **Requisitos:** As explicações devem ser simples para alunos iniciantes. Os 'type' dos itens devem corresponder aos IDs definidos na CONFIG."

## 🔄 Fluxo do Jogo

Para quem deseja entender a lógica de funcionamento do projeto:

1.  **Entrada e Detecção:** O sistema lê os parâmetros `tema` e `turma` da URL. Se o aluno já concluiu aquele quiz específico (verificado via `localStorage`), ele é redirecionado automaticamente para a aba de Ranking.
2.  **Início do Desafio:** O aluno clica em "Iniciar". O cronômetro começa a contar apenas o tempo de raciocínio.
3.  **Resposta e Pausa Pedagógica:** Ao clicar em uma opção:
    - O cronômetro **para imediatamente**.
    - Um modal do *SweetAlert2* surge com o feedback (Certo ou Errado) e uma **explicação pedagógica** detalhada.
    - O aluno lê a explicação com calma, sem pressão de tempo.
4.  **Retomada:** Ao clicar em "Entendi, Próxima", o cronômetro volta a contar e o próximo item é carregado.
5.  **Finalização:** Após as 15 questões, o sistema solicita o nome do aluno e envia (via Axios/POST) o `nome`, `turma`, `tema`, `acertos`, `erros` e o `tempo_total_segundos` para o servidor PHP.
6.  **Ranking Sincronizado:** O aluno visualiza sua posição no ranking. Os rankings são atualizados automaticamente a cada 30 segundos em todos os dispositivos da sala, permitindo que a turma acompanhe a competição em tempo real.

---
**Desenvolvido para fins educacionais · Nivelamento de Informática Básica**
