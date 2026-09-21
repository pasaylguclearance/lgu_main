@extends('backend.master.template')

@section('content')
<div class="container-fluid new-app-screen">
    <input type="hidden" id="selected_applicant_id" value="{{ $selectedApplicant ? $selectedApplicant->id : '' }}">
    <div class="header d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h1 class="header-title mb-1">New Applications</h1>
            <p class="text-muted mb-0">Fill out the applicant profile and proceed to biometrics capture.</p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ url('new_application') }}" class="btn btn-primary mr-3">New Applicant</a>
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('new_application') }}">Registration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New Application</li>
                </ol>
            </nav>
        </div>
    </div>

    @include('backend.partial.flash-message')

    <div class="card mb-3 helper-banner">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="mb-2 mb-md-0">
                    <strong>Required fields are marked with <span class="text-danger">*</span>.</strong>
                </div>
                <div class="quick-nav">
                    <a href="#sec-transaction">Transaction</a>
                    <a href="#sec-derogatory">Derogatory</a>
                    <a href="#sec-name">Applicant</a>
                    <a href="#sec-address">Address</a>
                    <a href="#sec-personal">Personal</a>
                    <a href="#sec-consent">Consent</a>
                </div>
            </div>
        </div>
    </div>

    @php($requestedTab = request('tab'))
    @php($activeTab = in_array($requestedTab, ['other','printing','renewal']) ? $requestedTab : 'applicant')
    <div class="tab">
        <ul class="nav nav-tabs app-main-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'applicant' ? 'active' : '' }}" href="#tab-applicant" data-toggle="tab" role="tab">Applicant Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'other' ? 'active' : '' }} {{ !$selectedApplicant ? 'disabled' : '' }}" href="#tab-other" data-toggle="tab" role="tab">Other Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'printing' ? 'active' : '' }} {{ !$selectedApplicant ? 'disabled' : '' }}" href="#tab-printing" data-toggle="tab" role="tab">Printing</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'renewal' ? 'active' : '' }} {{ !$selectedApplicant ? 'disabled' : '' }}" href="#tab-renewal" data-toggle="tab" role="tab">Renewal History</a>
            </li>
        </ul>

        <div class="tab-content pt-3">
            <div class="tab-pane {{ $activeTab === 'applicant' ? 'active show' : '' }}" id="tab-applicant" role="tabpanel">
                <form id="modal-form" action="{{ url('new_application/save') }}" method="post" enctype="multipart/form-data">
                @csrf()
                <input type="hidden" name="selected_applicant_id" id="selected_applicant_id_form" value="{{ $selectedApplicant ? $selectedApplicant->id : '' }}">

        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Lookup Existing Applicant</h5>
                    <button type="button" id="lookupToggleBtn" class="btn btn-sm btn-outline-secondary" onclick="toggleLookupPanel()">Show</button>
                </div>
            </div>
            <div class="card-body d-none" id="lookupCardBody">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label for="lookup_query">Search by Application No, UCID, or Name</label>
                        <input type="text" id="lookup_query" class="form-control" placeholder="Type at least 2 characters">
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        <button type="button" class="btn btn-outline-primary btn-block" onclick="lookupApplicant()">Search</button>
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        <button type="button" id="useLookupBtn" class="btn btn-primary btn-block" onclick="applyLookupRecord()" disabled>Use Selected</button>
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        <button type="button" id="renewalBtn" class="btn btn-outline-success btn-block" onclick="toggleRenewalPanel()" {{ $selectedApplicant ? '' : 'disabled' }}>Renewal</button>
                    </div>
                </div>
                <div class="mt-2">
                    <select id="lookup_results" class="form-control" size="5"></select>
                    <small id="lookup_help" class="text-muted">Select a previous record to auto-fill the form fields.</small>
                </div>

                <div id="renewalPanel" class="card mt-3 d-none border-success">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-success">Renewal Details</h6>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="renewal_application_id" value="{{ $selectedApplicant ? $selectedApplicant->id : '' }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_or_no">OR No.</label>
                                    <input type="text" id="renewal_or_no" class="form-control" maxlength="50" value="{{ $latestRenewal && $latestRenewal->or_no ? $latestRenewal->or_no : ($selectedApplicant ? $selectedApplicant->or_no : '') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_issued_or_date">OR Date</label>
                                    <input type="date" id="renewal_issued_or_date" class="form-control" value="{{ $latestRenewal && $latestRenewal->issued_or_date ? $latestRenewal->issued_or_date : ($selectedApplicant ? $selectedApplicant->issued_or_date : '') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_cedula_no">Cedula No.</label>
                                    <input type="text" id="renewal_cedula_no" class="form-control" maxlength="50" value="{{ $latestRenewal && $latestRenewal->cedula_no ? $latestRenewal->cedula_no : ($selectedApplicant ? $selectedApplicant->cedula_no : '') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_issued_date">Cedula Issued Date</label>
                                    <input type="date" id="renewal_issued_date" class="form-control" value="{{ $latestRenewal && $latestRenewal->issued_date ? $latestRenewal->issued_date : ($selectedApplicant ? $selectedApplicant->issued_date : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_date_renew">Date Renew <span class="text-danger">*</span></label>
                                    <input type="date" id="renewal_date_renew" class="form-control" value="{{ $latestRenewal ? $latestRenewal->date_renew : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_date_expiry">Date Expired <span class="text-danger">*</span></label>
                                    <input type="date" id="renewal_date_expiry" class="form-control" value="{{ $latestRenewal ? $latestRenewal->date_expiry : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end justify-content-end">
                                <button type="button" class="btn btn-success" onclick="saveRenewal()">Save Renewal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="sec-transaction">
            <div class="card-header">
                <h5 class="card-title mb-0">1. Transaction Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="application_type_display">Application Type</label>
                            <input type="text" id="application_type_display" name="application_type" class="form-control" value="NEW" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="purpose_id">Purpose <span class="text-danger">*</span></label>
                            <select class="form-control" id="purpose_id" name="purpose_id">
                                <option selected value="1">LOCAL EMPLOYMENT</option>
                                @foreach ($purposes as $purpose)
                                    <option value="{{ $purpose->id }}">{{ $purpose->purpose }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="issued_at">Issued At</label>
                            <input type="text" id="issued_at" name="issued_at" class="form-control" value="PASAY CITY">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="or_no">OR No.</label>
                            <input type="text" id="or_no" name="or_no" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="issued_or_date">OR Date</label>
                            <input type="date" id="issued_or_date" name="issued_or_date" class="form-control" value="{{ old('issued_or_date', $selectedApplicant ? $selectedApplicant->issued_or_date : '') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cedula_no">Cedula No.</label>
                            <input type="text" id="cedula_no" name="cedula_no" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="issued_date">Cedula Issued Date</label>
                            <input type="date" id="issued_date" name="issued_date" class="form-control" value="{{ old('issued_date', $selectedApplicant ? $selectedApplicant->issued_date : '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="sec-derogatory">
            <div class="card-header">
                <h5 class="card-title mb-0">2. Derogatory</h5>
            </div>
            <div class="card-body">
                @php($initialDerogatory = old('derogatory_values', ($selectedApplicationRecord && $selectedApplicationRecord->derogatory) ? $selectedApplicationRecord->derogatory : (($selectedApplicationRecord && $selectedApplicationRecord->finding) ? $selectedApplicationRecord->finding : 'NO DEROGATORY RECORDS FOUND')))
                <input type="hidden" id="derogatory_values" name="derogatory_values" value="{{ $initialDerogatory }}">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group mb-0">
                            <label>Derogatory Entries</label>
                            <div id="derogatoryRows"></div>
                            <small class="form-text text-muted">Remarks preview: <span id="derogatoryPreview">NO DEROGATORY RECORDS FOUND</span></small>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-primary btn-block" onclick="addDerogatoryRow('')">+ Add</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-10">
                        <div class="form-group mb-0">
                            <small class="text-muted">Multiple entries will be saved and displayed separated by <strong>/</strong>.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="sec-name">
            <div class="card-header">
                <h5 class="card-title mb-0">3. Applicant Name</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="firstname">First Name <span class="text-danger">*</span></label>
                            <input type="text" id="firstname" name="firstname" class="form-control" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="middlename">Middle Name</label>
                            <input type="text" id="middlename" name="middlename" class="form-control" maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lastname">Last Name <span class="text-danger">*</span></label>
                            <input type="text" id="lastname" name="lastname" class="form-control" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <select class="form-control" id="suffix" name="suffix">
                                <option value="">None</option>
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
            </div>
        </div>

        <div class="card mb-3" id="sec-address">
            <div class="card-header">
                <h5 class="card-title mb-0">4. Address Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="house_no">House/Lot/Block <span class="text-danger">*</span></label>
                            <input type="text" id="house_no" name="house_no" class="form-control" placeholder="House # / Lot # / Block #" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="street">Street/Area <span class="text-danger">*</span></label>
                            <input type="text" id="street" name="street" class="form-control" placeholder="Street / Area" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="barangay">Barangay <span class="text-danger">*</span></label>
                            <input type="text" id="barangay" name="barangay" class="form-control" placeholder="Barangay" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="municipality_id">Municipality <span class="text-danger">*</span></label>
                            @php($defaultPasayMunicipality = $municipalities->first(function($m){ return stripos($m->municipality, 'pasay') !== false; }))
                            @php($selectedMunicipalityId = old('municipality_id', $selectedApplicant ? $selectedApplicant->municipality_id : ($defaultPasayMunicipality ? $defaultPasayMunicipality->id : null)))
                            <select class="form-control" id="municipality_id" name="municipality_id">
                                <option value="" disabled {{ !$selectedMunicipalityId ? 'selected' : '' }}>Select a Municipality</option>
                                @foreach ($municipalities as $municipality)
                                    <option value="{{ $municipality->id }}" {{ (string)$selectedMunicipalityId === (string)$municipality->id ? 'selected' : '' }}>{{ $municipality->municipality }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="province">Province <span class="text-danger">*</span></label>
                            <input type="text" id="province" name="province" class="form-control" value="METRO MANILA" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="country">Country <span class="text-danger">*</span></label>
                            <input type="text" id="country" name="country" class="form-control" value="PHILIPPINES" required maxlength="200">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="sec-personal">
            <div class="card-header">
                <h5 class="card-title mb-0">5. Personal Profile</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="birthdate">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" id="birthdate" name="birthdate" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="birth_place">Place of Birth <span class="text-danger">*</span></label>
                            <input type="text" id="birth_place" name="birth_place" class="form-control" required maxlength="200">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option selected disabled>Select a Gender</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="nationality_id">Nationality <span class="text-danger">*</span></label>
                            <select class="form-control" id="nationality_id" name="nationality_id">
                                <option selected disabled>Select a Nationality</option>
                                @foreach ($nationalities as $nationality)
                                    <option value="{{ $nationality->id }}">{{ $nationality->nationality }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                            <select id="civil_status" name="civil_status" class="form-control" required>
                                <option selected disabled>Select a Civil Status</option>
                                <option value="SINGLE">SINGLE (WALANG ASAWA)</option>
                                <option value="MARRIED">MARRIED (MAY ASAWA)</option>
                                <option value="SEPARATED">SEPARATED (HIWALAY SA ASAWA)</option>
                                <option value="WIDOWED">WIDOWED (BIYUDA)</option>
                                <option value="WIDOWER">WIDOWER (BIYUDO)</option>
                                <option value="ANNULLED">ANNULLED (PINAWALANG-BISA)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="religion_id">Religion <span class="text-danger">*</span></label>
                            <select class="form-control" id="religion_id" name="religion_id">
                                <option selected disabled>Select a Religion</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}">{{ $religion->religion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="09123456789 or +639123456789" maxlength="13" pattern="^(?:\+63|0)9\d{9}$" title="Enter a valid PH mobile number (09123456789 or +639123456789)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="sec-consent">
            <div class="card-header">
                <h5 class="card-title mb-0">6. Consent</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="consent" name="consent" required checked>
                        <label class="custom-control-label consent-label" for="consent">
                            I, the Data Subject, hereby give my expressed consent to this station (DPO, PIC &amp; PIP) for the processing, storing, updating, and retrieving of my personal information indicated herein. I also certify that I was made aware that biometrics are necessary for record verification and that authorized public safety officers may access and validate the data given in this application in accordance with RA 10173.
                        </label>
                    </div>
                </div>
            </div>
        </div>

                <div class="form-group text-center mb-4">
                    <button type="reset" class="btn btn-outline-secondary btn-lg mr-2">Reset Form</button>
                    <button type="submit" class="btn btn-primary btn-lg submit-button" id="addBtn">{{ $selectedApplicant ? 'Update and Continue to Other Details' : 'Save and Continue to Other Details' }}</button>
                </div>
                </form>
            </div>

            <div class="tab-pane {{ $activeTab === 'other' ? 'active show' : '' }}" id="tab-other" role="tabpanel">
                @if ($selectedApplicant)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <h5 class="card-title mb-2 mb-md-0">Applicant: {{ $selectedApplicant->application_no }} - {{ $selectedApplicant->firstname }} {{ $selectedApplicant->lastname }}</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkTopazConnection()">Check Topaz Connection</button>
                                    <span id="topazStatus" class="ml-2 badge badge-secondary">Not checked</span>
                                    <div id="topazHint" class="small text-muted mt-1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="other-detail-box">
                                        <div class="od-title">Picture</div>
                                        @php($picturePath = $selectedApplicant->picture ? public_path('img/application_picture/'.$selectedApplicant->id.'/'.$selectedApplicant->picture.'.png') : null)
                                        @if ($picturePath && file_exists($picturePath))
                                            <img src="/img/application_picture/{{ $selectedApplicant->id }}/{{ $selectedApplicant->picture }}.png" alt="Picture" class="od-img">
                                        @else
                                            <div class="od-empty">NO IMAGE</div>
                                        @endif
                                        <a href="{{ url('application/picture/'.$selectedApplicant->id) }}" class="btn btn-sm btn-primary btn-block mt-2">Capture / Update</a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="other-detail-box">
                                        <div class="od-title">Right Fingerprint <span class="badge badge-light text-secondary border">Optional</span></div>
                                        @php($rightFpPath = $selectedApplicant->finger_print_right ? public_path('img/fingerprint_right/'.$selectedApplicant->id.'/'.$selectedApplicant->finger_print_right.'.png') : null)
                                        @if ($rightFpPath && file_exists($rightFpPath))
                                            <img src="/img/fingerprint_right/{{ $selectedApplicant->id }}/{{ $selectedApplicant->finger_print_right }}.png" alt="Right Fingerprint" class="od-img">
                                        @else
                                            <div class="od-empty">NO IMAGE</div>
                                        @endif
                                        <a href="{{ url('application/right_thumb/'.$selectedApplicant->id) }}" class="btn btn-sm btn-primary btn-block mt-2">Capture / Update</a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="other-detail-box">
                                        <div class="od-title">Left Fingerprint <span class="badge badge-light text-secondary border">Optional</span></div>
                                        @php($leftFpPath = $selectedApplicant->finger_print_left ? public_path('img/fingerprint_left/'.$selectedApplicant->id.'/'.$selectedApplicant->finger_print_left.'.png') : null)
                                        @if ($leftFpPath && file_exists($leftFpPath))
                                            <img src="/img/fingerprint_left/{{ $selectedApplicant->id }}/{{ $selectedApplicant->finger_print_left }}.png" alt="Left Fingerprint" class="od-img">
                                        @else
                                            <div class="od-empty">NO IMAGE</div>
                                        @endif
                                        <a href="{{ url('application/left_thumb/'.$selectedApplicant->id) }}" class="btn btn-sm btn-primary btn-block mt-2">Capture / Update</a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="other-detail-box">
                                        <div class="od-title">Signature</div>
                                        @php($signaturePath = $selectedApplicant->signature ? public_path('img/signature/'.$selectedApplicant->id.'/'.$selectedApplicant->signature) : null)
                                        @if ($signaturePath && file_exists($signaturePath))
                                            <img src="/img/signature/{{ $selectedApplicant->id }}/{{ $selectedApplicant->signature }}" alt="Signature" class="od-img">
                                        @else
                                            <div class="od-empty">NO IMAGE</div>
                                        @endif
                                        <a href="{{ url('application/signature/'.$selectedApplicant->id) }}" class="btn btn-sm btn-primary btn-block mt-2">Capture / Update</a>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-2">
                                <a href="{{ url('new_application?tab=printing&id='.$selectedApplicant->id) }}" class="btn btn-primary mr-2">Proceed to Printing</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Save applicant information first to generate the record ID, then continue here for picture, fingerprint, and signature.
                    </div>
                @endif
            </div>

            <div class="tab-pane {{ $activeTab === 'printing' ? 'active show' : '' }}" id="tab-printing" role="tabpanel">
                @if ($selectedApplicant)
                    @php($hasPicture = $selectedApplicant->picture && file_exists(public_path('img/application_picture/'.$selectedApplicant->id.'/'.$selectedApplicant->picture.'.png')))
                    @php($hasSignature = $selectedApplicant->signature && file_exists(public_path('img/signature/'.$selectedApplicant->id.'/'.$selectedApplicant->signature)))
                    @php($issuedAt = ($latestRenewal && $latestRenewal->date_renew) ? \Carbon\Carbon::parse($latestRenewal->date_renew) : ($selectedApplicationRecord ? \Carbon\Carbon::parse($selectedApplicationRecord->date) : now()))
                    @php($validUntil = ($latestRenewal && $latestRenewal->date_expiry) ? \Carbon\Carbon::parse($latestRenewal->date_expiry) : $issuedAt->copy()->addMonths(6))
                    @php($issuedOrDateRaw = ($latestRenewal && $latestRenewal->issued_or_date) ? $latestRenewal->issued_or_date : $selectedApplicant->issued_or_date)
                    @php($issuedDateRaw = ($latestRenewal && $latestRenewal->issued_date) ? $latestRenewal->issued_date : $selectedApplicant->issued_date)
                    @php($displayOrNo = ($latestRenewal && $latestRenewal->or_no) ? $latestRenewal->or_no : $selectedApplicant->or_no)
                    @php($displayCedulaNo = ($latestRenewal && $latestRenewal->cedula_no) ? $latestRenewal->cedula_no : $selectedApplicant->cedula_no)
                    @php($issuedOrDate = $issuedOrDateRaw ? \Carbon\Carbon::parse($issuedOrDateRaw)->format('M d, Y') : '-')
                    @php($issuedDate = $issuedDateRaw ? \Carbon\Carbon::parse($issuedDateRaw)->format('M d, Y') : '-')
                    @php($displayDerogatoryRemarks = ($selectedApplicationRecord && $selectedApplicationRecord->derogatory) ? $selectedApplicationRecord->derogatory : (($selectedApplicationRecord && $selectedApplicationRecord->finding) ? $selectedApplicationRecord->finding : 'NO DEROGATORY RECORDS FOUND'))
                    @php($loggedUserName = trim(optional(Auth::user())->name ?: 'SYSTEM'))
                    @php($loggedFirstName = strtoupper(trim(explode(' ', $loggedUserName)[0])))
                    @php($fullName = trim($selectedApplicant->firstname.' '.($selectedApplicant->middlename ?: '').' '.$selectedApplicant->lastname.' '.($selectedApplicant->suffix ?: '')))
                    @php($certificateProvince = trim((string) $selectedApplicant->province))
                    @php($showCertificateProvince = $certificateProvince !== '' && strcasecmp($certificateProvince, 'METRO MANILA') !== 0)
                    @php($addressText = trim($selectedApplicant->house_no.' '.$selectedApplicant->street.' '.$selectedApplicant->barangay.' '.optional($selectedApplicant->municipality)->municipality.' '.($showCertificateProvince ? $certificateProvince : '')))
                    @php($certificateAddressText = trim($selectedApplicant->house_no.' '.$selectedApplicant->street.' '.$selectedApplicant->barangay.' '.optional($selectedApplicant->municipality)->municipality.' '.($showCertificateProvince ? $certificateProvince : '')))
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Printing Preview</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs print-sub-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#printing-cert" data-toggle="tab" role="tab">Printing Certificate</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#printing-id" data-toggle="tab" role="tab">Printing ID</a>
                                </li>
                            </ul>

                            <div class="tab-content print-sub-content">
                                <div class="tab-pane active show" id="printing-cert" role="tabpanel">
                                    <div class="print-bg printing-preview">
                                        <div id="printCertificate" style="font-family: Arial;left: 10px;top: -10px;margin:auto;padding: 0px 10px;background: white;padding: 0 15px;">
                                            <div class="cert-bg" style="background-color: #fff;background-image: url(/img/new-background.png); background-position:center center; background-size: cover; background-repeat: no-repeat; width: 100%; height: auto;">
                                                <div style="padding: 0.3em;"></div>
                                                <div class="row" style="display:flex;align-items:center;">
                                                    <div class="col-md-3" style="text-align:center;flex:0 0 25%;max-width:25%;"><img src="/backend/img/logos/pasay-logo.png" style="width: 85px;display:block;margin:0 auto;" alt=""></div>
                                                    <div class="col-md-6" style="flex:0 0 50%;max-width:50%;">
                                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 10pt; font-weight: bold; color: black; text-transform: uppercase; font-family: 'Arial';" class="">Republic of the Philippines</p>
                                                        <p style="margin-bottom: 0px; text-align: center; font-size: 17pt; color: black; text-transform: uppercase; font-weight: bold; text-transform: uppercase; font-family: 'Arial'; margin-top: -5px;" class="">CITY GOVERNMENT OF PASAY</p>
                                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 9pt; color: black; text-transform: uppercase;  margin-top: -5px; font-weight: bold; font-family: 'Arial';" class="">F.B Harrison Street, Pasay City</p>
                                                        <p style="margin-bottom: 0px; text-align: center;  font-size: 9pt; color: black; text-transform: uppercase;  margin-top: -5px;" class="">TELEPHONE NUMBER: 02-82878343</p>
                                                    </div>
                                                    <div class="col-md-3" style="text-align:center;flex:0 0 25%;max-width:25%;"><img src="/backend/img/logos/rp-logo.png" style="width: 85px;display:block;margin:0 auto;" alt=""></div>
                                                </div>
                                                <div style="padding: 0.3em;"></div>
                                                <div class="row"><div class="col-md-12" style="padding:0px;"><p style="margin-bottom: 0px; text-align: center; font-weight: bold; color: white; font-size: 17pt; background: #ed1c24; text-transform: uppercase;" class="">Pasay local government Derogatory Clearance</p></div></div>
                                                <div style="padding: 0.5em;"></div>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <p style="margin-bottom: 0px; font-size: 9pt; color: black; font-weight: 700; text-transform: uppercase;" class="">To whom it may concern:</p>
                                                        <p style="margin-bottom: 0px; font-size: 8pt !important; color: black;" class="">This is to certify that the person whose name, signature, picture and finger prints appear hearon has requested a RECORD CLEARANCE from this office and result(s) is/are below:</p>
                                                    </div>
                                                    <div class="col-md-5" style="text-align:right;">
                                                        <p style="margin-bottom: 0px; font-size: 11pt; color: black; text-transform: uppercase; margin-top: -5px;" class="">
                                                            <span style="display: block; font-weight: bold;">Control Number:</span>
                                                            <span style="font-weight: bold;font-size: 15pt !important;color: #ed1c24;margin-top: -7px;display: block;">{{ $selectedApplicant->ucid }}</span>
                                                        </p>
                                                        <div style="margin-top: -10px;font-weight: bold;">
                                                            <span style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: -5px;" class="">Date Issued: {{ $issuedAt->format('M d, Y') }}</span>  |
                                                            <span style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: -5px;" class="">Valid Until: {{ $validUntil->format('M d, Y') }}</span>
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
                                                                        <div>NAME: {{ $fullName }}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:11pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">ADDRESS:</span>
                                                                        <p style="margin-bottom:0px;height:45px;" class="">{{ $certificateAddressText }}</p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">DATE OF BIRTH:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ \Carbon\Carbon::parse($selectedApplicant->birthdate)->format('d M Y') }}</p>
                                                                    </td>
                                                                    <td colspan="2" style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">PLACE OF BIRTH:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ $selectedApplicant->birth_place }}</p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">GENDER:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ $selectedApplicant->gender }}</p>
                                                                    </td>
                                                                    <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">CIVIL STATUS:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ $selectedApplicant->civil_status }}</p>
                                                                    </td>
                                                                    <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">CITIZENSHIP:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ optional($selectedApplicant->nationality)->nationality }}</p>
                                                                    </td>
                                                                    <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">RELIGION:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ optional($selectedApplicant->religion)->religion }}</p>
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                            <table style="width:100%;margin-top:10px;">
                                                                <tr>
                                                                    <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">PURPOSE:</span>
                                                                        <p style="margin-bottom:0px; font-weight:bold;" class="">{{ optional($selectedApplicant->purpose)->purpose }}</p>
                                                                        <p style="margin-bottom: 0px; display: none; font-style:italic;">(NOT VALID FOR ABROAD AND NATURALIZATION)</p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="text-transform:uppercase;padding: 0 8px;color: #000; font-size:9pt;">
                                                                        <span style="display:block;color:#000;font-weight:bold;">REMARKS:</span>
                                                                        <p style="margin-bottom:0px;" class="">{{ $displayDerogatoryRemarks }}</p>
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
                                                                            <div style="width:70px;height:70px;">
                                                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ rawurlencode($selectedApplicant->ucid) }}" alt="QR" style="width:70px;height:70px;">
                                                                            </div>
                                                                                <div id="footer-details" style="display: flex;padding: 0 10px;width: 100%;">
                                                                                <div style="padding:0 10px;">
                                                                                    <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">ENCODER:</b> {{ $loggedFirstName }}</div>
                                                                                    <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">PRINT BY:</b> {{ $loggedFirstName }}</div>
                                                                                    <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">PRINT DATE:</b> {{ now()->format('M d, Y') }}</div>
                                                                                    <div style="margin-bottom: 0px; font-size: 8pt; color: black; text-transform: uppercase; margin-top: 0;"><b style="font-weight:bold;">OR NUMBER:</b> {{ $displayOrNo ?: '-' }}</div>
                                                                                </div>
                                                                                <div style="padding:0 10px;">
                                                                                    <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;"><b style="font-weight:bold;">CEDULA NUMBER:</b> {{ $displayCedulaNo ?: '-' }}</p>
                                                                                    <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;"><b style="font-weight:bold;">ISSUED AT:</b> {{ $selectedApplicant->issued_at ?: '-' }}</p>
                                                                                    <p style="color: black; text-transform: uppercase;font-size: 8pt;margin:0px;"><b style="font-weight:bold;">ISSUED ON:</b> {{ $issuedDate }}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td style="width:2.3in; text-align:center;vertical-align: top;">
                                                            @if($hasPicture)
                                                                <div style="width:2in;height:2in;margin:auto;background:url('/img/application_picture/{{ $selectedApplicant->id }}/{{ $selectedApplicant->picture }}.png') no-repeat center center/cover;"></div>
                                                            @else
                                                                <div style="width:2in;height:2in;margin:auto;border:1px solid #000;display:flex;align-items:center;justify-content:center;background:#f3f4f6;">NO IMAGE</div>
                                                            @endif
                                                            @if($hasSignature)
                                                                <div style="background:url('/img/signature/{{ $selectedApplicant->id }}/{{ rawurlencode($selectedApplicant->signature) }}') no-repeat;width:2in;height:.5in;background-position:center top !important;background-size:100% 100%;margin-top:5px !important;margin:auto;border-bottom:2px solid #000;"></div>
                                                            @else
                                                                <div style="width:2in;height:2in;margin:auto;border:1px solid #000;display:flex;align-items:center;justify-content:center;background:#f3f4f6;">NO IMAGE</div>
                                                            @endif
                                                            <p style="margin-bottom: 0px; font-size: 9pt; color: black; text-transform: uppercase; ">Applicant's Signature</p>
                                                            <div class="finger-mark" style="display:flex;width:2in;margin:auto;">
                                                                <div class="left-mark" style="width:100%;height:90px;margin:5px;"></div>
                                                                <div class="right-mark" style="width:100%;height:90px;margin:5px;"></div>
                                                            </div>
                                                            <div style="text-align:right;padding-right:8px;line-height:1.2;">
                                                                <p style="font-size:8.5pt;color:red;text-transform:uppercase;margin:0;font-weight:bold;">NOT VALID WITHOUT DRYSEAL</p>
                                                                <p style="font-size:7.5pt;color:black;text-transform:uppercase;margin:0;white-space:normal;"><b style="font-weight:bold;">OR DATE/TOTAL AMOUNT:</b> {{ $issuedOrDate }}/P 250.00 </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="printing-id" role="tabpanel">
                                    <div class="print-bg printing-preview">
                                        <div id="printID" class="id-layout">
                                            <div style="height:100%;width:100%;text-transform:uppercase;background:url(/img/new-background.jpg);background-position:center;background-size:cover;font-family:'Arial'">
                                                <table class="headers" style="width:100%;height:2.51cm;background:#ffff2a45;">
                                                    <tr>
                                                        <td style="width:1.3cm;"></td>
                                                        <td style="width:1.9cm;"><img src="/backend/img/logos/pasay-logo.png" alt="" style="width:100%;"></td>
                                                        <td style="width:9.97cm;text-align:center;">
                                                            <div style="font-size:11px;font-family:Arial;font-weight:bold;">REPUBLIC OF THE PHILIPPINES</div>
                                                            <div style="font-size:18px;font-family:Arial;font-weight:bold;">CITY GOVERNMENT OF PASAY</div>
                                                            <div style="font-size:9px;font-family:Arial;font-weight:bold;">F.B. HARRISON STREET, PASAY CITY 02 88320-1125</div>
                                                        </td>
                                                        <td style="width:1.9cm;"><img src="/backend/img/logos/rp-logo.png" alt="" style="width:100%;"></td>
                                                        <td style="width:1.3cm;"></td>
                                                    </tr>
                                                </table>
                                                <div class="body" style="background-position:center;background-size:100% 100%;">
                                                    <div style="margin-bottom:0;font-size:18px;color:white;text-transform:uppercase;font-weight:bold;font-family:Arial;background:#ff0000;text-align:right;padding:1pt 5pt;">CN {{ $selectedApplicant->ucid }}</div>
                                                    <table style="width:100%;">
                                                        <tr>
                                                            <td style="width:30%;vertical-align:top;">
                                                                @if($hasPicture)
                                                                    <div style="width:120px;height:120px;background:url('/img/application_picture/{{ $selectedApplicant->id }}/{{ $selectedApplicant->picture }}.png') no-repeat center center/cover;border:1px solid #878787;margin:auto;">
                                                                        <div style="width:35px;height:35px;background:url('/img/application_picture/{{ $selectedApplicant->id }}/{{ $selectedApplicant->picture }}.png') no-repeat center center/cover;border-radius:50%;margin-top:80px;margin-left:80px;opacity:0.8;"></div>
                                                                    </div>
                                                                @else
                                                                    <div style="width:120px;height:120px;border:1px solid #878787;margin:auto;display:flex;align-items:center;justify-content:center;background:#f3f4f6;">NO IMAGE</div>
                                                                @endif
                                                                @if($hasSignature)
                                                                    <div style="height:45px;width:120px;background:url('/img/signature/{{ $selectedApplicant->id }}/{{ rawurlencode($selectedApplicant->signature) }}') no-repeat center top/100% 100%;margin:6px auto 0;"></div>
                                                                @else
                                                                    <div style="height:45px;width:120px;background:#f3f4f6;border:1px solid #d1d5db;display:flex;align-items:center;justify-content:center;margin:6px auto 0;">NO IMAGE</div>
                                                                @endif
                                                                <div style="font-weight:bold;font-family:arial;text-align:center;font-size:10px;color:#000;margin-top:2px;">HOLDER'S SIGNATURE</div>
                                                            </td>
                                                            <td style="vertical-align:top;">
                                                                <table style="width:100%;">
                                                                    <tr><td colspan="2" style="padding:0;text-transform:uppercase;"><span style="font-size:10px;font-weight:bold;color:#000;display:block;">NAME:</span><span style="font-size:20px;display:block;font-weight:bold;color:#000;">{{ $fullName }}</span></td></tr>
                                                                    <tr><td colspan="2" style="padding:0;text-transform:uppercase;"><span style="font-size:14px;font-weight:bold;color:#000;display:block;">ADDRESS:</span><span style="font-size:12px;display:block;color:#000;height:30px;white-space:normal;">{{ $addressText }}</span></td></tr>
                                                                    <tr><td style="padding:0;text-transform:uppercase;padding-bottom:5px;"><span style="font-size:14px;font-weight:bold;color:#000;display:block;">GENDER:</span><span style="font-size:12px;display:block;color:#000;font-weight:bold;">{{ $selectedApplicant->gender }}</span></td><td style="padding:0;text-transform:uppercase;padding-bottom:5px;"><span style="font-size:14px;font-weight:bold;color:#000;display:block;">NATIONALITY:</span><span style="font-size:12px;display:block;color:#000;font-weight:bold;">{{ optional($selectedApplicant->nationality)->nationality }}</span></td></tr>
                                                                    <tr><td style="padding:0;text-transform:uppercase;padding-bottom:5px;"><span style="font-size:14px;font-weight:bold;color:#000;display:block;">CIVIL STATUS:</span><span style="font-size:12px;display:block;color:#000;font-weight:bold;">{{ $selectedApplicant->civil_status }}</span></td><td style="padding:0;text-transform:uppercase;padding-bottom:5px;"><span style="font-size:14px;font-weight:bold;color:#000;display:block;">DATE OF BIRTH:</span><span style="font-size:12px;display:block;color:#000;font-weight:bold;">{{ \Carbon\Carbon::parse($selectedApplicant->birthdate)->format('d M Y') }}</span></td></tr>
                                                                    <tr><td colspan="2" style="padding:0;text-transform:uppercase;padding-bottom:5px;"><span style="font-size:14px;font-weight:bold;color:#000;display:block;">VALID UNTIL:</span><span style="font-size:12px;display:block;color:#000;font-weight:bold;">{{ $validUntil->format('M d, Y') }}</span></td></tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="footer" style="height:auto;padding:0;">
                                                    <p style="margin:0;font-size:40px;font-family:'barcode';text-transform:uppercase;text-align:center;">*{{ $selectedApplicant->ucid }}*</p>
                                                </div>
                                                <div style="background:#211f7c;color:#fff;text-align:center;font-size:15px;font-weight:bold;margin-top:5px;text-transform:uppercase;">Pasay local government Derogatory Clearance IDENTIFICATION CARD</div>
                                            </div>
                                            <div style="height:100%;width:100%;"><img src="/img/back.jpg" height="100%" width="100%" alt=""></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                @if (!($hasPicture && $hasSignature))
                                    <div class="alert alert-warning d-inline-block mb-0">Complete required details (picture and signature) in the <strong>Other Details</strong> tab before printing.</div>
                                @endif
                                <button type="button" class="btn btn-outline-secondary ml-2" onclick="printDiv()">Print Certificate</button>
                                <button type="button" class="btn btn-outline-secondary ml-2" onclick="printidDiv()">Print ID</button>
                                <a href="{{ route('new-application-complete-transaction') }}" class="btn btn-primary ml-2">Complete Transaction</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Save applicant information first, then continue to Printing.
                    </div>
                @endif
            </div>

            <div class="tab-pane {{ $activeTab === 'renewal' ? 'active show' : '' }}" id="tab-renewal" role="tabpanel">
                @if ($selectedApplicant)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Renewal Transactions: {{ $selectedApplicant->application_no }} - {{ $selectedApplicant->firstname }} {{ $selectedApplicant->lastname }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>#</th>
                                            <th>Date Renew</th>
                                            <th>Date Expired</th>
                                            <th>OR No.</th>
                                            <th>OR Date</th>
                                            <th>Cedula No.</th>
                                            <th>Cedula Issued Date</th>
                                            <th>Saved At</th>
                                        </tr>
                                    </thead>
                                    <tbody id="renewalHistoryBody">
                                        <tr><td colspan="9" class="text-center text-muted">Loading renewal history...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Select or save an applicant first, then renewal history will appear here.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    .new-app-screen {
        color: var(--pnp-text);
    }

    .new-app-screen .header-title,
    .new-app-screen .card-title,
    .new-app-screen label,
    .new-app-screen .custom-control-label,
    .new-app-screen .form-control {
        color: var(--pnp-text);
    }

    .new-app-screen .text-muted {
        color: var(--pnp-muted) !important;
    }

    .new-app-screen .breadcrumb-item,
    .new-app-screen .breadcrumb-item.active {
        color: var(--pnp-text) !important;
    }

    .new-app-screen .breadcrumb-item a {
        color: var(--pnp-blue) !important;
        font-weight: 600;
    }

    .new-app-screen .card-header {
        background: #FAFBFE;
        border-bottom: 1px solid var(--pnp-border);
    }

    .new-app-screen .form-control {
        background: #fff;
        border-color: #d1d5db;
    }

    .new-app-screen .form-control::placeholder {
        color: var(--pnp-muted);
        opacity: 1;
    }

    .helper-banner {
        border-left: 4px solid var(--pnp-blue);
        background: var(--pnp-blue-soft);
    }

    .quick-nav a {
        display: inline-block;
        margin-left: 10px;
        color: var(--pnp-blue);
        font-weight: 600;
        font-size: 13px;
    }

    .quick-nav a:hover {
        text-decoration: underline;
    }

    .card-header .card-title {
        font-weight: 700;
    }

    .tab .tab-content {
        background: transparent;
        padding: 0;
        box-shadow: none;
    }

    .app-main-tabs {
        border-bottom: 1px solid var(--pnp-border);
        gap: 8px;
        padding: 6px 0 10px 0;
    }

    .app-main-tabs .nav-link {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        background: #ffffff !important;
        color: var(--pnp-text) !important;
        font-weight: 600 !important;
        padding: 9px 14px !important;
        transition: all .2s ease-in-out;
    }

    .app-main-tabs .nav-link:hover {
        background: #f9fafb !important;
        border-color: var(--pnp-blue) !important;
        color: var(--pnp-text) !important;
    }

    .app-main-tabs .nav-link.active {
        background: var(--pnp-red) !important;
        color: #ffffff !important;
        border-color: var(--pnp-red) !important;
        box-shadow: none !important;
    }

    .app-main-tabs .nav-link.disabled {
        background: #f3f4f6 !important;
        color: #9ca3af !important;
        border-color: var(--pnp-border) !important;
        cursor: not-allowed;
    }

    .print-sub-tabs {
        border-bottom: 1px solid var(--pnp-border);
        gap: 8px;
    }

    .print-sub-tabs .nav-link {
        border: 1px solid #d1d5db !important;
        border-radius: 8px 8px 0 0 !important;
        color: var(--pnp-text) !important;
        font-weight: 600 !important;
        padding: 8px 14px !important;
        background: #ffffff !important;
    }

    .print-sub-tabs .nav-link:hover {
        background: #f9fafb !important;
        color: var(--pnp-text) !important;
        border-color: var(--pnp-blue) !important;
    }

    .print-sub-tabs .nav-link.active {
        background: var(--pnp-red) !important;
        color: #ffffff !important;
        border-color: var(--pnp-red) !important;
    }

    .new-app-screen .btn,
    .new-app-screen button,
    .new-app-screen input[type="button"],
    .new-app-screen input[type="submit"] {
        border-radius: 6px !important;
        font-weight: 600 !important;
        box-shadow: none !important;
    }

    .new-app-screen .btn-outline-secondary {
        background: #ffffff !important;
        border: 2px solid var(--pnp-muted) !important;
        color: var(--pnp-text) !important;
    }

    .new-app-screen .btn-outline-secondary:hover {
        background: #f3f4f6 !important;
        color: var(--pnp-text) !important;
    }

    .new-app-screen .btn-outline-primary {
        background: #ffffff !important;
        border: 2px solid var(--pnp-blue) !important;
        color: var(--pnp-blue) !important;
    }

    .new-app-screen .btn-outline-primary:hover {
        background: var(--pnp-blue-soft) !important;
        color: var(--pnp-blue-dark) !important;
    }

    .new-app-screen .btn-primary {
        background: var(--pnp-red) !important;
        border-color: var(--pnp-red) !important;
        color: #ffffff !important;
    }

    .new-app-screen .btn-primary:hover {
        background: var(--pnp-red-dark) !important;
        border-color: var(--pnp-red-dark) !important;
        color: #ffffff !important;
    }

    .print-sub-content .tab-pane {
        color: var(--pnp-text) !important;
    }

    .print-sub-content p,
    .print-sub-content span,
    .print-sub-content div,
    .print-sub-content td {
        color: var(--pnp-text) !important;
    }

    .print-sub-content {
        border: 1px solid var(--pnp-border);
        border-top: 0;
        border-radius: 0 8px 8px 8px;
        padding: 10px;
        background: #fff;
    }

    .other-detail-box {
        border: 1px solid var(--pnp-border);
        border-radius: 10px;
        background: #fff;
        padding: 12px;
        height: 100%;
    }

    .od-title {
        font-weight: 700;
        color: var(--pnp-text);
        margin-bottom: 10px;
    }

    .od-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #f3f4f6;
    }

    .od-empty {
        width: 100%;
        height: 180px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pnp-muted);
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .consent-label {
        font-size: 13px;
        line-height: 1.6;
        color: var(--pnp-text);
    }

    .printing-preview {
        background: #a9a9a9;
        padding: 10px;
        overflow: auto;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript" src="https://www.sigplusweb.com/SigWebTablet.js"></script>
<script>
    var lookupRecords = {};
    var renewalSaveUrl = "{{ route('new-application-renewal-save') }}";
    var renewalHistoryUrl = "{{ route('new-application-renewal-history') }}";
    var lookupByIdTemplateUrl = "{{ url('new_application/lookup') }}";
    var initialSelectedApplicant = {!! $selectedApplicant ? json_encode([
        'id' => $selectedApplicant->id,
        'purpose_id' => $selectedApplicant->purpose_id,
        'issued_at' => $selectedApplicant->issued_at,
        'or_no' => $selectedApplicant->or_no,
        'issued_or_date' => $selectedApplicant->issued_or_date,
        'cedula_no' => $selectedApplicant->cedula_no,
        'issued_date' => $selectedApplicant->issued_date,
        'firstname' => $selectedApplicant->firstname,
        'middlename' => $selectedApplicant->middlename,
        'lastname' => $selectedApplicant->lastname,
        'suffix' => $selectedApplicant->suffix,
        'house_no' => $selectedApplicant->house_no,
        'street' => $selectedApplicant->street,
        'barangay' => $selectedApplicant->barangay,
        'municipality_id' => $selectedApplicant->municipality_id,
        'province' => $selectedApplicant->province,
        'country' => $selectedApplicant->country,
        'birthdate' => $selectedApplicant->birthdate,
        'birth_place' => $selectedApplicant->birth_place,
        'gender' => $selectedApplicant->gender,
        'nationality_id' => $selectedApplicant->nationality_id,
        'civil_status' => $selectedApplicant->civil_status,
        'religion_id' => $selectedApplicant->religion_id,
        'contact_number' => $selectedApplicant->contact_number
    ]) : 'null' !!};

    function toggleLookupPanel() {
        var body = document.getElementById('lookupCardBody');
        var btn = document.getElementById('lookupToggleBtn');
        if (!body || !btn) return;
        var isHidden = body.classList.toggle('d-none');
        btn.textContent = isHidden ? 'Show' : 'Hide';
    }

    function toggleRenewalPanel(forceShow) {
        var panel = document.getElementById('renewalPanel');
        if (!panel) return;
        var now = new Date();
        var month = ('0' + (now.getMonth() + 1)).slice(-2);
        var day = ('0' + now.getDate()).slice(-2);
        var today = now.getFullYear() + '-' + month + '-' + day;

        if (forceShow === true) {
            panel.classList.remove('d-none');
            $('#renewal_date_renew').val(today);
            setRenewalExpiryDate();
            return;
        }
        panel.classList.toggle('d-none');
        if (!panel.classList.contains('d-none')) {
            $('#renewal_date_renew').val(today);
            setRenewalExpiryDate();
        }
    }

    function setRenewalExpiryDate() {
        var renewDate = $('#renewal_date_renew').val();
        if (!renewDate) return;
        var dt = new Date(renewDate + 'T00:00:00');
        dt.setMonth(dt.getMonth() + 6);
        var month = ('0' + (dt.getMonth() + 1)).slice(-2);
        var day = ('0' + dt.getDate()).slice(-2);
        $('#renewal_date_expiry').val(dt.getFullYear() + '-' + month + '-' + day);
    }

    function saveRenewal() {
        var payload = {
            _token: "{{ csrf_token() }}",
            application_id: $('#renewal_application_id').val(),
            or_no: $('#renewal_or_no').val(),
            issued_or_date: $('#renewal_issued_or_date').val(),
            cedula_no: $('#renewal_cedula_no').val(),
            issued_date: $('#renewal_issued_date').val(),
            date_renew: $('#renewal_date_renew').val(),
            date_expiry: $('#renewal_date_expiry').val()
        };

        if (!payload.application_id) {
            alert('Select an applicant first.');
            return;
        }
        if (!payload.date_renew || !payload.date_expiry) {
            alert('Date Renew and Date Expired are required.');
            return;
        }

        $.post(renewalSaveUrl, payload)
            .done(function () {
                window.location.href = "{{ url('new_application') }}?tab=printing&id=" + encodeURIComponent(payload.application_id);
            })
            .fail(function (xhr) {
                var message = 'Failed to save renewal.';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            });
    }

    function formatDateHuman(value, withTime) {
        if (!value) return '-';
        if (typeof moment !== 'undefined') {
            return withTime ? moment(value).format('MMM DD, YYYY hh:mm A') : moment(value).format('MMM DD, YYYY');
        }
        return value;
    }

    function renderRenewalHistory(records) {
        var $body = $('#renewalHistoryBody');
        if (!$body.length) return;
        $body.empty();

        if (!records || !records.length) {
            $body.append('<tr><td colspan="9" class="text-center text-muted">No renewal history yet for this applicant.</td></tr>');
            return;
        }

        records.forEach(function (item, idx) {
            var row = '' +
                '<tr class="renewal-history-row">' +
                    '<td><button type="button" class="btn btn-sm btn-outline-primary" onclick="useRenewalHistory(this)" ' +
                    'data-renew-id="' + (item.id || '') + '" ' +
                    'data-or-no="' + (item.or_no || '') + '" ' +
                    'data-issued-or-date="' + (item.issued_or_date || '') + '" ' +
                    'data-cedula-no="' + (item.cedula_no || '') + '" ' +
                    'data-issued-date="' + (item.issued_date || '') + '" ' +
                    'data-date-renew="' + (item.date_renew || '') + '" ' +
                    'data-date-expiry="' + (item.date_expiry || '') + '">Use</button></td>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + formatDateHuman(item.date_renew, false) + '</td>' +
                    '<td>' + formatDateHuman(item.date_expiry, false) + '</td>' +
                    '<td>' + (item.or_no || '-') + '</td>' +
                    '<td>' + formatDateHuman(item.issued_or_date, false) + '</td>' +
                    '<td>' + (item.cedula_no || '-') + '</td>' +
                    '<td>' + formatDateHuman(item.issued_date, false) + '</td>' +
                    '<td>' + formatDateHuman(item.created_at, true) + '</td>' +
                '</tr>';
            $body.append(row);
        });
    }

    function loadRenewalHistory() {
        var applicationId = $('#renewal_application_id').val() || $('#selected_applicant_id').val();
        var $body = $('#renewalHistoryBody');
        if (!$body.length) return;

        if (!applicationId) {
            $body.html('<tr><td colspan="9" class="text-center text-muted">Select an applicant first.</td></tr>');
            return;
        }

        $body.html('<tr><td colspan="9" class="text-center text-muted">Loading renewal history...</td></tr>');
        $.get(renewalHistoryUrl, { application_id: applicationId })
            .done(function (res) {
                renderRenewalHistory((res && res.records) ? res.records : []);
            })
            .fail(function () {
                $body.html('<tr><td colspan="9" class="text-center text-danger">Failed to load renewal history.</td></tr>');
            });
    }

    function useRenewalHistory(el) {
        if (!el || !el.dataset) return;
        var d = el.dataset;
        var applicantId = $('#renewal_application_id').val() || $('#selected_applicant_id').val();

        if (!applicantId) {
            alert('No selected applicant.');
            return;
        }

        $.get(lookupByIdTemplateUrl + '/' + encodeURIComponent(applicantId))
            .done(function (res) {
                var original = res && res.record ? res.record : null;
                if (original) {
                    // Always restore original applicant profile first.
                    populateApplicantForm(original);
                }

                // Apply selected renewal transaction values for display/edit.
                setFieldValue('renewal_or_no', d.orNo || '');
                setFieldValue('renewal_issued_or_date', d.issuedOrDate || '');
                setFieldValue('renewal_cedula_no', d.cedulaNo || '');
                setFieldValue('renewal_issued_date', d.issuedDate || '');
                setFieldValue('renewal_date_renew', d.dateRenew || '');
                setFieldValue('renewal_date_expiry', d.dateExpiry || '');

                setFieldValue('or_no', d.orNo || '');
                setFieldValue('issued_or_date', d.issuedOrDate || '');
                setFieldValue('cedula_no', d.cedulaNo || '');
                setFieldValue('issued_date', d.issuedDate || '');

                $('.app-main-tabs a[href="#tab-applicant"]').tab('show');
                $('#renewalBtn').prop('disabled', false);
                if ($('#lookup_help').length) {
                    $('#lookup_help').text('Original profile restored and selected renewal transaction applied.');
                }
            })
            .fail(function () {
                alert('Failed to load original applicant profile.');
            });
    }

    function setFieldValue(id, value) {
        var $el = $('#' + id);
        if (!$el.length) return;
        $el.val(value || '');
        $el.trigger('change');
    }

    function setApplicantSubmitMode(selectedId) {
        var hasSelected = !!selectedId;
        $('#selected_applicant_id_form').val(hasSelected ? selectedId : '');
        $('#addBtn').text(hasSelected ? 'Update and Continue to Other Details' : 'Save and Continue to Other Details');
    }

    function escapeHtml(text) {
        return (text || '').replace(/[&<>"']/g, function (m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    function updateDerogatoryCombined() {
        var values = [];
        $('#derogatoryRows .derogatory-item').each(function () {
            var val = ($(this).val() || '').trim();
            if (val) values.push(val);
        });
        if (!values.length) {
            values.push('NO DEROGATORY RECORDS FOUND');
        }
        var joined = values.join(' / ');
        $('#derogatory_values').val(joined);
        $('#derogatoryPreview').text(joined);
    }

    function removeDerogatoryRow(btn) {
        $(btn).closest('.derogatory-row').remove();
        if (!$('#derogatoryRows .derogatory-row').length) {
            addDerogatoryRow('');
        }
        updateDerogatoryCombined();
    }

    function addDerogatoryRow(value) {
        var html = '' +
            '<div class="input-group mb-2 derogatory-row">' +
                '<input type="text" class="form-control derogatory-item" maxlength="200" value="' + escapeHtml(value || '') + '" placeholder="Enter derogatory detail">' +
                '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-outline-danger" onclick="removeDerogatoryRow(this)">Remove</button>' +
                '</div>' +
            '</div>';
        $('#derogatoryRows').append(html);
        updateDerogatoryCombined();
    }

    function initDerogatoryRows() {
        var initial = ($('#derogatory_values').val() || '').trim();
        var parts = initial ? initial.split('/').map(function (x) { return x.trim(); }).filter(Boolean) : [];
        $('#derogatoryRows').empty();
        if (!parts.length) {
            parts = ['NO DEROGATORY RECORDS FOUND'];
        }
        parts.forEach(function (part) {
            addDerogatoryRow(part);
        });
        updateDerogatoryCombined();
    }

    function setDerogatoryFromString(value) {
        $('#derogatory_values').val((value || '').trim());
        initDerogatoryRows();
    }

    function populateApplicantForm(a) {
        if (!a) return;
        setFieldValue('purpose_id', a.purpose_id);
        setFieldValue('issued_at', a.issued_at);
        setFieldValue('or_no', a.or_no);
        setFieldValue('issued_or_date', a.issued_or_date);
        setFieldValue('cedula_no', a.cedula_no);
        setFieldValue('issued_date', a.issued_date);
        setFieldValue('firstname', a.firstname);
        setFieldValue('middlename', a.middlename);
        setFieldValue('lastname', a.lastname);
        setFieldValue('suffix', a.suffix);
        setFieldValue('house_no', a.house_no);
        setFieldValue('street', a.street);
        setFieldValue('barangay', a.barangay);
        setFieldValue('municipality_id', a.municipality_id);
        setFieldValue('province', a.province);
        setFieldValue('country', a.country);
        setFieldValue('birthdate', a.birthdate);
        setFieldValue('birth_place', a.birth_place);
        setFieldValue('gender', a.gender);
        setFieldValue('nationality_id', a.nationality_id);
        setFieldValue('civil_status', a.civil_status);
        setFieldValue('religion_id', a.religion_id);
        setFieldValue('contact_number', a.contact_number);
        setApplicantSubmitMode(a.id);
        if (typeof a.derogatory_values !== 'undefined') {
            setDerogatoryFromString(a.derogatory_values);
        }
    }

    function lookupApplicant() {
        var q = ($('#lookup_query').val() || '').trim();
        var $results = $('#lookup_results');
        var $help = $('#lookup_help');
        $('#useLookupBtn').prop('disabled', true);
        lookupRecords = {};
        $results.empty();

        if (q.length < 2) {
            $help.text('Enter at least 2 characters to search.');
            return;
        }

        $help.text('Searching...');
        $.get("{{ route('new-application-lookup') }}", { q: q })
            .done(function (res) {
                var rows = (res && res.records) ? res.records : [];
                if (!rows.length) {
                    $help.text('No matching applicant found.');
                    return;
                }

                rows.forEach(function (row) {
                    lookupRecords[row.id] = row;
                    $results.append($('<option>', {
                        value: row.id,
                        text: row.display
                    }));
                });

                $results.prop('selectedIndex', 0).trigger('change');
                $help.text('Select a record then click "Use Selected".');
            })
            .fail(function () {
                $help.text('Lookup failed. Please try again.');
            });
    }

    function applyLookupRecord() {
        var selectedId = $('#lookup_results').val();
        if (!selectedId || !lookupRecords[selectedId]) return;
        var a = lookupRecords[selectedId];
        var baseUrl = "{{ url('new_application') }}";
        setApplicantSubmitMode(selectedId);

        setFieldValue('purpose_id', a.purpose_id);
        setFieldValue('issued_at', a.issued_at);
        setFieldValue('or_no', a.or_no);
        setFieldValue('issued_or_date', a.issued_or_date);
        setFieldValue('cedula_no', a.cedula_no);
        setFieldValue('issued_date', a.issued_date);
        setFieldValue('firstname', a.firstname);
        setFieldValue('middlename', a.middlename);
        setFieldValue('lastname', a.lastname);
        setFieldValue('suffix', a.suffix);
        setFieldValue('house_no', a.house_no);
        setFieldValue('street', a.street);
        setFieldValue('barangay', a.barangay);
        setFieldValue('municipality_id', a.municipality_id);
        setFieldValue('province', a.province);
        setFieldValue('country', a.country);
        setFieldValue('birthdate', a.birthdate);
        setFieldValue('birth_place', a.birth_place);
        setFieldValue('gender', a.gender);
        setFieldValue('nationality_id', a.nationality_id);
        setFieldValue('civil_status', a.civil_status);
        setFieldValue('religion_id', a.religion_id);
        setFieldValue('contact_number', a.contact_number);
        if (typeof a.derogatory_values !== 'undefined') {
            setDerogatoryFromString(a.derogatory_values);
        } else {
            setDerogatoryFromString('NO DEROGATORY RECORDS FOUND');
        }
        setFieldValue('renewal_application_id', selectedId);
        setFieldValue('renewal_or_no', a.or_no);
        setFieldValue('renewal_issued_or_date', a.issued_or_date);
        setFieldValue('renewal_cedula_no', a.cedula_no);
        setFieldValue('renewal_issued_date', a.issued_date);

        $('#lookup_help').text('Form auto-populated from the selected record.');
        $('#renewalBtn').prop('disabled', false);
        $('#renewalPanel').addClass('d-none');

        // Unlock tabs and route to selected applicant context.
        var otherUrl = baseUrl + '?tab=other&id=' + encodeURIComponent(selectedId);
        var printingUrl = baseUrl + '?tab=printing&id=' + encodeURIComponent(selectedId);
        var $otherTab = $('.app-main-tabs a[href="#tab-other"], .app-main-tabs a[href*="tab=other"]');
        var $printingTab = $('.app-main-tabs a[href="#tab-printing"], .app-main-tabs a[href*="tab=printing"]');
        var $renewalTab = $('.app-main-tabs a[href="#tab-renewal"], .app-main-tabs a[href*="tab=renewal"]');

        $otherTab.removeClass('disabled').removeAttr('data-toggle').attr('href', otherUrl);
        $printingTab.removeClass('disabled').removeAttr('data-toggle').attr('href', printingUrl);
        $renewalTab.removeClass('disabled').attr('data-toggle', 'tab').attr('href', '#tab-renewal');
        setFieldValue('selected_applicant_id', selectedId);
        loadRenewalHistory();
    }

    $(document).on('change', '#lookup_results', function () {
        var hasSelection = !!$(this).val();
        $('#useLookupBtn').prop('disabled', !hasSelection);
        $('#renewalBtn').prop('disabled', !hasSelection);
    });

    $(document).on('keypress', '#lookup_query', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            lookupApplicant();
        }
    });

    $(document).on('change', '#renewal_date_renew', function () {
        setRenewalExpiryDate();
    });

    $(document).on('input', '.derogatory-item', function () {
        updateDerogatoryCombined();
    });

    $('a[href="#tab-renewal"]').on('shown.bs.tab', function () {
        loadRenewalHistory();
    });

    function printDiv() {
        var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
        var divToPrint=document.getElementById('printCertificate');
        if (!divToPrint) return;
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write(
            '<html><head>' + myStyle +
            '<style>' +
            '@page{size:auto;margin:8mm;}' +
            'html,body{margin:0;padding:0;width:100%;overflow:visible;}' +
            '#printCertificate{width:100% !important;max-width:100% !important;}' +
            '#printCertificate .cert-bg{width:100% !important;max-width:100% !important;}' +
            '#printCertificate .row{margin-left:0 !important;margin-right:0 !important;}' +
            '#printCertificate [class*="col-"]{padding-left:8px !important;padding-right:8px !important;}' +
            '#printCertificate img{max-width:100% !important;height:auto !important;}' +
            '</style>' +
            '</head><body onload="window.print()"><div id="printCertificate">' + divToPrint.innerHTML + '</div></body></html>'
        );
        newWin.document.close();
    }

    function printidDiv() {
        var myStyle = '<link rel="stylesheet" href="/backend/css/modern.css" />';
        var divToPrint=document.getElementById('printID');
        if (!divToPrint) return;
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><style>@font-face {font-family: "barcode";src: url("/font/barcode.ttf");}</style><body onload="window.print()" style="margin:0px;">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
    }

    function setTopazStatus(text, kind) {
        var el = document.getElementById('topazStatus');
        if (!el) return;
        el.className = 'ml-2 badge';
        if (kind === 'ok') {
            el.classList.add('badge-success');
        } else if (kind === 'warn') {
            el.classList.add('badge-warning');
        } else {
            el.classList.add('badge-danger');
        }
        el.textContent = text;
    }

    function setTopazHint(text) {
        var el = document.getElementById('topazHint');
        if (!el) return;
        el.textContent = text || '';
    }

    function checkTopazConnection() {
        try {
            setTopazHint('');
            if (typeof IsSigWebInstalled !== 'function') {
                setTopazStatus('SigWeb script not loaded', 'fail');
                setTopazHint('Open /topaz/diagnostics and allow Local Network Access for this site in browser settings.');
                return;
            }

            if (IsSigWebInstalled()) {
                setTopazStatus('Connected', 'ok');
                setTopazHint('Topaz SigWeb detected on this workstation.');
            } else {
                setTopazStatus('SigWeb not running', 'warn');
                setTopazHint('Start SigWeb app, then allow Local Network Access for this site in Chrome/Edge.');
            }
        } catch (e) {
            setTopazStatus('Connection check failed', 'fail');
            setTopazHint('Check browser Local Network Access and use /topaz/diagnostics for detailed errors.');
        }
    }

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    if (!$('#issued_date').val()) {
        $('#issued_date').val(today);
    }
    if (!$('#issued_or_date').val()) {
        $('#issued_or_date').val(today);
    }
    if (!$('#renewal_date_renew').val()) {
        $('#renewal_date_renew').val(today);
        setRenewalExpiryDate();
    }

    setApplicantSubmitMode($('#selected_applicant_id_form').val());
    populateApplicantForm(initialSelectedApplicant);
    initDerogatoryRows();

    if ($('#tab-renewal').hasClass('active') || $('#tab-renewal').hasClass('show')) {
        loadRenewalHistory();
    }
</script>
@endsection

