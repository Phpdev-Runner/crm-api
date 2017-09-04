<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 04.09.2017
 * Time: 11:38
 */

namespace App\Transformers;

use App\User;

class CommentTransformer extends Transformer
{

    public function transformMany($comment)
    {
		dd("not configured yet");

        return [

        ];
    }

    public function transformOne($comment)
    {
//		dd($comment);

        return [
            'id' => $comment['id'],
            'user_id'=> $comment['user_id'],
            'user' => User::getUserNameById($comment['user_id']),
            'lead_id' => $comment['lead_id'],
            'comment' => $comment['comment'],
            'updated_at' => $comment['updated_at']
        ];
    }
}