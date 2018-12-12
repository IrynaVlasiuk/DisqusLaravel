<?php

namespace App\Http\Controllers\Comment;

use App\Comment;
use App\Rating;
use App\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('comments.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['message' => 'required']);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors(), "status" => 400]);
        } else {
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                return response()->json(["message" =>$e->getMessage(), "status" => $e->getCode()]);
            }

            $comment = Comment::create([
                'message' => $request->get('message'),
                'user_id' => Auth::user()->id,
                'parent_id' => $request->get('parent_id') ? $request->get('parent_id') : 0,
            ]);
            $comment->save();

            return response()->json(["message" => "Your comment was successfully added", "status" => 200]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $replies = DB::table('comments')
            ->leftJoin('ratings', 'comments.id', '=', 'ratings.comment_id')
            ->select('comments.id AS id', 'comments.message AS message', 'comments.updated_at AS updated_at','comments.user_id AS user_id', DB::raw("count(ratings.id) AS count"))
            ->where('comments.parent_id', $id)->groupBy('id','message','updated_at', 'user_id')
            ->get();
        return response()->json(["data" => $replies]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['message' => 'required']);
        if ($validator->fails()) {
            return response()->json(["message" => "The message field is required", "status" => 400]);
        } else {
            try {
                $message = $request->input('message');
                $comment = Comment::findOrFail($id);
                $comment->message = $message;
                $comment->save();
            } catch (Exception $e) {
                return response()->json(["message" => $e->getMessage(), "status" => 400]);
            }
            return response()->json(["message" => ["Your comment was successfully edited"], "status" => 200, "data" => $comment]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $comment = Comment::find($id);
            $comment->delete();
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage(), "status" => 400]);
        }
        return response()->json(["id" => $id, "status" => 200]);
    }
}
