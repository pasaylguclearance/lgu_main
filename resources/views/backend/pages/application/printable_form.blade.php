@extends('backend.master.template')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 page-title">Printable Application Form</h1>
        <button type="button" class="btn btn-primary" onclick="window.print()">Print Form</button>
    </div>

    <div class="print-sheet-wrap" id="printForm">
        <div class="a4-grid">
            @for($copy = 1; $copy <= 3; $copy++)
            <div class="print-form-sheet">
                <div class="sheet-top">
                    <div class="sheet-title">
                        <div class="main">POLICE CLEARANCE APPLICATION FORM</div>
                        <div class="sub">(PAKI-FILL UP PO NG MAAYOS AT MALINAW)</div>
                    </div>
                    <div class="sheet-app-box">
                        <div class="app-box-row">
                            <span><strong>APPLICATION NO:</strong> {{ $applicant ? $applicant->application_no : '' }}</span>
                        </div>
                        <div><strong>UCID / OR NO:</strong> {{ $applicant ? $applicant->ucid.' / '.($applicant->or_no ?: '') : '' }}</div>
                    </div>
                </div>

                <div class="row-4">
                    <div class="fbox"><div class="label">FIRST NAME</div><div class="value">{{ $applicant ? $applicant->firstname : '' }}</div></div>
                    <div class="fbox"><div class="label">MIDDLE NAME</div><div class="value">{{ $applicant ? $applicant->middlename : '' }}</div></div>
                    <div class="fbox"><div class="label">LAST NAME</div><div class="value">{{ $applicant ? $applicant->lastname : '' }}</div></div>
                    <div class="fbox"><div class="label">PURPOSE</div><div class="value">{{ $applicant ? optional($applicant->purpose)->purpose : '' }}</div></div>
                </div>

                <div class="row-1">
                    <div class="fbox long"><div class="label">ADDRESS</div><div class="value">{{ $applicant ? trim($applicant->house_no.' '.$applicant->street.' '.$applicant->barangay.' '.$applicant->province) : '' }}</div></div>
                </div>

                <div class="row-4">
                    <div class="fbox"><div class="label">DATE OF BIRTH</div><div class="value">{{ $applicant && $applicant->birthdate ? \Carbon\Carbon::parse($applicant->birthdate)->format('M d, Y') : '' }}</div></div>
                    <div class="fbox"><div class="label">PLACE OF BIRTH</div><div class="value">{{ $applicant ? $applicant->birth_place : '' }}</div></div>
                    <div class="fbox"><div class="label">GENDER</div><div class="value">{{ $applicant ? $applicant->gender : '' }}</div></div>
                    <div class="fbox"><div class="label">NATIONALITY</div><div class="value">{{ $applicant ? optional($applicant->nationality)->nationality : '' }}</div></div>
                </div>

                <div class="row-3">
                    <div class="fbox"><div class="label">CIVIL STATUS</div><div class="value">{{ $applicant ? $applicant->civil_status : '' }}</div></div>
                    <div class="fbox"><div class="label">RELIGION</div><div class="value">{{ $applicant ? optional($applicant->religion)->religion : '' }}</div></div>
                    <div class="fbox"><div class="label">CONTACT NO.</div><div class="value">{{ $applicant ? $applicant->contact_number : '' }}</div></div>
                </div>

                <div class="row-1">
                    <div class="fbox long"><div class="label">COMPANY NAME</div><div class="value">{{ $applicant ? ($applicant->company_name ?? '') : '' }}</div></div>
                </div>

                <div class="bottom-meta">
                    <div class="meta-block">
                        <div class="meta-label">REMARKS</div>
                        <div class="meta-space"></div>
                    </div>
                    <div class="meta-block">
                        <div class="meta-label">APPLICANT SIGNATURE</div>
                        <div class="meta-space"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    body {
        background: #e8ebf1;
    }

    .page-title {
        color: #15344d;
        font-weight: 700;
        font-size: 32px;
        letter-spacing: .2px;
    }

    .container-fluid {
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .btn.btn-primary {
        background: #2f78d8;
        border-color: #2f78d8;
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        padding: 8px 16px;
        line-height: 1.2;
    }

    .d-flex.justify-content-between.align-items-center.mb-3 {
        margin-bottom: 12px !important;
    }

    .print-sheet-wrap {
        overflow: auto;
    }

    .a4-grid {
        display: block;
    }

    .print-form-sheet {
        width: 100%;
        margin: 0 auto 14px auto;
        background: #f7f7f7;
        border: 1px solid #111;
        padding: 8px;
        color: #111;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
    }

    .sheet-top {
        display: grid;
        grid-template-columns: 1fr 390px;
        gap: 8px;
        align-items: start;
    }

    .sheet-title .main {
        font-weight: 700;
        font-size: 22px;
        letter-spacing: .2px;
        line-height: 1.15;
        text-align: center;
    }

    .sheet-title .sub {
        font-weight: 700;
        font-size: 15px;
        margin-top: 2px;
        line-height: 1.2;
        text-align: center;
    }

    .sheet-app-box {
        border: 1px solid #333;
        padding: 8px 10px;
        min-height: 64px;
        font-size: 12px;
        line-height: 1.2;
    }

    .app-box-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .copy-no {
        font-weight: 400;
    }

    .row-4, .row-3, .row-1 {
        display: grid;
        gap: 4px;
        margin-top: 4px;
    }

    .row-4 { grid-template-columns: repeat(4, 1fr); }
    .row-3 { grid-template-columns: repeat(3, 1fr); }
    .row-1 { grid-template-columns: 1fr; }

    .fbox {
        border: 1px solid #333;
        min-height: 38px;
        padding: 4px 6px;
    }

    .fbox.long {
        min-height: 40px;
    }

    .fbox .label {
        font-weight: 700;
        font-size: 10px;
        line-height: 1.05;
    }

    .fbox .value {
        margin-top: 3px;
        font-size: 10px;
        min-height: 14px;
        line-height: 1.2;
    }

    .bottom-meta {
        margin-top: 6px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .meta-block {
        padding-top: 0;
    }

    .meta-label {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .meta-space {
        min-height: 86px;
    }

    @media print {
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .sidebar,
        .navbar,
        .header,
        .footer,
        .btn,
        .main > .content > .container-fluid > .d-flex:first-child {
            display: none !important;
        }

        .wrapper, .main, .content, .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
        }

        .print-sheet-wrap {
            width: 100%;
            height: calc(297mm - 6mm);
            padding: 0;
            margin: 0;
            overflow: visible !important;
        }

        .a4-grid {
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 2mm;
        }

        .print-form-sheet {
            width: 100%;
            flex: 1 1 0;
            margin: 0;
            page-break-inside: avoid;
            break-inside: avoid;
            overflow: hidden;
            padding: 5px 6px;
            background: #fff;
        }

        .sheet-top {
            grid-template-columns: 1fr 340px;
            gap: 6px;
        }

        .sheet-title .main {
            font-size: 14px;
        }

        .sheet-title .sub {
            font-size: 10px;
        }

        .sheet-app-box {
            min-height: 46px;
            font-size: 10px;
            padding: 6px 8px;
        }

        .row-4, .row-3, .row-1 {
            gap: 3px;
            margin-top: 3px;
        }

        .fbox {
            min-height: 28px;
            padding: 3px 5px;
        }

        .fbox.long {
            min-height: 30px;
        }

        .fbox .label {
            font-size: 9px;
        }

        .fbox .value {
            font-size: 9px;
            min-height: 10px;
            margin-top: 2px;
        }

        .bottom-meta {
            margin-top: 5px;
            gap: 12px;
        }

        .meta-label {
            font-size: 10px;
        }

        .meta-space {
            min-height: 46px;
        }

        @page {
            size: A4 portrait;
            margin: 3mm;
        }
    }
</style>
@endsection
