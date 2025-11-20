document.addEventListener('DOMContentLoaded', () => {
    //ELEMENTOS DO DOM
    const gameBoard = document.querySelector('.game-board');
    const boardConfigDisplay = document.querySelector('.board-config');
    const movesDisplay = document.querySelector('.moves');
    const timerDisplay = document.querySelector('.timer');
    const classicModeBtn = document.querySelector('.mode-toggle .mode-btn:nth-child(1)');
    const timeTrialBtn = document.querySelector('.mode-toggle .mode-btn:nth-child(2)');
    
    //Botões de Trapaça 
    const activateCheatBtn = document.querySelector('.cheat-button.on');
    const deactivateCheatBtn = document.querySelector('.cheat-button.off');

    const endGameModal = document.querySelector('#endGameModal');
    const modalTitle = document.querySelector('#modalTitle');
    const modalMessage = document.querySelector('#modalMessage');
    const playAgainBtn = document.querySelector('#playAgainBtn');

    //VARIÁVEIS DE ESTADO DO JOGO
    let hasFlippedCard = false;
    let lockBoard = false;
    let firstCard, secondCard;
    let moves = 0;
    let pairsFound = 0;
    let totalPairs = 0;

    // --- VARIÁVEIS DO CONFIGURACOES ---
    let timerInterval = null;
    let totalTimeInSeconds = 0;
    let gameMode = 'classico';
    let cardTheme = 'frutas';

    const frutas = [
        '🍎', '🍌', '🍇', '🍓', '🍒', '🍑', '🍍', '🥥',
        '🥝', '🍅', '🍆', '🥑', '🥦', '🥬', '🥒', '🌶️',
        '🌽', '🥕', '🧄', '🧅', '🥔', '🍠', '🥐', '🥨',
        '🧀', '🥚', '🥞', '🧇', '🥓', '🥩', '🍗', '🍖'
    ];

    const taylorSwiftImages = [
        'assets/img/jogotay/1989.png', 'assets/img/jogotay/1989tv.png', 'assets/img/jogotay/apeak(2).png', 'assets/img/jogotay/apeak.png',
        'assets/img/jogotay/eras.png', 'assets/img/jogotay/evermore.png', 'assets/img/jogotay/fearless.png', 'assets/img/jogotay/folklore.png',
        'assets/img/jogotay/louro.png', 'assets/img/jogotay/loverset.png', 'assets/img/jogotay/midnights.png', 'assets/img/jogotay/movie.png',
        'assets/img/jogotay/palco.png', 'assets/img/jogotay/paula.png', 'assets/img/jogotay/piano.png', 'assets/img/jogotay/red.png',
        'assets/img/jogotay/redtv.png', 'assets/img/jogotay/reputacion.png', 'assets/img/jogotay/Showgirl.png', 'assets/img/jogotay/speaknow.png',
        'assets/img/jogotay/speaknowtv.png', 'assets/img/jogotay/taybrina.png', 'assets/img/jogotay/tayed.png', 'assets/img/jogotay/tayflorence.png',
        'assets/img/jogotay/TTPD.png', 'assets/img/jogotay/TTPD2.png', 'assets/img/jogotay/debut.png', 'assets/img/jogotay/fearlesstv.png', 
        'assets/img/jogotay/friendship.png', 'assets/img/jogotay/hands.png', 'assets/img/jogotay/lover-album.png', 'assets/img/jogotay/mirrorball.png',
    ]

    let contentSource = [];

    // --- FUNÇÕES DE CONFIGURAÇÃO E INICIALIZAÇÃO ---

    function getGameSettings() {
        const config = (typeof CONFIG_JOGO !== 'undefined') ? CONFIG_JOGO : {
            modo: 'classico',
            tema: 'frutas',
            tamanho: '4x4'
        };

        const sizeParam = config.tamanho;
        gameMode = config.modo;
        cardTheme = config.tema;

        console.log("Iniciando jogo com: ", config);

        if (cardTheme === 'taylor_swift') {
            contentSource = taylorSwiftImages;
        } else {
            contentSource = frutas;
        }

        boardConfigDisplay.textContent = sizeParam;
        const size = parseInt(sizeParam.split('x')[0]);
        totalPairs = (size * size) / 2;

        if (gameMode === 'contra_tempo') {
            classicModeBtn.classList.remove('active');
            timeTrialBtn.classList.add('active');

            switch (sizeParam) {
                case '2x2': totalTimeInSeconds = 60; break;
                case '4x4': totalTimeInSeconds = 120; break;
                case '6x6': totalTimeInSeconds = 180; break;
                case '8x8': totalTimeInSeconds = 240; break;
                default: totalTimeInSeconds = 120;
            }

        } else {
            classicModeBtn.classList.add('active');
            timeTrialBtn.classList.remove('active');
        }
    }

    function createBoard() {
        const size = Math.sqrt(totalPairs * 2);

        const contentPairs = contentSource.slice(0, totalPairs).concat(contentSource.slice(0, totalPairs));
        contentPairs.sort(() => Math.random() - 0.5);
        gameBoard.innerHTML = '';
        gameBoard.style.gridTemplateColumns = `repeat(${size}, auto)`;
        gameBoard.style.gridTemplateRows = `repeat(${size}, auto)`;

        contentPairs.forEach(item => {
            const card = document.createElement('div');
            card.classList.add('card');
            card.dataset.value = item;

            const backContent = cardTheme === 'taylor_swift'
                ? `<img src="${item}" alt="Imagem do Jogo">`
                : item;

            card.innerHTML = `
                <div class="face front"></div>
                <div class="face back">${backContent}</div>
            `;

            if (cardTheme === 'taylor_swift') {
                const frontFace = card.querySelector('.front');
                frontFace.style.backgroundImage = "url('assets/img/jogotay/carta.png')"
                frontFace.style.backgroundSize = "cover";
                frontFace.style.backgroundPosition = "center";
            }

            card.addEventListener('click', flipCard);
            gameBoard.appendChild(card);
        });
    }

    // --- FUNÇÕES DO CRONÔMETRO ---

    function startTimer() {
        if (timerInterval) clearInterval(timerInterval);

        if (gameMode === 'classico') {
            totalTimeInSeconds = 0;
            timerInterval = setInterval(tickUp, 1000);
        } else if (gameMode === 'contra_tempo') {
            const sizeParam = boardConfigDisplay.textContent;
             switch (sizeParam) {
                case '2x2': totalTimeInSeconds = 60; break;
                case '4x4': totalTimeInSeconds = 120; break;
                case '6x6': totalTimeInSeconds = 180; break;
                case '8x8': totalTimeInSeconds = 240; break;
                default: totalTimeInSeconds = 120;
            }
            timerInterval = setInterval(tickDown, 1000);
        }
        updateTimerDisplay();
    }

    function tickUp() {
        totalTimeInSeconds++;
        updateTimerDisplay();
    }

    function tickDown() {
        totalTimeInSeconds--;
        updateTimerDisplay();
        if (totalTimeInSeconds <= 0) {
            endGame(false);
        }
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(totalTimeInSeconds / 60);
        const seconds = totalTimeInSeconds % 60;
        timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    function stopTimer() {
        clearInterval(timerInterval);
    }

    //Funções do Jogo
    function flipCard() {
        if (lockBoard || this === firstCard) return;
        this.classList.add('flip');

        if (!hasFlippedCard) {
            hasFlippedCard = true;
            firstCard = this;
            return;
        }

        secondCard = this;
        incrementMoves();
        checkForMatch();
    }

    function checkForMatch() {
        const isMatch = firstCard.dataset.value === secondCard.dataset.value;
        isMatch ? disableCards() : unflipCards();
    }

    function disableCards() {
        firstCard.removeEventListener('click', flipCard);
        secondCard.removeEventListener('click', flipCard);

        firstCard.classList.add('matched');
        secondCard.classList.add('matched');
        
        pairsFound++;
        if (pairsFound === totalPairs) {
            endGame(true);
        }

        resetBoard();
    }

    function unflipCards() {
        lockBoard = true;
        setTimeout(() => {
            firstCard.classList.remove('flip');
            secondCard.classList.remove('flip');
            resetBoard();
        }, 1200);
    }

    function resetBoard() {
        [hasFlippedCard, lockBoard] = [false, false];
        [firstCard, secondCard] = [null, null];
    }

    function incrementMoves() {
        moves++;
        movesDisplay.textContent = moves;
    }

    function endGame(didWin) {
        stopTimer();
        lockBoard = true;

        const textoResultado = didWin ? 'Vitória' : 'Derrota';
        salvarPartidaNoBanco(textoResultado);
        
        setTimeout(() => {
            if (didWin) {
                modalTitle.textContent = 'Parabéns, Você Venceu!';
                modalMessage.textContent = `Você encontrou todos os pares em ${moves} jogadas.`;
            } else {
                modalTitle.textContent = 'Fim de Jogo!';
                modalMessage.textContent = 'O tempo acabou. Tente novamente!';
            }
            endGameModal.classList.add('show'); // Mostra o pop-up
        }, 700); //Aumentei um pouco o tempo para dar tempo da animação da carta
    }

    function activateCheat() {
        const allCards = document.querySelectorAll('.card');
        allCards.forEach(card => {
            card.classList.add('flip');
        });
    }

    function deactivateCheat() {
        const allCards = document.querySelectorAll('.card');
        allCards.forEach(card => {
            // Só vira de volta as cartas que ainda não foram combinadas
            if (!card.classList.contains('matched')) {
                card.classList.remove('flip');
            }
        });
    }

    function restartGame() {
        hasFlippedCard = false;
        lockBoard = false;
        firstCard = null;
        secondCard = null;
        moves = 0;
        pairsFound = 0;
        movesDisplay.textContent = moves;

        endGameModal.classList.remove('show');

        createBoard();
        startTimer();
    }

    function init() {
        getGameSettings();
        createBoard();
        startTimer();

        activateCheatBtn.addEventListener('click', activateCheat);
        deactivateCheatBtn.addEventListener('click', deactivateCheat);

        playAgainBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Impede que o link '#' recarregue a página de forma padrão
            restartGame();
        });
    }

    function salvarPartidaNoBanco(resultado) {
        const dadosDaPartida = {
            dimensoes: boardConfigDisplay.textContent,
            modalidade: gameMode === 'classico' ? 'Clássica' : 'Contra o Tempo',
            tempo: timerDisplay.textContent,
            jogadas: moves,
            resultado: resultado
        };

        fetch('salvar_partida.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dadosDaPartida)
        })
        .then(response => responde.json())
        .then(data => {
            console.log('Partida salva com sucesso', data);
        })
        .catch(error => {
            console.error('Erro ao salvar partida: ', error);
        });
    }
    init();
});