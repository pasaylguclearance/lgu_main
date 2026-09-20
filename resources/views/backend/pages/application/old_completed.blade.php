@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    For Printing/Completed Application
                </h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">For Printing/Completed Application Screen

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
                                                <th>Type</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applications as $key => $application)
                                                <tr>
                                                    <td>{{ ++$key}}</td>
                                                    <td class="table-action">
                                                        <a href="{{url('application/destroy/' . $application->id)}}" onclick="alert('Are you sure you want to Delete?')"><i class="align-middle fas fa-fw fa-trash"></i></a>
                                                        <a href="#" class="align-middle fas fa-fw fa-print" title="View" data-toggle="modal" onclick="certificate({{ $application->id }})" data-target="#defaultModalPrint{{ $application->new_application->application_no }}"></a>
                                                        <a href="#" class="align-middle fas fa-fw fa-address-card" title="View" data-toggle="modal" onclick="certificate({{ $application->id }})" data-target="#defaultModalID"></a>
                                                    </td>
                                                    <td>{{ $application->new_application->application_no}}</td>
                                                    <td>{{ $application->new_application->firstname . ' ' . $application->new_application->middlename . ' ' . $application->new_application->lastname . ', ' . $application->new_application->suffix}}</td>
                                                    <td>{{ $application->type}}</td>
                                                    <td>{{ date('M-d-Y', strtotime($application->date))}}</td>
                                                    @if ($application->status == 'ON-PROCESS')
                                                        <td class="badge badge-primary m-2">{{ $application->status}}</td>
                                                    @elseif($application->status == 'WITH FINDINGS')
                                                        <td class="badge badge-warning m-2">{{ $application->status}}</td>
                                                    @elseif($application->status == 'CANCELLED')
                                                        <td class="badge badge-danger m-2">{{ $application->status}}</td>
                                                    @endif
                                                        <td class="badge badge-success m-2">{{ $application->status}}</td>
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
                        <h5 class="modal-title">Add Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('application/save')}}" method="post">
                            @csrf
                        <div class="form-group col-md-12">
                            <label for="inputPassword4">Application Name</label>
                            <input type="text" class="form-control" id="application" name="application" placeholder="Enter Application">
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

        
        {{-- PRINT CERTIFICATE MODAL --}}
        @foreach ($applications as $key => $application)

        <div class="modal fade bd-example-modal-xl" id="defaultModalPrint{{ $application->new_application->application_no }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <div id="printCertificate{{$application->id}}" style="width: 8.3in; height: 5.8in; font-family: Arial; left: 10px; top: -10px; margin:auto; padding: 0px 2em; background: white;">
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
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; margin-top: -5px;" class="">Control Number: <span>{{ $application->new_application->application_no }}</span></p>
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; margin-top: -5px;" class="">Date Issued: <span>{{date('m-d-Y')}}</span></p>
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; margin-top: -5px;" class="">Valid Until: <span>{{date('m-d-Y', strtotime('6 months'))}}</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">NAME : <span>{{ $application->new_application->lastname . ' ' . $application->new_application->suffix . ','}} {{ $application->new_application->firstname }} {{ $application->new_application->middlename }}</span></p>
                                                <p style="margin-bottom: 0px; font-size: 14px; color: black; text-transform: uppercase; font-weight: bold; text-transform: uppercase; font-weight: 800; margin-top: -5px;" class="">UCID: </p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class=""><span>{{ $application->new_application->ucid }}</span></p>

                                            </div>
                                            <div class="col-md-6">
                                            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Address :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->house_no . ' ' . $application->new_application->street . ' '  .$application->new_application->barangay . ' ' . $application->new_application->municipality->municipality . ' ' . (strcasecmp(trim((string) $application->new_application->province), 'METRO MANILA') === 0 ? '' : $application->new_application->province)}}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Date of Birth :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->birthdate }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Place of Birth :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->birth_place }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                {{-- <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Age :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">47</p> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Gender :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->gender }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Civil Status :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->civil_status }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Citizenship :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->nationality->nationality }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Religion :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->religion->religion }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p style="margin-bottom: 0px; font-size: 13px; color: black; text-transform: uppercase; font-weight: 800;" class="">Purpose :</p>
                                                <p style="margin-bottom: 0px; font-size: 13px; color: black; text-transform: uppercase; font-weight: 800; margin-top: -5px;" class="">{{ $application->new_application->purpose->purpose }}</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px; text-style:italic;" class=""></p>
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; font-weight: 800;" class="">Address :</p>
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; text-transform: uppercase; margin-top: -5px;" class="">{{ $application->new_application->house_no . ' ' . $application->new_application->street . ' '  .$application->new_application->barangay . ' ' . $application->new_application->municipality->municipality . ' ' . (strcasecmp(trim((string) $application->new_application->province), 'METRO MANILA') === 0 ? '' : $application->new_application->province)}}</p>
                                            </div>
                                            <div class="col-md-6">
                                            
                                            </div>
                                        </div> --}}
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12" style="text-align: center;">
                                                <img src="/img/application_picture/{{ $application->id }}/{{ $application->new_application->picture }}.png"  style="width: 120px; border: 1px solid black;" alt="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="text-align: center;">
                                                <img src="/img/signature/{{ $application->id }}/{{ $application->new_application->signature }}" style="width: 100px;" alt="">
                                                <p style="margin-bottom: 0px; font-size: 11px; color: black; text-transform: uppercase; border-top: 1px Solid;">Applicant's Signature</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row"> 
                                        <div class="col-md-6" style="text-align: center;">
                                            <img src="/img/bolivar.png" style="width: 100px;" alt="">
                                            <p style="margin-bottom: 0px; font-size: 12px; font-weight: 800; color: black; text-transform: uppercase; border-top: 1px Solid;">PEMS EDGAR C. BOLIVAR</p>
                                            <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; margin-top: -5px;">::: CHIEF, CLEARANCE :::</p>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <img src="/img/tabernilla.png" style="width: 100px;" alt="">
                                            <p style="margin-bottom: 0px; font-size: 12px; font-weight: 800; color: black; text-transform: uppercase; border-top: 1px Solid;">PCOL BYRON TAGLE TABERNILLA</p>
                                            <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; margin-top: -5px;">::: OFFICER IN CHARGE :::</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" style="text-align:  left;">
                                            <p ></p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">ENCODER: </p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">PRINT BY: </p>
                                            <p style="margin-bottom: 0px; font-size: 8px; color: black; text-transform: uppercase; margin-top: -5px;">PRINT DATE: </p>
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
                        <button type="submit" onclick='printDiv({{ $application->id }});' class="btn btn-primary submit-button">Print</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- END PRINT CERTIFICATE MODAL --}}

       

        {{-- PRINT CERTIFICATE MODAL --}}
        @foreach ($applications as $key => $application)

        <div class="modal fade bd-example-modal-xl" id="defaultModalPrint{{ $application->new_application->application_no }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <div id="printCertificate2q{{$application->id}}" style="width: 8.5in; height: 14in; font-family: Arial; left: 10px; top: -10px; margin:auto; padding: 0px 2em; background: white;">
                                <div class="cert-bg" style="background-image: url(/backend/img/logos/pnp-opacity.png); background-position: center; background-size: contain; background-repeat: no-repeat;">
                                <div style="padding: 1em;"></div>
                                <div class="row">
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pasay.png" style="width: 150px;" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black;" class="">Republic of the Philippines</p>
                                        <p style="margin-bottom: 0px; text-align: center; font-size: 25px; text-transform: uppercase; font-weight: bold; color: #eb0710;" class="">Pasay City Police Station</p>
                                        <p style="margin-bottom: 0px; text-align: center; color: black; font-style: italic;" class="">F.B Harison Street, Police Station Pasay City</p>
                                        <p style="margin-bottom: 0px; text-align: center; color: black; font-style: italic;" class="">02 8832-1125</p>
                                    </div>
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pnp.png" style="width: 150px;" alt="">
                                    </div>
                                </div>

                                <div style="padding: 0.5em;"></div>
                                <div style="border: 1px Solid gray;"></div>
                                <div style="padding: 0.5em;"></div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: black; font-size: 30px;" class="">Police Clearance</p>
                                    </div>
                                </div>

                                <div style="padding: 0.5em;"></div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black;" class="">This is to certify that the person whose photo, signature and right thumbark appearing in the card has requested for RECORD CLEARANCE CHECK from the Police District. Verification has been made and the result(s) and finding(s) are listed:</p>
                                    </div>
                                </div>

                                <div style="padding: 1em;"></div>

                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Application No. - UCID:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class=""> {{ $application->new_application->application_no }} - {{ $application->new_application->ucid }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Valid Until:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black;" class="">APRIL 10, 2023</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Last Name:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->lastname . ' ' . $application->new_application->suffix }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">First Name:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->firstname }}</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Middle Name:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->middlename }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Gender:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; transform: uppercase;" class="">{{ $application->new_application->gender }}</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-12">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Address:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->house_no . ' ' . $application->new_application->street . ' '  .$application->new_application->barangay . ' ' . $application->new_application->municipality->municipality }}</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Date of Birth:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->birthdate }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Place of Birth:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->birth_place }}</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Nationality:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->nationality->nationality }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Civil Status:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->civil_status }}</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Purpose:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">{{ $application->new_application->purpose->purpose }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Remarks:</p>
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase;" class="">NO DEROGATORY REMARKS</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-12">
                                        <img src="/img/signature/{{ $application->id }}/{{ $application->new_application->signature }}" style="width: 150px;" alt="">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">Signature:</p>
                                    </div>
                                </div>

                                <div style="padding: 1em;"></div>

                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-4" style="text-align: center;">
                                        <img src="/img/application_picture/{{ $application->id }}/{{ $application->new_application->picture }}.png" style="width: 200px; border: 1px solid gray;" alt="">
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; font-weight: bold; font-style: italic;" class="">Applicant's Image</p>
                                    </div>
                                    <div class="col-md-2">

                                    </div>
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/img/fingerprint_left/{{ $application->id }}/{{ $application->new_application->finger_print_left }}.png" style="width: 200px; border: 1px solid gray;" alt="">
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; font-weight: bold;  font-style: italic;" class="">Left Thumbmark</p>
                                    </div>
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/img/fingerprint_right/{{ $application->id }}/{{ $application->new_application->finger_print_right }}.png" style="width: 200px; border: 1px solid gray;" alt="">
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; text-transform: uppercase; font-weight: bold;  font-style: italic;" class="">Right Thumbmark</p>
                                    </div>
                                </div>
                                <div style="padding: 1em;"></div>

                                <div class="row" style="margin-bottom: 10px; text-align: center;">
                                    <div class="col-md-12">
                                        <img src="/img/bolivar.png" style="width: 150px;" alt="">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">PEMS EDGAR C. BOLIVAR</p>
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; font-weight: bold;" class="">::: CHIEF, CLEARANCE :::</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 10px; text-align: center; padding-top:0.5em;">
                                    <div class="col-md-12">
                                        <img src="/img/tabernilla.png" style="width: 150px;" alt="">
                                        <p style="margin-bottom: 0px; font-size: 15px; color: black; text-transform: uppercase; font-weight: bold;" class="">PCOL BYRON TAGLE TABERNILLA</p>
                                        <p style="margin-bottom: 0px; font-size: 12px; color: black; font-weight: bold;" class="">::: CHIEF OF POLICE :::</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" onclick='printDiv({{ $application->id }});' class="btn btn-primary submit-button">Print</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

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
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Date of Birth: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->birthdate }}</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Civil Status: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->civil_status }}</span></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Place of Birth: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->birth_place }}</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Gender: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->gender }}</span></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Nationality: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->nationality->nationality }}</span></p>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Findings: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->finding }}</span></p>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Purpose: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->purpose->purpose }}</span></p>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Date Issued: <span style="font-weight: bold; text-transform: uppercase;">{{ $application->new_application->purpose->purpose }}</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase;" class="">Valid Until: <span style="font-weight: bold; text-transform: uppercase;">07/18/2023</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="text-align: center;">
                                        <img src="/img/application_picture/{{ $application->id }}/{{ $application->new_application->picture }}.png" alt="" style="width: 110px; margin-bottom: 5px;">
                                        <p style="margin-bottom: 0px; font-size: 10px; color: black; text-transform: uppercase; text-align: center" class=""><span style="font-weight: bold; text-transform: uppercase;">Juan Miguel Dela Cruz </span></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="/img/pnp-qr.png" alt="" style="width: 80px; margin-bottom: 5px; border: 1px solid;">
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
                url: '/application/edit/' + id,
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
                    $('#modal-form').attr('action', 'application/update/' + data.applications.id);
                }
            });
        }

        function certificate(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/application/certificate/' + id,
                method: 'get',
                success: function(data) {
                    console.log(data);
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
                $('.modal-title').text('Add Application');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'application/save');
            })
        });

        function printDiv(id) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/application/certificate/' + id,
                method: 'get',
                success: function(data) {
                    console.log(data);
                }
            });

        var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
        var divToPrint=document.getElementById('printCertificate' + id);
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
