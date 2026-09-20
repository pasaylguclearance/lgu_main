@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    Payments
                </h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Payments
                                {{-- <button type="button" class="btn btn-primary add" data-toggle="modal" data-target="#defaultModalPrimary" style="float:right">
                                    Add Payment
                                </button> --}}
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
                                                <th>Application No.</th>
                                                <th>OR No.</th>
                                                <th>Full Name</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payments as $key => $payment)
                                                <tr>
                                                    <td>{{ ++$key}}</td>
                                                    <td>{{ $payment->application->new_application->application_no}}</td>
                                                    <td>{{ $payment->or_number}}</td>
                                                    <td>{{ $payment->application->new_application->firstname . ' ' . $payment->application->new_application->middlename . ' ' . $payment->application->new_application->lastname . ',' . $payment->application->new_application->suffix}}</td>
                                                    <td>{{ $payment->application->status}}</td>
                                                    <td>₱ {{ number_format($payment->amount, 2)}}</td>
                                                    <td class="table-action">
                                                        <a href="#" class="align-middle fas fa-fw fa-print" title="Edit" data-toggle="modal" data-target="#defaultModalReceipt"></a>
                                                        <a href="#" class="align-middle fas fa-fw fa-pen edit" title="Edit" data-toggle="modal" data-target="#defaultModalPrimary" id={{$payment->id}}></a>
                                                        <a href="{{url('purpose/destroy/' . $payment->id)}}" onclick="alert('Are you sure you want to Delete?')"><i class="align-middle fas fa-fw fa-trash"></i></a>
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
                        <h5 class="modal-title">Add Clearance Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('purpose/save')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Application No.</label>
                                    <div class="input-group">
                                    <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Search Application No.">
                                    <button class="btn btn-primary" type="button">Search Application No.</button>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Application Type</label>
                                    <input type="text" class="form-control" id="purpose" name="purpose" placeholder="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">First Name</label>
                                    <input type="text" class="form-control" id="findings" name="findings" placeholder="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Middle Name</label>
                                    <input type="text" class="form-control" id="issued_at" name="issued_at" placeholder="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Last Name</label>
                                    <input type="text" class="form-control" id="issued_on" name="issued_on" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Purpose</label>
                                    <input type="text" class="form-control" id="action" name="action" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Specification</label>
                                    <input type="text" class="form-control" id="action" name="action" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">OR Number</label>
                                    <input type="text" class="form-control" id="action" name="action" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">UCID</label>
                                    <input type="text" class="form-control" id="action" name="action" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Status</label>
                                    <input type="text" class="form-control" id="action" name="action" >
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Date of Expiration</label>
                                    <input type="date" class="form-control" id="action" name="action" >
                                </div>
                                <div class="form-group col-md-8">

                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Total</label>
                                    <input type="number" class="form-control" id="action" name="action" >
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

         {{-- MODAL --}}
         <div class="modal fade" id="defaultModalReceipt" tabindex="-1" role="dialog" aria-hidden="true">
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
                            @foreach ($payments as $key => $payment)
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
                                    <p style="margin-bottom: 0px; text-align: center; color: black;" class="">Php <span class="purpose_amount"> 100</span></p>
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
                                    <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Php <span class="total_amount">{{ number_format($payment->amount, 2)}}</span></p>
                                </div>
                            </div>
                            <div style="padding: 1em;"></div>
                            <div class="row">
                                <div class="form-group col-md-12" style="text-align: center">
                                    <p style="margin-bottom: 0px; text-align: center; text-transform: uppercase; font-weight: bold; color: black;" class="">This serves as your official receipt!</p>
                                </div>
                            </div>
                            @endforeach
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
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
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
            $('#datatables').DataTable({
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
