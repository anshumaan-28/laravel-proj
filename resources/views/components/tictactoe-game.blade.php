<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="card-title mb-1 fw-bold text-gradient">
                    <i class="fas fa-gamepad text-primary me-2"></i>Tic Tac Toe
                </h5>
                <p class="text-muted small mb-0">Play against the computer</p>
            </div>
            <button class="btn btn-primary" id="resetGameBtn">
                <i class="fas fa-redo-alt me-2"></i>New Game
            </button>
        </div>

        <div class="board-container mb-4">
            <div class="board-grid"></div>
        </div>

        <div id="gameMessage" class="text-center p-2 rounded-3"></div>
    </div>
</div>

@push('styles')
<style>
.board-container {
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
    aspect-ratio: 1;
}

.board-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
    height: 100%;
    background: var(--border-color);
    padding: 0.5rem;
    border-radius: 1rem;
}

.cell {
    background: var(--surface-color, white);
    border: none;
    border-radius: 0.5rem;
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    aspect-ratio: 1;
}

.cell:hover {
    background: var(--hover-color, #f8fafc);
}

.cell.x {
    color: var(--primary-color);
}

.cell.o {
    color: var(--secondary-color, #64748b);
}

.cell.winner {
    background: var(--success-color-light, #dcfce7);
    color: var(--success-color, #059669);
}

#gameMessage {
    min-height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: all 0.3s ease;
}

#gameMessage.success {
    background: var(--success-color-light, #dcfce7);
    color: var(--success-color, #059669);
}

#gameMessage.draw {
    background: var(--warning-color-light, #fef3c7);
    color: var(--warning-color, #d97706);
}

@media (max-width: 576px) {
    .cell {
        font-size: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
class TicTacToe {
    constructor() {
        this.board = Array(9).fill('');
        this.humanPlayer = 'X';
        this.computerPlayer = 'O';
        this.currentPlayer = this.humanPlayer;
        this.gameOver = false;
        this.initializeBoard();
        this.setupEventListeners();
    }

    initializeBoard() {
        const boardGrid = document.querySelector('.board-grid');
        boardGrid.innerHTML = '';
        
        for (let i = 0; i < 9; i++) {
            const cell = document.createElement('div');
            cell.className = 'cell';
            cell.dataset.index = i;
            boardGrid.appendChild(cell);
        }
        
        this.board = Array(9).fill('');
        this.currentPlayer = this.humanPlayer;
        this.gameOver = false;
        this.updateMessage('Your turn (X)');
    }

    setupEventListeners() {
        document.querySelector('.board-grid').addEventListener('click', (e) => {
            if (!e.target.classList.contains('cell')) return;
            this.makeHumanMove(e.target);
        });

        document.getElementById('resetGameBtn').addEventListener('click', () => {
            this.initializeBoard();
        });
    }

    async makeHumanMove(cell) {
        const index = cell.dataset.index;
        if (this.board[index] || this.gameOver || this.currentPlayer !== this.humanPlayer) return;

        // Make human move
        this.board[index] = this.humanPlayer;
        cell.textContent = this.humanPlayer;
        cell.classList.add(this.humanPlayer.toLowerCase());

        if (this.checkWinner()) {
            this.gameOver = true;
            this.updateMessage('You win!', 'success');
            this.highlightWinningCells();
            return;
        }

        if (this.board.every(cell => cell)) {
            this.gameOver = true;
            this.updateMessage("It's a draw!", 'draw');
            return;
        }

        // Computer's turn
        this.currentPlayer = this.computerPlayer;
        this.updateMessage('Computer is thinking...');
        
        // Add slight delay for better UX
        await new Promise(resolve => setTimeout(resolve, 500));
        this.makeComputerMove();
    }

    makeComputerMove() {
        const bestMove = this.findBestMove();
        const cell = document.querySelector(`[data-index="${bestMove}"]`);
        
        this.board[bestMove] = this.computerPlayer;
        cell.textContent = this.computerPlayer;
        cell.classList.add(this.computerPlayer.toLowerCase());

        if (this.checkWinner()) {
            this.gameOver = true;
            this.updateMessage('Computer wins!', 'success');
            this.highlightWinningCells();
            return;
        }

        if (this.board.every(cell => cell)) {
            this.gameOver = true;
            this.updateMessage("It's a draw!", 'draw');
            return;
        }

        this.currentPlayer = this.humanPlayer;
        this.updateMessage('Your turn (X)');
    }

    findBestMove() {
        let bestScore = -Infinity;
        let bestMove = 0;

        for (let i = 0; i < 9; i++) {
            if (this.board[i] === '') {
                this.board[i] = this.computerPlayer;
                let score = this.minimax(this.board, 0, false);
                this.board[i] = '';
                
                if (score > bestScore) {
                    bestScore = score;
                    bestMove = i;
                }
            }
        }

        return bestMove;
    }

    minimax(board, depth, isMaximizing) {
        const winner = this.checkWinner();
        
        if (winner) {
            return winner === this.computerPlayer ? 10 - depth : depth - 10;
        }
        
        if (!board.includes('')) {
            return 0;
        }

        if (isMaximizing) {
            let bestScore = -Infinity;
            for (let i = 0; i < 9; i++) {
                if (board[i] === '') {
                    board[i] = this.computerPlayer;
                    let score = this.minimax(board, depth + 1, false);
                    board[i] = '';
                    bestScore = Math.max(score, bestScore);
                }
            }
            return bestScore;
        } else {
            let bestScore = Infinity;
            for (let i = 0; i < 9; i++) {
                if (board[i] === '') {
                    board[i] = this.humanPlayer;
                    let score = this.minimax(board, depth + 1, true);
                    board[i] = '';
                    bestScore = Math.min(score, bestScore);
                }
            }
            return bestScore;
        }
    }

    checkWinner() {
        const winPatterns = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
            [0, 4, 8], [2, 4, 6] // Diagonals
        ];

        for (let pattern of winPatterns) {
            const [a, b, c] = pattern;
            if (this.board[a] && 
                this.board[a] === this.board[b] && 
                this.board[a] === this.board[c]) {
                return this.board[a];
            }
        }
        return null;
    }

    highlightWinningCells() {
        const winPatterns = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];

        const winner = winPatterns.find(pattern => {
            const [a, b, c] = pattern;
            return this.board[a] &&
                   this.board[a] === this.board[b] &&
                   this.board[a] === this.board[c];
        });

        if (winner) {
            const cells = document.querySelectorAll('.cell');
            winner.forEach(index => {
                cells[index].classList.add('winner');
            });
        }
    }

    updateMessage(message, type = '') {
        const messageEl = document.getElementById('gameMessage');
        messageEl.textContent = message;
        messageEl.className = 'text-center p-2 rounded-3' + (type ? ` ${type}` : '');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new TicTacToe();
});
</script>
@endpush