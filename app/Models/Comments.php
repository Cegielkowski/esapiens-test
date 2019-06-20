<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comments extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'post_id', 'type', 'content','coins','highlight'];

    /**
     * @param $userId
     * @param $time
     * @return mixed
     * Return the quantity of the last comments of the user
     */
    public static function getLastComments($userId, $time) {
        return Comments::where('user_id', $userId)
            ->where('created_at','>', $time)
            ->count();
    }

    /**
     * @param $userId
     * @param $postId
     * @return string
     *
     * Delete all comments from one user at the post
     */
    public static function deleteAllComments($userId, $postId) {
        Comments::where(['user_id' => $userId, 'post_id' => $postId])->forceDelete();
        return 'deleted all comments from this user on this post';
    }
}
