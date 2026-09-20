@extends('backend.master.template')
@section('content')
    <div class="container-fluid">
        <div class="header d-flex flex-wrap align-items-center justify-content-between mb-3">
            <div>
                <h1 class="header-title mb-1">Application</h1>
                <p class="text-muted mb-0">Track active applications and create renewal/card re-issue requests.</p>
            </div>
            <button type="button" class="btn btn-primary add mt-2 mt-md-0" data-toggle="modal" data-target="#defaultModalPrimary">
                <i class="fas fa-plus mr-1"></i> Renewal / Card Re-issue
            </button>
        </div>

        @php
            $totalCount = $applications->count();
            $onProcessCount = $applications->where('status', 'ON-PROCESS')->count();
            $withFindingsCount = $applications->where('status', 'WITH FINDINGS')->count();
            $cancelledCount = $applications->where('status', 'CANCELLED')->count();
        @endphp

        <div class="row mb-3">
            <div class="col-6 col-lg-3 mb-2">
                <div class="stat-card">
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $totalCount }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-2">
                <div class="stat-card">
                    <div class="stat-label">On-Process</div>
                    <div class="stat-value text-primary">{{ $onProcessCount }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-2">
                <div class="stat-card">
                    <div class="stat-label">With Findings</div>
                    <div class="stat-value text-warning">{{ $withFindingsCount }}</div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-2">
                <div class="stat-card">
                    <div class="stat-label">Cancelled</div>
                    <div class="stat-value text-danger">{{ $cancelledCount }}</div>
                </div>
            </div>
        </div>

        @include('backend.partial.flash-message')

        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title mb-2 mb-md-0">Application Master List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="mr-2 mb-2 mb-md-0">
                        <select id="statusFilter" class="form-control form-control-sm">
                            <option value="">All Status</option>
                            <option value="ON-PROCESS">ON-PROCESS</option>
                            <option value="WITH FINDINGS">WITH FINDINGS</option>
                            <option value="CANCELLED">CANCELLED</option>
                            <option value="PAID">PAID</option>
                        </select>
                    </div>
                    <div class="mb-2 mb-md-0">
                        <input type="text" id="quickSearch" class="form-control form-control-sm" placeholder="Search application, name, UCID...">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatables" class="table table-hover app-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Application No</th>
                                <th>Name</th>
                                <th>UCID</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $key => $application)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        <a href="{{ url('application/destroy/' . $application->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this application?')">
                                            <i class="align-middle fas fa-fw fa-trash"></i>
                                        </a>
                                    </td>
                                    <td><strong>{{ $application->new_application->application_no }}</strong></td>
                                    <td>{{ trim($application->new_application->firstname . ' ' . $application->new_application->middlename . ' ' . $application->new_application->lastname . ', ' . $application->new_application->suffix) }}</td>
                                    <td>{{ $application->new_application->ucid }}</td>
                                    <td>{{ $application->type }}</td>
                                    <td>{{ date('M d, Y', strtotime($application->date)) }}</td>
                                    <td>
                                        @if ($application->status == 'ON-PROCESS')
                                            <span class="badge badge-pill badge-primary">{{ $application->status }}</span>
                                        @elseif($application->status == 'WITH FINDINGS')
                                            <span class="badge badge-pill badge-warning">{{ $application->status }}</span>
                                        @elseif($application->status == 'CANCELLED')
                                            <span class="badge badge-pill badge-danger">{{ $application->status }}</span>
                                        @else
                                            <span class="badge badge-pill badge-success">{{ $application->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Renewal/Card Re-issue Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-3">
                    <form id="modal-form" action="{{url('application/save')}}" method="post">
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
    <div class="modal fade" id="applicationModal" style="background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Application No</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-3">
                    <table id="application_table" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Application No</th>
                                <th>Name</th>
                                <th>UCID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applicants as $key => $applicant)
                                <tr data-dismiss="modal" aria-label="Close" onclick="selectApplication({{ $applicant->id }}, '{{ $applicant->application_no }}')">
                                    <td>{{ ++$key}}</td>
                                    <td>{{ $applicant->application_no}}</td>
                                    <td>{{ $applicant->firstname . ' ' . $applicant->middlename . ' ' . $applicant->lastname . ', ' . $applicant->suffix}}</td>
                                    <td>{{ $applicant->ucid}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        function edit(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/application/edit/' + id,
                method: 'get',
                success: function(data) {
                    $('.modal-title').text('Update Application');
                    $('.submit-button').text('Update');
                    $.each(data, function() {
                        $.each(this, function(k, v) {
                            $('#'+k).val(v);
                        });
                    });
                    $('#modal-form').attr('action', 'application/update/' + data.applications.id);
                }
            });
        }

        function selectApplication(id, value) {
            $('#new_application_id').val(id);
            $('.application_value').val(value);
        }

        $(function() {
            var listTable = $('#datatables').DataTable({
                responsive: true,
                scrollX: true,
                pageLength: 25,
                order: [[0, 'desc']]
            });

            $('#application_table').DataTable({
                responsive: true,
                scrollX: true,
                pageLength: 25
            });

            $('#quickSearch').on('keyup', function() {
                listTable.search(this.value).draw();
            });

            $('#statusFilter').on('change', function() {
                var val = this.value;
                if (val === '') {
                    listTable.column(7).search('').draw();
                } else {
                    listTable.column(7).search('^' + val + '$', true, false).draw();
                }
            });

            $("table").on("click", ".edit", function() {
                edit(this.id);
            });

            $('.add').click(function(){
                $('.modal-title').text('Add Application');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'application/save');
            });
        });
    </script>
    <style>
        .stat-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 14px;
            background: #fff;
            height: 100%;
        }

        .stat-label {
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.1;
            margin-top: 4px;
        }

        .app-table thead th,
        .app-table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .gap-2 > * {
            margin-right: .5rem;
        }

        .gap-2 > *:last-child {
            margin-right: 0;
        }
    </style>
@endsection
