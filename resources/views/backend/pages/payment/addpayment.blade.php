@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    Add Payment
                </h1>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Payment Information</h5>
                            <h6 class="card-subtitle text-muted">Information for payment of application.</h6>
                        </div>
                        <div class="card-body">
                            <form id="modal-form" action="{{url('payment/save')}}" method="post" enctype="multipart/form-data">
                                @csrf()
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Application No</label>
                                        <div class="row col-12">
                                            <input type="hidden" id="application_id" name="application_id" class="form-control col-10"/>
                                            <input type="text" class="form-control col-10 application_value" placeholder="Select Application No" disabled/>
                                            <button type="button" class="btn btn-primary col-2" data-toggle="modal" data-target="#applicationModal"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Application Type</label>
                                        <input type="text" class="form-control" id="type" name="type" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">First Name</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Middle Name</label>
                                        <input type="text" class="form-control" id="middlename" name="middlename" placeholder="">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Purpose</label>
                                        <input type="text" class="form-control" id="purpose" name="purpose" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Specification</label>
                                        <input type="text" class="form-control" id="specification" name="specification" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">OR Number</label>
                                        <input type="text" class="form-control" id="or_number" name="or_number"  value="{{ $or_number }}" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">UCID</label>
                                        <input type="text" class="form-control" id="ucid" name="ucid" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Status</label>
                                        <input type="text" class="form-control" id="status" name="status" >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Date of Expiration</label>
                                        <input type="date" class="form-control" id="date_of_expiration" name="date_of_expiration" >
                                    </div>
                                    <div class="form-group col-md-8">

                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="display:none;">
                        <div class="card-header">
                            <h5 class="card-title">Payment Summary</h5>
                            <h6 class="card-subtitle text-muted">Summary of fees and total payment</h6>
                        </div>
                        <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-6 text-sm-right">Card Fee:</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label text-sm-right">Php 100.00</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-6 text-sm-right">Clearance Certificate:</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label text-sm-right">Php <span class="purpose_amount">0.00</span></label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-6 text-md-right">Total Amount:</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label text-md-right">Php <span class="total_amount">0.00</span></label>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Clearance Payment</h5>
                            <h6 class="card-subtitle text-muted">Pay the total amount.  </h6>
                        </div>
                        <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4 text-sm-right">Amount:</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" placeholder="Total Amount" id="amount" name="amount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6" style="text-align: right;">
                                        <a href="" data-toggle="modal" data-target="#defaultModalPrimary"><button class="btn btn-primary">View Receipt</button></a>
                                    </div>
                                        <button type="submit" class="btn btn-primary" id="addBtn">Add Payment</button>
                                </div>
                            </form>
                        </div>
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
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>UCID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $key => $application)
                                        <tr data-dismiss="modal" aria-label="Close" onclick="selectApplication({{ $application->id }}, '{{ $application->new_application->application_no }}'
                                        , '{{ $application->type }}', '{{ $application->new_application->firstname }}', '{{ $application->new_application->middlename }}' , '{{ $application->new_application->lastname }}'
                                        , '{{ $application->new_application->suffix }}', '{{ $application->new_application->purpose->purpose }}', '{{ number_format($application->new_application->purpose->cost, 2) }}'
                                        , '{{ number_format($application->new_application->purpose->cost + 100, 2) }}', '{{ $application->date }}', '{{ $application->new_application->ucid }}')">
                                            <td>{{ ++$key}}</td>
                                            <td>{{ $application->new_application->application_no}}</td>
                                            <td>{{ $application->new_application->firstname . ' ' . $application->new_application->middlename . ' ' . $application->new_application->lastname . ', ' . $application->new_application->suffix}}</td>
                                            <td>{{ $application->type}}</td>
                                            <td>{{ date('M-d-Y', strtotime($application->date))}}</td>
                                            <td>{{ $application->new_application->ucid}}</td>
                                            @if ($application->status == 'ON-PROCESS')
                                                <td class="badge badge-primary m-2">{{ $application->status}}</td>
                                            @elseif($application->status == 'WITH FINDINGS')
                                                <td class="badge badge-warning m-2">{{ $application->status}}</td>
                                            @elseif($application->status == 'CANCELLED')
                                                <td class="badge badge-danger m-2">{{ $application->status}}</td>
                                            @endif
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
                        <h5 class="modal-title">Payment Receipt</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3" style="border: 1px solid;">
                        <div class="" id="printableDiv">
                            <div style="padding: 1em;"></div>
                            <div class="row">
                                <div class="form-group col-md-12" style="text-align: center">
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">ARRONET SOLUTIONS INTEGRATORS INC.</p>
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">TIN: 007-888-081-000</p>
                                </div>
                                <div class="padding: 0.5em;"></div>
                                <div class="form-group col-md-12" style="text-align: center;">
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color:  black;" class="">CASHIER:</p>
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">OR #:</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6" style="text-align: left">
                                    <p style="margin-bottom: 0px; text-align: center; color: black;" class="">Processing Fee:</p>
                                </div>
                                <div class="form-group col-md-6" style="text-align: right;">
                                    <p style="margin-bottom: 0px; text-align: center; color: black;" class="">Php <span class="purpose_amount"></span></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6" style="text-align: left">
                                    <p style="margin-bottom: 0px; text-align: center; color: black;" class="">Card Fee:</p>
                                </div>
                                <div class="form-group col-md-6" style="text-align: right;">
                                    <p style="margin-bottom: 0px; text-align: center; color: black;" class="">Php 100.00</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6" style="text-align: left">
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Total Amount:</p>
                                </div>
                                <div class="form-group col-md-6" style="text-align: right;">
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Php <span class="total_amount"></span></p>
                                </div>
                            </div>
                            <div style="padding: 1em;"></div>
                            <div class="row">
                                <div class="form-group col-md-12" style="text-align: center">
                                    <p style="margin-bottom: 0px; text-align: center; text-transform: uppercase; font-weight: bold; color: black;" class="">This serves as your official receipt!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" onclick='printDiv();'class="btn btn-primary submit-button">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    {{-- <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}
    <script>
        function selectApplication(id, value, type, firstname, middlename, lastname, suffix, purpose, purpose_amount, total, date, ucid) {
            $('#application_id').val(id);
            $('.application_value').val(value);
            $('#type').val(type);
            $('#firstname').val(firstname);
            $('#middlename').val(middlename);
            $('#lastname').val(lastname);
            $('#suffix').val(suffix);
            $('#purpose').val(purpose);
            $('.purpose_amount').text(purpose_amount);
            $('.total_amount').text(total)
            $('#date_of_expiration').val(date);
            $('#ucid').val(ucid);
        }

        function edit(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/purpose/edit/' + id,
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('.modal-title').text('Update Clearance Purpose');
                    $('.submit-button').text('Update');
                        $.each(data, function() {
                            $.each(this, function(k, v) {
                                $('#'+k).val(v);
                            });
                        });
                    $('#modal-form').attr('action', 'purpose/update/' + data.purposes.id);
                }
            });

        }

        $(function() {
            $('#datatables, #application_table').DataTable({
                responsive: true,
                "pageLength": 100
            });

            $( "table" ).on( "click", ".edit", function() {
                edit(this.id);
            });

            $('.add').click(function(){
                $('.modal-title').text('Add Clearance Purpose');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'purpose/save');

            })
        });

        function printDiv() {

            var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
            var divToPrint=document.getElementById('printableDiv');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write(myStyle + '<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            // setTimeout(function(){newWin.close();},10);

        };
    </script>
@endsection
