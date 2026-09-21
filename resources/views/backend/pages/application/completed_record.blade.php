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
                                <div class="col-sm-3">
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
                                <div class="col-sm-3">
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
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <label for="contact_number"> Contact Number
                                            <span class="req-fields"> </span>
                                        </label>
                                        <input type="text" id="contact_number" name="contact_number" onkeypress="return isNumberKey(event)" class="form-control" placeholder="09xxxxxxxxx"  maxlength="11">

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
                            <div id="printCertificate" style="font-family: Arial; margin: auto; background: white;">
                                            <style>
                                                /* Certificate design. Lives inside #printCertificate on purpose: printDiv() copies
                                                   innerHTML into a bare print window, so these rules travel with the markup. */
                                                .pnp-cert, .pnp-cert * { box-sizing: border-box; }
                                                .pnp-cert { font-family: Arial, Helvetica, sans-serif; color: #111827; line-height: 1.25; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                                                .pnp-cert p { margin: 0; }
                                                .pnp-cert img { display: block; }
                                                .pc-sheet { background: #fff url(/img/new-background.png) center center / cover no-repeat; border-top: 5px solid #14213d; padding: 10px 16px 10px; }
                                                .pc-head { display: flex; align-items: center; justify-content: space-between; padding-bottom: 6px; border-bottom: 1px solid #cfd5e1; }
                                                .pc-head-logo { width: 68px; height: 68px; flex: 0 0 68px; object-fit: contain; }
                                                .pc-head-text { flex: 1; text-align: center; padding: 0 12px; }
                                                .pc-eyebrow { font-size: 8pt; letter-spacing: 2px; text-transform: uppercase; color: #4b5563; }
                                                .pc-org { font-size: 18pt; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; color: #14213d; margin: 1px 0; line-height: 1.1; }
                                                .pc-sub { font-size: 8pt; letter-spacing: .3px; text-transform: uppercase; color: #4b5563; }
                                                .pc-titlebar { display: flex; align-items: center; justify-content: space-between; margin: 8px 0 8px; }
                                                .pc-title { border-left: 6px solid #d62828; padding: 2px 0 2px 12px; }
                                                .pc-title-eyebrow { display: block; font-size: 8.5pt; font-weight: bold; letter-spacing: 2.5px; text-transform: uppercase; color: #d62828; }
                                                .pc-title-main { display: block; font-size: 17pt; font-weight: bold; letter-spacing: .5px; white-space: nowrap; text-transform: uppercase; color: #14213d; line-height: 1.05; }
                                                .pc-control { min-width: 236px; text-align: right; background: rgba(255,255,255,.9); border: 1px solid #cfd5e1; border-radius: 6px; padding: 6px 12px; }
                                                .pc-control-label { display: block; font-size: 7.5pt; letter-spacing: 1.5px; text-transform: uppercase; color: #6b7280; }
                                                .pc-control-no { display: block; font-size: 15pt; font-weight: bold; letter-spacing: .5px; color: #d62828; line-height: 1.2; }
                                                .pc-control-dates { display: block; font-size: 7pt; white-space: nowrap; text-transform: uppercase; color: #374151; margin-top: 2px; }
                                                .pc-control-dates b { color: #14213d; }
                                                .pc-body { display: flex; align-items: stretch; }
                                                .pc-main { flex: 1; min-width: 0; margin-right: 14px; background: rgba(255,255,255,.88); border: 1px solid #cfd5e1; border-radius: 6px; padding: 10px 12px; }
                                                .pc-certify { font-size: 8pt; color: #374151; margin-bottom: 8px; }
                                                .pc-certify b { color: #14213d; letter-spacing: .5px; text-transform: uppercase; }
                                                .pc-label { display: block; font-size: 7pt; letter-spacing: 1.2px; text-transform: uppercase; color: #6b7280; margin-bottom: 1px; }
                                                .pc-value { display: block; font-size: 9pt; text-transform: uppercase; color: #111827; min-height: 12px; }
                                                .pc-name { padding-bottom: 6px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; }
                                                .pc-name .pc-value { font-size: 15pt; font-weight: bold; letter-spacing: .3px; color: #14213d; }
                                                .pc-grid { display: flex; flex-wrap: wrap; margin: 0 -6px; }
                                                .pc-field { flex: 0 0 25%; max-width: 25%; padding: 0 6px; margin-bottom: 6px; }
                                                .pc-field.g1 { flex: 0 0 19%; max-width: 19%; }
                                                .pc-field.g2 { flex: 0 0 25%; max-width: 25%; }
                                                .pc-field.g3 { flex: 0 0 25%; max-width: 25%; }
                                                .pc-field.g4 { flex: 0 0 31%; max-width: 31%; }
                                                .pc-field.w2 { flex: 0 0 50%; max-width: 50%; }
                                                .pc-field.w4 { flex: 0 0 100%; max-width: 100%; }
                                                .pc-address .pc-value { min-height: 24px; }
                                                .pc-purpose .pc-value { font-weight: bold; }
                                                .pc-note { display: none; font-size: 8pt; font-style: italic; color: #374151; margin-top: 1px; }
                                                .pc-result { display: flex; align-items: center; background: #f1f4f9; border: 1px solid #cfd5e1; border-left: 4px solid #14213d; border-radius: 4px; padding: 6px 10px; margin-top: 1px; }
                                                .pc-result .pc-label { margin: 0 10px 0 0; flex: 0 0 auto; }
                                                .pc-result .pc-value { font-size: 10pt; font-weight: bold; color: #14213d; }
                                                .pc-disclaimer { font-size: 7pt; font-style: italic; text-transform: uppercase; color: #4b5563; margin-top: 8px; }
                                                .pc-side { flex: 0 0 2.35in; display: flex; flex-direction: column; align-items: center; background: rgba(255,255,255,.88); border: 1px solid #cfd5e1; border-radius: 6px; padding: 10px; }
                                                .pc-photo { width: 2in; height: 2in; border: 1px solid #9ca3af; background: #f3f4f6 center center / cover no-repeat; }
                                                .pc-photo.empty, .pc-sig.empty { display: flex; align-items: center; justify-content: center; font-size: 7.5pt; letter-spacing: 1px; text-transform: uppercase; color: #9ca3af; }
                                                .pc-sig { width: 2in; height: .5in; margin-top: 6px; border-bottom: 2px solid #14213d; background: center top / 100% 100% no-repeat; }
                                                .pc-caption { font-size: 7pt; letter-spacing: 1.5px; text-transform: uppercase; color: #6b7280; margin-top: 3px; }
                                                .pc-thumbs { display: flex; width: 2in; margin-top: 8px; }
                                                .pc-thumb { flex: 1; height: 64px; border: 1px dashed #b6bdca; border-radius: 4px; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 3px; font-size: 6.5pt; letter-spacing: 1px; text-transform: uppercase; color: #9ca3af; }
                                                .pc-thumb + .pc-thumb { margin-left: 6px; }
                                                .pc-foot { display: flex; align-items: center; margin-top: 8px; padding-top: 8px; border-top: 1px solid #cfd5e1; }
                                                .pc-foot-brand { display: flex; align-items: center; flex: 0 0 auto; margin-right: 14px; }
                                                .pc-foot-brand img { width: 66px; height: 66px; object-fit: contain; margin-right: 8px; }
                                                .pc-qr { width: 84px; height: 84px; padding: 2px; background: #fff; border: 1px solid #cfd5e1; }
                                                .pc-qr img { max-width: 100%; height: auto; }
                                                .pc-meta { flex: 1; display: flex; flex-wrap: wrap; align-content: center; background: rgba(255,255,255,.88); border: 1px solid #cfd5e1; border-radius: 6px; padding: 5px 8px; margin-right: 14px; }
                                                .pc-meta div { flex: 0 0 50%; max-width: 50%; padding: 1px 6px; font-size: 7pt; text-transform: uppercase; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                                                .pc-meta b { display: inline; font-size: 6.5pt; font-weight: bold; letter-spacing: .8px; color: #6b7280; margin-right: 3px; }
                                                .pc-seal { flex: 0 0 auto; text-align: center; }
                                                .pc-seal-badge { display: inline-block; border: 2px solid #d62828; border-radius: 4px; padding: 4px 9px; font-size: 7.5pt; white-space: nowrap; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; color: #d62828; }
                                                .pc-seal-amount { font-size: 7pt; text-transform: uppercase; color: #374151; margin-top: 5px; }
                                                .pc-seal-amount b { color: #14213d; }
                                            </style>
                                <div class="pnp-cert">
                                    <div class="pc-sheet">
                                        <div class="pc-head">
                                            <img class="pc-head-logo" src="/backend/img/logos/pasay-logo.png" alt="">
                                            <div class="pc-head-text">
                                                <div class="pc-eyebrow">Republic of the Philippines</div>
                                                <div class="pc-org">City Government of Pasay</div>
                                                <div class="pc-sub">F.B Harrison Street, Pasay City &nbsp;&middot;&nbsp; Telephone Number: 02-82878343</div>
                                            </div>
                                            <img class="pc-head-logo" src="/backend/img/logos/rp-logo.png" alt="">
                                        </div>

                                        <div class="pc-titlebar">
                                            <div class="pc-title">
                                                <span class="pc-title-eyebrow">Pasay Local Government</span>
                                                <span class="pc-title-main">Derogatory Clearance</span>
                                            </div>
                                            <div class="pc-control">
                                                <span class="pc-control-label">Control Number</span>
                                                <span class="pc-control-no" id="c_ucid"></span>
                                                <span class="pc-control-dates"><b>Date Issued:</b> <span id="c_issued"></span> &nbsp;&middot;&nbsp; <b>Valid Until:</b> <span id="c_valid"></span></span>
                                            </div>
                                        </div>

                                        <div class="pc-body">
                                            <div class="pc-main">
                                                <p class="pc-certify"><b>To whom it may concern:</b> This is to certify that the person whose name, signature, picture and finger prints appear hearon has requested a RECORD CLEARANCE from this office and result(s) is/are below:</p>
                                                <div class="pc-name">
                                                    <span class="pc-label">Name</span>
                                                    <span class="pc-value" id="c_name"></span>
                                                </div>
                                                <div class="pc-grid">
                                                    <div class="pc-field w4 pc-address">
                                                        <span class="pc-label">Address</span>
                                                        <span class="pc-value" id="c_address"></span>
                                                    </div>
                                                    <div class="pc-field w2">
                                                        <span class="pc-label">Date of Birth</span>
                                                        <span class="pc-value" id="c_dob"></span>
                                                    </div>
                                                    <div class="pc-field w2">
                                                        <span class="pc-label">Place of Birth</span>
                                                        <span class="pc-value" id="c_bplace"></span>
                                                    </div>
                                                    <div class="pc-field g1">
                                                        <span class="pc-label">Gender</span>
                                                        <span class="pc-value" id="c_gender"></span>
                                                    </div>
                                                    <div class="pc-field g2">
                                                        <span class="pc-label">Civil Status</span>
                                                        <span class="pc-value" id="c_civil_status"></span>
                                                    </div>
                                                    <div class="pc-field g3">
                                                        <span class="pc-label">Citizenship</span>
                                                        <span class="pc-value" id="c_nationality"></span>
                                                    </div>
                                                    <div class="pc-field g4">
                                                        <span class="pc-label">Religion</span>
                                                        <span class="pc-value" id="c_religion"></span>
                                                    </div>
                                                    <div class="pc-field w4 pc-purpose">
                                                        <span class="pc-label">Purpose</span>
                                                        <span class="pc-value" id="c_purpose"></span>
                                                        <p class="pc-note" id="not_valid">(NOT VALID FOR ABROAD AND NATURALIZATION)</p>
                                                    </div>
                                                </div>
                                                <div class="pc-result">
                                                    <span class="pc-label">Remarks</span>
                                                    <span class="pc-value" id="c_derogatory">NO DEROGATORY RECORDS FOUND</span>
                                                </div>
                                                <p class="pc-disclaimer">*The information on this Pasay local government Derogatory Clearance has been subjected verification against Pasay OCC and PNP watch list. Any discrepancies and tampering may lead to legal actions in accordance to Philippine Laws.</p>
                                            </div>

                                            <div class="pc-side">
                                                <div id="c_picture_2" class="pc-photo"></div>
                                                <div id="id_signature_cert_2" class="pc-sig"></div>
                                                <div class="pc-caption">Applicant's Signature</div>
                                                <div class="pc-thumbs">
                                                    <div class="pc-thumb">Left Thumb</div>
                                                    <div class="pc-thumb">Right Thumb</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pc-foot">
                                            <div class="pc-foot-brand">
                                                <img src="/backend/img/logos/mayor-logo.png" alt="">
                                                <div class="pc-qr"><div id="qrcode"></div></div>
                                            </div>
                                            <div class="pc-meta">
                                                <div><b>Encoder</b> JOYCE TIMTIMAN</div>
                                                <div><b>Print By</b> JOYCE TIMTIMAN</div>
                                                <div><b>Print Date</b> <span id="c_or_date"></span></div>
                                                <div><b>OR Number</b> <span id="c_or_no"></span></div>
                                                <div><b>Cedula Number</b> <span id="c_cedula_no"></span></div>
                                                <div><b>Issued At</b> <span id="c_issued_at"></span></div>
                                                <div><b>Issued On</b> <span id="c_issued_date"></span></div>
                                            </div>
                                            <div class="pc-seal">
                                                <div class="pc-seal-badge">Not Valid Without Dryseal</div>
                                                <div class="pc-seal-amount"><b>OR Date / Total Amount:</b> <span id="c_issued_or_date"></span> / P 250.00</div>
                                            </div>
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
                                                                    <span style="font-size: 20px;display: block;font-weight: bold;color: #000;font-weight: bold;" id="id_name"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" style="padding:0px;text-transform: uppercase;">
                                                                    <span style="font-size: 14px;font-weight: bold;color: #000;display:block;">ADDRESS:</span>
                                                                    <span style="font-size: 12px;display: block;color: #000;height: 30px;white-space: normal;font-weight: bold;" id="id_address"></span>
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
                                        <p style="margin: 0px;font-size: 34px;font-family: 'barcode';text-transform: uppercase;text-align: center;" class=""><span id="id_bar" style="text-align: center;"></span></p>
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
                responsive: true,
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
                        $('#id_signature_2').attr('style', 'background:url(/img/blank.png)no-repeat;height: 45px;width: 120px;background-position: center top !important;background-size: cover;margin-top: 1.5pt !important;margin:auto;');
                    }
                    else {
                        $('#id_signature').attr('src', '/img/signature/'+data.application.new_application_id+'/'+data.application.new_application.signature );
                        $('#id_signature_2').attr('style', 'background:url("/img/signature/'+data.application.new_application_id+'/'+data.application.new_application.signature.replaceAll(' ','%20')+'")no-repeat;height: 45px;width: 120px;background-position: center top !important;background-size: cover;margin-top: 1.5pt !important;margin:auto;');
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
                responsive: true,
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
        /* Search panel sits above the table (in flow, not fixed) — styled in theme.css */
        div#search_box {
            position: static;
        }
        .renewal-info {
            display: flex;
            padding: 10px;
            background: var(--pnp-blue-soft);
            border-radius: 12px;
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
