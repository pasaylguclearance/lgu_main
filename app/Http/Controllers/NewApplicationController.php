<?php

namespace App\Http\Controllers;

use App\NewApplication;
use App\Application;
use App\Purpose;
use App\Municipality;
use App\Religion;
use App\Nationality;
use App\Renewal;
use Auth;
use Session;
use File;
use Carbon\Carbon;

use Illuminate\Http\Request;

class NewApplicationController extends Controller
{
    public function index()
    {
        $purposes = Purpose::orderBy('id')->get();
        $municipalities = Municipality::orderBy('id')->get();
        $religions = Religion::orderBy('id')->get();
        $nationalities = Nationality::orderBy('id')->get();
        $selectedApplicant = null;
        $selectedApplicationRecord = null;
        $latestRenewal = null;
        $renewalHistory = collect();
        $requestedTab = request('tab');

        // Opening plain /new_application means start a fresh transaction context.
        if (!request()->filled('id') && !$requestedTab) {
            session()->forget('active_new_application_id');
        }

        if (request()->filled('id')) {
            $selectedApplicant = NewApplication::where('id', request('id'))->first();
            if ($selectedApplicant) {
                session(['active_new_application_id' => $selectedApplicant->id]);
                $selectedApplicationRecord = Application::where('new_application_id', $selectedApplicant->id)->orderBy('id', 'desc')->first();
                $latestRenewal = Renewal::where('application_id', $selectedApplicant->id)->orderBy('id', 'desc')->first();
                $renewalHistory = Renewal::where('application_id', $selectedApplicant->id)->orderBy('id', 'desc')->get();
            }
        } elseif (in_array($requestedTab, ['other', 'printing', 'renewal']) && session()->has('active_new_application_id')) {
            $selectedApplicant = NewApplication::where('id', session('active_new_application_id'))->first();
            if ($selectedApplicant) {
                $selectedApplicationRecord = Application::where('new_application_id', $selectedApplicant->id)->orderBy('id', 'desc')->first();
                $latestRenewal = Renewal::where('application_id', $selectedApplicant->id)->orderBy('id', 'desc')->first();
                $renewalHistory = Renewal::where('application_id', $selectedApplicant->id)->orderBy('id', 'desc')->get();
            }
        }

        return view('backend.pages.application.new_application', compact('purposes', 'municipalities', 'religions', 'nationalities', 'selectedApplicant', 'selectedApplicationRecord', 'latestRenewal', 'renewalHistory'));
    }

