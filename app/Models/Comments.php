<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helper;
use Cache;


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


    public static function getCommentsByPostId($postId, $paginationSize = 20, $page = 1, $type = 'post_id')
    {
        Helper::fixNegativeNumbers($paginationSize, 20);
        Helper::fixNegativeNumbers($page, 1);
        $expiration = 1/30; //Minutes that will be cached, in this case, it will be for 2 seconds
        $key = "postcomments_" . $postId . "_" . $page;

        if ($type == 'user') {
            User::findOrFail($postId);
            $type = 'user_id';
        } else {
            Posts::findOrFail($postId);
            $type = 'post_id';
        }

        return Cache::remember($key,$expiration, function() use($paginationSize,$postId,$type) {
            return DB::table('comments AS c')
                ->selectRaw('c.user_id, c.id as comment_id, u.email as login, u.subscriber, c.highlight, c.created_at, c.content')
                ->join('posts AS p', 'p.id', '=', 'c.post_id')
                ->join('users AS u', 'u.id', '=', 'c.user_id')
                ->where('c.'.$type, $postId)
                ->orderByRaw('((c.updated_at + INTERVAL c.coins minute >  now() ) * c.coins) DESC')
                ->orderBy('c.created_at', 'desc')
                ->paginate($paginationSize);
        });
    }

}
