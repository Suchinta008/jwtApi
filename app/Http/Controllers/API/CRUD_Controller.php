<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;// for v3
use Intervention\Image\Facades\Image;//for v2
use Illuminate\Support\Facades\Validator;
use App\Models\API\Data;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Facades\JWTAuth;


class CRUD_Controller extends Controller
{

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $data = Data::latest()->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found.'
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully.',
                'data' => $data
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token is missing'], 401);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'fullName' => 'required|string|min:3|max:255',
                'languages' => 'required|array|min:1',
                'languages.*' => 'in:English,Hindi,Bengali',
                'gender' => 'required|in:Male,Female,Other',
                'country' => 'required|string',
                'message' => 'nullable|string|max:1000',
                'dob' => 'required|date|before_or_equal:today',
                'phone' => ['required', 'digits:10'],
                'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            ], [
                // Full Name
                'fullName.required' => 'Full Name is required.',
                'fullName.min' => 'Full Name must be at least 3 characters.',
                'fullName.max' => 'Full Name must not exceed 255 characters.',

                // Languages
                'languages.required' => 'Please select at least one language.',
                'languages.min' => 'You must choose at least one language.',
                'languages.*.in' => 'Selected language is invalid.',

                // Gender
                'gender.required' => 'Please select your gender.',
                'gender.in' => 'Selected gender is invalid.',

                // Country
                'country.required' => 'Please select your country.',

                // Message
                'message.max' => 'Message should not exceed 1000 characters.',

                // DOB
                'dob.required' => 'Date of birth is required.',
                'dob.before_or_equal' => 'Date of birth cannot be in the future.',

                // Phone
                'phone.required' => 'Phone number is required.',
                'phone.digits' => 'Phone number must be exactly 10 digits.',

                // Photo
                'photo.required' => 'Please upload a photo.',
                'photo.image' => 'The uploaded file must be an image.',
                'photo.mimes' => 'Photo must be in jpeg, jpg, png, or webp format.',
                'photo.max' => 'Photo size must not exceed 2MB.',
            ]);


            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], );
            }

            //-------------------Check if phone already exists-------------------//
            $check_phone = Data::where('phone', $request->phone)->exists();
            if ($check_phone) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone number already exists.'
                ]);
            }


            try {
                // Compress and save photo
                $image = Image::make($request->file('photo')->getRealPath())
                    ->resize(300, 300, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });

                //If not folder is not created create itself
                $upload_file_path = public_path('uploads/photos');
                if (!file_exists($upload_file_path)) {
                    mkdir($upload_file_path, 0755, true);
                }

                $photo_name = time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $image->save($upload_file_path . '/' . $photo_name, 75);

                // Store in DB
                $data = new Data();
                $data->full_name = $request->fullName;
                $data->languages = implode(',', $request->languages);
                $data->gender = $request->gender;
                $data->country = $request->country;
                $data->message = $request->message;
                $data->photo = $photo_name;
                $data->dob = $request->dob;
                $data->phone = $request->phone;

                $data->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Data saved successfully!',
                    'data' => $data
                ]);

            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['error' => 'Token has expired'], 401);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['error' => 'Token is invalid'], 401);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['error' => 'Token is missing'], 401);
            } catch (\Throwable $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request method.'
            ], 405);
        }
    }

    public function show($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $data = Data::find($id);

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data not found.'
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data retrieved successfully.',
                'data' => $data,
                'image_path' => asset('uploads/photos/' . $data->photo)
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token is missing'], 401);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request,$id)
    {
        
        // ----------------- JWT Authentication -----------------
        try {

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found.'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['status' => false, 'message' => 'Token has expired.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => false, 'message' => 'Token is invalid.'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Token is missing.'], 401);
        }

        // ----------------- Check Request Method -----------------
        if (!$request->isMethod('post')) {
            return response()->json(['status' => false, 'message' => 'Invalid request method.'], 405);
        }

        try {
            // ----------------- Find Existing Data -----------------
            $data = Data::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Data not found.'], 404);
        }

        // ----------------- Validation Rules -----------------
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|min:3|max:255',
            'languages' => 'required|array|min:1',
            'languages.*' => 'in:English,Hindi,Bengali',
            'gender' => 'required|in:Male,Female,Other',
            'country' => 'required|string',
            'message' => 'nullable|string|max:1000',
            'dob' => 'required|date|before_or_equal:today',
            'phone' => ['required', 'digits:10'],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // ----------------- Check Phone Uniqueness -----------------
        $check_phone = Data::where('phone', $request->phone)
            ->where('id', '!=', $id)
            ->exists();
        if ($check_phone) {
            return response()->json(['status' => false, 'message' => 'Phone number already exists.'], 409);
        }

        try {
            // ----------------- If New Photo Uploaded -----------------
            if ($request->hasFile('photo')) {
                $image = Image::make($request->file('photo')->getRealPath())
                    ->resize(300, 300, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });

                $upload_file_path = public_path('uploads/photos');
                if (!file_exists($upload_file_path)) {
                    mkdir($upload_file_path, 0755, true);
                }

                // Delete old image if exists
                if (!empty($data->photo) && file_exists($upload_file_path . '/' . $data->photo)) {
                    unlink($upload_file_path . '/' . $data->photo);
                }

                // Generate UUID filename
                $photo_name = (string) Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();
                $image->save($upload_file_path . '/' . $photo_name, 75);

                $data->photo = $photo_name;
            }

            // ----------------- Update Other Fields -----------------
            $data->full_name = $request->fullName;
            $data->languages = implode(',', $request->languages);
            $data->gender = $request->gender;
            $data->country = $request->country;
            $data->message = $request->message;
            $data->dob = $request->dob;
            $data->phone = $request->phone;

            $data->save();

            return response()->json([
                'status' => true,
                'message' => 'Data updated successfully!',
                'data' => $data,
                'image_path' => asset('uploads/photos/' . $data->photo)
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }


        try {
            $data = Data::find($id);
            if (!$data) {
                return response()->json([
                    'type' => 'error',
                    'text' => 'Data not found.'
                ]);
            }

            // Delete photo from filesystem
            $photo_path = public_path('uploads/photos/' . $data->photo);
            if (file_exists($photo_path)) {
                unlink($photo_path);
            }

            // Delete record from DB
            $data->delete();

            return response()->json([
                'status' => true,
                'type' => 'success',
                'text' => 'Data deleted successfully.'
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Token is missing'], 401);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'text' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }




}