    public function store(Request $request)
    {
        $application_record = $request->validate([
            'selected_applicant_id' => ['nullable', function ($attribute, $value, $fail) {
                if ($value !== null && $value !== '' && !NewApplication::where('id', $value)->exists()) {
                    $fail('The selected applicant was not found.');
                }
            }],
            'derogatory_values' => ['nullable', 'max:4000'],
            'application_no' => [ 'max:250'],
            'or_no' => [ 'max:250'],
            'cedula_no' => [ 'max:250'],
            'issued_at' => [ 'max:250'],
            'issued_date' => [ 'max:250'],
            'issued_or_date' => [ 'max:250'],
            'firstname' => [ 'required', 'max:250'],
            'middlename' => [ 'max:250'],
            'lastname' => [ 'required', 'max:250'],
            'suffix' => [ 'max:250'],
            'purpose_id' => [ 'required', 'max:250'],
            'application_type' => [ 'max:250'],
            'house_no' => [ 'required', 'max:250'],
            'street' => [ 'required', 'max:250'],
            'barangay' => [ 'required', 'max:250'],
            'municipality_id' => [ 'required', 'max:250'],
            'province' => [ 'required', 'max:250'],
            'country' => [ 'required', 'max:250'],
            'birthdate' => [ 'required', 'max:250'],
            'birth_place' => [ 'required', 'max:250'],
            'gender' => [ 'required', 'max:250'],
            'nationality_id' => [ 'required', 'max:250'],
            'civil_status' => [ 'required', 'max:250'],
            'religion_id' => [ 'required', 'max:250'],
            'height' => [ 'max:250'],
            'weight' => [ 'max:250'],
            'hair_color' => [ 'max:250'],
            'eye_color' => [ 'max:250'],
            'contact_number' => [ 'nullable', 'regex:/^(?:\\+63|0)9\\d{9}$/'],
            'mole' => [ 'max:250'],
            'scar' => [ 'max:250'],
            'tattoo' => [ 'max:250'],
            'birthmark' => [ 'max:250'],
            'harelip' => [ 'max:250'],
            'skin_tag' => [ 'max:250'],
            'occupation_id' => [ 'max:250'],
            'occupation' => [ 'max:250'],
            'employer' => [ 'max:250'],
            'employer_address' => [ 'max:250'],
            'blood_type' => [ 'max:250'],
            'contact_person' => [ 'max:250'],
            'contact_no' => [ 'max:250'],
            'contact_address' => [ 'max:250'],
            'finger_print' => [ 'max:250'],
            'picture' => [ 'max:250'],
            'signature' => [ 'max:250'],
            'status' => [ 'max:250'],
            'reference_num' => [ 'max:250'],
            'type' => [ 'max:250'],
            'stage' => [ 'max:250']
        ], [
            'contact_number.regex' => 'Contact number must be a valid PH mobile number (e.g., 09123456789 or +639123456789).',
        ]);

        $selectedApplicantId = $request->input('selected_applicant_id');
        if (!empty($selectedApplicantId)) {
            $existingApplicant = NewApplication::find($selectedApplicantId);
            if ($existingApplicant) {
                // Keep updates resilient for fields not shown in current form.
                $payload = $request->all();
                $payload['height'] = $request->input('height', $existingApplicant->height ?? '');
                $payload['weight'] = $request->input('weight', $existingApplicant->weight ?? '');
                $payload['hair_color'] = $request->input('hair_color', $existingApplicant->hair_color ?? '');
                $payload['eye_color'] = $request->input('eye_color', $existingApplicant->eye_color ?? '');

                // Preserve generated identifiers on update.
                unset($payload['application_no'], $payload['ucid'], $payload['selected_applicant_id']);

                $existingApplicant->update($payload);
                session(['active_new_application_id' => $existingApplicant->id]);

                $latestApplication = Application::where('new_application_id', $existingApplicant->id)
                    ->orderBy('id', 'desc')
                    ->first();
                $derogatoryValue = $request->input('derogatory_values', 'NO DEROGATORY RECORDS FOUND');
                if ($latestApplication) {
                    $latestApplication->derogatory = $derogatoryValue;
                    // Keep legacy field in sync for screens still reading `finding`.
                    $latestApplication->finding = $derogatoryValue;
                    $latestApplication->save();
                } else {
                    // Some legacy records may have applicant rows without an application record yet.
                    $newApplicationRow = new Application;
                    $newApplicationRow->new_application_id = $existingApplicant->id;
                    $newApplicationRow->type = 'NEW';
                    $newApplicationRow->date = Carbon::now();
                    $newApplicationRow->derogatory = $derogatoryValue;
                    $newApplicationRow->finding = $derogatoryValue;
                    $newApplicationRow->save();
                }

                return redirect(url('new_application?tab=other&id='.$existingApplicant->id))
                    ->with('success', 'Applicant updated. Continue with other details.');
            }
        }

        // Number sequence must consider ALL rows, not just the ones this account may see.
        $last_id = NewApplication::unrestricted()->orderBy('id', 'desc')->first();
        $id = ($last_id === NULL) ? $code = 1 : $code = $last_id->id + 1;
        $ucid = 'PSY-'.Carbon::today()->format('mdY').'-'. str_pad($code + 2000, 7, '0', STR_PAD_LEFT);
        $code = 'PC-'. str_pad($code, 7, '0', STR_PAD_LEFT);
        $request->request->add(['application_no' => $code]);
        $request->request->add(['ucid' => $ucid]);
        // Keep inserts resilient for required DB fields when older forms omit them.
        $request->request->add(['height' => $request->input('height', '')]);
        $request->request->add(['weight' => $request->input('weight', '')]);
        $request->request->add(['hair_color' => $request->input('hair_color', '')]);
        $request->request->add(['eye_color' => $request->input('eye_color', '')]);
        $request->request->add(['gender' => $request->input('gender', '')]);
        $request->request->add(['nationality_id' => $request->input('nationality_id', '')]);


        
        $new_application = NewApplication::create($request->all());

        // SAVE ON APPLICATION TABLE

        $application = new Application;
        $application->new_application_id = $new_application->id;
        $application->type = 'NEW';
        $application->date = Carbon::now();
        $application->derogatory = $request->input('derogatory_values', 'NO DEROGATORY RECORDS FOUND');
        $application->finding = $application->derogatory;
        $application->save();
        session(['active_new_application_id' => $new_application->id]);

        return redirect(url('new_application?tab=other&id='.$new_application->id))->with('success','Applicant saved. Continue with other details.');

    }

