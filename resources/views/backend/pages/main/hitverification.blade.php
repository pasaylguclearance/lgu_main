@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    Hit Verification
                </h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Hit Verification Screen
                                <button type="button" class="btn btn-primary add" data-toggle="modal" data-target="#defaultModalPrimary" style="float:right">
                                 Add Findings
                                </button>
                                <a href="#" class="align-middle fas fa-fw fa-eye" title="View" data-toggle="modal" data-target="#defaultModalPrint"></a>
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
                                                <th>Full Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($newapplications as $key => $newapplication)
                                                <tr>
                                                    <td>{{ ++$key}}</td>
                                                    <td>{{ $newapplication->id}}</td>
                                                    <td>{{ $newapplication->application_no}}</td>
                                                    <td>{{ $newapplication->fullname}}</td>
                                                    <td>{{ $newapplication->status}}</td>
                                                    <td class="table-action">
                                                        <a href="#" class="align-middle fas fa-fw fa-eye" title="View" data-toggle="modal" data-target="#defaultModalPrint"></a>
                                                        <a href="#" class="align-middle fas fa-fw fa-pen edit" title="Edit" data-toggle="modal" data-target="#defaultModalPrimary" id={{$newapplication->id}}></a>
                                                        <a href="{{url('rate/destroy/' . $newapplication->id)}}" onclick="alert('Are you sure you want to Delete?')"><i class="align-middle fas fa-fw fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach --}}
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
                        <h5 class="modal-title">Add Findings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('newapplication/save')}}" method="post">
                            @csrf
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Application No.</label>
                            <div class="input-group">
                            <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Search Full Name">
                            <button class="btn btn-primary" type="button">Search</button>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Full Name</label>
                            <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Enter Full Name">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Purpose</label>
                            <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Enter Purpose">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Findings</label>
                            <input type="text" class="form-control" id="findings" name="findings" placeholder="Enter Findings">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Issued At</label>
                            <input type="text" class="form-control" id="issued_at" name="issued_at" placeholder="Enter Issued At">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Issued On</label>
                            <input type="date" class="form-control" id="issued_on" name="issued_on" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Action</label>
                            <input type="date" class="form-control" id="action" name="action" >
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

        {{-- View --}}
        <div class="modal fade bd-example-modal-xl" id="defaultViewHit" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Hit Verification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('newapplication/save')}}" method="post">
                            @csrf
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Application No.</label>
                            <div class="input-group">
                            <input type="text" class="form-control" id="purpose" name="purpose" value="PCAN-0001" disabled placeholder="Search Full Name">
                            {{-- <button class="btn btn-primary" type="button">Search</button> --}}
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Full Name</label>
                            <input type="text" class="form-control" id="purpose" name="purpose" value="Juan Dela Cruz" disabled placeholder="Enter Full Name">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Purpose</label>
                            <input type="text" class="form-control" id="purpose" name="purpose" value="Multi-Purpose Clearance" disabled placeholder="Enter Purpose">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Findings</label>
                            <input type="text" class="form-control" id="findings" name="findings" value="No Derogatory Record/Information" disabled placeholder="Enter Findings">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Issued At</label>
                            <input type="text" class="form-control" id="issued_at" name="issued_at" value="Pasay City" disabled placeholder="Enter Issued At">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Issued On</label>
                            <input type="date" class="form-control" id="issued_on" value="01-01-2022" disabled name="issued_on" >
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Action</label>
                            <input type="date" class="form-control" id="action" value="01-01-2022" disabled name="action" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit-button">Print</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRINT CERTIFICATE MODAL --}}
        <div class="modal fade bd-example-modal-xl" id="defaultModalPrint" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Print Police Clearance Certificate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="print-bg" style="background: gray;">
                            <div id="printCertificate" style="width: 8.3in; height: 5.8in; font-family: Arial; left: 10px; top: -10px; margin:auto; padding: 0px 2em; background: white;">
                                <div class="cert-bg" style="background-image: url(/backend/img/logos/pnp-opacity.png); background-position:top center; background-size: contain; background-repeat: no-repeat; width: 100%; height: 100%;">
                                <div style="padding: 0.3em;"></div>
                                <div class="row">
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pnp.png" style="width: 80px;" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Times New Roman'; font-size: 11px;" class="">Republic of the Philippines</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Times New Roman'; font-size: 11px; margin-top: -5px;" class="">National Police Commission</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Times New Roman'; font-size: 11px; margin-top: -5px;" class="">Philippine National Police</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-size: 20px; color: black; text-transform: uppercase; font-weight: bold; text-transform: uppercase; font-family: 'Times New Roman'; margin-top: -5px;" class="">Pasay City Police Station</p>
                                        <p style="margin-bottom: 0px; text-align: center; color: black; text-transform: uppercase; font-size: 11px; margin-top: -5px; font-weight: bold; font-family: 'Times New Roman';" class="">F.B Harison Street, Pasay City</p>
                                        <p style="margin-bottom: 0px; text-align: center; color: black; text-transform: uppercase; font-size: 11px; margin-top: -5px;" class="">TELEPHONE NUMBER: 02 8832-1125</p>
                                    </div>
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pasay.png" style="width: 80px;" alt="">
                                    </div>
                                </div>
                                <div style="padding: 0.3em;"></div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: white; font-size: 24px; background: #ff99ab; text-transform: uppercase;" class="">Police Clearance Certificate</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; font-weight: 700; text-transform: uppercase;" class="">To whom it may concern:</p>
                                    </div>
                                    <div class="4"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <p style="margin-bottom: 0px; font-size: 11px !important; color: black;" class="">This is to certify that the person whose name, signature, picture and finger prints appear hearon has requested a RECORD CLEARANCE from this office and result(s) is/are below:</p>
                                    </div>
                                    <div style="text-align:right;" class="col-md-4">
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; margin-top: -5px;" class="">Control Number: <span>TEST</span></p>
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; margin-top: -5px;" class="">Date Issued: <span>TEST</span></p>
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; margin-top: -5px;" class="">Valid Until: <span>TEST</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">NAME :</p>
                                                <p style="margin-bottom: 0px; font-size: 14px; color: black; text-transform: uppercase; font-weight: bold; text-transform: uppercase; font-weight: 800; margin-top: -5px;" class="">Application No.:</p>
                                            </div>
                                            <div class="col-md-6">
                                            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Address :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">1814 TRAMO ST. RIVERSIDE PASAY CITY</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Date of Birth :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">25 April 1975</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Age :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">47</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Place of Birth :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">Javier Leyte</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Gender :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">Male</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Civil Status :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">Married</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Citizenship :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">Filipino</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Religion :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">Roman Catholic</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p style="margin-bottom: 0px; font-size: 13px; color: black; text-transform: uppercase; font-weight: 800;" class="">Purpose :</p>
                                                <p style="margin-bottom: 0px; font-size: 13px; color: black; text-transform: uppercase; font-weight: 800; margin-top: -5px;" class="">Local Employment</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px; text-style:italic;" class="">(Not Valid for Abroad/Naturalization/Firearms)</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Address :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">1814 TRAMO ST. RIVERSIDE PASAY CITY</p>
                                            </div>
                                            <div class="col-md-6">
                                            
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12" style="text-align: center;">
                                                <img src="/img/sample-img.jpg" style="width: 120px; border: 1px solid black;" alt="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="text-align: center;">
                                                <img src="/img/sample-signature.png" style="width: 100px;" alt="">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; border-top: 1px Solid;">Applicant's Signature</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row"> 
                                        <div class="col-md-6" style="text-align: center;">
                                            <img src="/img/sample-signature.png" style="width: 100px;" alt="">
                                            <p style="margin-bottom: 0px; font-size: 12px; font-weight: 800; color: black; text-transform: uppercase; border-top: 1px Solid;">PEMS EDGAR C. BOLIVAR</p>
                                            <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; margin-top: -5px;">::: CHIEF, CLEARANCE :::</p>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <img src="/img/sample-signature.png" style="width: 100px;" alt="">
                                            <p style="margin-bottom: 0px; font-size: 12px; font-weight: 800; color: black; text-transform: uppercase; border-top: 1px Solid;">PCOL BYRON TAGLE TABERNILLA</p>
                                            <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; margin-top: -5px;">::: OFFICER IN CHARGE :::</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" style="text-align:  left;">
                                            <p ></p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">ENCODER: JOYCE TIMTIMAN</p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">PRINT BY: JOYCE</p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">PRINT DATE: SEP 21, 2022</p>
                                        </div>
                                        <div class="col-md-4" style="text-align:  left;">
                                            <p style="margin-bottom: 0px; font-size: 11px; color: red; text-transform: uppercase; margin-top: -5px;">NOT VALID WITHOUT DRYSEAL</p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">OR DATE/TOTAL AMOUNT: SEP 21, 2022/P 350.00 CC+CI</p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">CEDULA NUMBER: NA</p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">ISSUED AT: PASAY CITY</p>
                                        </div>
                                        <div class="col-md-4" style="text-align:  left;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" onclick='printDiv();' class="btn btn-primary submit-button">Print</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- END PRINT CERTIFICATE MODAL --}}

        
          {{-- PRINT ID MODAL --}}
          <div class="modal fade bd-example-modal-xl" id="defaultModalID" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Print Police Clearance ID</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="print-bg" style="background: gray;">
                            <div id="printID" style="width: 7in; height: 4in; font-family: Arial; left: 10px; top: -10px; margin:auto; padding: 0px 1em; background: white; border: 1px solid;">
                                <div style="padding: 0.5em"></div>
                                <div class="row">
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pnp.png" style="width: 100px; !important;" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Pasay City Office</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Pasay City Police Station</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Pasay City, Philippines</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black; font-size: 25px; !important;" class="">Police Clearance ID</p>
                                    </div>
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pasay.png" style="width: 100px; !important;" alt="">
                                    </div>
                                </div>
                                <div style="padding: 0.2em"></div>
                                <div class="row">
                                    <div class="col-md-12" style="text-align: center;">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Pasay City, Philippines</p>
                                    </div>
                                </div>
                                <div style="padding: 0.2em"></div>
                                
                                <div class="row">
                                    <div class="col-md-8" style="height: 100%; margin: auto;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Date of Birth: <span style="font-weight: bold; text-transform: uppercase;">01/01/2022</span></p>
                                            </div>  
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Civil Status: <span style="font-weight: bold; text-transform: uppercase;">Single</span></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Place of Birth: <span style="font-weight: bold; text-transform: uppercase;">Pasay City</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Gender: <span style="font-weight: bold; text-transform: uppercase;">Male</span></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Nationality: <span style="font-weight: bold; text-transform: uppercase;">Filipino</span></p>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Findings: <span style="font-weight: bold; text-transform: uppercase;">No Derogatory Record/Information</span></p>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Purpose: <span style="font-weight: bold; text-transform: uppercase;">Local Employment</span></p>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Date Issued: <span style="font-weight: bold; text-transform: uppercase;">07/18/2022</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Valid Until: <span style="font-weight: bold; text-transform: uppercase;">07/18/2023</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="text-align: center;">
                                        <img src="/img/sample-img.jpg" alt="" style="width: 110px; margin-bottom: 5px; border: 1px solid;">
                                        <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; text-align: center" class=""><span style="font-weight: bold; text-transform: uppercase;">Juan Miguel Dela Cruz </span></p>
                                    </div>
                                </div>  

                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="/img/sample-qr.png" alt="" style="width: 80px; margin-bottom: 5px; border: 1px solid;">
                                    </div>
                                    <div class="col-md-4" style="height: 100%; margin: auto;">
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; text-align: center;" class="">Carlo Perez Dalisay</p>
                                        <p style="margin-bottom: 0px; font-size: 10px; color: black; text-align: center;" class="">Police Superintendent</p>
                                        <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; text-align: center;" class="">Officer-In-Charge</p>
                                    
                                    </div>
                                    <div class="col-md-4" style="height: 100%; margin: auto;">
                                        <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; text-align: center;" class="">Signature</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" onclick='printidDiv();' class="btn btn-primary submit-button">Print</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
          {{-- END PRINT ID MODAL --}}


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
                url: '/rate/edit/' + id,
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
                    $('#modal-form').attr('action', 'rate/update/' + data.rates.id);
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
                $('.modal-title').text('Add Hit Verification');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'rate/save');

            })
        });

        function printDiv() {

            var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
            var divToPrint=document.getElementById('printCertificate');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write(myStyle + '<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            // setTimeout(function(){newWin.close();},10);

        };

        function printidDiv() {

        var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
        var divToPrint=document.getElementById('printID');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write(myStyle + '<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        // setTimeout(function(){newWin.close();},10);

        };
    </script>
@endsection
