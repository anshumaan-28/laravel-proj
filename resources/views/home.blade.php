@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="dashboard-sidebar">
        <div class="sidebar-content">
            <!-- User Profile -->
            <div class="user-profile mb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=2563eb&color=fff" 
                             alt="Profile" 
                             class="rounded-circle">
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid mb-4">
                <div class="stat-card bg-primary-subtle rounded-4 p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <span class="text-primary fw-bold">Completed</span>
                    </div>
                    <h3 class="mb-0 text-primary">{{ $todos->where('completed', true)->count() }}</h3>
                </div>
                <div class="stat-card bg-warning-subtle rounded-4 p-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-warning me-2"></i>
                        <span class="text-warning fw-bold">Pending</span>
                    </div>
                    <h3 class="mb-0 text-warning">{{ $todos->where('completed', false)->count() }}</h3>
                </div>
            </div>

            <!-- Radio Player -->
            @include('components.radio-player')
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-main">
        <!-- Tasks Section -->
        <div class="content-section mb-5">
            <div class="section-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold text-gradient d-flex align-items-center">
                        <i class="fas fa-tasks text-primary me-2"></i>Tasks
                        <span class="badge bg-primary-subtle text-primary ms-2">{{ $todos->count() }}</span>
                    </h4>
                    <p class="text-muted mb-0">Manage your daily tasks efficiently</p>
                </div>
            </div>
            
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <!-- Add Todo Form -->
                    <form action="{{ route('todos.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" name="title" 
                                class="form-control border-end-0 @error('title') is-invalid @enderror" 
                                placeholder="Add a new task..." 
                                required>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('title')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </form>

                    <!-- Todo List -->
                    <div class="todo-list">
                        @forelse ($todos as $todo)
                            <div class="todo-item p-3 mb-2 rounded-3 bg-white border {{ $todo->completed ? 'border-success border-opacity-25 bg-success-subtle' : '' }}" 
                                 data-todo-id="{{ $todo->id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                class="form-check-input todo-checkbox" 
                                                {{ $todo->completed ? 'checked' : '' }}
                                                data-todo-id="{{ $todo->id }}">
                                        </div>
                                        <span class="todo-title {{ $todo->completed ? 'text-success text-decoration-line-through' : '' }}">
                                            {{ $todo->title }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <small class="text-secondary">{{ $todo->created_at->diffForHumans() }}</small>
                                        <button type="button" class="btn btn-link text-danger p-0 delete-todo" 
                                                data-todo-id="{{ $todo->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5" id="empty-state">
                                <div class="mb-3">
                                    <i class="fas fa-clipboard-list fa-3x text-secondary opacity-25"></i>
                                </div>
                                <h5 class="text-secondary">No tasks yet</h5>
                                <p class="text-secondary opacity-75 small">Start by adding your first task above.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Games Section -->
        <div class="content-section games-section">
            <div class="section-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold text-gradient d-flex align-items-center">
                        <i class="fas fa-gamepad text-primary me-2"></i>Games & Activities
                    </h4>
                    <p class="text-muted mb-0">Take a break and enjoy some brain training games</p>
                </div>
            </div>

            <!-- Game Categories -->
            <div class="game-categories">
                <!-- Word Games -->
                <div class="game-category mb-5">
                    <div class="category-header d-flex align-items-center justify-content-between mb-4">
                        <h6 class="text-uppercase fw-bold mb-0">
                            <i class="fas fa-font text-primary me-2"></i>Word Games
                        </h6>
                        <span class="badge bg-primary-subtle text-primary">2 Games</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-xl-6 game-card" data-aos="fade-up">
                            @include('components.wordle-game')
                        </div>
                        <div class="col-xl-6 game-card" data-aos="fade-up" data-aos-delay="100">
                            @include('components.hangman-game')
                        </div>
                    </div>
                </div>

                <!-- Logic Games -->
                <div class="game-category">
                    <div class="category-header d-flex align-items-center justify-content-between mb-4">
                        <h6 class="text-uppercase fw-bold mb-0">
                            <i class="fas fa-brain text-primary me-2"></i>Logic Games
                        </h6>
                        <span class="badge bg-primary-subtle text-primary">2 Games</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-xl-6 game-card" data-aos="fade-up" data-aos-delay="200">
                            @include('components.tictactoe-game')
                        </div>
                        <div class="col-xl-6 game-card" data-aos="fade-up" data-aos-delay="300">
                            @include('components.sudoku-game')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Dashboard Layout */
.dashboard-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    min-height: calc(100vh - 4rem);
    padding: 2rem;
    background: var(--surface-color);
}

/* Sidebar Styles */
.dashboard-sidebar {
    position: relative;
}

.sidebar-content {
    position: sticky;
    top: 2rem;
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
}

.user-profile .avatar img {
    width: 48px;
    height: 48px;
    border: 2px solid var(--primary-color);
    padding: 2px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
}

/* Main Content Styles */
.dashboard-main {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
}

.content-section {
    position: relative;
}

