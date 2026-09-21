@extends('backend.master.template')

@section('content')
    <div class="form-group" align="center">
        <h2 class="title">Applicant Image Capture</h2>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card card-primary">
                <div class="card-header card-heading-opc">
                    <h3 class="card-title card-title-opc">Applicant Information</h3>
                    <h2 style="text-transform: uppercase;">{{$latest_record->firstname}} {{$latest_record->middlename}} {{$latest_record->lastname}} {{$latest_record->suffix}}</h2>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" align="center">
                                    <div id="my_camera"></div>
                                    <input type="hidden" name="picture"/>
                                    <div style="padding: 1em;"></div>
                                    <button type="button" id="snapshotBtn" onClick="take_snapshot()" class="btn btn-danger">Take Snapshot</button>
                                    <div style="padding: .5em;"></div>
                                    <label class="btn btn-outline-primary mb-0">
                                        Upload Photo
                                        <input type="file" id="upload_photo" accept="image/*" style="display:none;">
                                    </label>
                                    <p class="mb-0 mt-2 text-muted" style="font-size:12px;">Use either snapshot or upload.</p>
                                </div>
                            </div>
                            <div class="col-md-6" style="text-align:center;">
                                <div id="results"><div style="padding:20px;color:#6b7280;">No image selected</div></div>
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
                            <button type="button" class="btn btn-primary btn-lg submit-button" id="addBtn">
                                Submit
                            </button>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1 col-md-2"></div>
    </div>

    <div class="modal fade" id="cropPhotoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Uploaded Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="max-height:65vh;overflow:auto;">
                        <img id="cropImage" src="" alt="Crop Source" style="max-width:100%;display:block;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="applyCropBtn">Apply Crop</button>
                </div>
            </div>
        </div>
    </div>
@method('PUT')
@endsection
@section('style')
<style>
#results {
    border: 1px solid;
    min-height: 302px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
@section('scripts')
{{-- <script src="{{ asset('true_admin/admin/assets/lib/datatables-bs4/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script> --}}
<script type="text/javascript" src="{{asset('/webcam.min.js')}}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
    var cropper = null;
    var uploadedImageData = null;
    var cameraReady = false;

    function showPreview(dataUri) {
        $('input[name="picture"]').val(1);
        document.getElementById('results').innerHTML =
            '<img id="imageprev" src="'+dataUri+'" width="400" height="400" style="object-fit:cover;"/>';
    }

    function showCameraError(message) {
        cameraReady = false;
        document.getElementById('my_camera').innerHTML =
            '<div style="width:400px;max-width:100%;height:300px;display:flex;align-items:center;justify-content:center;' +
            'padding:16px;border:1px dashed #9ca3af;color:#6b7280;font-size:13px;">' + message +
            '<br><br>You can still use <b>Upload Photo</b> below.</div>';
        $('#snapshotBtn').prop('disabled', true);
    }

    function take_snapshot() {
        if (!cameraReady) {
            alert('The camera is not available. Please allow camera access, or use Upload Photo instead.');
            return;
        }
        try {
            Webcam.snap(function(data_uri) {
                showPreview(data_uri);
            });
        } catch (err) {
            alert('Could not take a snapshot: ' + (err && err.message ? err.message : err));
        }
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        Webcam.set({
            width: 400,
            height: 400,
            image_format: 'jpeg',
            jpeg_quality: 90,
            enable_flash: false,
            force_flash: false
        });
        Webcam.on('live', function () {
            cameraReady = true;
            $('#snapshotBtn').prop('disabled', false);
        });
        Webcam.on('error', function (err) {
            var reason = (err && err.message) ? err.message : String(err);
            if (/NotAllowed|Permission/i.test(reason)) {
                reason = 'Camera access was blocked. Click the camera icon in the address bar to allow it, then reload.';
            } else if (/NotFound|DevicesNotFound/i.test(reason)) {
                reason = 'No camera was found on this computer.';
            } else if (!navigator.mediaDevices && location.protocol !== 'https:' && location.hostname !== 'localhost') {
                reason = 'Browsers only allow the camera on https:// or localhost. Open the system via localhost or HTTPS.';
            }
            showCameraError(reason);
        });
        $('#snapshotBtn').prop('disabled', true);
        try {
            Webcam.attach('#my_camera');
        } catch (err) {
            showCameraError((err && err.message) ? err.message : String(err));
        }

        $('#upload_photo').on('change', function(e){
            var file = e.target.files && e.target.files[0] ? e.target.files[0] : null;
            $(this).val('');
            if (!file) return;
            if (file.type && file.type.indexOf('image/') !== 0) {
                alert('Please choose an image file (JPG or PNG).');
                return;
            }
            if (file.size > 15 * 1024 * 1024) {
                alert('The photo is too large (max 15 MB). Please choose a smaller image.');
                return;
            }
            var reader = new FileReader();
            reader.onerror = function(){
                alert('Could not read the selected file. Please try another image.');
            };
            reader.onload = function(evt){
                uploadedImageData = evt.target.result;
                var probe = new Image();
                probe.onerror = function(){
                    uploadedImageData = null;
                    alert('This image format is not supported by your browser (e.g. HEIC). Please use a JPG or PNG.');
                };
                probe.onload = function(){
                    $('#cropImage').attr('src', uploadedImageData);
                    $('#cropPhotoModal').modal('show');
                };
                probe.src = uploadedImageData;
            };
            reader.readAsDataURL(file);
        });

        $('#cropPhotoModal').on('shown.bs.modal', function () {
            var image = document.getElementById('cropImage');
            if (!image || !uploadedImageData) return;
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                responsive: true,
                background: false
            });
        });

        $('#cropPhotoModal').on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        $('#applyCropBtn').on('click', function () {
            if (!cropper) return;
            var canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            if (!canvas) return;
            showPreview(canvas.toDataURL('image/jpeg', 0.9));
            $('#cropPhotoModal').modal('hide');
        });

        $(document).on('click','#addBtn',function(e){
            e.preventDefault();
            var picture = $('input[name="picture"]').val();
            var imageNode = document.getElementById("imageprev");
            var base64image = imageNode ? imageNode.src : null;

            if (picture != 1 || !base64image || base64image.indexOf('data:image/') !== 0) {
                alert('Picture is required. Take a snapshot or upload a photo first.');
                return;
            }
            if (!$('#consent').is(':checked')) {
                alert('Please check the consent data policy');
                return;
            }

            var $btn = $('#addBtn');
            $btn.prop('disabled', true).text('Saving...');
            $.post("{{url('new_application/picture')}}",{picture:base64image,id:"{{$latest_record->id}}"})
                .done(function(data){
                    if (data && data.success) {
                        window.location.href = "{{ url('new_application?tab=other&id='.$latest_record->id) }}";
                        return;
                    }
                    alert((data && data.message) ? data.message : 'Failed to save picture.');
                    $btn.prop('disabled', false).text('Submit');
                })
                .fail(function(xhr){
                    var message = 'Failed to save picture.';
                    if (xhr && xhr.status === 419) {
                        message = 'Your session has expired. Please reload the page and try again.';
                    } else if (xhr && xhr.status === 401) {
                        message = 'You have been logged out. Please sign in again.';
                    } else if (xhr && xhr.status === 413) {
                        message = 'The image is too large for the server to accept.';
                    } else if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr && xhr.status === 0) {
                        message = 'Could not reach the server. Check your network connection and try again.';
                    }
                    alert(message);
                    $btn.prop('disabled', false).text('Submit');
                });
        });

    });
</script>
@endsection
