@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    Application
                </h1>
            </div>
            
            <div class="row" id="search_box">
                <div class="col-6">
                    <label>Search by</label>  
                </div>
                <div class="col-6 text-right">
                    <button class="btn btn-sm btn-primary" onclick="generateRecord()">Generate</button>
                    <button class="btn btn-sm btn-light" onclick="clearFilter()">Clear</button>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">
                            <input type="text" id="filter_fname" class="form-control input-sm" placeholder="Firstname"/>
                        </div>
                        <div class="col-4">
                            <input type="text" id="filter_mname" class="form-control input-sm" placeholder="Middlename"/>
                        </div>
                        <div class="col-4">
                            <input type="text" id="filter_lname" class="form-control input-sm" placeholder="Lastname"/>
                        </div>
                    </div>       
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Application Maintenance Screen
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
                                                <th>Action</th>
                                                <th>Application No</th>
                                                <th>Name</th>
                                                <th>Picture</th>
                                                <th>Signature</th>
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
            </div>
        </div>
        {{-- MODAL --}}
        <div class="modal" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Renewal/Card Re-issue Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('applicant/save')}}" method="post">
                            @csrf
                        <div class="form-group col-md-12">
                            <label for="Application">Application Type</label> <span class="text-danger"> *</span>
                            <select id="type" name="type" class="form-control mb-3">
                                <option value="RENEWAL" selected>RENEWAL</option>
                                <option value="CARD RE-ISSUE">CARD RE-ISSUE</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Application No <span class="text-danger"> *</span></label>
                            <div class="row col-12">
                                <input type="hidden" id="new_application_id" name="new_application_id" class="form-control col-10"/>
                                <input type="text" class="form-control col-10 application_value" placeholder="Select Application No" disabled/>
                                <button type="button" class="btn btn-primary col-2" data-toggle="modal" data-target="#applicationModal"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Date <span class="text-danger"> *</span></label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
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

           {{-- APPLICATION MODAL --}}
           <div class="modal fade" id="applicationModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Application No</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <table id="application_table" class="table table-striped pnp-picker" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Application No</th>
                                    <th>Name</th>
                                    <th>UCID</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
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
                    $('.modal-title').text('Update Application');
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

        function applicant(){
            $('#datatables').dataTable().fnDestroy();
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength:10,
                ordering: false,
                ajax: {
                    url: "/application/list_applicant",
                    type: 'GET',
                },
                columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'lastname',
                            render: function(data, type, row, meta) {
                                return  '<a href="/application/picture/' + row.id + '"><i class="align-middle fas fa-fw fa-camera"></i></a>' +
                                        // '<a href="/application/right_thumb/' + row.id + '"><i class="align-middle fas fa-fw fa-thumbs-up"></i></a>' +
                                        // '<a href="/application/left_thumb/' + row.id + '"><i class="align-middle fas fa-fw fa-thumbs-up"></i></a>' +
                                        '<a href="/application/signature/' + row.id + '"><i class="align-middle fas fa-fw fa-signature"></i></a>'
                                       
                            }
                        },
                        {data: 'application_no', name: 'application_no' },
                        {data: 'firstname',
                            render: function(data, type, row, meta) {
                                return row.firstname + ' ' + (row.middlename !== null && row.middlename !== '' ? row.middlename : ' ') + ' ' + row.lastname + ' ' +  (row.suffix !== null && row.suffix !== '' ? row.suffix : ' ');
                            }
                        },
                        {data: 'middlename',
                            render: function(data, type, row, meta) {
                                if(row.picture == null) {
                                    return "<small class='badge badge-danger'>NO PICTURE</small>"
                                } else {
                                    return "<img src='/img/application_picture/"+row.id+"/"+row.picture+".png' width='80px'>";
                                }
                            }
                        },
                        {data: 'signature',
                            render: function(data, type, row, meta) {
                                if(row.signature == null) {
                                    return "<small class='badge badge-danger'>NO SIGNATURE</small>"
                                } else {
                                    return "<img src='/img/signature/"+row.id+"/"+row.signature+"' width='80px'>";
                                }
                            }
                        },
                    ],
                order: [[0, 'desc']]
            });
        }

        function renewal(){
            $('#application_table').dataTable().fnDestroy();
            $('#application_table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength:10,
                ajax: {
                url: "/application/list_applicant",
                type: 'GET',
                },
                columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'application_no', name: 'application_no' },
                        {data: 'firstname',
                            render: function(data, type, row, meta) {
                                return row.firstname + ' ' + (row.middlename !== null && row.middlename !== '' ? row.middlename : ' ') + ' ' + row.lastname + ' ' +  (row.suffix !== null && row.suffix !== '' ? row.suffix : ' ');
                            }
                        },
                        {data: 'ucid', name: 'ucid' },
                    ],
                order: [[0, 'desc']]
            });
        }

        

        function selectApplication(id, value) {
            $('#new_application_id').val(id);
            $('.application_value').val(value);
            $('.modal-backdrop.fade.show').remove();
        }

        $(function() {

            $('#datatables, #application_table').DataTable({
                responsive: true,
                "pageLength": 10
            });

            applicant();

            $( "table" ).on( "click", ".edit", function() {
                edit(this.id);
            });

            $('#application_table tbody').on('click', 'tr', function(){
                var data = $('#application_table').DataTable().row(this).data();
                selectApplication(data.id, data.application_no);
                $("#applicationModal").modal('toggle')
            })

            $('.add').click(function(){
                renewal();
                $('.modal-title').text('Add Application');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'applicant/save');
            })
        });

        
        function generateRecord() {
            
            $('#datatables').dataTable().fnDestroy();
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength:10,
                ordering: false,
                ajax: {
                    url: "/application/list_applicant_filter",
                    type: 'POST',
                    data: {
                        _token: "{{csrf_token()}}",
                        fname: $('#filter_fname').val(),
                        mname: $('#filter_mname').val(),
                        lname: $('#filter_lname').val(),
                    }
                },
                columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'lastname',
                            render: function(data, type, row, meta) {
                                return  '<a href="/application/picture/' + row.id + '"><i class="align-middle fas fa-fw fa-camera"></i></a>' +
                                        // '<a href="/application/right_thumb/' + row.id + '"><i class="align-middle fas fa-fw fa-thumbs-up"></i></a>' +
                                        // '<a href="/application/left_thumb/' + row.id + '"><i class="align-middle fas fa-fw fa-thumbs-up"></i></a>' +
                                        '<a href="/application/signature/' + row.id + '"><i class="align-middle fas fa-fw fa-signature"></i></a>'
                                       
                            }
                        },
                        {data: 'application_no', name: 'application_no' },
                        {data: 'firstname',
                            render: function(data, type, row, meta) {
                                return row.firstname + ' ' + (row.middlename !== null && row.middlename !== '' ? row.middlename : ' ') + ' ' + row.lastname + ' ' +  (row.suffix !== null && row.suffix !== '' ? row.suffix : ' ');
                            }
                        },
                        {data: 'middlename',
                            render: function(data, type, row, meta) {
                                if(row.picture == null) {
                                    return "<small class='badge badge-danger'>NO PICTURE</small>"
                                } else {
                                    return "<img src='/img/application_picture/"+row.id+"/"+row.picture+".png' width='80px'>";
                                }
                            }
                        },
                        {data: 'signature',
                            render: function(data, type, row, meta) {
                                if(row.signature == null) {
                                    return "<small class='badge badge-danger'>NO SIGNATURE</small>"
                                } else {
                                    return "<img src='/img/signature/"+row.id+"/"+row.signature+"' width='80px'>";
                                }
                            }
                        },
                    ],
                order: [[0, 'desc']]
            });
        }

        function clearFilter() {
            $('#filter_fname').val('');
            $('#filter_mname').val('');
            $('#filter_lname').val('');
            
            generateRecord();
        }
    </script>

    <style>
        table thead th, table td{
            white-space: nowrap
        }
        
        /* Search panel sits above the table (in flow, not fixed) — styled in theme.css */
        div#search_box {
            position: static;
        }
    </style>
@endsection
