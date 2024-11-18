<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="card-title mb-1 fw-bold text-gradient">
                    <i class="fas fa-puzzle-piece text-primary me-2"></i>Sudoku
                </h5>
                <p class="text-muted small mb-0">Take a break with a quick game</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" id="newGameBtn">
                    <i class="fas fa-sync-alt me-2"></i>New Game
                </button>
                <button class="btn btn-success" id="solveBtn">
                    <i class="fas fa-magic me-2"></i>Solve
                </button>
                <button class="btn btn-outline-primary" id="checkBtn">
                    <i class="fas fa-check me-2"></i>Check
                </button>
            </div>
        </div>

        <div class="sudoku-board mb-4">
            <div class="sudoku-container">
                <!-- Board will be generated here -->
            </div>
        </div>

        <div class="sudoku-numpad mb-3">
            <!-- Numbers 1-9 for input -->
        </div>

        <div id="gameStatus" class="sudoku-status text-center p-2 rounded-3"></div>
    </div>
</div>

@push('styles')
<style>
:root {
    --sudoku-primary: #2563eb;
    --sudoku-secondary: #64748b;
    --sudoku-success: #059669;
    --sudoku-error: #dc2626;
    --sudoku-bg: #f1f5f9;
    --sudoku-surface: #ffffff;
    --sudoku-border: #e2e8f0;
    --sudoku-text: #1e293b;
    --sudoku-text-light: #64748b;
}

.sudoku-board {
    width: 100%;
    max-width: 450px;
    margin: 0 auto;
    background: var(--sudoku-surface);
    padding: 1rem;
    border-radius: 1rem;
}

.sudoku-container {
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    gap: 1px;
    background: var(--sudoku-border);
    border: 2px solid var(--sudoku-border);
    border-radius: 0.5rem;
    overflow: hidden;
    aspect-ratio: 1;
}

.sudoku-cell {
    width: 100%;
    height: 100%;
    background: var(--sudoku-surface);
    border: none;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--sudoku-text);
    text-align: center;
    transition: all 0.2s ease;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sudoku-cell:focus {
    outline: none;
    background: #eff6ff;
}

.sudoku-cell.given {
    background: var(--sudoku-bg);
    color: var(--sudoku-text-light);
    font-weight: 700;
}

.sudoku-cell.error {
    color: var(--sudoku-error);
    background: #fee2e2;
}

.sudoku-cell.highlight {
    background: #dbeafe;
}

/* Border for 3x3 boxes */
.sudoku-container > *:nth-child(3n):not(:last-child) {
    border-right: 2px solid var(--sudoku-border);
}

.sudoku-container > *:nth-child(n+19):nth-child(-n+27),
.sudoku-container > *:nth-child(n+46):nth-child(-n+54) {
    border-bottom: 2px solid var(--sudoku-border);
}

.sudoku-numpad {
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    gap: 0.5rem;
    max-width: 450px;
    margin: 0 auto;
}

.sudoku-num-btn {
    aspect-ratio: 1;
    border: none;
    background: var(--sudoku-bg);
    border-radius: 0.5rem;
    font-weight: 600;
    color: var(--sudoku-text);
    transition: all 0.2s ease;
    font-size: 1.25rem;
}

.sudoku-num-btn:hover {
    background: var(--sudoku-border);
    transform: translateY(-2px);
}

.sudoku-num-btn.selected {
    background: var(--sudoku-primary);
    color: white;
}

