@extends('backend.master.template')
@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    For Printing/Completed Application
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
        <div class="modal fade" id="applicationModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <form id="modal-form" action="{{url('new_application/update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="application_type_display"> Application Type
                                            <span class="req-fields"></span>
                                        </label>
                                        <input type="text" id="application_type_display" name="application_type" class="form-control" value="NEW" readonly>

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group ">
                                        <label for="purpose"> Purpose
                                            <span class="req-fields">*</span>
                                        </label>
                                        <select class="form-control" id="purpose_id"  name="purpose_id">
                                            <option selected disabled>Select a Purpose</option>
                                            @foreach ($purposes as $purpose)
                                                <option value="{{ $purpose->id }}">{{ $purpose->purpose }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="first_name"> First Name
                                            <span class="req-fields">*</span>
                                        </label>
                                        <input type="text" id="firstname" name="firstname" class="form-control" required maxlength="200">

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="middle_name"> Middle Name </label>
                                        <input type="text" id="middlename" name="middlename" class="form-control" maxlength="200">

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="last_name"> Last Name
                                            <span class="req-fields">*</span>
                                        </label>
                                        <input type="text" id="lastname" name="lastname" class="form-control" required maxlength="200">

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="middle_name"> Suffix </label>
                                        <select class="form-control" name="suffix">
                                            <option selected disabled>Select a Suffix</option>
                                            <option value="Sr">Sr</option>
                                            <option value="Jr">Jr</option>
                                            <option value="I">I</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                            <option value="V">V</option>
                                            <option value="VI">VI</option>
                                            <option value="VII">VII</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="house_lot_block"> Address
                                            <span class="req-fields">*</span>
                                        </label>
                                        <input type="text" id="house_no" name="house_no" class="form-control"  placeholder="(HOUSE # / LOT # / BLOCK #)" required maxlength="200">

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="street_area">
                                            <span class="req-fields">&nbsp;</span>
                                        </label>
                                        <input type="text" id="street" name="street" class="form-control"  placeholder="(STREET / AREA)" required maxlength="200">

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="barangay">
                                            <span class="req-fields">&nbsp;</span>
                                        </label>
                                        <input type="text" id="barangay" name="barangay" class="form-control"  placeholder="(BARANGAY)" required maxlength="200">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="city_municipality">
                                            <span class="req-fields">&nbsp;</span>
                                        </label>
                                        <select class="form-control" id="municipality_id"  name="municipality_id">
                                            <option selected disabled>Select a Municipality</option>
                                            @foreach ($municipalities as $municipality)
                                                <option value="{{ $municipality->id }}">{{ $municipality->municipality }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="province">
                                            <span class="req-fields">&nbsp;</span>
                                        </label>
                                        <input type="text" id="province" name="province" class="form-control" value="METRO MANILA" placeholder="(PROVINCE)" required maxlength="200">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group ">
                                        <label for="country">
                                            <span class="req-fields">&nbsp;</span>
                                        </label>
                                        <input type="text" id="country" name="country" class="form-control" value="PHILIPPINES"  placeholder="(COUNTRY)" required maxlength="200">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label for="date_of_birth"> Date of Birth
                                            <span class="req-fields">*</span>
                                        </label>
                                        <div class="input-group date" id="birthday-date-picker">
                                            <input type="date" id="birthdate" name="birthdate" class="form-control" placeholder="YYYY-MM-DD" required>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label for="place_of_birth"> Place of Birth
                                            <span class="req-fields">*</span>
                                        </label>
                                        <input type="text" id="birth_place" name="birth_place" class="form-control"  required maxlength="200">

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label for="gender"> Gender
                                            <span class="req-fields">*</span>
                                        </label>
                                        <select id="gender" name="gender" class="form-control"  required>
                                            <option selected disabled>Select a Gender</option>
                                            <option value="MALE"> MALE</option>
                                            <option value="FEMALE"> FEMALE</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label for="nationality"> Nationality
                                            <span class="req-fields">*</span>
                                        </label>
                                        <select class="form-control" id="nationality_id"  name="nationality_id">
                                            <option selected disabled>Select a Nationality</option>
                                            @foreach ($nationalities as $nationality)
                                                <option value="{{ $nationality->id }}">{{ $nationality->nationality }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="civil_status"> Civil Status
                                            <span class="req-fields">*</span>
                                        </label>
                                        <select id="civil_status" name="civil_status" class="form-control"  required>
                                            <option selected disabled>Select a Civil Status</option>
                                            <option value="SINGLE"> SINGLE (WALANG ASAWA) </option>
                                            <option value="MARRIED"> MARRIED (MAY ASAWA) </option>
                                            <option value="SEPARATED"> SEPARATED (HIWALAY SA ASAWA) </option>
                                            <option value="WIDOWED"> WIDOWED (BIYUDA) </option>
                                            <option value="WIDOWER"> WIDOWER (BIYUDO) </option>
                                            <option value="ANNULLED"> ANNULLED (PINAWALANG-BISA) </option>
                                    </select>

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="religion"> Religion
                                            <span class="req-fields">*</span>
                                        </label>
                                        <select class="form-control" id="religion_id"  name="religion_id">
                                            <option selected disabled>Select a Religion</option>
                                            @foreach ($religions as $religion)
                                                <option value="{{ $religion->id }}">{{ $religion->religion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="contact_number"> Contact Number
                                            <span class="req-fields"> </span>
                                        </label>
                                        <input type="text" id="contact_number" name="contact_number" onkeypress="return isNumberKey(event)" class="form-control" placeholder="09xxxxxxxxx"  maxlength="11">

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="cedula_no"> Cedula No.
                                            <span class="req-fields"> </span>
                                        </label>
                                        <input type="number" id="cedula_no" name="cedula_no" class="form-control">

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="cedula_no"> OR No.
                                            <span class="req-fields"> </span>
                                        </label>
                                        <input type="number" id="or_no" name="or_no" class="form-control">

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group ">
                                        <label for="cedula_no"> Issued Date
                                            <span class="req-fields"> </span>
                                        </label>
                                        <input type="date" id="issued_date" name="issued_date" class="form-control">

                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit-button">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        {{-- PRINT CERTIFICATE MODAL --}}

        <div class="modal fade bd-example-modal-xl" id="certificatePrint" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pasay Police Clearance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="print-bg" style="background: gray;">
                            <div id="printCertificate" style="font-family: Arial;left: 10px;top: -10px;margin:auto;padding: 0px 10px;background: white;padding: 0 15px;">
                                <div class="cert-bg" style="background-color: #fff;background-image: url(/img/new-background.png); background-position:center center; background-size: cover; background-repeat: no-repeat; width: 100%; height: auto;">
                                <div style="padding: 0.3em;"></div>
                                <div class="row">
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/pasay-logo.png" style="width: 85px;" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 10pt; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Arial'; " class="">Republic of the Philippines</p>
                                        {{-- <p style="margin-bottom: 0px; text-align: center;  font-size: 10pt; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Arial';  margin-top: -5px;" class="">National Police Commission</p>
                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 10pt; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Arial';  margin-top: -5px;" class="">Philippine National Police</p> --}}
                                        <p style="margin-bottom: 0px; text-align: center; font-size: 17pt; color: black; text-transform: uppercase; font-weight: bold; text-transform: uppercase; font-family: 'Arial'; margin-top: -5px;" class="">CITY GOVERNMENT OF PASAY</p>
                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 9pt; color: black; text-transform: uppercase;  margin-top: -5px; font-weight: bold; font-family: 'Arial';" class="">F.B Harrison Street, Pasay City</p>
                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 9pt; color: black; text-transform: uppercase;  margin-top: -5px;" class="">TELEPHONE NUMBER: 02-82878343</p>
                                    </div>
                                    <div class="col-md-3" style="text-align: center;">
                                        <img src="/backend/img/logos/rp-logo.png" style="width: 85px;" alt="">
                                    </div>
                                </div>
                                <div style="padding: 0.3em;"></div>

                                <div class="row">
                                    <div class="col-md-12" style="padding:0px;">
                                        <p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: white; font-size: 17pt; background: #ed1c24; text-transform: uppercase;" class="">Pasay local government Derogatory Clearance</p>
                                    </div>
                                </div>
                                <div style="padding: 0.5em;"></div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <p style="margin-bottom: 0px; font-size: 9pt; color: black; font-weight: 700; text-transform: uppercase;" class="">To whom it may concern:</p>
                                        <p style="margin-bottom: 0px; font-size: 8pt !important; color: black;" class="">This is to certify that the person whose name, signature, picture and finger prints appear hearon has requested a RECORD CLEARANCE from this office and result(s) is/are below:</p>
                                    </div>
                                    <div style="text-align:right;" class="col-md-5">
                                        <p style="margin-bottom: 0px; font-size: 11pt; color: black; text-transform: uppercase; margin-top: -5px;" class="">
                                            <span style="display: block; font-weight: bold;">Control Number:</span>
                                        <span id="c_ucid" style="font-weight: bold;font-size: 15pt !important;color: #ed1c24;margin-top: -7px;display: block;"></span></p>
                                        <div style="margin-top: -10px;font-weight: bold;">
                                            <span style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: -5px;" class="">Date Issued: <span id="c_issued"></span></span>  |  
                                            <span style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: -5px;" class="">Valid Until: <span id="c_valid"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <table style="width:100%; margin-top:5px;">
                                        <tr>
                                            <td style="width:auto;vertical-align: top;">
                                                <table style="width:100%;">
                                                    <tr>
                                                        <td colspan="4" style="text-transform:uppercase;font-size: 12pt !important;font-weight: bold;padding: 0 8px;color: #000;">
                                                            <div>NAME: <span id="c_name"></span></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:11pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">ADDRESS:</span>
                                                            <p style="margin-bottom:0px;height:45px;" class="" id="c_address"></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">DATE OF BIRTH:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_dob"></p>
                                                        </td>
                                                        <td colspan="2" style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">PLACE OF BIRTH:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_bplace"></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">GENDER:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_gender"></p>
                                                        </td>
                                                        <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">CIVIL STATUS:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_civil_status"></p>
                                                        </td>
                                                        <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">CITIZENSHIP:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_nationality"></p>
                                                        </td>
                                                        <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">RELIGION:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_religion"></p>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <table style="width:100%;margin-top:10px;">
                                                    <tr>
                                                        <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">PURPOSE:</span>
                                                            <p style="margin-bottom:0px; font-weight:bold;" class="" id="c_purpose"></p>
                                                            <p style="margin-bottom: 0px; display: none; font-style:italic;" id="not_valid">(NOT VALID FOR ABROAD AND NATURALIZATION)</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                            <span style="display:block;color:#000;font-weight:bold;">REMARKS:</span>
                                                            <p style="margin-bottom:0px;" class="" id="c_derogatory">NO DEROGATORY RECORDS FOUND</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" style="width:25%;text-align:left;line-height: 11px;vertical-align: top;padding:15px 0px;padding-bottom: 0px;">
                                                            <div style="font-size: 8pt;color: black;text-transform: uppercase;margin-top: 0;white-space: normal;margin-bottom: 10px;font-style:italic;">*The information on this Pasay local government Derogatory Clearance has been subjected verification against Pasay OCC and PNP watch list. Any discrepancies and tampering may lead to legal actions in accordance to Philippine Laws.</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="qr-container" style="display:flex;">
                                                                <div>
                                                                    <img src="/backend/img/logos/mayor-logo.png" style="width: 90px;margin-right:10px;" alt="">
                                                                </div>
                                                                <div id="qrcode"></div>
                                                                <div id="footer-details" style="display: flex;padding: 0 10px;width: 100%;">
                                                                    <div style="padding:0 10px;">
                                                                        <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">ENCODER:</b> JOYCE TIMTIMAN</div>
                                                                        <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">PRINT BY:</b> JOYCE TIMTIMAN</div>
                                                                        <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">PRINT DATE:</b> <span id="c_or_date"></span></div>
                                                                        <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">OR NUMBER:</b> <span id="c_or_no"></span></div>
                                                                    </div>
                                                                    <div style="padding:0 10px;">
                                                                        <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;"><b style="font-weight:bold;">CEDULA NUMBER:</b> <span id="c_cedula_no"></span></p>
                                                                        <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;"><b style="font-weight:bold;">ISSUED AT:</b> <span id="c_issued_at"></span></p>
                                                                        <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;"><b style="font-weight:bold;">ISSUED ON:</b> <span id="c_issued_date"></span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td style="width:25%;text-align:left;line-height: 11px;vertical-align: top;">
                                                            <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;">ENCODER: JOYCE TIMTIMAN</div>
                                                            <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;">PRINT BY: JOYCE TIMTIMAN</div>
                                                            <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;">PRINT DATE: <span id="c_or_date"></span></div>
                                                            <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;">OR NUMBER: <span id="c_or_no"></span></div>
                                                        </td>
                                                        <td style="width:25%;text-align:left;line-height: 11px;vertical-align: top;">
                                                            <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;">CEDULA NUMBER: <span id="c_cedula_no"></span></p>
                                                            <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;">ISSUED AT: <span id="c_issued_at"></span></p>
                                                            <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;">ISSUED ON: <span id="c_issued_date"></span></p>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <img src="/img/hepe.png" style="height: 39px;" alt="">
                                                            <p style="margin-bottom: 0px; font-size: 8pt; font-weight: 800; color: black; text-transform: uppercase; border-top: 1px Solid;">PSMS MARJHUNE A MENDOZA </p>
                                                            <p style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: -5px;">::: CRIMINAL RECORD PNCO :::</p>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <img src="/img/mayames.png" style="height: 39px;" alt="">
                                                            <p style="margin-bottom: 0px; font-size: 8pt; font-weight: 800; color: black; text-transform: uppercase; border-top: 1px Solid;">PCOL MARIO L MAYAMES, JR</p>
                                                            <p style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: -5px;">::: OFFICER IN-CHARGE :::</p>
                                                        </td>
                                                    </tr> --}}
                                                </table>
                                            </td>
                                            <td style="width:2.3in; text-align:center;vertical-align: top;">
                                                <div id="c_picture_2" style="width:2in; height:2in;margin:auto;"></div>
                                                <div id="id_signature_cert_2" style="width:2in; height:2in;margin:auto;"></div>
                                                <p style="margin-bottom: 0px; font-size: 9pt; color: black; text-transform: uppercase; ">Applicant's Signature</p>
                                                <div class="finger-mark" style="display:flex;width:2in;margin:auto;">
                                                    <div class="left-mark" style="width:100%;height:90px;margin:5px;">
                                                        {{-- <div style="height:70px;border:1px solid #000;"></div>
                                                        <div style="border:1px solid #000;font-size:10px;text-align:center;height:20px;padding:2px;">LEFT THUMB</div> --}}
                                                    </div>
                                                    <div class="right-mark" style="width:100%;height:90px;margin:5px;">
                                                        {{-- <div style="height:70px;border:1px solid #000;"></div>
                                                        <div style="border:1px solid #000;font-size:10px;text-align:center;height:20px;padding:2px;">RIGHT THUMB</div> --}}
                                                    </div>
                                                </div>
                                                <div style="text-align:right;">
                                                    <p style="font-size: 9pt;color: red; text-transform: uppercase;margin:0px;font-weight:bold;">NOT VALID WITHOUT DRYSEAL</p>
                                                    <p style="font-size: 8pt;color: black; text-transform: uppercase;margin:0px;"><b style="font-weight:bold;">OR DATE/TOTAL AMOUNT:</b> <span id="c_issued_or_date"></span>/P 250.00 </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    {{-- <table style="width:100%; margin-top:10px;">
                                        <tr>
                                            <td colspan="2" style="line-height: 10px;vertical-align: top;margin-top:5px;padding: 3px;">
                                            </td>
                                            <td style="text-align:right;line-height: 11px;vertical-align: top;margin-top:5px;padding: 3px;" colspan="2">
                                                <p style="font-size: 9pt;color: red; text-transform: uppercase;margin:0px;font-weight:bold;">NOT VALID WITHOUT DRYSEAL</p>
                                                <p style="font-size: 8pt;color: black; text-transform: uppercase;margin:0px;">OR DATE/TOTAL AMOUNT: <span id="c_issued_or_date"></span>/P 250.00 </p>
                                            </td>
                                        </tr>
                                    </table> --}}
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
                        <div class="" style="text-align: right;">
                            <span>Name Size: </span>
                            <button type="button" id="font-plus">+</button>
                            <button type="button" id="font-minus">-</button>
                        </div>
                        <div class="print-bg" style="background: gray;overflow: auto;">
                            <div id="printID" class="id-layout">
                                <div style="height:100%;width:100%;text-transform:uppercase;background:url(/img/new-background.jpg);background-position: center;background-size: cover;font-family: 'Arial'">
                                    <table class="headers" style="width:100%;height:2.51cm;background: #ffff2a45;">
                                        <tr>
                                            <td style="width:1.3cm;"></td>
                                            <td style="width:1.9cm;"><img src="/backend/img/logos/pasay-logo.png" alt="" style="width:100%;"></td>
                                            <td style="width:9.97cm;text-align:center;">
                                                <div style="font-size: 11px;font-family: Arial;font-weight: bold;">REPUBLIC OF THE PHILIPPINES</div>
                                                {{-- <div style="font-size: 11px;font-family: Arial;font-weight: bold;">NATIONAL POLICE COMMISSION</div>
                                                <div style="font-size: 11px;font-family: Arial;font-weight: bold;">PHILIPPINE NATIONAL POLICE</div> --}}
                                                <div style="font-size: 18px;font-family: Arial;font-weight: bold;">CITY GOVERNMENT OF PASAY</div>
                                                <div style="font-size: 9px;font-family: Arial;font-weight: bold;">F.B. HARRISON STREET, PASAY CITY 02 88320-1125</div>
                                            </td>
                                            <td style="width:1.9cm;"><img src="/backend/img/logos/rp-logo.png" alt="" style="width:100%;"></td>
                                            <td style="width:1.3cm;"></td>
                                        </tr>
                                    </table>
                                    <div class="body" style="background-position: center;background-size: 100% 100%;">
                                        <div style="margin-bottom: 0px;font-size: 18px;color: white;text-transform: uppercase;font-weight: bold;font-family: Arial;background: #ff0000;text-align: right;padding: 1pt 5pt;" class="">CN <span id="id_ucid"></span></div>
                                        
                                        <div>
                                            <table style="width:100%;">
                                                <tr>
                                                    <td style="width:30%; vertical-align:top;">
                                                        <div style="" id="id_new_picture"><div style="" id="id_new_picture_2"></div></div>
                                                        <div style="" id="id_signature_2"></div>
                                                        <div style="font-weight: bold;font-family: arial;text-align: center;font-size: 10px;color: #000;margin-top: 2px;">HOLDER'S SIGNATURE</div>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <table style="width:100%;">
                                                            <tr>
                                                                <td colspan="2" style="padding:0px;text-transform: uppercase;">
                                                                    <span style="font-size: 10px;font-weight: bold;color: #000;display:block;">NAME:</span>
                                                                    <span style="font-size: 20px;display: block;font-weight: bold;color: #000;" id="id_name"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" style="padding:0px;text-transform: uppercase;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">ADDRESS:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;height: 30px;white-space: normal;" id="id_address"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px;text-transform: uppercase;padding-bottom: 5px;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">GENDER:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;font-weight: bold;" id="id_gender"></span>
                                                                </td>
                                                                <td style="padding:0px;text-transform: uppercase;padding-bottom: 5px;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">NATIONALITY:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;font-weight: bold;" id="id_nationality"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding:0px;text-transform: uppercase;padding-bottom: 5px;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">CIVIL STATUS:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;font-weight: bold;" id="id_status"></span>
                                                                </td>
                                                                <td style="padding:0px;text-transform: uppercase;padding-bottom: 5px;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">DATE OF BIRTH:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;font-weight: bold;" id="id_dob"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" style="padding:0px;text-transform: uppercase;padding-bottom: 5px;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">VALID UNTIL:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;font-weight: bold;" id="id_valid"></span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="footer" style="height: auto;padding: 0px;">
                                        <p style="margin: 0px;font-size: 40px;font-family: 'barcode';text-transform: uppercase;text-align: center;" class=""><span id="id_bar" style="text-align: center;"></span></p>
                                    </div>
                                <div style="background: #211f7c;color: #fff;text-align: center;font-size: 15px;font-weight: bold;margin-top:5px;text-transform:uppercase;">Pasay local government Derogatory Clearance IDENTIFICATION CARD</div>
                                </div>
                                <div style="height:100%;width:100%;">
                                    <img src="/img/back.jpg" height="100%" width="100%" alt="">
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

        
        <div class="modal fade bd-example-modal-xl" id="renewalModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Renewal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3">
                        <div class="renewal-info">
                            <div class="r-img">
                                <img src="" alt="" width="100px">
                            </div>
                            <div class="r-details">
                                <div class="r-name">-</div>
                                <div class="r-last-date">-</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Date Renew:</label>
                                <input type="date" id="date_renew" name="date_renew" class="form-control" onchange="selectRenewDate()"/>
                            </div>
                            <div class="form-group col-6">
                                <label>Date Expired:</label>
                                <input type="date" id="date_expiry" name="date_expiry" class="form-control"disable/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary submit-button" onclick="renewApplicant()">Renew</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('scripts')
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="/js/qrcode.js"></script>
    <script src="/js/qrcode.min.js"></script>
    <script>
        var renew_id = null;
        var qrcode = null;
        function edit(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/application/completed_edit/' + id,
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('.modal-title').text('Update Application');
                        $.each(data, function() {
                            $.each(this, function(k, v) {
                                $('#'+k).val(v);
                            });
                        });
                    $('#modal-form').attr('action', '/application/completed_update/' + data.application.id);
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

                    var rec = {
                        id: data.application.new_application.ucid,
                        name: data.application.new_application.firstname + (data.application.new_application.middlename !== null?' ' + data.application.new_application.middlename:'') + ' ' + data.application.new_application.lastname + (data.application.new_application.suffix !== null && data.application.new_application.suffix !== ''?' ' + ' ' +data.application.new_application.suffix:''),
                    };
                    
                    var q_code = JSON.stringify(rec);

                    $('#certificatePrint').modal('show');
                    $('#c_ucid').text(data.application.new_application.ucid);
                    $('#c_issued').text(moment((data.application.renew !== null?data.application.renew.date_renew:data.application.date)).format('MMM DD, YYYY'));
                    $('#c_valid').text(moment((data.application.renew !== null?data.application.renew.date_renew:data.application.date)).add(6, 'month').format('MMM DD, YYYY'));
                    $('#c_name').text(data.application.new_application.firstname + (data.application.new_application.middlename !== null?' ' + data.application.new_application.middlename:'') + ' ' + data.application.new_application.lastname + (data.application.new_application.suffix !== null && data.application.new_application.suffix !== ''?' ' + ' ' +data.application.new_application.suffix:''));
                    $('#c_address').text(data.application.new_application.house_no + ' ' + data.application.new_application.street + ' ' + data.application.new_application.barangay + ' ' + data.application.new_application.municipality.municipality);
                    $('#c_dob').text(moment(data.application.new_application.birthdate).format('DD MMM Y'));
                    $('#c_bplace').text(data.application.new_application.birth_place);
                    $('#c_gender').text(data.application.new_application.gender);
                    $('#c_civil_status').text(data.application.new_application.civil_status);
                    $('#c_nationality').text(data.application.new_application.nationality.nationality);
                    $('#c_religion').text(data.application.new_application.religion.religion);
                    $('#c_purpose').text(data.application.new_application.purpose.purpose);
                    $('#c_derogatory').text(data.application.derogatory ? data.application.derogatory : (data.application.finding ? data.application.finding : 'NO DEROGATORY RECORDS FOUND'));
                    $('#c_or_date').text(moment(data.application.date).format('DD MMM Y'));
                    $('#c_or_no').text(data.application.new_application.or_no);
                    $('#c_issued_or_date').text(moment(data.application.issued_or_date).format('DD MMM Y'));
                    $('#c_cedula_no').text(data.application.new_application.cedula_no);
                    $('#c_issued_at').text(data.application.new_application.issued_at);
                    $('#c_issued_date').text(moment(data.application.issued_date).format('DD MMM Y'));
                    $('#c_picture_2').attr('style', 'background:url(/img/application_picture/'+data.application.new_application_id+'/'+data.application.new_application.picture+'.png)no-repeat;width:2in; height:2in;background-position: center center !important;background-size: cover !important;border: 1px solid #000;margin:auto;');

                    console.log(q_code);
                    
                    if(qrcode !== null) {
                        qrcode.clear();
                        qrcode.makeCode(q_code);
                    }
                    else {
                        qrcode= new QRCode(document.getElementById("qrcode"), {
                            text: q_code.toUpperCase().replaceAll('Ñ', 'N'),
                            width: 90,
                            height: 90,
                            size: 1000,
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    }


                    if(data.application.new_application.purpose.purpose !== "WORK ABROAD" && data.application.new_application.purpose.purpose !== "NATURALIZATION" && data.application.new_application.purpose.purpose !== "TRAVEL ABROAD") { 
                        $('#not_valid').css('display', 'block');
                    }

                    if(data.application.new_application.signature === null) {
                        $('#id_signature_cert').attr('src', '/img/blank.png');
                        $('#id_signature_cert_2').attr('style', 'background:url(/img/blank.png)no-repeat;width:2in; height:2in;background-position: center center !important;background-size: 100% 100% !important;margin-top: 5px;border: 1px solid #000;margin:auto;');
                    }
                    else {
                        $('#id_signature_cert').attr('src', '/img/signature/'+data.application.new_application_id+'/'+data.application.new_application.signature );
                        $('#id_signature_cert_2').attr('style', 'background:url("/img/signature/'+data.application.new_application_id+'/'+data.application.new_application.signature.replaceAll(' ','%20')+'")no-repeat;width:2in;height: .5in;background-position: center top !important;background-size: 100% 100%;margin-top: 5px !important;margin:auto;border-bottom: 2px solid #000;');
                    }
                }
            });
        }

        function applicant(){
            $('#datatables').dataTable().fnDestroy();
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                pageLength:10,
                ajax: {
                    url: "/application/masterlist_applicant_record",
                    type: 'GET',
                },
                columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'new_application.lastname',
                            render: function(data, type, row, meta) {
                                return  '<a onclick="confirmDelete('+ row.id +')"><i class="align-middle fas fa-fw fa-trash"></i></a>'+
                                        '<a href="#" class="align-middle fas fa-fw fa-pen" title="Edit" data-toggle="modal" onclick="edit('+ row.id +')" data-target="#applicationModal"></a>'+
                                        '<a href="#" class="align-middle fas fa-fw fa-print" title="View" data-toggle="modal" onclick="certificate('+ row.id +')" data-target="#defaultModalPrint ' + row.new_application.application_no + '"></a>'+
                                        '<a href="#" class="align-middle fas fa-fw fa-address-card" title="View" data-toggle="modal" onclick="id_layout('+ row.id +')"></a>' +
                                        '<a href="#" class="align-middle fas fa-fw fa-retweet" title="Renew" data-toggle="modal" onclick="renew('+ row.new_application.id +')"></a>' 
                            }
                        },
                        {data: 'new_application.application_no', name: 'new_application.application_no' },
                        {data: 'new_application.firstname',
                            render: function(data, type, row, meta) {
                                return row.new_application.firstname + ' ' + (row.new_application.middlename !== null && row.new_application.middlename !== '' ? row.new_application.middlename : ' ') + ' ' + row.new_application.lastname + ' ' +  (row.new_application.suffix !== null && row.new_application.suffix !== '' ? row.new_application.suffix : ' ');
                            }
                        },
                        {data: 'type', name: 'type' },
                        {data: 'date',
                            render: function(data, type, row, meta) {
                                return moment(row.date).format("MMM DD, YYYY");
                            }
                        },
                        {data: 'status',
                            render: function(data, type, row, meta) {
                                if(row.status == 'ON-PROCESS') {
                                    return '<span class="badge badge-primary m-2">' + row.status + '</span>'
                                } else if(row.status == 'WITH FINDINGS'){
                                    return '<span class="badge badge-warning m-2">' + row.status + '</span>'
                                } else if(row.status == 'CANCELLED'){
                                    return '<span class="badge badge-danger m-2">' + row.status + '</span>'
                                } else{
                                    return '<span class="badge badge-success m-2">' + row.status + '</span>' + (row.renew !== null?"<span class='badge badge-secondary'>Renewed at " + moment(row.renew.date_renew).format('MMM DD, YYYY') + "</span>":'');
                                }
                            }
                        },
                    ],
                order: [[0, 'desc']]
            });
        }

        function confirmDelete(id) {
            if(confirm('Are you sure you want to Delete?') == true) {
                location.href = '/application/destroy/' + id;
            }
        }
        
        function id_layout(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/application/certificate/' + id,
                method: 'get',
                success: function(data) {
                    $('#defaultModalID').modal('show');
                    $('#id_name').text(data.application.new_application.firstname + (data.application.new_application.middlename !== null?' ' + data.application.new_application.middlename:'') + ' ' + data.application.new_application.lastname + (data.application.new_application.suffix !== null && data.application.new_application.suffix !== ''?' ' + ' ' +data.application.new_application.suffix:''));
                    $('#id_address').text(data.application.new_application.house_no + ' ' + data.application.new_application.street + ' ' + data.application.new_application.barangay + ' ' + data.application.new_application.municipality.municipality);
                    $('#id_gender').text(data.application.new_application.gender);
                    $('#id_ucid').text(data.application.new_application.ucid);
                    $('#id_bar').text("*" + data.application.new_application.ucid + "*");
                    $('#id_nationality').text(data.application.new_application.nationality.nationality);
                    $('#id_status').text(data.application.new_application.civil_status);
                    $('#id_dob').text(moment(data.application.new_application.birthdate).format('DD MMM Y'));
                    $('#id_valid').text(moment((data.application.renew !== null?data.application.renew.date_renew:data.application.date)).add(6, 'month').format('MMM DD, YYYY')); 
                    // moment((data.application.renew !== null?data.application.renew.date_renew:data.application.date)).format('MMM DD, YYYY')
                    // $('#id_thumb').attr('src', '/img/fingerprint_right/'+data.application.new_application_id+'/'+data.application.new_application.finger_print_right+'.png');
                    $('#id_picture').attr('src', '/img/application_picture/'+data.application.new_application_id+'/'+data.application.new_application.picture+'.png');
                    $('#id_picture_2').attr('src', '/img/application_picture/'+data.application.new_application_id+'/'+data.application.new_application.picture+'.png');

                    $('#id_new_picture').attr('style', 'background:url(/img/application_picture/'+data.application.new_application_id+'/'+data.application.new_application.picture+'.png)no-repeat;width: 120px;height: 120px;background-position: center center !important;background-size: cover !important;border: 1px solid #878787;margin:auto;');
                    $('#id_new_picture_2').attr('style', 'background:url(/img/application_picture/'+data.application.new_application_id+'/'+data.application.new_application.picture+'.png)no-repeat;width: 35px;height: 35px;background-position: center center !important;background-size: cover !important;border-radius: 50%;margin-top: 80px;margin-left: 80px;opacity: 0.8;');

                    
                    if(data.application.new_application.signature === null) {
                        $('#id_signature').attr('src', '/img/blank.png');
                        $('#id_signature_2').attr('style', 'background:url(/img/blank.png)no-repeat;height: 45px;width: 120px;background-position: center top !important;background-size: 100% 100%;margin-top: 1.5pt !important;margin:auto;');
                    }
                    else {
                        $('#id_signature').attr('src', '/img/signature/'+data.application.new_application_id+'/'+data.application.new_application.signature );
                        $('#id_signature_2').attr('style', 'background:url("/img/signature/'+data.application.new_application_id+'/'+data.application.new_application.signature.replaceAll(' ','%20')+'")no-repeat;height: 45px;width: 120px;background-position: center top !important;background-size: 100% 100%;margin-top: 1.5pt !important;margin:auto;');
                    }
                }
            });
        }

        $(function() {
            $('#datatables').DataTable({
                responsive: true,
                "pageLength": 100
            });

            applicant();

            $( "table" ).on( "click", ".edit", function() {
                edit(this.id);
            });

            $('.add').click(function(){
                $('.modal-title').text('Add Application');
                $('.submit-button').text('Add');
                $('#modal-form').attr('action', 'application/save');
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

        $(function () {
            $("#font-plus").click(function () {
                var fontSize = parseInt($("#id_name").css("font-size"));
                fontSize = fontSize + 1 + "px";
                $("#id_name").css({'font-size':fontSize});
            })
            
            $("#font-minus").click(function () {
                var fontSize = parseInt($("#id_name").css("font-size"));
                fontSize = fontSize - 1 + "px";
                $("#id_name").css({'font-size':fontSize});
            })
        });

        function printidDiv() {

            var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
            var divToPrint=document.getElementById('printID');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><style>@font-face {font-family: "barcode";src: url("/font/barcode.ttf");}</style><body onload="window.print()" style="margin:0px;">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
        // setTimeout(function(){newWin.close();},10);

        };

        function generateRecord() {
            
            $('#datatables').dataTable().fnDestroy();
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ordering: false,
                pageLength:10,
                ajax: {
                    url: "/application/masterlist_applicant_filter",
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
                        {data: 'new_application.lastname',
                            render: function(data, type, row, meta) {
                                return  '<a onclick="confirmDelete('+ row.id +')"><i class="align-middle fas fa-fw fa-trash"></i></a>'+
                                        '<a href="#" class="align-middle fas fa-fw fa-pen" title="Edit" data-toggle="modal" onclick="edit('+ row.id +')" data-target="#applicationModal"></a>'+
                                        '<a href="#" class="align-middle fas fa-fw fa-print" title="View" data-toggle="modal" onclick="certificate('+ row.id +')" data-target="#defaultModalPrint ' + row.new_application.application_no + '"></a>'+
                                        '<a href="#" class="align-middle fas fa-fw fa-address-card" title="View" data-toggle="modal" onclick="id_layout('+ row.id +')"></a>' +
                                        '<a href="#" class="align-middle fas fa-fw fa-retweet" title="Renew" data-toggle="modal" onclick="renew('+ row.new_application.id +')"></a>' 
                            }
                        },
                        {data: 'new_application.application_no', name: 'new_application.application_no' },
                        {data: 'new_application.firstname',
                            render: function(data, type, row, meta) {
                                return row.new_application.firstname + ' ' + (row.new_application.middlename !== null && row.new_application.middlename !== '' ? row.new_application.middlename : ' ') + ' ' + row.new_application.lastname + ' ' +  (row.new_application.suffix !== null && row.new_application.suffix !== '' ? row.new_application.suffix : ' ');
                            }
                        },
                        {data: 'type', name: 'type' },
                        {data: 'date',
                            render: function(data, type, row, meta) {
                                return moment(row.date).format("MMM DD, YYYY");
                            }
                        },
                        {data: 'status',
                            render: function(data, type, row, meta) {
                                if(row.status == 'ON-PROCESS') {
                                    return '<span class="badge badge-primary m-2">' + row.status + '</span>'
                                } else if(row.status == 'WITH FINDINGS'){
                                    return '<span class="badge badge-warning m-2">' + row.status + '</span>'
                                } else if(row.status == 'CANCELLED'){
                                    return '<span class="badge badge-danger m-2">' + row.status + '</span>'
                                } else{
                                    return '<span class="badge badge-success m-2">' + row.status + '</span>' + (row.renew !== null?"<span class='badge badge-secondary'>Renewed at " + moment(row.renew.date_renew).format('MMM DD, YYYY') + "</span>":'');
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

        function renew(id) {

            $.get('/application/get_application/' + id, function(response) {
                var data = response;
                renew_id = id;
                
                $('.r-img img').attr('src', '/img/application_picture/'+data.application.id+'/'+data.application.picture+'.png'); 
                $('.r-name').text(data.application.firstname + (data.application.middlename !== null?' ' + data.application.middlename:'') + ' ' + data.application.lastname + (data.application.suffix !== null && data.application.suffix !== ''?' ' + ' ' +data.application.suffix:'')); 
                $('.r-last-date').text(moment((data.application.renew !== null?data.application.renew.date_renew:data.application.issued_date)).format('MMM DD, YYYY'));

                $('#renewalModal').modal('show');
            });

        }

        function selectRenewDate() {
            var newDate = new Date($('#date_renew').val());
            var expireDate = newDate.setMonth(newDate.getMonth() + 6);
            
            $('#date_expiry').val(moment(expireDate).format('YYYY-MM-DD'));
        }

        function renewApplicant() {
            var data = {
                _token: "{{csrf_token()}}",
                application_id: renew_id,
                date_renew: $('#date_renew').val(),
                date_expiry: $('#date_expiry').val(),
            };

            $.post('/application/renew', data).done(function(response) {
                
                $('#date_renew').val('');
                $('#date_expiry').val('');

                $('#renewalModal').modal('hide');
            });
        }
        
    </script>
    <style>
        table thead th, table td{
            white-space: nowrap
        }
        /* div#printID {
            align-items: center !important;
            display: flex;
            justify-content: center;
            height: 523px;
        }
        div#printID>div {
            transform: scale(2);
        } */
        @font-face {
            font-family: 'barcode';
            src: url("/font/barcode.ttf");
        }
        div#search_box {
            position: fixed;
            bottom: 0;
            z-index: 9;
            background: black;
            width: 100%;
            left: 0;
            padding: 12px;
            color: #fff;
        }
        .renewal-info {
            display: flex;
            padding: 10px;
            background: #eee;
            margin-bottom: 20px;
        }
        .r-details {
            padding: 5px 20px;
            width: 100%;
        }
        .r-name {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@endsection
