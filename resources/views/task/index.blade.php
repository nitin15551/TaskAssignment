@extends('layouts.app')

@section('content')

    {{-- Add Modal --}}
    <div  id="AddTaskModal" tabindex="-1" aria-labelledby="AddTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddTaskModalLabel">PHP Simple To Do List App</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul id="save_msgList"></ul>

                    <div class="form-group mb-3">
                        <input type="text" required class="task form-control" placeholder="Type Task..">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary add_task">Add Task</button>
                </div>

            </div>
        </div>
    </div>


    {{-- Delete Modal --}}
    <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Task Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Confirm to Delete Data ?</h4>
                    <input type="hidden" id="deleteing_id">
                    <input type="hidden" id="checked_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary delete_task">Yes Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End - Delete Modal --}}

    {{-- Checked Modal --}}
    <div class="modal fade" id="CheckModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Done Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4>Confirm to Done ?</h4>
                    <input type="hidden" id="checked_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary checked_task">Yes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End - checked Modal --}}


    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">

                <div id="success_message"></div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            fetchtask();

            function fetchtask() {
                $.ajax({
                    type: "GET",
                    url: "/fetch-tasks",
                    dataType: "json",
                    success: function (response) {
                        $('tbody').html("");
                        $.each(response.listtasks, function (key, item) {
                            $('tbody').append('<tr>\
                            <td>' + item.id + '</td>\
                            <td>' + item.task + '</td>\
                            <td>' + item.status + '</td>\
                            <td>' + item.checbox + '\
                            <button type="button" value="' + item.id + '" class="btn btn-danger deletebtn btn-sm">Delete</button>\
                            </td>\
                           </tr>');
                        });
                    }
                });
            }

            $(document).on('click', '.add_task', function (e) {
                e.preventDefault();

                $(this).text('Sending..');

                var data = {
                    'task': $('.task').val(),
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/tasks",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 400) {
                            $('#save_msgList').html("");
                            $('#save_msgList').addClass('alert alert-danger');
                            $.each(response.errors, function (key, err_value) {
                                $('#save_msgList').append('<li>' + err_value + '</li>');
                            });
                            $('.add_task').text('Save');
                        } else {
                            $('#save_msgList').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#AddTaskModal').find('input').val('');
                            $('.add_task').text('Save');
                            $('#AddTaskModal').modal('hide');
                            fetchtask();
                        }
                    }
                });

            });

            $(document).on('click', '.deletebtn', function () {
                var stud_id = $(this).val();
                $('#DeleteModal').modal('show');
                $('#deleteing_id').val(stud_id);
            });

            $(document).on('click', '.delete_task', function (e) {
                e.preventDefault();

                $(this).text('Deleting..');
                var id = $('#deleteing_id').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "DELETE",
                    url: "/delete-tasks/" + id,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 404) {
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('.delete_task').text('Yes Delete');
                        } else {
                            $('#success_message').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('.delete_task').text('Yes Delete');
                            $('#DeleteModal').modal('hide');
                            fetchtask();
                        }
                    }
                });
            });


            $(document).on('click', '.form-check-input', function () {
                var task_id = $(this).val();
                $('#CheckModal').modal('show');
                $('#checked_id').val(task_id);
            });


            $(document).on('click', '.checked_task', function (e) {
                e.preventDefault();

                $(this).text('Checked..');
                var id = $('#checked_id').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "DELETE",
                    url: "/checked-tasks/" + id,
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        if (response.status == 404) {
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('.form-check-input').text('Yes checked');
                        } else {
                            $('#success_message').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('.form-check-input').text('Yes ');
                            $('#CheckModal').modal('hide');
                            fetchtask();
                        }
                    }
                });
            });
        });

    </script>

@endsection
