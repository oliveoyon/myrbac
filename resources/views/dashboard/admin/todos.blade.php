@extends('dashboard.layouts.admin-layout')

@section('title', 'ToDo List')

@push('styles')
<style>
    .todo-page {
        display: grid;
        gap: 16px;
    }

    .todo-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .todo-panel-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        padding: 15px 18px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .todo-panel-header h1,
    .todo-panel-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #1f2937;
    }

    .todo-panel-header p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .todo-panel-body {
        padding: 16px 18px;
    }

    .todo-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: end;
    }

    .todo-filter-row .form-control {
        min-width: 190px;
    }

    .todo-calendar {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 8px;
    }

    .todo-calendar-day {
        display: block;
        min-height: 76px;
        padding: 9px;
        border: 1px solid #dde7e1;
        border-radius: 8px;
        background: #fff;
        color: #1f2937;
        text-decoration: none;
    }

    .todo-calendar-day:hover,
    .todo-calendar-day.is-active {
        border-color: #7fae96;
        background: #f1faf5;
        color: #163d31;
    }

    .todo-calendar-day small {
        display: block;
        color: #64748b;
        font-weight: 700;
    }

    .todo-calendar-count {
        display: inline-flex;
        margin-top: 8px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #e8f5ee;
        color: #17643a;
        font-size: 12px;
        font-weight: 800;
    }

    .todo-task-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        background: #fff;
    }

    .todo-task-card h3 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 800;
        color: #243b35;
    }

    .todo-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .todo-meta span {
        padding: 2px 7px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #374151;
        font-size: 12px;
        font-weight: 650;
    }

    .todo-status-form {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-top: 10px;
    }

    .todo-empty {
        padding: 18px;
        border: 1px dashed #bfd8cb;
        border-radius: 8px;
        background: #f8fcfa;
        color: #385448;
        text-align: center;
        font-weight: 650;
    }

    @media (max-width: 768px) {
        .todo-calendar {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .todo-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .todo-status-form {
            align-items: stretch;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<section class="todo-page">
    <div class="todo-panel">
        <div class="todo-panel-header">
            <div>
                <h1>Daily ToDo List</h1>
                <p>Personal tasks and shared follow-up tasks for your district/PNGO scope.</p>
            </div>
            <form method="GET" action="{{ route('todos.index') }}" class="todo-filter-row">
                <div>
                    <label class="form-label">Task Date</label>
                    <input type="date" name="task_date" class="form-control form-control-sm" value="{{ $selectedDate }}">
                </div>
                <button class="btn btn-primary btn-sm" type="submit">
                    <i class="fas fa-filter"></i> Load
                </button>
            </form>
        </div>
        <div class="todo-panel-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please check the form.</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="todo-calendar">
                @foreach ($calendarDays as $day)
                    <a href="{{ route('todos.index', ['task_date' => $day['date']]) }}" class="todo-calendar-day {{ $selectedDate === $day['date'] ? 'is-active' : '' }}">
                        <small>{{ $day['day'] }}</small>
                        <strong>{{ $day['label'] }}</strong>
                        <br>
                        <span class="todo-calendar-count">{{ $day['total'] }} task{{ $day['total'] === 1 ? '' : 's' }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="todo-panel">
        <div class="todo-panel-header">
            <div>
                <h2>Add Personal Task</h2>
                <p>This task is visible only in your own ToDo list.</p>
            </div>
        </div>
        <div class="todo-panel-body">
            <form method="POST" action="{{ route('todos.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="task_date" class="form-control" value="{{ old('task_date', $selectedDate) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Details</label>
                        <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="todo-panel h-100">
                <div class="todo-panel-header">
                    <div>
                        <h2>My Tasks</h2>
                        <p>Unsolved personal tasks for {{ \Carbon\Carbon::parse($selectedDate)->format('j M, Y') }}.</p>
                    </div>
                </div>
                <div class="todo-panel-body">
                    @forelse ($personalTodos as $todo)
                        <div class="todo-task-card">
                            <h3>{{ $todo->title }}</h3>
                            @if ($todo->description)
                                <p class="mb-1">{{ $todo->description }}</p>
                            @endif
                            <div class="todo-meta">
                                <span>{{ $statusOptions[$todo->status] ?? $todo->status }}</span>
                                <span>{{ optional($todo->task_date)->format('j M, Y') }}</span>
                            </div>
                            <form method="POST" action="{{ route('todos.status', $todo) }}" class="todo-status-form">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control form-control-sm">
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $todo->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-success">Update</button>
                            </form>
                        </div>
                    @empty
                        <div class="todo-empty">No personal tasks for this date.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="todo-panel h-100">
                <div class="todo-panel-header">
                    <div>
                        <h2>Follow-up Tasks</h2>
                        <p>Shared tasks from follow-up interventions in your district/PNGO scope.</p>
                    </div>
                </div>
                <div class="todo-panel-body">
                    @forelse ($followUpTasks as $task)
                        <div class="todo-task-card">
                            <h3>{{ $task->intervention_to_be_taken }}</h3>
                            <p class="mb-1">
                                <strong>Case:</strong> {{ $task->case_central_id ?: $task->central_id }}
                                @if ($task->case_name)
                                    - {{ $task->case_name }}
                                @endif
                            </p>
                            <div class="todo-meta">
                                <span>{{ $statusOptions[$task->task_status] ?? $task->task_status }}</span>
                                <span>Due {{ optional($task->to_be_taken_date)->format('j M, Y') }}</span>
                                <span>Created by {{ $task->creator->name ?? 'Unknown' }}</span>
                                @if ($task->completedBy)
                                    <span>Solved by {{ $task->completedBy->name }}</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('follow-up-tasks.status', $task->id) }}" class="todo-status-form">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control form-control-sm">
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $task->task_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-success">Update</button>
                            </form>
                        </div>
                    @empty
                        <div class="todo-empty">No follow-up tasks for this date.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
