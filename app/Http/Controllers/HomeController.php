<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Storage, Str, Auth, URL;

class HomeController extends Controller
{
    public function index() 
    {
        return view('front.home.index');
    }

    public function dashboard() 
    {
        return view('front.home.dashboard');
    }

    // handle fetch all eamployees ajax request
    public function fetchAll() {
        $imgs = Image::where('user_id', Auth::user()->id)->get();
        $output = '';
        if ($imgs->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle" id="image-manager">
            <thead>
              <tr>
                <th>Image</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($imgs as $img) {
                $image = url('storage/'.$img->image);
                $output .= '<tr>
                <td><img src="' . $image . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>
                  <a href="#" id="' . $img->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editImageModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $img->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new image ajax request
    public function store(Request $request) {
        try{
            $file = $request->file('image');
            $imageName = Str::random(32).'.'.$file->getClientOriginalExtension();
            $path = 'image/'.$imageName;
            Storage::disk('public')->put($path, file_get_contents($request->image));
            $data = Image::create([
                    'image' => $path,
                    'user_id' => Auth::user()->id
            ]);
            return response()->json([
                    'message' => 'image created successfully',
                    'status' => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'something went really wrong',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    // handle edit an image ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $img = Image::find($id);
        if (!$img) {
            return response()->json([
                'message' => 'no image data found',
                'status' => 404,
            ], 404);
        } else {
            $image = url('storage/'.$img->image);
            return response()->json([
                'message' => 'no image data found',
                'id' => $id,
                'image' => $image,
                'status' => 200,
            ], 200);
        }
    }

    // handle update an image ajax request
    public function update(Request $request) {
        try {
            $fileName = '';
            $img = Image::find($request->img_id);
            if (!$img) {
                return response()->json([
                    'message' => 'no image data found',
                    'status' => 404,
                ], 404);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $storage = Storage::disk('public');
                if($storage->exists($img->image)) {
                    $storage->delete($img->image);
                }
                $imageName = Str::random(32).'.'.$file->getClientOriginalExtension();
                $path = 'image/'.$imageName;
                Storage::disk('public')->put($path, file_get_contents($request->image));
                $img->image = $path;
                $img->save();
                return response()->json([
                        'message' => 'image update successfully',
                        'status' => 200,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'not image update successfully',
                    'status' => 300,
                ], 300);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'something went really wrong',
                'error' =>$e->getMessage(),
            ], 500);
        }
        
    }

    // handle delete an image ajax request
    public function delete(Request $request) {
        try{
            $id = $request->id;
            $image = Image::find($id);
            if (!$image) {
                return response()->json([
                    'status' => 404,
                    'message' => 'no image found',
                ], 404);
            }
            
            $storage = Storage::disk('public');
            if($storage->exists($image->image)) {
                $storage->delete($image->image);
            }
            $image->delete();

            return response()->json([
                'status' => 200,
                'message' => 'image deleted successfully',
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'something went really wrong',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    // handle check an exoh ajax request
    public function check(Request $request) 
    {
        try{
            $check = $request->check;
            $checkfirst = $request->checkfirst;
            $checksecond = $request->checksecond;
            $fstr = substr_count($check, $checkfirst);
            $sstr = substr_count($check, $checksecond);
            if($fstr == 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'no letter found in first checking letter',
                ], 404);
            } elseif($sstr == 0) {
                return response()->json([
                    'status' => 404,
                    'message' => 'no letter found in second checking letter',
                ], 404);
            } else {
                if($fstr == $sstr) {
                    return response()->json([
                        'status' => 200,
                        'message' => true,
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => false,
                    ], 200);
                }
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'something went really wrong',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }
}
