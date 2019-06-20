<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use App\Models\User;
use App\Models\Comments;
use Illuminate\Http\Request;
use App\Libraries\Helper;

class CommentController extends Controller
{
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
            'coins' => 'required|integer',
            'content' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $lessOneMinute = date('Y-m-d H:i:s', mktime(date('H'),date('i')-1,date('s'), date('m'),date('d'), date('Y')));
        $user = User::findOrFail($request->get('user_id'));
        $post = Posts::findOrFail($request->get('post_id'));
        $postOwner = User::findOrFail($post->user_id);
        $lastComments = Comments::getLastComments($user->id, $lessOneMinute);

        if( $lastComments >= 3) {
            return $this->response->array(['error' => 'You can not comment more than 3 times per minute'])->setStatusCode(401);
        }

        $coins = $request->get('coins');
        $tax = $highlight = 0;
        if(!empty($coins)) {
            if($user->coins < $coins) {
                return $this->response->array(['error' => 'You do not have enough coins'])->setStatusCode(401);
            }
            $user->coins -= $coins;
            $user->save();
            $highlight = 1;
            $tax = Helper::applySystemTax($coins);
        } else {
            if ($postOwner->id != $user->id) {
                if($user->subcriber == 0 && $postOwner->subcriber == 0) {
                    return $this->response->array(['error' => 'You are not a subscriber, so you can not comment on a post from an another not subscriber'])->setStatusCode(401);
                }
            }
        }

        $attributes = [
            'user_id' => $request->get('user_id'),
            'post_id' => $request->get('post_id'),
            'type' => $request->get('type'),
            'content' => $request->get('content'),
            'coins' => $coins,
            'highlight'=> $highlight
        ];

        $comment = Comments::create($attributes);

        $result['data'] = [
            'comment' => $comment,
        ];

        Helper::registerTransaction($user->id, $coins, $tax, $comment->id);
        Helper::notify($user->id, $postOwner->id, $comment->id,$post->id);

        return $this->response->array($result)->setStatusCode(201);
    }

    public function delete(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = User::findOrFail($request->get('user_id'));
        $comment = Comments::findOrFail($request->get('id'));
        $post = Posts::findOrFail($comment->post_id);
        $postOwner = ($user->id == $post->user_id) ? true : false;
        $commentOwner = ($user->id == $comment->user_id) ? true : false;

        if (!$postOwner && !$commentOwner) {
            return $this->response->array(['error' => 'You are not the owner of the post or comment to delete this'])->setStatusCode(401);
        }

        if(empty($request->get('delete_all'))) {
            $comment->forceDelete();
        } else {
            if(!$postOwner) {
                return $this->response->array(['error' => 'You are not the owner of the post to delete all comments from this user'])->setStatusCode(401);
            }

            $comment = Comments::deleteAllComments($comment->user_id, $comment->post_id);
        }

        $result['data'] = [
            'comment' => $comment,
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}