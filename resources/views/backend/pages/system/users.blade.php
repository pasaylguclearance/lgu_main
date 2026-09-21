@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    System Users
                </h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">System User Screen
                                <button type="button" class="btn btn-primary add" data-toggle="modal" data-target="#defaultModalPrimary" style="float:right">
                                    Add System User
                                </button>
                            </h5>
                        </div>
                        @include('backend.partial.flash-message')
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table id="datatables" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $key => $user)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $user->firstname." ".$user->lastname }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td class="table-action">
                                                        <a href="#" class="align-middle fas fa-fw fa-pen edit" title="Edit" data-toggle="modal" data-target="#defaultModalPrimary" onclick="edit({{$user->id}})"></a>
                                                        <a href="#" data-toggle="modal" data-target="#confirmation" onclick="delete_id={{$user->id}};"><i class="align-middle fas fa-fw fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL --}}
        <div class="modal fade" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add System User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="modal-form" action="{{url('/user/save')}}" method="post">
                        <div class="modal-body m-3">
                                @csrf
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Middle Name</label>
                                <input type="text" class="form-control" id="middlename" name="middlename" value="">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Suffx</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary submit-button">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
        <div class="modal fade" id="confirmation" tabindex="-1" role="dialog" aria-labelledby="confirmationLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Are you sure you want to delete this data?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" onclick="confirmDelete(delete_id)">Yes</button>
                </div>
            </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        var delete_id = '';

        function edit(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/user/edit/' + id,
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('.modal-title').text('Update User');
                    $('.submit-button').text('Update');
                        $.each(data, function() {
                            $.each(this, function(k, v) {
                                $('#'+k).val(v);
                            });
                        });
                    $('#modal-form').attr('action', '/user/update/' + id);
                }
            });
        }
        
        function confirmDelete(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/user/destroy/' + id,
                method: 'get',
                data: {
                },
                success: function(data) {
                    location.reload();
                }
            });
        }

        $(function() {
            $('#datatables').DataTable({
                responsive: true
            });

            // "View Profile" (header account menu) lands here with ?edit=<own id>:
            // open the existing Update User modal for that account.
            var profileEdit = new URLSearchParams(window.location.search).get('edit');
            if (profileEdit && /^\d+$/.test(profileEdit)) {
                edit(profileEdit);
                $('#defaultModalPrimary').modal('show');
            }

            $('.add').click(function(){
                $('.modal-title').text('Add User');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', '/user/save');

            })
        });
    </script>
@endsection