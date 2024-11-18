<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="card-title mb-1 fw-bold text-gradient">
                    <i class="fas fa-spell-check text-primary me-2"></i>Wordle
                </h5>
                <p class="text-muted small mb-0">Guess the programming word in 6 tries</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <div class="stats-badges d-flex gap-2">
                    <span class="badge bg-primary-subtle text-primary">
                        <i class="fas fa-trophy me-1"></i>Wins: <span id="wordleWins">0</span>
                    </span>
                    <span class="badge bg-success-subtle text-success">
                        <i class="fas fa-fire me-1"></i>Streak: <span id="wordleStreak">0</span>
                    </span>
                </div>
                <button class="btn btn-primary" id="newWordleBtn">
                    <i class="fas fa-redo-alt me-2"></i>New Game
                </button>
            </div>
        </div>

        <div class="wordle-container text-center">
            <!-- Category Badge -->
            <div class="category-badge mb-3">
                <span class="badge bg-light text-secondary" id="categoryDisplay"></span>
            </div>

            <!-- Game Board -->
            <div class="wordle-board mb-4"></div>

            <!-- Keyboard -->
            <div class="wordle-keyboard mb-4"></div>

            <!-- Game Status -->
            <div id="wordleStatus" class="game-status p-2 rounded-3"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
.wordle-container {
    max-width: 400px;
    margin: 0 auto;
    perspective: 1000px;
}

.wordle-board {
    display: grid;
    grid-template-rows: repeat(6, 1fr);
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.wordle-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.5rem;
}

.wordle-cell {
    aspect-ratio: 1;
    border: 2px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: 1.5rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    transition: all 0.3s ease;
    background: var(--surface-color);
    position: relative;
    transform-style: preserve-3d;
    cursor: default;
}

.wordle-cell.active {
    border-color: var(--primary-color);
    transform: scale(1.05);
}

.wordle-cell.pop {
    animation: popIn 0.15s ease-in-out;
}

.wordle-cell.shake {
    animation: shakeCell 0.5s ease-in-out;
}

.wordle-cell.flip {
    animation: flipCell 0.6s ease-in-out;
}

.wordle-cell.correct {
    background: var(--success-color);
    border-color: var(--success-color);
    color: white;
}

.wordle-cell.present {
    background: var(--warning-color);
    border-color: var(--warning-color);
    color: white;
}

.wordle-cell.absent {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    color: white;
}

