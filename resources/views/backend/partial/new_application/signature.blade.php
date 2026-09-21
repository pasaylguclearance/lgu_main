@extends('backend.master.template')

@section('content')
    <div class="form-group" align="center">
        <h2 class="title">POLICE CLEARANCE APPLICATION</h2>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card card-primary">
                <div class="card-header card-heading-opc">
                    <h3 class="card-title card-title-opc">APPLICANT DETAILS</h3>
                </div>
                <div class="card-body">
                        <h1>{{$latest_record->firstname}} {{$latest_record->middlename}} {{$latest_record->lastname}} {{$latest_record->suffix}}</h1>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" align="center">
                                    <b>Signature:</b><br>
                                    <canvas id="cnv" name="cnv" width="500" height="100" style="border: 1px solid black;"></canvas><br>
                                    <input id="button1" name="ClearBtn" type="button" value="Clear" onclick="javascript:onClear()">
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
                            <button type="submit" class="btn btn-success btn-lg submit-button" id="addBtn">
                                <i class="fas fa-file-import"></i>&nbsp;&nbsp;SUBMIT
                            </button>
                            <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#defaultModalPrimary">UPLOAD</button>
                        </div>
                </div>

                        {{-- MODAL --}}
                <div class="modal fade" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Upload Signature</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body m-3">
                                <form id="modal-form" action="{{url('new_application/signature/' . $latest_record->id)}}" method="post"  enctype="multipart/form-data">
                                    @csrf
                                @if ($errors->has('picture'))
                                    <div class="alert alert-danger py-2 mx-3">{{ $errors->first('picture') }}</div>
                                @endif
                                <div class="form-group col-md-12">
                                    <label for="picture">Signature image (JPG or PNG)</label>
                                    <input type="file" class="form-control" id="picture" name="picture" accept="image/*" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary submit-button">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-md-2"></div>
    </div>
@endsection
@section('scripts')
<script src="{{ asset('true_admin/admin/assets/lib/datatables-bs4/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://www.sigplusweb.com/SigWebTablet.js"></script>
<script>
    var sigWebTimer = null;

     function onSign()
    {
        if(IsSigWebInstalled()){
        var ctx = document.getElementById('cnv').getContext('2d');
        SetDisplayXSize( 500 );
        SetDisplayYSize( 100 );
        SetTabletState(0, sigWebTimer);
        SetImagePenWidth( 13 );
        SetDisplayPenWidth( 13 );
        SetJustifyMode(0);
        ClearTablet();
        if(sigWebTimer == null)
        {
            sigWebTimer = SetTabletState(1, ctx, 50);
        }
        else
        {
            SetTabletState(0, sigWebTimer);
            sigWebTimer = null;
            sigWebTimer = SetTabletState(1, ctx, 50);
        }
        } else{
        alert("Unable to communicate with SigWeb. Please confirm that SigWeb is installed and running on this PC.");
        }
    }
    function onClear()
    {
        ClearTablet();
    }

    function skip()
    {
        window.location.href = "{{ url('new_application?tab=other&id='.$latest_record->id) }}";
    }

    $(document).ready(function() {
        @if ($errors->has('picture'))
        $('#defaultModalPrimary').modal('show');
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        onSign();
        $(document).on('click','#addBtn',function(e){
            e.preventDefault();
            var canvas = document.getElementById('cnv');
            var imgData = canvas.toDataURL();
            if($('#consent').is(':checked')) {
                $('#addBtn').prop('disabled',true);
                $.post("{{ route('new-application-signature-capture') }}",{
                    _token: "{{ csrf_token() }}",
                    picture: imgData,
                    id: "{{$latest_record->id}}"
                },function(data){
                    if(data && data.success){
                        SetTabletState(0, sigWebTimer);
                        sigWebTimer = null;
                        window.location.href = "{{ url('new_application?tab=other&id='.$latest_record->id) }}";
                    }else{
                        alert((data && data.message) ? data.message : 'Error while saving signature');
                        $('#addBtn').prop('disabled',false);
                    }
                }).fail(function(xhr) {
                    var msg = 'Error while saving signature';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                    $('#addBtn').prop('disabled',false);
                });
            }else{
                alert('Please check the consent data policy');
            }

        });

        window.addEventListener('beforeunload', function() {
            try {
                SetTabletState(0, sigWebTimer);
                sigWebTimer = null;
            } catch (e) {}
        });
    });
</script>
@endsection
