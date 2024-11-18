<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="card-title mb-1 fw-bold text-gradient">
                    <i class="fas fa-person-hanging text-primary me-2"></i>Hangman
                </h5>
                <p class="text-muted small mb-0">Guess the word before it's too late!</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-primary-subtle text-primary" id="scoreDisplay">Score: 0</span>
                <button class="btn btn-primary" id="newHangmanBtn">
                    <i class="fas fa-redo-alt me-2"></i>New Game
                </button>
            </div>
        </div>

        <div class="hangman-container text-center">
            <!-- Category Badge -->
            <div class="category-badge mb-3">
                <span class="badge bg-light text-secondary" id="categoryDisplay"></span>
            </div>

            <!-- Hangman Drawing -->
            <div class="hangman-drawing mb-4">
                <svg width="200" height="200" viewBox="0 0 200 200" class="hangman-svg">
                    <!-- Base -->
                    <line x1="40" y1="180" x2="160" y2="180" class="hangman-part" />
                    <!-- Pole -->
                    <line x1="100" y1="180" x2="100" y2="20" class="hangman-part" />
                    <!-- Top -->
                    <line x1="100" y1="20" x2="140" y2="20" class="hangman-part" />
                    <!-- Rope -->
                    <line x1="140" y1="20" x2="140" y2="40" class="hangman-part hangman-hidden" data-part="0" />
                    <!-- Head -->
                    <circle cx="140" cy="55" r="15" class="hangman-part hangman-hidden" data-part="1" />
                    <!-- Body -->
                    <line x1="140" y1="70" x2="140" y2="120" class="hangman-part hangman-hidden" data-part="2" />
                    <!-- Left Arm -->
                    <line x1="140" y1="85" x2="120" y2="100" class="hangman-part hangman-hidden" data-part="3" />
                    <!-- Right Arm -->
                    <line x1="140" y1="85" x2="160" y2="100" class="hangman-part hangman-hidden" data-part="4" />
                    <!-- Left Leg -->
                    <line x1="140" y1="120" x2="120" y2="140" class="hangman-part hangman-hidden" data-part="5" />
                    <!-- Right Leg -->
                    <line x1="140" y1="120" x2="160" y2="140" class="hangman-part hangman-hidden" data-part="6" />
                </svg>
            </div>

            <!-- Hints Section -->
            <div class="hints-section mb-3">
                <span class="badge bg-warning-subtle text-warning" id="hintsLeft">
                    <i class="fas fa-lightbulb me-1"></i>Hints: 3
                </span>
                <button class="btn btn-sm btn-warning ms-2" id="hintBtn">
                    <i class="fas fa-lightbulb me-1"></i>Use Hint
                </button>
            </div>

            <!-- Word Display -->
            <div class="word-display mb-4" id="wordDisplay"></div>

            <!-- Keyboard -->
            <div class="keyboard-container mb-4" id="keyboard"></div>

            <!-- Game Status -->
            <div id="hangmanStatus" class="game-status p-2 rounded-3"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hangman-container {
    max-width: 400px;
    margin: 0 auto;
    perspective: 1000px;
}

.hangman-svg {
    max-width: 200px;
    margin: 0 auto;
    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    transform-style: preserve-3d;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate3d(0, 1, 0, 0deg); }
    25% { transform: translateY(-5px) rotate3d(0, 1, 0, 3deg); }
    75% { transform: translateY(5px) rotate3d(0, 1, 0, -3deg); }
}

