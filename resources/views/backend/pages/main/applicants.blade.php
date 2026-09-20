@extends('backend.master.index')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    Applicants
                </h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">List of Applicants
                                <button type="button" class="btn btn-primary add" data-toggle="modal" data-target="#defaultModalPrimary" style="float:right">
                                    Add Applicant
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
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>Email Address</th>
                                                <th>Contact No.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applicants as $key => $applicant)
                                                <tr>
                                                    <td>{{ ++$key}}</td>
                                                    <td>{{ $applicant->firstname}}</td>
                                                    <td>{{ $applicant->middlename}}</td>
                                                    <td>{{ $applicant->lastname}}</td>
                                                    <td>{{ $applicant->email}}</td>
                                                    <td>{{ $applicant->contact}}</td>
                                                    <td class="table-action">
                                                        <a href="#" class="align-middle fas fa-fw fa-pen edit" title="Edit" data-toggle="modal" data-target="#defaultModalPrimary" id={{$applicant->id}}></a>
                                                        <a href="{{url('applicant/destroy/' . $applicant->id)}}" onclick="alert('Are you sure you want to Delete?')"><i class="align-middle fas fa-fw fa-trash"></i></a>
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
        <div class="modal fade bd-example-modal-xl" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Applicant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('applicant/save')}}" method="post">
                            @csrf
                        <label for="" style="font-weight: bold;">Full Name</label>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">First Name</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Middle Name</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter Middle Name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Last Name</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name">
                            </div>
                        </div>

                        <label for="" style="font-weight: bold;">Address</label>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">House/Street No.</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="house_no" name="house_no" placeholder="House/Street No.">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Barangay</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="barangay" name="barangay" placeholder="Barangay">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Municipality</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="municipality" name="municipality" placeholder="Municipality">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Province</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="province" name="province" placeholder="Province">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Zip Code</label> <span class="text-danger"> *</span>
                                <input type="number" class="form-control" id="zipcode" name="zipcode" placeholder="Zip Code">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Country</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="country" name="country" placeholder="Country">
                            </div>
                        </div>

                        <label for="" style="font-weight: bold;">Other Information</label>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Place of Birth</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" placeholder="Place of Birth">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Nationality</label> <span class="text-danger"> *</span>
                                <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Nationality">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Email Address</label> <span class="text-danger"> *</span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="sample@gmail.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Contact No.</label> <span class="text-danger"> *</span>
                                <input type="number" class="form-control" id="contact" name="contact" placeholder="+639XXXXXXXXX">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Date of Birth</label> <span class="text-danger"> *</span>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Sex">Sex</label> <span class="text-danger"> *</span>
                                <select name="gender" id="gender" class="form-control mb-3">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Civil Status">Civil Status</label> <span class="text-danger"> *</span>
                                <select name="civil_status" id="civil_status" class="form-control mb-3">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <label for="" style="font-weight: bold;">ID Information</label>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">TIN No.</label>
                                <input type="text" class="form-control" id="tin" name="tin" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">SSS No.</label>
                                <input type="text" class="form-control" id="sss" name="sss" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">PAGIBIG No.</label>
                                <input type="text" class="form-control" id="pagibig" name="pagibig" placeholder="">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">PhilHealth No.</label>
                                <input type="text" class="form-control" id="philhealth" name="philhealth" placeholder="">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit-button">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        function edit(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/applicant/edit/' + id,
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('.modal-title').text('Update Applicant Information');
                    $('.submit-button').text('Update');
                        $.each(data, function() {
                            $.each(this, function(k, v) {
                                $('#'+k).val(v);
                            });
                        });
                    $('#modal-form').attr('action', 'applicant/update/' + data.applicants.id);
                }
            });

        }

        $(function() {
            $('#datatables').DataTable({
                responsive: true,
                "pageLength": 100
            });

            $( "table" ).on( "click", ".edit", function() {
                edit(this.id);
            });

            $('.add').click(function(){
                $('.modal-title').text('Add Applicant');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'applicant/save');

            })
        });
    </script>
@endsection