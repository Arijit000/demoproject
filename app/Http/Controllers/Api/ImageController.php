<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Storage, Str;


class ImageController extends Controller
{
    public function index()
    {
        try{
            $image = Image::all();
            return response()->json([
                'message' => 'all image fetch successfully',
                'images' => $image,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went really wrong',
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $image = Image::find($id);
            if (!$image) {
                return response()->json([
                    'message' => 'no image found',
                ], 404);
            }
            return response()->json([
                'message' => 'image fetch successfully',
                'images' => $image,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went really wrong',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            $imageName = Str::random(32).'.'.$request->image->getClientOriginalExtension();
            $path = 'image/'.$imageName;
            Storage::disk('public')->put($path, file_get_contents($request->image));
            $data = Image::create([
                'image' => $path,
                'user_id' => $request->user()->id
            ]);
            return response()->json([
                'message' => 'image created successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went really wrong',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $image = Image::find($id);
            if (!$image) {
                return response()->json([
                    'message' => 'no image found',
                ], 404);
            }
            if($request->image) {
                $storage = Storage::disk('public');
                if($storage->exists($image->image)) {
                    $storage->delete($image->image);
                }
                $imageName = Str::random(32).'.'.$request->image->getClientOriginalExtension();
                $path = 'image/'.$imageName;
                Storage::disk('public')->put($path, file_get_contents($request->image));
                $image->image = $path;
                $image->save();

                return response()->json([
                    'message' => 'image update successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'not image update successfully',
                ], 300);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went really wrong',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try{
            $image = Image::find($id);
            if (!$image) {
                return response()->json([
                    'message' => 'no image found',
                ], 404);
            }
            
            $storage = Storage::disk('public');
            if($storage->exists($image->image)) {
                $storage->delete($image->image);
            }
            $image->delete();

            return response()->json([
                'message' => 'image deleted successfully',
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'something went really wrong',
                'error' =>$e->getMessage(),
            ], 500);
        }
    }

    public function check(Request $request) 
    {
        try{
            $this->validate($request, [
                'check' => 'required',
                'checkfirst' => 'required',
                'checksecond' => 'required',
            ]);
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
