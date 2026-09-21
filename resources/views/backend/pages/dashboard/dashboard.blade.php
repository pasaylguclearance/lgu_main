@extends('backend.master.template')

@section('content')
<div class="container-fluid">

    <div class="header">
        <h1 class="header-title">
            DASHBOARD
        </h1>
    </div>
    <div class="filter-container mb-5">
        <div class="row">
            <div class="col-3">
                <label for="filter_date">Filter Date:</label>
                <input type="date" class="form-control" name="date" id="filter_date" onchange="filterData()">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-xxl-12 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card dash-card" id="daily_card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Total Issued Clearance</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-dark">
                                                <i class="align-middle" data-feather="users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="display-5 mt-1 mb-3" id="application">{{($application)}}</h1>
                                <div class="mb-0">
                                    <span>Daily Count</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card dash-card" id="applicant_card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Total Applicant</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-dark">
                                                <i class="align-middle" data-feather="users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="display-5 mt-1 mb-3" id="applicant">{{$applicant}}</h1>
                                <div class="mb-0">
                                    <span>Applicant Count</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="card dash-card" >
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Local Applications</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-dark">
                                                <i class="align-middle" data-feather="shopping-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="display-5 mt-1 mb-3" id="local">{{$local}}</h1>
                                <div class="mb-0">
                                    <span class="text-danger">Pasay Residents</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="card dash-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col mt-0">
                                        <h5 class="card-title">Non-Local Applications</h5>
                                    </div>

                                    <div class="col-auto">
                                        <div class="avatar">
                                            <div class="avatar-title rounded-circle bg-primary-dark">
                                                <i class="align-middle" data-feather="shopping-cart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="display-5 mt-1 mb-3" id="non_local">{{$outside}}</h1>
                                <div class="mb-0">
                                    <span class="text-danger">Outside Pasay Residents</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="recordViewer" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="record_view"></table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function filterData() {
        var data = {
            _token: "{{ csrf_token() }}",
            filter_date: $('#filter_date').val()
        };

        $.post('/dashboard/filterRecord', data, (response)=>{
            console.log(response);
            $('#application').text(response.application);
            $('#application_month').text(response.application_month);
            $('#applicant').text(response.applicant);
            $('#payments').text(response.payment);
            $('#local').text(response.local);
            $('#non_local').text(response.outside);
        });
    }

    function getRecord(get) {
        var data = {
            _token: "{{ csrf_token() }}",
            filter_date: $('#filter_date').val() !== ''?$('#filter_date').val():'none'
        };

        if(get === 'payment') {
            $('#record_view').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/dashboard/record/"+get+"/"+data.filter_date,
                    type: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', title: '#'},
                    {data: 'application.new_application.ucid', name: 'control_number', title: 'Control #'},
                    {name: 'name', title: 'Name', render: (data, type, row)=> {
                        return row.application.new_application.firstname + " " + (row.application.new_application.middlename !== '' && row.application.new_application.middlename !== null ? row.application.new_application.middlename + " ":'') + row.application.new_application.lastname;
                    }}
                ],
                order: [[0, 'desc']],
                "bDestroy": true
            });
        }
        else if(get === 'local' || get === 'other'){
            $('#record_view').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/dashboard/record/"+get+"/"+data.filter_date,
                    type: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', title: '#'},
                    {data: 'ucid', name: 'control_number', title: 'Control #'},
                    {name: 'name', title: 'Name', render: (data, type, row)=> {
                        return row.firstname + " " + (row.middlename !== '' && row.middlename !== null ? row.middlename + " ":'') + row.lastname;
                    }}
                ],
                order: [[0, 'desc']],
                "bDestroy": true
            });
        }
        else {
            $('#record_view').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/dashboard/record/"+get+"/"+data.filter_date,
                    type: 'GET'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', title: '#'},
                    {data: 'new_application.ucid', name: 'control_number', title: 'Control #'},
                    {name: 'name', title: 'Name', render: (data, type, row)=> {
                        return row.new_application.firstname + " " + (row.new_application.middlename !== '' && row.new_application.middlename !== null ? row.new_application.middlename + " ":'') + row.new_application.lastname;
                    }}
                ],
                order: [[0, 'desc']],
                "bDestroy": true
            });
        }

        $('#recordViewer').modal('show');

    }
</script>

<style>
.dash-card {
    cursor: pointer;
    transition: .3s;
}
.dash-card:hover {
    background: var(--pnp-blue-soft) !important;
    transform: translateY(-2px);
}
li.paginate_button.page-item {
    padding: 0px !important;
}
</style>
