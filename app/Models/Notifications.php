<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cache;

class Notifications extends Model
{
    use SoftDeletes;

    protected $fillable = ['notification_from', 'notification_to', 'comment_id', 'post_id'];

    /**
     * @param $userId
     * @param int $page
     * @return mixed
     *
     * This function will retrieve the notifications from the user
     */
    public function get($userId, $page = 1)
    {
        if (empty($page)) {
            $page = 1;
        }
        $expiration = 1/30; //Minutes that will be cached, in this case, it will be for 2 seconds
        $key = "notification_" . $userId . "_" . $page;
        $lessOneHour = date('Y-m-d H:i:s', mktime(date('H')-1,date('i'),date('s'), date('m'),date('d'), date('Y')));
        $where = " notification_to = $userId and viewed is null or notification_to = $userId and viewed = 1 and updated_at > '$lessOneHour' ";

        return Cache::remember($key,$expiration, function() use($where){
            return  $this->whereRaw($where)->paginate(15);
        });
    }

    public function viewed(array $userId)
    {
        return $this->whereIn('id', $userId)->update(array('viewed' => 1));
    }


}
