<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiVideoController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Video::get()->toArray() , 200);
    }

    public function show($id): JsonResponse
    {
        $video = Video::find($id);
        if(!$video) {
            return response()->json(['status' => 'error', 'message' => 'Video not found!'], 404);
        }
        return response()->json($video->toArray(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            [
                'titulo'  =>  $request->titulo,
                'descricao' => $request->descricao,
                'url' => $request->url
            ],
            [
                'titulo'  => 'required|min:1',
                'descricao' => 'required|min:1',
                'url' => 'required|url',
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '; ';
            }
            return response()->json(['status' => 'error', 'message' => $errorMessage], 206);
        }

        $video = Video::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'url' => $request->url
        ]);
        return response()->json($video, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $video = Video::find($id);
        if(!$video) {
            return response()->json(['status' => 'error', 'message' => 'Video not found!'], 404);
        }
        $validator = Validator::make(
            [
                'titulo'  =>  $request->titulo,
                'descricao' => $request->descricao,
                'url' => $request->url
            ],
            [
                'titulo'  => 'required|min:1',
                'descricao' => 'required|min:1',
                'url' => 'required|url',
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '; ';
            }
            return response()->json(['status' => 'error', 'message' => $errorMessage], 206);
        }
        $video->fill($request->all());
        $video->save();

        return json_encode($video);
    }

    public function destroy($id): JsonResponse
    {
        $video = Video::find($id);
        if(!$video) {
            return response()->json(['status' => 'error', 'message' => 'Video not found!'], 404);
        }
        $video->delete();
        return response()->json(['status' => 'ok', 'message' => 'Video deleted with success!'], 200);
    }
}
