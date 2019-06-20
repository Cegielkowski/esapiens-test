<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Integer;

class Notifications extends Model
{
    use SoftDeletes;

    protected $fillable = ['notification_from', 'notification_to', 'comment_id', 'post_id'];

    public function get($userId)
    {
        $lessOneHour = date('Y-m-d H:i:s', mktime(date('H')-1,date('i'),date('s'), date('m'),date('d'), date('Y')));
        $where = " notification_to = $userId and viewed is null or notification_to = $userId and viewed = 1 and updated_at > '$lessOneHour' ";

        return  $this->whereRaw($where)->paginate(15);
    }

    public function viewed(array $userId)
    {
        return $this->whereIn('id', $userId)->update(array('viewed' => 1));
    }


}
