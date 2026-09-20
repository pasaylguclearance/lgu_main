<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}">

    <title>FingerPrint Web App</title>
</head>
<style type="text/css">


    #mainDiv {
        background: #88ccfc;
        padding: 10px;
        border: 5px solid;
        border-color: #42adf4;
        border-radius: 20px;

        position: absolute;
        top: 10px;
        bottom: 10px;
        left: 10px;
        right: 10px;
    }

    #mainTable {
        width: 100%;
    }

    #mainTable td:FIRST-CHILD {
        text-align: right;
    }

    #user, #urlSistema {
        width: 99%;
    }

    .button {
        width: 120px;
        border: 3px solid;
        border-color: #42adf4;
        border-radius: 20px;
        background: #a1d6fc;
        color: #204a87;
        cursor: pointer;
    }

    .fingerprint-div {
        width: 160px;
        height: 240px;
        border: 5px solid #42adf4;
    }

    .fingerprint-div > img {
        width: 100%;
        height: 100%;
    }

    .disclaimer {
        font-size: 10px;
        color: #204a87;
    }

    .title {
        vertical-align: middle;
    }
</style>
<body>
    <script src="{{ asset('/jquery-3.5.0.min.js') }}"></script>
    <script src="{{ asset('/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('/es6-shim.js') }}"></script>
    <script src="{{ asset('/websdk.client.bundle.min.js') }}"></script>
    <script src="{{ asset('/fingerprint.sdk.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-base64@2.5.2/base64.min.js"></script>
    <script src="{{ asset('/custom.js') }}"></script>

    <div class="container">
        <div id="controls" class="row justify-content-center mx-5 mx-sm-0 mx-lg-5">
            <div class="col-sm mb-2 ml-sm-5">
                <button id="createEnrollmentButton" type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#createEnrollment" onclick="beginEnrollment()">Create Enrollment</button>
            </div>
            <div class="col-sm mb-2 mr-sm-5">
                <button id="verifyIdentityButton" type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#verifyIdentity" onclick="beginIdentification()">Verify Identity</button>
            </div>
        </div>
    </div>
</body>

<section>
    <div class="modal fade" id="createEnrollment" data-backdrop="static" tabindex="-1" aria-labelledby="createEnrollmentTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title my-text my-pri-color" id="createEnrollmentTitle">Create Enrollment</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearCapture()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" onsubmit="return false">
                        <div id="enrollmentStatusField" class="text-center">
                            <!--Enrollment Status will be displayed Here-->
                        </div>
                        <div class="form-row mt-3">
                            <div class="col mb-3 mb-md-0 text-center">
                                <label for="enrollReaderSelect" class="my-text7 my-pri-color">Choose Fingerprint Reader</label>
                                <select name="readerSelect" id="enrollReaderSelect" class="form-control" onclick="beginEnrollment()">
                                    <option selected>Select Fingerprint Reader</option>
                                </select>
                            </div>
                        </div>
                        <div class="fingerprint-div" id="fff">
							<img id="fingerprint" />
						</div>
                        <div class="form-row mt-2">
                            <div class="col mb-3 mb-md-0 text-center">
                                <label for="userID" class="my-text7 my-pri-color">Specify UserID</label>
                                <input id="userID" type="text" class="form-control" value="1" required>
                            </div>
                        </div>
                        <div class="form-row mt-1">
                            <div class="col text-center">
                                <p class="my-text7 my-pri-color mt-3">Capture Index Finger</p>
                            </div>
                        </div>
                        <div id="indexFingers" class="form-row justify-content-center">
                            <div id="indexfinger1" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                            <div id="indexfinger2" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                            <div id="indexfinger3" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                            <div id="indexfinger4" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                        </div>
                        <div class="form-row mt-1">
                            <div class="col text-center">
                                <p class="my-text7 my-pri-color mt-5">Capture Middle Finger</p>
                            </div>
                        </div>
                        <div id="middleFingers" class="form-row justify-content-center">
                            <div id="middleFinger1" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                            <div id="middleFinger2" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                            <div id="middleFinger3" class="col mb-3 mb-md-0 text-center">
                                <span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                            <div id="middleFinger4" class="col mb-3 mb-md-0 text-center" value="true">
                                <span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                        </div>
                        <div class="form-row m-3 mt-md-5 justify-content-center">
                            <div class="col-4">
                                <button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="beginCapture()">Start Capture</button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="serverEnroll()">Enroll</button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-secondary btn-outline-warning btn-block my-text-button py-1 border-0" type="button" onclick="clearCapture()">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-row">
                        <div class="col">
                            <button class="btn btn-secondary my-text8 btn-outline-danger border-0" type="button" data-dismiss="modal" onclick="clearCapture()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <!--Verify Identity Section-->
    <div id="verifyIdentity" class="modal fade" data-backdrop="static" tabindex="-1" aria-labelledby="verifyIdentityTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title my-text my-pri-color" id="verifyIdentityTitle">Identity Verification</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearCapture()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" onsubmit="return false">
                        <div id="verifyIdentityStatusField" class="text-center">
                            <!--verifyIdentity Status will be displayed Here-->
                        </div>
                        <div class="form-row mt-3">
                            <div class="col mb-3 mb-md-0 text-center">
                                <label for="verifyReaderSelect" class="my-text7 my-pri-color">Choose Fingerprint Reader</label>
                                <select name="readerSelect" id="verifyReaderSelect" class="form-control" onclick="beginIdentification()">
                                    <option selected>Select Fingerprint Reader</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row mt-4">
                            <div class="col mb-md-0 text-center">
                                <label for="userIDVerify" class="my-text7 my-pri-color m-0">Specify UserID</label>
                                <input type="text" id="userIDVerify" class="form-control mt-1" required>
                            </div>
                        </div>
                        <div class="form-row mt-3">
                            <div class="col text-center">
                                <p class="my-text7 my-pri-color mt-1">Capture Verification Finger</p>
                            </div>
                        </div>
                        <div id="verificationFingers" class="form-row justify-content-center">
                            <div id="verificationFinger" class="col mb-md-0 text-center">
                                <span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
                            </div>
                        </div>
                        <div class="form-row mt-3" id="userDetails">
                            <!--this is where user details will be displayed-->
                        </div>
                        <canvas id="qualityInputBox"></canvas>
                        <div class="form-row m-3 mt-md-5 justify-content-center">
                            <div class="col-4">
                                <button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="captureForIdentify()">Start Capture</button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="serverIdentify()">Identify</button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-secondary btn-outline-warning btn-block my-text-button py-1 border-0" type="button" onclick="clearCapture()">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-row">
                        <div class="col">
                            <button class="btn btn-secondary my-text8 btn-outline-danger border-0" type="button" data-dismiss="modal" onclick="clearCapture()">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</html>
