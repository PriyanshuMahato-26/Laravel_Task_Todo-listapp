@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">To-Do List</h3>
            </div>
            <div class="card-body">
                <form id="task-form" class="mb-4">
                    <div class="input-group">
                        <input type="text" id="task-input" class="form-control" placeholder="Add a new task..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
                    <div id="error-message" class="error-message"></div>
                </form>

                <div class="d-flex justify-content-between mb-3">
                    <h5>Tasks</h5>
                    <button id="show-all-btn" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-eye"></i> Show All Tasks
                    </button>
                </div>

                <div id="task-list" class="task-list">
                    @foreach($tasks as $task)
                        <div class="task-item" data-id="{{ $task->id }}">
                            <input type="checkbox" class="task-checkbox form-check-input" {{ $task->completed ? 'checked' : '' }}>
                            <span class="task-title {{ $task->completed ? 'completed' : '' }}">{{ $task->title }}</span>
                            <div class="task-actions">
                                <button class="btn btn-sm btn-danger delete-task">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Setup AJAX CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add new task
        $('#task-form').on('submit', function(e) {
            e.preventDefault();
            
            const taskTitle = $('#task-input').val().trim();
            
            $.ajax({
                type: 'POST',
                url: '{{ route('tasks.store') }}',
                data: {
                    title: taskTitle
                },
                success: function(response) {
                    // Create new task item
                    const newTaskHtml = `
                        <div class="task-item" data-id="${response.id}">
                            <input type="checkbox" class="task-checkbox form-check-input">
                            <span class="task-title">${response.title}</span>
                            <div class="task-actions">
                                <button class="btn btn-sm btn-danger delete-task">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Add to task list
                    $('#task-list').prepend(newTaskHtml);
                    
                    // Clear input field
                    $('#task-input').val('');
                    
                    // Hide error message if it was visible
                    $('#error-message').hide();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        $('#error-message').text(xhr.responseJSON.error).show();
                    } else {
                        $('#error-message').text('An error occurred. Please try again.').show();
                    }
                }
            });
        });

        // Mark task as completed
        $(document).on('change', '.task-checkbox', function() {
            const taskItem = $(this).closest('.task-item');
            const taskId = taskItem.data('id');
            const isCompleted = $(this).is(':checked');
            
            $.ajax({
                type: 'PUT',
                url: `/tasks/${taskId}`,
                data: {
                    completed: isCompleted
                },
                success: function() {
                    // Toggle completed class
                    if (isCompleted) {
                        taskItem.find('.task-title').addClass('completed');
                        // Optional: hide completed task
                        taskItem.fadeOut('slow');
                    } else {
                        taskItem.find('.task-title').removeClass('completed');
                    }
                }
            });
        });

        // Delete task
        $(document).on('click', '.delete-task', function() {
            const taskItem = $(this).closest('.task-item');
            const taskId = taskItem.data('id');
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure to delete this task?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete the task
                    $.ajax({
                        type: 'DELETE',
                        url: `/tasks/${taskId}`,
                        success: function() {
                            // Remove task from DOM
                            taskItem.fadeOut('fast', function() {
                                $(this).remove();
                            });
                            
                            // Show success message
                            Swal.fire(
                                'Deleted!',
                                'Your task has been deleted.',
                                'success'
                            );
                        }
                    });
                }
            });
        });

        // Show all tasks button
        $('#show-all-btn').on('click', function() {
            $.ajax({
                type: 'GET',
                url: '{{ route('tasks.all') }}',
                success: function(tasks) {
                    // Clear current task list
                    $('#task-list').empty();
                    
                    // Add all tasks
                    tasks.forEach(function(task) {
                        const taskHtml = `
                            <div class="task-item" data-id="${task.id}">
                                <input type="checkbox" class="task-checkbox form-check-input" ${task.completed ? 'checked' : ''}>
                                <span class="task-title ${task.completed ? 'completed' : ''}">${task.title}</span>
                                <div class="task-actions">
                                    <button class="btn btn-sm btn-danger delete-task">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        
                        $('#task-list').append(taskHtml);
                    });
                }
            });
        });
    });
</script>
@endsection