    public function lookup(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['records' => []]);
        }

        $records = NewApplication::with(['purpose', 'municipality', 'nationality', 'religion'])
            ->where(function ($query) use ($q) {
                $parts = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);
                $query->where('application_no', 'like', '%'.$q.'%')
                    ->orWhere('ucid', 'like', '%'.$q.'%')
                    ->orWhere('firstname', 'like', '%'.$q.'%')
                    ->orWhere('middlename', 'like', '%'.$q.'%')
                    ->orWhere('lastname', 'like', '%'.$q.'%')
                    ->orWhereRaw("CONCAT_WS(' ', firstname, middlename, lastname) LIKE ?", ['%'.$q.'%'])
                    ->orWhereRaw("CONCAT_WS(' ', firstname, lastname) LIKE ?", ['%'.$q.'%'])
                    ->orWhereRaw("CONCAT_WS(', ', lastname, CONCAT_WS(' ', firstname, middlename)) LIKE ?", ['%'.$q.'%']);

                if (!empty($parts)) {
                    $query->orWhere(function ($nameQuery) use ($parts) {
                        foreach ($parts as $part) {
                            $nameQuery->where(function ($pieceQuery) use ($part) {
                                $pieceQuery->where('firstname', 'like', '%'.$part.'%')
                                    ->orWhere('middlename', 'like', '%'.$part.'%')
                                    ->orWhere('lastname', 'like', '%'.$part.'%');
                            });
                        }
                    });
                }
            })
            ->orderBy('id', 'desc')
            ->limit(15)
            ->get();

        $payload = $records->map(function ($a) {
            $latestDerogatory = Application::where('new_application_id', $a->id)->orderBy('id', 'desc')->value('derogatory');
            $latestFinding = Application::where('new_application_id', $a->id)->orderBy('id', 'desc')->value('finding');
            return [
                'id' => $a->id,
                'application_no' => $a->application_no,
                'ucid' => $a->ucid,
                'application_type' => $a->application_type,
                'purpose_id' => $a->purpose_id,
                'issued_at' => $a->issued_at,
                'or_no' => $a->or_no,
                'issued_or_date' => $a->issued_or_date,
                'cedula_no' => $a->cedula_no,
                'issued_date' => $a->issued_date,
                'firstname' => $a->firstname,
                'middlename' => $a->middlename,
                'lastname' => $a->lastname,
                'suffix' => $a->suffix,
                'house_no' => $a->house_no,
                'street' => $a->street,
                'barangay' => $a->barangay,
                'municipality_id' => $a->municipality_id,
                'province' => $a->province,
                'country' => $a->country,
                'birthdate' => $a->birthdate,
                'birth_place' => $a->birth_place,
                'gender' => $a->gender,
                'nationality_id' => $a->nationality_id,
                'civil_status' => $a->civil_status,
                'religion_id' => $a->religion_id,
                'contact_number' => $a->contact_number,
                'derogatory_values' => $latestDerogatory ?: ($latestFinding ?: 'NO DEROGATORY RECORDS FOUND'),
                'display' => trim(($a->application_no ?: 'N/A').' | '.$a->lastname.', '.$a->firstname.' '.$a->middlename.' | '.$a->birthdate),
            ];
        });

        return response()->json(['records' => $payload]);
    }

    public function lookupById($id)
    {
        $a = NewApplication::with(['purpose', 'municipality', 'nationality', 'religion'])
            ->where('id', $id)
            ->first();

        if (!$a) {
            return response()->json(['record' => null], 404);
        }

        $record = [
            'id' => $a->id,
            'application_no' => $a->application_no,
            'ucid' => $a->ucid,
            'application_type' => $a->application_type,
            'purpose_id' => $a->purpose_id,
            'issued_at' => $a->issued_at,
            'or_no' => $a->or_no,
            'issued_or_date' => $a->issued_or_date,
            'cedula_no' => $a->cedula_no,
            'issued_date' => $a->issued_date,
            'firstname' => $a->firstname,
            'middlename' => $a->middlename,
            'lastname' => $a->lastname,
            'suffix' => $a->suffix,
            'house_no' => $a->house_no,
            'street' => $a->street,
            'barangay' => $a->barangay,
            'municipality_id' => $a->municipality_id,
            'province' => $a->province,
            'country' => $a->country,
            'birthdate' => $a->birthdate,
            'birth_place' => $a->birth_place,
            'gender' => $a->gender,
            'nationality_id' => $a->nationality_id,
            'civil_status' => $a->civil_status,
            'religion_id' => $a->religion_id,
            'contact_number' => $a->contact_number,
            'derogatory_values' =>
                (Application::where('new_application_id', $a->id)->orderBy('id', 'desc')->value('derogatory')
                ?: Application::where('new_application_id', $a->id)->orderBy('id', 'desc')->value('finding'))
                ?: 'NO DEROGATORY RECORDS FOUND',
        ];

        return response()->json(['record' => $record]);
    }

    public function renewalStore(Request $request)
    {
        $validated = $request->validate([
            'application_id' => ['required', 'exists:new_applications,id'],
            'or_no' => ['nullable', 'max:250'],
            'issued_or_date' => ['nullable', 'date'],
            'cedula_no' => ['nullable', 'max:250'],
            'issued_date' => ['nullable', 'date'],
            'date_renew' => ['required', 'date'],
            'date_expiry' => ['required', 'date'],
        ]);

        $applicant = NewApplication::findOrFail($validated['application_id']);

        Renewal::create([
            'application_id' => $validated['application_id'],
            'or_no' => $validated['or_no'] ?? null,
            'issued_or_date' => $validated['issued_or_date'] ?? null,
            'cedula_no' => $validated['cedula_no'] ?? null,
            'issued_date' => $validated['issued_date'] ?? null,
            'date_renew' => $validated['date_renew'],
            'date_expiry' => $validated['date_expiry'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        session(['active_new_application_id' => $applicant->id]);

        return redirect(url('new_application?tab=printing&id='.$applicant->id))
            ->with('success', 'Renewal saved. You can now print the renewed application.');
    }

    public function renewalHistory(Request $request)
    {
        $applicationId = $request->input('application_id');
        if (!$applicationId) {
            return response()->json(['records' => []]);
        }

        $records = Renewal::where('application_id', $applicationId)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($renewal) {
                return [
                    'id' => $renewal->id,
                    'date_renew' => $renewal->date_renew,
                    'date_expiry' => $renewal->date_expiry,
                    'or_no' => $renewal->or_no,
                    'issued_or_date' => $renewal->issued_or_date,
                    'cedula_no' => $renewal->cedula_no,
                    'issued_date' => $renewal->issued_date,
                    'created_at' => $renewal->created_at ? $renewal->created_at->format('Y-m-d H:i:s') : null,
                ];
            });

        return response()->json(['records' => $records]);
    }

    public function edit($id)
    {
        $fees = Fee::where('id', $id)->orderBy('id')->firstOrFail();
        return response()->json(compact('fees'));
    }

    public function update(Request $request, $id)
    {
        Fee::find($id)->update($request->all());
        return redirect()->back()->with('success','Successfully Updated');
    }

    public function destroy($id)
    {
        $fee_destroy = Fee::find($id);
        $fee_destroy->delete();
        return redirect()->back()->with('success','Successfully Deleted!');
    }

    /**
     * Decode a browser data-URI (camera snapshot, cropped upload, signature pad,
     * fingerprint SDK) and write it to public/img/{$folder}/{$applicantId}/{$filename}.png.
     *
     * The image is validated and re-encoded through GD so the file on disk is a
     * real PNG regardless of what the browser sent (webcam.js sends JPEG) — every
     * consumer of these files hard-codes the .png extension.
     *
     * @return string|null  error message, or null on success
     */
    private function storeDataUriAsPng($dataUri, $folder, $applicantId, $filename)
    {
        if (!is_string($dataUri) || !preg_match('#^data:image/[\w.+-]+;base64,(.+)$#s', $dataUri, $m)) {
            return 'No image captured. Please take a snapshot or upload a photo first.';
        }

        $bytes = base64_decode($m[1], true);
        if ($bytes === false || $bytes === '') {
            return 'Unable to decode the image data.';
        }

        $directory = public_path('img/'.$folder.'/'.$applicantId);
        if (!File::isDirectory($directory) && !File::makeDirectory($directory, 0777, true, true)) {
            return 'Unable to create the image folder on the server.';
        }

        $path = $directory.DIRECTORY_SEPARATOR.$filename.'.png';

        if (function_exists('imagecreatefromstring')) {
            $image = @imagecreatefromstring($bytes);
            if ($image === false) {
                return 'The file is not a valid image.';
            }
            imagesavealpha($image, true);
            $written = imagepng($image, $path);
            imagedestroy($image);
        } else {
            $written = file_put_contents($path, $bytes) !== false;
        }

        return $written ? null : 'Failed to write the image file on the server.';
    }

    /**
     * Shared handler for the four base64 capture endpoints. Writes the file
     * first, then records the filename on the applicant, so a failed write never
     * leaves the record pointing at a missing image.
     */
    private function saveCapture(Request $request, $folder, $column, $withExtension = false)
    {
        $data = $request->all();

        if (empty($data['id'])) {
            return response()->json(['success' => false, 'message' => 'Invalid request'], 422);
        }

        $record = NewApplication::find($data['id']);
        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Applicant not found'], 404);
        }

        $filename = date('YmdHis');
        $error = $this->storeDataUriAsPng($data['picture'] ?? null, $folder, $record->id, $filename);
        if ($error !== null) {
            return response()->json(['success' => false, 'message' => $error], 422);
        }

        session(['active_new_application_id' => $record->id]);
        $record->{$column} = $withExtension ? $filename.'.png' : $filename;
        if (!$record->save()) {
            return response()->json(['success' => false, 'message' => 'Failed to update the applicant record'], 500);
        }

        return response()->json(['success' => true]);
    }

    public function picture(Request $request) {
        return $this->saveCapture($request, 'application_picture', 'picture');
    }

    public function captureSignature(Request $request)
    {
        // signature column stores the full filename (with .png); the others do not
        return $this->saveCapture($request, 'signature', 'signature', true);
    }

    public function fingerprint_right_show(Request $request){
        $applicants = NewApplication::where('status', '!=', 'PAID')->orderBy('id', 'desc')->get();
        return view('backend.pages.application.applicant_detail', compact('applicants'));
    }

    public function fingerprint_right(Request $request){
        return $this->saveCapture($request, 'fingerprint_right', 'finger_print_right');
    }

    public function fingerprint_left_show(Request $request) {
        $applicants = NewApplication::where('status', '!=', 'PAID')->orderBy('id', 'desc')->get();
        return view('backend.pages.application.applicant_detail', compact('applicants'));
    }

    public function fingerprint_left(Request $request){
        return $this->saveCapture($request, 'fingerprint_left', 'finger_print_left');
    }

    public function signature_show(Request $request){
        $applicants = NewApplication::where('status', '!=', 'PAID')->orderBy('id', 'desc')->get();
        return view('backend.pages.application.applicant_detail', compact('applicants'));
    }

    public function signature_update(Request $request, $id){
        // Not the `image` rule: on this Laravel 5.8 / Symfony combination
        // guessExtension() returns "jpg", which `image` does not accept.
        $request->validate([
            'picture' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp|max:10240',
        ], [
            'picture.required' => 'Please choose a signature image to upload.',
            'picture.mimes'    => 'The signature must be a JPG, PNG, GIF, BMP or WEBP image.',
            'picture.max'      => 'The signature image must be smaller than 10 MB.',
        ]);

        $record = NewApplication::findOrFail($id);

        $filename = date('YmdHis');
        $dataUri = 'data:image/png;base64,'.base64_encode(file_get_contents($request->file('picture')->getRealPath()));
        $error = $this->storeDataUriAsPng($dataUri, 'signature', $record->id, $filename);
        if ($error !== null) {
            return redirect()->back()->withErrors(['picture' => $error]);
        }

        $record->signature = $filename.'.png';
        $record->save();
        session(['active_new_application_id' => $record->id]);

        return redirect(url('new_application?tab=other&id='.$record->id))->with('success','Signature saved.');
    }


    public function signature(Request $request){
        $data = $request->all();
        $filename= date("Ymdhmis");
        $update = NewApplication::find($data['id']);
        session(['active_new_application_id' => $data['id']]);
        $prev = $update->finger_print;
        $update->finger_print = $filename;

        $destination  = 'img/fingerprint/'.$data['id'].'/'.$update->finger_print.'.png';
        $destination_temp  = 'img/fingerprint/'.$data['id'].'/';
        File::makeDirectory(public_path().'/'.'img/fingerprint/'.$data['id'].'/',0777,true);
        $image_parts = explode(";base64,", $data['picture']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $result = file_put_contents($destination, $image_base64);
    }

    public function completeTransaction()
    {
        session()->forget('active_new_application_id');

        return redirect(url('new_application'))
            ->with('success', 'Transaction completed. You can process another applicant.');
    }
}
