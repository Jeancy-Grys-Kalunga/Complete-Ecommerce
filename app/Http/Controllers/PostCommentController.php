<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Notification;
use App\Models\User;
use App\Notifications\StatusNotification;
use App\Models\PostComment;
class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments=PostComment::getAllComments();
        return view('backend.comment.index')->with('comments',$comments);
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
        // return $request->all();
        $post_info=Post::getPostBySlug($request->slug);
        // return $post_info;
        $data=$request->all();
        $data['user_id']=$request->user()->id;
        // $data['post_id']=$post_info->id;
        $data['status']='active';
        // return $data;
        $status=PostComment::create($data);
        $user=User::where('name','Admin')->get();
        $details=[
            'title'=>"Nouveau commentaire",
            'actionURL'=>route('blog.detail',$post_info->slug),
            'fas'=>'fas fa-comment'
        ];
        Notification::send($user, new StatusNotification($details));
        if($status){
            request()->session()->flash('success','Merci pour votre commentaire');
        }
        else{
            request()->session()->flash('error','Erreur survenue lors de l\'envois de votre commentaire !!!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comments=PostComment::find($id);
        if($comments){
            return view('backend.comment.edit')->with('comment',$comments);
        }
        else{
            request()->session()->flash('error','Aucun commentaire trouvé !!!');
            return redirect()->back();
        }
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
        $comment=PostComment::find($id);
        if($comment){
            $data=$request->all();
            // return $data;
            $status=$comment->fill($data)->update();
            if($status){
                request()->session()->flash('success','Commentaire modifié avec succès !!!');
            }
            else{
                request()->session()->flash('error','Erreur survenue lors de la modificaftion de votre commentaire !!');
            }
            return redirect()->route('comment.index');
        }
        else{
            request()->session()->flash('error','Aucun commentaire trouvé !!');
            return redirect()->back();
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
        $comment=PostComment::find($id);
        if($comment){
            $status=$comment->delete();
            if($status){
                request()->session()->flash('success','Commentaire à ce poste est supprimé avec succès ');
            }
            else{
                request()->session()->flash('error','Erreur survenue lors de la suppression du commentaire à ce Post !!!');
            }
            return back();
        }
        else{
            request()->session()->flash('error','Aucun commentaire trouvé à ce POst !!!');
            return redirect()->back();
        }
    }
}
