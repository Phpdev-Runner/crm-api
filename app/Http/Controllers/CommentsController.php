<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreCommentPost;
use App\Http\Requests\UpdateCommentPost;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Role;

class CommentsController extends ApiController
{

    #region PROPERTIES
    private $commentTransformer;
    #endregion

    #region MAIN METHODS
    public function __construct(CommentTransformer $commentTransformer)
    {
        $this->commentTransformer = $commentTransformer;
    }

    public function storeComment(StoreCommentPost $request)
    {
        // AUTHORIZATION
        $this->authorize('create', Comment::class);

        $dataComment = [
            'user_id' => Auth::id(),
            'lead_id' => Input::get('lead_id'),
            'comment' => Input::get('comment')
        ];

        $comment = $this->saveComment($dataComment);

        if(isset($comment->id) && $comment->id >0){
            return $this->respondCreated("new comment successfully created!");
        }else{
            return $this->respondDataConflict("Due to unknown reason Lead was not saved!");
        }
    }

    public function editComment($id)
    {
        $comment = $this->findComment($id);

        if($comment == null){
            return $this->respondNoContent("Comment with ID {$id} does not exits!");
        }

        // AUTHORIZATION
        $this->authorize('view', $comment);

        $comment = $this->commentTransformer->transformOneModel($comment);

        return $this->respond($comment);
    }

    public function updateComment(UpdateCommentPost $request, $id)
    {
        $comment = $this->findComment($id);

        if($comment == null){
            return $this->respondNoContent("Comment with ID {$id} does not exits!");
        }

        // AUTHORIZATION
        $this->authorize('update', $comment);

        $dataComment = [
            'user_id' => Auth::id(),
            'comment' => Input::get('comment')
        ];

        $updateCommentStatus = $comment->update($dataComment);

        if($updateCommentStatus === true){
            return $this->respondUpdated("Comment for lead was updated!");
        }else{
            return $this->respondDataConflict("Due to unknown reason Comment was not updated!");
        }

    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);

        if($comment == null){
            return $this->respondNoContent("Comment with requested ID {$id} was not found!");
        }

        // AUTHORIZATION
        $this->authorize('delete', $comment);

        $comment->delete();
        return $this->respondDeleted("Comment with ID {$id} was successfully deleted!");

    }
    #endregion

    #region SERVICE METHODS
    private function saveComment($commentData)
    {
        $comment = Comment::create($commentData);
        return $comment;
    }

    private function findComment(int $id)
    {
        $comment = Comment::viewComment($id);
        return $comment;
    }
    #endregion
}
