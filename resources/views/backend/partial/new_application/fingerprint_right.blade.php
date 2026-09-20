@extends('backend.master.template')

@section('content')
    <div class="form-group" align="center">
        <h2 class="title">Fingerprint Capture</h2>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card card-primary">
                <div class="card-header card-heading-opc">
                    <h3 class="card-title card-title-opc">Applicant Information</h3>
                    <h2>{{$latest_record->firstname}} {{$latest_record->middlename}} {{$latest_record->lastname}} {{$latest_record->suffix}}</h2>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="enrollReaderSelect" class="my-text7 my-pri-color">Choose Fingerprint Reader</label>
                                    <select name="readerSelect" id="enrollReaderSelect" class="form-control" onclick="beginEnrollment()">
                                        <option selected>Select Fingerprint Reader</option>
                                    </select>
                                </div>
                                <div class="form-group" align="center">
                                    <b>Right Thumbmark:</b><br>
                                    <div class="fingerprint-div" id="fff" >
                                        <img id="picture" src="/img/fingerprint-default.svg" style="height: 300px;width: 250px;border: 1px solid black;margin-bottom: 15px;" />
                                    </div>
                                    <button class="btn btn-danger my-sec-bg my-text-button py-1" type="submit" onclick="beginCapture()">Start Capture</button>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                                <label>Consent</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="checkbox" id="consent" name="consent"  required data-validation-error-msg="Please read and give your consent to the terms governing the use of this service." checked>
                                    <i>I, the Data Subject, hereby give my expressed consent to this station (DPO, PIC &amp; PIP) for the processing, storing, updating, and retrieving of my personal information indicated herein. I also certify that I was made aware of the following: That the data and/or biometrics captured are necessary to countercheck any crime committed in the past should there be any. That any crime committed in the future will be appended to my personal information. That authorized public safety officers are allowed to access and validate the data given in this application. That information will be stored and kept confidential, within its validity making it available for my next transaction. That processing of information is made through online technologies. That I was made aware of my Rights as Data Subject as indicated in RA 10173, and I was informed about the contact details of the DPO, PIC &amp; PIP.</i>
                                </div>
                            </div>
                        </div>
                                                                                                <br>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-primary btn-lg submit-button" id="addBtn">
                                Submit
                               </button>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-md-2"></div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('es6-shim.js')}}"></script>
<script src="{{asset('websdk.client.bundle.min.js')}}"></script>
<script src="{{asset('fingerprint.sdk.min.js')}}"></script>
<script src="{{asset('base64.min.js')}}"></script>
<script src="{{asset('custom.js')}}"></script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        $(document).on('click','#addBtn',function(){
            var imgData = document.getElementById('picture').src;

            if($('#consent').is(':checked')) {
                $('#addBtn').prop('disabled',true);
                $.post("{{url('new_application/fingerprint_right/save')}}",{picture:imgData,id:"{{$latest_record->id}}"},function(data){
                    window.location.href = "{{ url('new_application?tab=other&id='.$latest_record->id) }}"
                });
            }else{
                alert('Please check the consent data policy');
            }
        });

    });
</script>
@endsection