/* Animations */
@keyframes popIn {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes shakeCell {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-4px); }
    40% { transform: translateX(4px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
}

@keyframes flipCell {
    0% { transform: rotateX(0); }
    50% { transform: rotateX(90deg); }
    100% { transform: rotateX(0); }
}

/* Keyboard Styles */
.wordle-keyboard {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.keyboard-row {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
}

.keyboard-btn {
    min-width: 2.5rem;
    height: 3.5rem;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    background: var(--surface-color);
    color: var(--text-color);
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.keyboard-btn.wide {
    min-width: 4rem;
}

.keyboard-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    background: var(--hover-color);
}

.keyboard-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.keyboard-btn.correct {
    background: var(--success-color);
    color: white;
}

.keyboard-btn.present {
    background: var(--warning-color);
    color: white;
}

.keyboard-btn.absent {
    background: var(--secondary-color);
    color: white;
}

/* Game Status */
.game-status {
    min-height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: all 0.3s ease;
}

.game-status.success {
    background: var(--success-color-light);
    color: var(--success-color);
}

.game-status.error {
    background: var(--error-color-light);
    color: var(--error-color);
}

/* Responsive Design */
@media (max-width: 576px) {
    .wordle-cell {
        font-size: 1.25rem;
    }
    
    .keyboard-btn {
        min-width: 2rem;
        height: 3rem;
        font-size: 0.875rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
class WordleGame {
    constructor() {
        this.words = {
            'Frontend': [
                'REACT', 'REDUX', 'STYLE', 'CLASS', 'PROPS',
                'STATE', 'HOOKS', 'QUERY', 'FETCH', 'ASYNC',
                'AWAIT', 'ROUTE', 'MOUNT', 'BUILD', 'BABEL',
                'STORE', 'VUEJS', 'FORMS', 'INPUT', 'MODAL'
            ],
            'Backend': [
                'MYSQL', 'REDIS', 'QUEUE', 'CACHE', 'ROUTE',
                'MODEL', 'BLADE', 'NGINX', 'HTTPS', 'TOKEN',
                'OAUTH', 'GUARD', 'SCOPE', 'TRAIT', 'YIELD',
                'QUERY', 'TABLE', 'INDEX', 'JOINS', 'VIEWS'
            ],
            'Languages': [
                'SWIFT', 'SCALA', 'KOTLIN', 'RUST', 'PYTHON',
                'RUBY', 'JAVA', 'PERL', 'PHP', 'DART',
                'BASH', 'HTML', 'SASS', 'LESS', 'SHELL',
                'GOLANG', 'JULIA', 'FLASK', 'NODE'
            ],
            'DevOps': [
                'LINUX', 'NGINX', 'AZURE', 'SHELL', 'STACK',
                'CLOUD', 'NODES', 'SCALE', 'PROXY', 'HTTPS',
                'CICD', 'HELM', 'KUBE', 'YAML', 'DOCK',
                'PODS', 'LOGS', 'TEST', 'BUILD', 'PUSH'
            ]
        };
        
        this.loadStats();
        this.initializeGame();
        this.setupEventListeners();
        this.loadGameState();
    }

    loadStats() {
        this.stats = {
            wins: parseInt(localStorage.getItem('wordleWins') || 0),
            streak: parseInt(localStorage.getItem('wordleStreak') || 0),
            bestStreak: parseInt(localStorage.getItem('wordleBestStreak') || 0),
            gamesPlayed: parseInt(localStorage.getItem('wordleGamesPlayed') || 0),
            averageTries: parseFloat(localStorage.getItem('wordleAvgTries') || 0),
            lastPlayed: localStorage.getItem('wordleLastPlayed') || null
        };
    }

    initializeGame() {
        this.resetGame();
        this.createBoard();
        this.createKeyboard();
        this.selectWord();
        this.updateStats();
        this.saveGameState();
    }

    resetGame() {
        this.currentRow = 0;
        this.currentCol = 0;
        this.guesses = [];
        this.gameOver = false;
        this.updateStatus('');
    }

    selectWord() {
        // Check if it's a new day
        const today = new Date().toDateString();
        if (this.stats.lastPlayed !== today) {
            // Select random category and word
            const categories = Object.keys(this.words);
            this.currentCategory = categories[Math.floor(Math.random() * categories.length)];
            const categoryWords = this.words[this.currentCategory];
            this.word = categoryWords[Math.floor(Math.random() * categoryWords.length)];
            
            // Save today's word
            localStorage.setItem('wordleCurrentWord', this.word);
            localStorage.setItem('wordleCurrentCategory', this.currentCategory);
            localStorage.setItem('wordleLastPlayed', today);
        } else {
            // Use today's saved word
            this.word = localStorage.getItem('wordleCurrentWord');
            this.currentCategory = localStorage.getItem('wordleCurrentCategory');
        }
        
        // Update category display with animation
        const categoryDisplay = document.getElementById('categoryDisplay');
        categoryDisplay.style.opacity = '0';
        setTimeout(() => {
            categoryDisplay.textContent = `Category: ${this.currentCategory}`;
            categoryDisplay.style.opacity = '1';
        }, 300);
    }

    createBoard() {
        const board = document.querySelector('.wordle-board');
        board.innerHTML = '';
        
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('div');
            row.className = 'wordle-row';
            
            for (let j = 0; j < 5; j++) {
                const cell = document.createElement('div');
                cell.className = 'wordle-cell';
                row.appendChild(cell);
            }
            
            board.appendChild(row);
        }
    }

    createKeyboard() {
        const keyboard = document.querySelector('.wordle-keyboard');
        keyboard.innerHTML = '';
        
        const layout = [
            ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
            ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
            ['ENTER', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '⌫']
        ];
        
        layout.forEach(row => {
            const keyboardRow = document.createElement('div');
            keyboardRow.className = 'keyboard-row';
            
            row.forEach(key => {
                const button = document.createElement('button');
                button.className = 'keyboard-btn' + (key.length > 1 ? ' wide' : '');
                button.textContent = key;
                button.dataset.key = key;
                keyboardRow.appendChild(button);
            });
            
            keyboard.appendChild(keyboardRow);
        });
    }

    setupEventListeners() {
        document.addEventListener('keydown', this.handleKeyPress.bind(this));
        
        document.querySelectorAll('.keyboard-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const key = btn.dataset.key;
                this.handleInput(key);
            });
        });
        
        document.getElementById('newWordleBtn').addEventListener('click', () => {
            this.initializeGame();
        });
    }

    handleKeyPress(e) {
        if (this.gameOver) return;
        
        if (e.key === 'Enter') {
            this.handleInput('ENTER');
        } else if (e.key === 'Backspace') {
            this.handleInput('⌫');
        } else if (/^[A-Za-z]$/.test(e.key)) {
            this.handleInput(e.key.toUpperCase());
        }
    }

    handleInput(key) {
        if (this.gameOver) return;
        
        if (key === 'ENTER') {
            this.submitGuess();
        } else if (key === '⌫') {
            this.deleteLetter();
        } else if (this.currentCol < 5) {
            this.addLetter(key);
        }
    }

    addLetter(letter) {
        if (this.currentCol < 5) {
            const cell = this.getCurrentCell();
            cell.textContent = letter;
            cell.classList.add('pop');
            this.currentCol++;
        }
    }

    deleteLetter() {
        if (this.currentCol > 0) {
            this.currentCol--;
            const cell = this.getCurrentCell();
            cell.textContent = '';
            cell.classList.remove('pop');
        }
    }

    async submitGuess() {
        if (this.currentCol !== 5 || this.gameOver) return;

        const guess = this.getCurrentRow()
            .map(cell => cell.textContent)
            .join('');
            
        if (!this.isValidWord(guess)) {
            this.shakeRow();
            this.updateStatus('Not a valid word', 'error');
            return;
        }

        // Add guess to history
        this.guesses.push(guess);
        
        const result = this.checkGuess(guess);
        await this.animateResult(result);
        
        if (guess === this.word) {
            await this.handleWin();
        } else if (this.currentRow === 5) {
            await this.handleLoss();
        } else {
            this.currentRow++;
            this.currentCol = 0;
            this.saveGameState();
        }
    }

    getCurrentCell() {
        return document.querySelector(`.wordle-row:nth-child(${this.currentRow + 1}) .wordle-cell:nth-child(${this.currentCol + 1})`);
    }

    getCurrentRow() {
        return Array.from(document.querySelectorAll(`.wordle-row:nth-child(${this.currentRow + 1}) .wordle-cell`));
    }

    shakeRow() {
        this.getCurrentRow().forEach(cell => {
            cell.classList.add('shake');
            setTimeout(() => cell.classList.remove('shake'), 500);
        });
    }

    async animateResult(result) {
        const row = this.getCurrentRow();
        const guess = row.map(cell => cell.textContent).join('');
        
        for (let i = 0; i < 5; i++) {
            const cell = row[i];
            const letter = guess[i];
            
            // Add flip animation
            cell.classList.add('flip');
            
            // Wait for flip animation midpoint
            await new Promise(resolve => setTimeout(resolve, 250));
            
            // Update cell style
            cell.classList.remove('flip');
            cell.classList.add(result[i]);
            
            // Update keyboard
            this.updateKeyboard(letter, result[i]);
            
            // Wait before next letter
            await new Promise(resolve => setTimeout(resolve, 250));
        }
    }

    async handleWin() {
        this.gameOver = true;
        
        // Update stats
        this.stats.wins++;
        this.stats.streak++;
        this.stats.bestStreak = Math.max(this.stats.streak, this.stats.bestStreak);
        this.stats.gamesPlayed++;
        
        // Update average tries
        const tries = this.currentRow + 1;
        this.stats.averageTries = (this.stats.averageTries * (this.stats.gamesPlayed - 1) + tries) / this.stats.gamesPlayed;
        
        this.saveStats();
        this.updateStats();
        
        // Animate winning row
        const row = this.getCurrentRow();
        for (let i = 0; i < row.length; i++) {
            const cell = row[i];
            await new Promise(resolve => setTimeout(resolve, 100));
            cell.style.transform = 'scale(1.1) rotate(360deg)';
            cell.style.transition = 'all 0.5s ease';
        }

        await new Promise(resolve => setTimeout(resolve, 600));
        this.updateStatus(`Excellent! You got it in ${tries} ${tries === 1 ? 'try' : 'tries'}!`, 'success');
        this.showWinStats();
    }

    showWinStats() {
        const status = document.getElementById('wordleStatus');
        status.innerHTML = `
            <div class="d-flex flex-column align-items-center">
                <div class="mb-2">🎉 You won! 🎉</div>
                <div class="small text-muted">
                    Tries: ${this.currentRow + 1} | 
                    Avg: ${this.stats.averageTries.toFixed(1)} | 
                    Streak: ${this.stats.streak} 🔥
                </div>
            </div>
        `;
    }

    async handleLoss() {
        this.gameOver = true;
        this.streak = 0;
        this.saveStats();
        this.updateStats();
        
        // Reveal correct word with animation
        const word = this.word.split('');
        const status = document.getElementById('wordleStatus');
        status.innerHTML = '';
        
        for (let i = 0; i < word.length; i++) {
            const span = document.createElement('span');
            span.textContent = word[i];
            span.style.opacity = '0';
            span.style.transform = 'translateY(20px)';
            status.appendChild(span);
            
            await new Promise(resolve => setTimeout(resolve, 100));
            span.style.transition = 'all 0.3s ease';
            span.style.opacity = '1';
            span.style.transform = 'translateY(0)';
        }
        
        status.className = 'game-status p-2 rounded-3 error';
    }

    isValidWord(word) {
        return Object.values(this.words).some(
            category => category.includes(word)
        );
    }

    checkGuess(guess) {
        const result = Array(5).fill('absent');
        const wordArray = this.word.split('');
        const guessArray = guess.split('');
        
        // First pass: mark correct letters
        guessArray.forEach((letter, i) => {
            if (letter === wordArray[i]) {
                result[i] = 'correct';
                wordArray[i] = null;
                guessArray[i] = null;
            }
        });
        
        // Second pass: mark present letters
        guessArray.forEach((letter, i) => {
            if (letter === null) return;
            
            const index = wordArray.indexOf(letter);
            if (index !== -1) {
                result[i] = 'present';
                wordArray[index] = null;
            }
        });
        
        return result;
    }

    updateKeyboard(letter, result) {
        const btn = Array.from(document.querySelectorAll('.keyboard-btn'))
            .find(b => b.dataset.key === letter);
            
        if (!btn) return;
        
        if (result === 'correct') {
            btn.className = 'keyboard-btn correct';
        } else if (result === 'present' && !btn.classList.contains('correct')) {
            btn.className = 'keyboard-btn present';
        } else if (result === 'absent' && !btn.classList.contains('correct') && !btn.classList.contains('present')) {
            btn.className = 'keyboard-btn absent';
        }
    }

    saveStats() {
        Object.entries(this.stats).forEach(([key, value]) => {
            localStorage.setItem(`wordle${key.charAt(0).toUpperCase() + key.slice(1)}`, value);
        });
    }

    updateStats() {
        document.getElementById('wordleWins').textContent = this.stats.wins;
        document.getElementById('wordleStreak').textContent = `${this.stats.streak} 🔥`;
    }

    updateStatus(message, type = '') {
        const status = document.getElementById('wordleStatus');
        status.textContent = message;
        status.className = 'game-status p-2 rounded-3' + (type ? ` ${type}` : '');
    }

    saveGameState() {
        const gameState = {
            guesses: this.guesses,
            word: this.word,
            category: this.currentCategory,
            currentRow: this.currentRow,
            gameOver: this.gameOver,
            date: new Date().toDateString()
        };
        localStorage.setItem('wordleGameState', JSON.stringify(gameState));
    }

    loadGameState() {
        const savedState = localStorage.getItem('wordleGameState');
        if (savedState) {
            const state = JSON.parse(savedState);
            if (state.date === new Date().toDateString()) {
                // Restore game state
                this.word = state.word;
                this.currentCategory = state.category;
                this.currentRow = state.currentRow;
                this.gameOver = state.gameOver;
                
                // Replay guesses
                state.guesses.forEach((guess, row) => {
                    const cells = document.querySelectorAll(`.wordle-row:nth-child(${row + 1}) .wordle-cell`);
                    const result = this.checkGuess(guess);
                    
                    guess.split('').forEach((letter, col) => {
                        cells[col].textContent = letter;
                        cells[col].classList.add(result[col]);
                        this.updateKeyboard(letter, result[col]);
                    });
                });
                
                if (this.gameOver) {
                    if (state.guesses[state.guesses.length - 1] === this.word) {
                        this.showWinStats();
                    } else {
                        this.updateStatus(`Game Over! The word was: ${this.word}`, 'error');
                    }
                }
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new WordleGame();
});
</script>
@endpush