.game-category {
    background: var(--surface-color);
    padding: 2rem;
    border-radius: 1rem;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.game-category:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.game-card {
    transition: all 0.3s ease;
}

.game-card .card {
    height: 100%;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.game-card:hover .card {
    border-color: var(--primary-color);
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.08) !important;
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .dashboard-container {
        background: var(--dark-bg);
    }

    .sidebar-content,
    .dashboard-main {
        background: var(--dark-surface);
    }

    .game-category {
        background: var(--dark-surface);
        border-color: var(--dark-border);
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-container {
        grid-template-columns: 1fr;
    }

    .dashboard-sidebar {
        order: 2;
    }

    .sidebar-content {
        position: static;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }

    .dashboard-main {
        padding: 1rem;
    }

    .game-category {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Todo Form Submission
    const todoForm = document.querySelector('form');
    const todoInput = todoForm.querySelector('input[name="title"]');
    const todoList = document.querySelector('.todo-list');
    const emptyState = document.getElementById('empty-state');

    todoForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const formData = new FormData();
            formData.append('title', todoInput.value);
            
            const response = await fetch('{{ route('todos.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Failed to add todo');
            
            const todo = await response.json();
            
            // Hide empty state if it's visible
            if (emptyState) emptyState.remove();
            
            // Add new todo to the list
            const todoHtml = createTodoHtml(todo);
            todoList.insertAdjacentHTML('afterbegin', todoHtml);
            
            // Clear input
            todoInput.value = '';
            
            // Setup event listeners for new todo
            setupTodoEventListeners(todoList.firstElementChild);
            
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to add todo. Please try again.');
        }
    });

    // Toggle Todo Status
    document.querySelectorAll('.todo-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleTodo);
    });

    // Delete Todo
    document.querySelectorAll('.delete-todo').forEach(button => {
        button.addEventListener('click', deleteTodo);
    });

    async function toggleTodo() {
        const todoId = this.dataset.todoId;
        const todoItem = document.querySelector(`[data-todo-id="${todoId}"]`);
        const checkbox = todoItem.querySelector('.todo-checkbox');
        const originalChecked = checkbox.checked;
        
        try {
            const formData = new FormData();
            formData.append('_method', 'PATCH');
            
            const response = await fetch(`/todos/${todoId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Failed to toggle todo');
            
            const { completed } = await response.json();
            
            // Update UI
            todoItem.classList.toggle('border-success', completed);
            todoItem.classList.toggle('border-opacity-25', completed);
            todoItem.classList.toggle('bg-success-subtle', completed);
            todoItem.querySelector('.todo-title').classList.toggle('text-success', completed);
            todoItem.querySelector('.todo-title').classList.toggle('text-decoration-line-through', completed);
            checkbox.checked = completed;
            
        } catch (error) {
            console.error('Error:', error);
            checkbox.checked = originalChecked; // Revert checkbox state
            alert('Failed to update todo. Please try again.');
        }
    }

    async function deleteTodo() {
        if (!confirm('Are you sure you want to delete this task?')) return;
        
        const todoId = this.dataset.todoId;
        const todoItem = document.querySelector(`[data-todo-id="${todoId}"]`);
        
        try {
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            
            const response = await fetch(`/todos/${todoId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (!response.ok) throw new Error('Failed to delete todo');
            
            // Remove todo from UI with animation
            todoItem.style.opacity = '0';
            todoItem.style.transform = 'translateX(-1rem)';
            setTimeout(() => {
                todoItem.remove();
                
                // Show empty state if no todos left
                if (!todoList.children.length) {
                    todoList.innerHTML = `
                        <div class="text-center py-5" id="empty-state">
                            <div class="mb-3">
                                <i class="fas fa-clipboard-list fa-3x text-secondary opacity-25"></i>
                            </div>
                            <h5 class="text-secondary">No tasks yet</h5>
                            <p class="text-secondary opacity-75 small">Start by adding your first task above.</p>
                        </div>
                    `;
                }
            }, 300);
            
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to delete todo. Please try again.');
        }
    }

    function createTodoHtml(todo) {
        return `
            <div class="todo-item p-3 mb-2 rounded-3 bg-white border" 
                 data-todo-id="${todo.id}"
                 style="opacity: 0; transform: translateY(-1rem);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check">
                            <input type="checkbox" 
                                class="form-check-input todo-checkbox" 
                                data-todo-id="${todo.id}">
                        </div>
                        <span class="todo-title">${todo.title}</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <small class="text-secondary">just now</small>
                        <button type="button" class="btn btn-link text-danger p-0 delete-todo" 
                                data-todo-id="${todo.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function setupTodoEventListeners(todoElement) {
        const checkbox = todoElement.querySelector('.todo-checkbox');
        const deleteBtn = todoElement.querySelector('.delete-todo');
        
        checkbox.addEventListener('change', toggleTodo);
        deleteBtn.addEventListener('click', deleteTodo);
        
        // Animate new todo entry
        requestAnimationFrame(() => {
            todoElement.style.transition = 'all 0.3s ease';
            todoElement.style.opacity = '1';
            todoElement.style.transform = 'translateY(0)';
        });
    }

    // Games Section Toggle
    const toggleBtn = document.getElementById('toggleGamesBtn');
    const gamesContainer = document.querySelector('.games-container');
    
    toggleBtn.addEventListener('click', () => {
        gamesContainer.classList.toggle('collapsed');
        toggleBtn.querySelector('i').classList.toggle('fa-chevron-up');
        toggleBtn.querySelector('i').classList.toggle('fa-chevron-down');
    });
});
</script>
@endpush