.hangman-part {
    stroke: var(--primary-color);
    stroke-width: 4;
    stroke-linecap: round;
    stroke-linejoin: round;
    fill: none;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.hangman-hidden {
    opacity: 0;
    transition: opacity 0.5s ease, transform 0.5s ease;
    transform: scale(0.8) rotate(-10deg);
    transform-origin: center;
}

.hangman-hidden.show {
    opacity: 1;
    transform: scale(1) rotate(0);
}

.category-badge {
    transform: translateY(0);
    animation: bounce 1s ease infinite;
}

@keyframes bounce {
    50% { transform: translateY(-5px); }
}

.word-display {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    margin: 0 auto;
    max-width: 100%;
    overflow-x: auto;
    padding: 1rem;
    perspective: 1000px;
}

.word-display span {
    width: 2rem;
    height: 2.5rem;
    font-size: 1.5rem;
    font-weight: bold;
    font-family: monospace;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 3px solid var(--primary-color);
    color: var(--primary-color);
    transition: all 0.3s ease;
    transform-style: preserve-3d;
    animation: letterFloat 3s ease-in-out infinite;
    animation-delay: calc(var(--letter-index) * 0.1s);
}

@keyframes letterFloat {
    0%, 100% { transform: translateY(0) rotateX(0); }
    50% { transform: translateY(-5px) rotateX(10deg); }
}

.word-display span.revealed {
    animation: revealLetter 0.5s ease forwards;
}

@keyframes revealLetter {
    0% { 
        transform: rotateX(-90deg) translateY(20px);
        opacity: 0;
    }
    100% { 
        transform: rotateX(0) translateY(0);
        opacity: 1;
    }
}

.keyboard-container {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    max-width: 500px;
    margin: 0 auto;
    perspective: 1000px;
}

.keyboard-row {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    transform-style: preserve-3d;
    animation: rowAppear 0.5s ease backwards;
}

.keyboard-row:nth-child(1) { animation-delay: 0.1s; }
.keyboard-row:nth-child(2) { animation-delay: 0.2s; }
.keyboard-row:nth-child(3) { animation-delay: 0.3s; }

@keyframes rowAppear {
    0% { 
        transform: rotateX(-30deg) translateY(30px);
        opacity: 0;
    }
    100% { 
        transform: rotateX(0) translateY(0);
        opacity: 1;
    }
}

.keyboard-btn {
    width: 2.5rem;
    height: 2.5rem;
    border: none;
    background: var(--surface-color);
    border: 2px solid var(--border-color);
    border-radius: 0.5rem;
    font-weight: 600;
    color: var(--text-color);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.keyboard-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
}

.keyboard-btn:hover:not(:disabled)::before {
    transform: translateX(100%);
}

.keyboard-btn:hover:not(:disabled) {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.keyboard-btn:active:not(:disabled) {
    transform: translateY(-2px) scale(0.95);
}

.keyboard-btn.correct {
    background: linear-gradient(135deg, var(--success-color-light), #dcfce7);
    border-color: var(--success-color);
    color: var(--success-color);
    animation: correctPop 0.3s ease;
}

.keyboard-btn.wrong {
    background: linear-gradient(135deg, var(--error-color-light), #fee2e2);
    border-color: var(--error-color);
    color: var(--error-color);
    animation: wrongShake 0.3s ease;
}

@keyframes correctPop {
    50% { transform: scale(1.2); }
}

@keyframes wrongShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}

.game-status {
    min-height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    background: var(--surface-color);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform-style: preserve-3d;
}

.game-status.success {
    background: linear-gradient(135deg, var(--success-color-light), #dcfce7);
    color: var(--success-color);
    animation: statusPop 0.5s ease;
}

.game-status.error {
    background: linear-gradient(135deg, var(--error-color-light), #fee2e2);
    color: var(--error-color);
    animation: statusPop 0.5s ease;
}

@keyframes statusPop {
    0% { transform: scale(0.9) translateY(10px); opacity: 0; }
    100% { transform: scale(1) translateY(0); opacity: 1; }
}

.hints-section {
    transition: all 0.3s ease;
    animation: hintsAppear 0.5s ease backwards;
    animation-delay: 0.4s;
}

@keyframes hintsAppear {
    0% { transform: translateY(20px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

.hints-section button {
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hints-section button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .keyboard-btn {
        background: var(--surface-color, #1f2937);
        border-color: var(--border-color, #374151);
    }

    .hangman-part {
        stroke: var(--primary-color, #60a5fa);
    }

    .word-display span {
        border-bottom-color: var(--primary-color, #60a5fa);
        color: var(--primary-color, #60a5fa);
    }
}

/* Responsive design */
@media (max-width: 576px) {
    .keyboard-btn {
        width: 2rem;
        height: 2rem;
        font-size: 0.875rem;
    }

    .word-display span {
        width: 1.5rem;
        height: 2rem;
        font-size: 1.25rem;
    }

    .hangman-svg {
        max-width: 150px;
    }
}
</style>
@endpush

@push('scripts')
<script>
class HangmanGame {
    constructor() {
        this.categories = {
            'Programming': [
                'JAVASCRIPT', 'PYTHON', 'LARAVEL', 'PROGRAMMING',
                'DEVELOPER', 'COMPUTER', 'ALGORITHM', 'DATABASE'
            ],
            'Web Dev': [
                'HTML', 'CSS', 'REACT', 'ANGULAR', 'VUE', 'NODE',
                'EXPRESS', 'MONGODB', 'MYSQL', 'POSTGRESQL'
            ],
            'Tools': [
                'GITHUB', 'DOCKER', 'LINUX', 'VSCODE', 'GIT',
                'NGINX', 'APACHE', 'TERMINAL', 'WEBPACK', 'BABEL'
            ]
        };
        this.maxWrong = 6;
        this.score = 0;
        this.hintsLeft = 3;
        this.initializeGame();
        this.setupEventListeners();
    }

    initializeGame() {
        const categories = Object.keys(this.categories);
        this.currentCategory = categories[Math.floor(Math.random() * categories.length)];
        const words = this.categories[this.currentCategory];
        this.word = words[Math.floor(Math.random() * words.length)];
        
        this.guessed = new Set();
        this.wrongCount = 0;
        this.gameOver = false;
        
        this.renderKeyboard();
        this.renderWord();
        this.resetHangman();
        this.updateCategory();
        this.updateScore();
        this.updateHints();
        this.updateStatus('Start guessing!');
    }

    setupEventListeners() {
        document.getElementById('newHangmanBtn').addEventListener('click', () => {
            this.initializeGame();
        });

        document.getElementById('hintBtn').addEventListener('click', () => {
            this.giveHint();
        });

        const handleKeyPress = (e) => {
            if (this.gameOver) return;
            const key = e.key.toUpperCase();
            if (/^[A-Z]$/.test(key)) {
                this.makeGuess(key);
            }
        };

        document.removeEventListener('keydown', handleKeyPress);
        document.addEventListener('keydown', handleKeyPress);
    }

    renderKeyboard() {
        const keyboard = document.getElementById('keyboard');
        keyboard.innerHTML = '';
        
        const rows = [
            'QWERTYUIOP',
            'ASDFGHJKL',
            'ZXCVBNM'
        ];
        
        rows.forEach(row => {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'keyboard-row';
            
            row.split('').forEach(letter => {
                const button = document.createElement('button');
                button.className = 'keyboard-btn';
                button.textContent = letter;
                button.addEventListener('click', () => this.makeGuess(letter));
                rowDiv.appendChild(button);
            });
            
            keyboard.appendChild(rowDiv);
        });
    }

    renderWord() {
        const wordDisplay = document.getElementById('wordDisplay');
        wordDisplay.innerHTML = this.word
            .split('')
            .map(letter => `<span>${this.guessed.has(letter) ? letter : '_'}</span>`)
            .join('');
    }

    makeGuess(letter) {
        if (this.gameOver || this.guessed.has(letter)) return;

        this.guessed.add(letter);
        const btn = Array.from(document.querySelectorAll('.keyboard-btn'))
            .find(b => b.textContent === letter && !b.disabled);
        
        if (this.word.includes(letter)) {
            if (btn) {
                btn.classList.add('correct');
                btn.disabled = true;
            }
            
            this.renderWord();
            
            if (this.checkWin()) {
                this.gameOver = true;
                this.score += 10;
                this.updateScore();
                this.updateStatus('Congratulations! You won! 🎉', 'success');
                this.disableAllButtons();
            }
        } else {
            if (btn) {
                btn.classList.add('wrong');
                btn.disabled = true;
            }
            
            this.wrongCount++;
            document.querySelector(`[data-part="${this.wrongCount - 1}"]`).classList.add('show');
            
            if (this.wrongCount === this.maxWrong) {
                this.gameOver = true;
                this.score = Math.max(0, this.score - 5);
                this.updateScore();
                this.updateStatus(`Game Over! The word was: ${this.word}`, 'error');
                this.disableAllButtons();
                this.revealWord();
            }
        }
    }

    giveHint() {
        if (this.gameOver || this.hintsLeft <= 0) return;
        
        // Find unguessed letters
        const unguessed = this.word
            .split('')
            .filter(letter => !this.guessed.has(letter));
            
        if (unguessed.length === 0) return;
        
        // Reveal a random unguessed letter
        const hint = unguessed[Math.floor(Math.random() * unguessed.length)];
        this.makeGuess(hint);
        
        // Update hints
        this.hintsLeft--;
        this.updateHints();
    }

    updateCategory() {
        const categoryDisplay = document.getElementById('categoryDisplay');
        categoryDisplay.textContent = `Category: ${this.currentCategory}`;
    }

    updateScore() {
        const scoreDisplay = document.getElementById('scoreDisplay');
        scoreDisplay.textContent = `Score: ${this.score}`;
    }

    updateHints() {
        const hintsLeft = document.getElementById('hintsLeft');
        const hintBtn = document.getElementById('hintBtn');
        
        hintsLeft.textContent = `Hints: ${this.hintsLeft}`;
        hintBtn.disabled = this.hintsLeft <= 0 || this.gameOver;
    }

    checkWin() {
        return this.word.split('').every(letter => this.guessed.has(letter));
    }

    resetHangman() {
        document.querySelectorAll('.hangman-hidden').forEach(part => {
            part.classList.remove('show');
        });
    }

    updateStatus(message, type = '') {
        const status = document.getElementById('hangmanStatus');
        status.textContent = message;
        status.className = 'game-status p-2 rounded-3' + (type ? ` ${type}` : '');
    }

    disableAllButtons() {
        document.querySelectorAll('.keyboard-btn').forEach(btn => {
            btn.disabled = true;
        });
    }

    revealWord() {
        const wordDisplay = document.getElementById('wordDisplay');
        wordDisplay.innerHTML = this.word
            .split('')
            .map(letter => `<span class="text-danger">${letter}</span>`)
            .join('');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new HangmanGame();
});
</script>
@endpush 