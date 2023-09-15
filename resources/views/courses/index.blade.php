@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Courses List
                @if(auth()->user()->user_type->name==\App\Enums\UserTypeEnum::TEACHER || auth()->user()->user_type->name==\App\Enums\UserTypeEnum::ADMIN)
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#add_modal">Create Course</button>
                @endif
            </div>
            <div class="card-body">
                <table class="table" id="table">
                    <thead>
                    <tr>
                        <th>Sr #</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <form id="add_form">
        <div class="modal fade" id="add_modal">

            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="10">
                            </textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="add_active" name="active">
                                    <label class="form-check-label" for="add_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="edit_form">
        <div class="modal fade" id="edit_modal">
            @csrf
            @method('put')
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" id="code" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="10">
                            </textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="update_active" name="active">
                                    <label class="form-check-label" for="update_active">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="hidden_id" id="hidden_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('course.index') }}",
                    dataType: 'json',
                    type: 'GET',
                },
                columns: [
                    {'data': 'id'},
                    {'data': 'name'},
                    {'data': 'code'},
                    {'data': 'active'},
                    {'data': 'options', orderable: false, searchable: false},
                ],
                order: [0, 'desc'],
                bDestroy: true
            });


            $('#add_form').on('submit', function (e) {
                e.preventDefault()
                $.ajax({
                    type: 'POST',
                    url: '{{route('course.store')}}',
                    data: new FormData(this),
                    contentType: false,
                    data_type: 'json',
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        loader()
                    },
                    success: function (response) {
                        swal.close()
                        $('#table').DataTable().ajax.reload()
                        alertMsg(response.message, response.status)
                        $('#add_form')[0].reset()
                    },
                    error: function (xhr, error, status) {
                        swal.close()
                        var response = xhr.responseJSON
                        alertMsg(response.message, 'error')
                    },
                })
            })

            $('#edit_form').on('submit', function (e) {
                e.preventDefault()
                var id = $('#hidden_id').val()
                var route = "{{route('course.update',['course'=>':course'])}}"
                route = route.replace(':course', id)
                $.ajax({
                    type: 'POST',
                    url: route,
                    data: new FormData(this),
                    contentType: false,
                    data_type: 'json',
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        Swal.showLoading()
                    },
                    success: function (response) {
                        swal.close()
                        $('#table').DataTable().ajax.reload()
                        alertMsg(response.message, response.status)
                        $('#edit_modal').modal('hide')
                    },
                    error: function (xhr, error, status) {
                        swal.close()
                        var response = xhr.responseJSON
                        alertMsg(response.message, 'error')
                    }
                })
            })
        })

        $(document).on('click', '.edit_data', function () {
            var data = $(this).data('params')
            console.log(data)
            $('#name').val(data.name)
            $('#code').val(data.code)
            $('#description').text(data.description)
            $('#hidden_id').val(data.id)
            if (data.active) {
                $('#update_active').attr('checked', true)
            } else {
                $('#update_active').attr('checked', false)
            }
            $('#edit_modal').modal('show')
        })

        $(document).on('submit', '.delete_form', function (e) {
            e.preventDefault()
            var route = $(this).attr('action')
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this data!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: route,
                        data: new FormData(this),
                        contentType: false,
                        data_type: 'json',
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            Swal.showLoading()
                        },
                        success: function (response) {
                            swal.close()
                            alertMsg(response.message, 'error')
                            $('#table').DataTable().ajax.reload()
                        },
                        error: function (xhr, error, status) {
                            swal.close()
                            var response = xhr.responseJSON
                            alertMsg(response.message, 'error')
                        },
                    })
                }
            })
        })
    </script>
@endpush