.sudoku-status {
    font-weight: 500;
    transition: all 0.3s ease;
    min-height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sudoku-status.success {
    background: #dcfce7;
    color: var(--sudoku-success);
}

.sudoku-status.error {
    background: #fee2e2;
    color: var(--sudoku-error);
}

.sudoku-cell.solving {
    animation: sudokuSolve 0.5s ease forwards;
}

@keyframes sudokuSolve {
    0% {
        background: var(--sudoku-primary);
        color: transparent;
        transform: scale(0.95);
    }
    50% {
        background: var(--sudoku-primary);
        color: white;
        transform: scale(1.05);
    }
    100% {
        background: #e8f5e9;
        color: var(--sudoku-success);
        transform: scale(1);
    }
}

.sudoku-solve-btn {
    transition: all 0.3s ease;
}

.sudoku-solve-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

@media (max-width: 576px) {
    .sudoku-cell {
        font-size: 1rem;
    }
    
    .sudoku-num-btn {
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
class SudokuGame {
    constructor() {
        this.board = Array(9).fill().map(() => Array(9).fill(0));
        this.solution = Array(9).fill().map(() => Array(9).fill(0));
        this.selectedCell = null;
        this.selectedNumber = null;
        this.initializeGame();
    }

    initializeGame() {
        this.generateSolution();
        this.createPuzzle();
        this.renderBoard();
        this.setupEventListeners();
        document.getElementById('gameStatus').textContent = 'Game started! Fill in the empty cells.';
    }

    generateSolution() {
        const fillBoard = (board) => {
            const find = this.findEmpty(board);
            if (!find) return true;
            
            const [row, col] = find;
            const numbers = this.shuffle([1, 2, 3, 4, 5, 6, 7, 8, 9]);
            
            for (let num of numbers) {
                if (this.isValid(board, num, row, col)) {
                    board[row][col] = num;
                    if (fillBoard(board)) return true;
                    board[row][col] = 0;
                }
            }
            return false;
        };

        fillBoard(this.solution);
        this.board = this.solution.map(row => [...row]);
    }

    createPuzzle() {
        const cellsToRemove = 45; // Adjust difficulty (40-55 is good range)
        let count = 0;
        
        while (count < cellsToRemove) {
            const row = Math.floor(Math.random() * 9);
            const col = Math.floor(Math.random() * 9);
            
            if (this.board[row][col] !== 0) {
                this.board[row][col] = 0;
                count++;
            }
        }
    }

    renderBoard() {
        const container = document.querySelector('.sudoku-container');
        container.innerHTML = '';
        
        for (let i = 0; i < 9; i++) {
            for (let j = 0; j < 9; j++) {
                const cell = document.createElement('input');
                cell.type = 'text';
                cell.maxLength = 1;
                cell.className = 'sudoku-cell';
                cell.dataset.row = i;
                cell.dataset.col = j;
                
                if (this.board[i][j] !== 0) {
                    cell.value = this.board[i][j];
                    cell.readOnly = true;
                    cell.classList.add('given');
                }
                
                container.appendChild(cell);
            }
        }

        // Create number pad
        const numberPad = document.querySelector('.sudoku-numpad');
        numberPad.innerHTML = '';
        for (let i = 1; i <= 9; i++) {
            const btn = document.createElement('button');
            btn.className = 'sudoku-num-btn';
            btn.textContent = i;
            btn.dataset.number = i;
            numberPad.appendChild(btn);
        }
    }

    setupEventListeners() {
        const cells = document.querySelectorAll('.sudoku-cell');
        const numberBtns = document.querySelectorAll('.sudoku-num-btn');
        
        cells.forEach(cell => {
            if (!cell.readOnly) {
                cell.addEventListener('input', (e) => {
                    const value = e.target.value;
                    if (!/^[1-9]$/.test(value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    const row = parseInt(cell.dataset.row);
                    const col = parseInt(cell.dataset.col);
                    this.board[row][col] = parseInt(value);
                    
                    this.checkCell(row, col);
                });

                cell.addEventListener('focus', () => {
                    cells.forEach(c => c.classList.remove('highlight'));
                    this.highlightRelatedCells(cell);
                });
            }
        });

        numberBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const focused = document.activeElement;
                if (focused.classList.contains('sudoku-cell') && !focused.readOnly) {
                    focused.value = btn.dataset.number;
                    const row = parseInt(focused.dataset.row);
                    const col = parseInt(focused.dataset.col);
                    this.board[row][col] = parseInt(btn.dataset.number);
                    this.checkCell(row, col);
                }
            });
        });

        document.getElementById('newGameBtn').addEventListener('click', () => {
            this.board = Array(9).fill().map(() => Array(9).fill(0));
            this.solution = Array(9).fill().map(() => Array(9).fill(0));
            this.initializeGame();
        });

        document.getElementById('checkBtn').addEventListener('click', () => {
            this.checkSolution();
        });

        document.getElementById('solveBtn').addEventListener('click', () => {
            this.autoSolve();
        });
    }

    highlightRelatedCells(cell) {
        const row = parseInt(cell.dataset.row);
        const col = parseInt(cell.dataset.col);
        const boxStartRow = Math.floor(row / 3) * 3;
        const boxStartCol = Math.floor(col / 3) * 3;

        document.querySelectorAll('.sudoku-cell').forEach(currentCell => {
            const currentRow = parseInt(currentCell.dataset.row);
            const currentCol = parseInt(currentCell.dataset.col);
            if (currentRow === row || currentCol === col || 
                (currentRow >= boxStartRow && currentRow < boxStartRow + 3 && 
                 currentCol >= boxStartCol && currentCol < boxStartCol + 3)) {
                currentCell.classList.add('highlight');
            }
        });
    }

    checkCell(row, col) {
        const value = this.board[row][col];
        const cell = document.querySelector(`[data-row="${row}"][data-col="${col}"]`);
        
        if (!this.isValid(this.board, value, row, col, true)) {
            cell.classList.add('error');
        } else {
            cell.classList.remove('error');
        }
    }

    checkSolution() {
        const status = document.getElementById('gameStatus');
        let isComplete = true;
        let isCorrect = true;

        for (let i = 0; i < 9; i++) {
            for (let j = 0; j < 9; j++) {
                if (this.board[i][j] === 0) {
                    isComplete = false;
                } else if (this.board[i][j] !== this.solution[i][j]) {
                    isCorrect = false;
                }
            }
        }

        if (!isComplete) {
            status.textContent = 'Keep going! The puzzle is not complete yet.';
            status.className = '';
        } else if (!isCorrect) {
            status.textContent = 'There are some errors in your solution.';
            status.className = 'error';
        } else {
            status.textContent = 'Congratulations! You solved the puzzle!';
            status.className = 'success';
        }
    }

    isValid(board, num, row, col, checkOnly = false) {
        let origValue;
        
        if (!checkOnly) {
            origValue = board[row][col];
            board[row][col] = 0;
        }

        // Check row
        for (let x = 0; x < 9; x++) {
            if (board[row][x] === num && x !== col) {
                if (!checkOnly) board[row][col] = origValue;
                return false;
            }
        }

        // Check column
        for (let x = 0; x < 9; x++) {
            if (board[x][col] === num && x !== row) {
                if (!checkOnly) board[row][col] = origValue;
                return false;
            }
        }

        // Check box
        const boxRow = Math.floor(row / 3) * 3;
        const boxCol = Math.floor(col / 3) * 3;

        for (let i = 0; i < 3; i++) {
            for (let j = 0; j < 3; j++) {
                if (board[boxRow + i][boxCol + j] === num && 
                    (boxRow + i !== row || boxCol + j !== col)) {
                    if (!checkOnly) board[row][col] = origValue;
                    return false;
                }
            }
        }

        if (!checkOnly) board[row][col] = origValue;
        return true;
    }

    findEmpty(board) {
        for (let i = 0; i < 9; i++) {
            for (let j = 0; j < 9; j++) {
                if (board[i][j] === 0) return [i, j];
            }
        }
        return null;
    }

    shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    async autoSolve() {
        const solveBtn = document.getElementById('solveBtn');
        solveBtn.disabled = true;
        
        const cells = document.querySelectorAll('.sudoku-cell');
        const delay = 50; // Delay between each cell animation (milliseconds)
        
        for (let i = 0; i < 9; i++) {
            for (let j = 0; j < 9; j++) {
                if (this.board[i][j] === 0) {
                    const cell = document.querySelector(`[data-row="${i}"][data-col="${j}"]`);
                    const solution = this.solution[i][j];
                    
                    // Add solving animation
                    cell.classList.add('solving');
                    
                    // Wait for animation
                    await new Promise(resolve => setTimeout(resolve, delay));
                    
                    // Update cell value
                    this.board[i][j] = solution;
                    cell.value = solution;
                }
            }
        }
        
        // Update game status
        const status = document.getElementById('gameStatus');
        status.textContent = 'Puzzle solved automatically!';
        status.className = 'text-center p-2 rounded-3 success';
        
        solveBtn.disabled = false;
    }
}

// Initialize game when document is ready
document.addEventListener('DOMContentLoaded', () => {
    new SudokuGame();
});
</script>
@endpush 