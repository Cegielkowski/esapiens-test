<?php

namespace App\Libraries;

use App\Models\Comments;
use App\Models\Notifications;
use App\Models\Posts;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class Helper
{
    private $tax = 0.1; // 10% tax from system

    public function __construct()
    {
        //
    }

    /**
     * @param int $userId
     * @param int $value
     * @param int $tax
     * @param int $commentId
     * @return array
     *
     * This function will create 2 transactions, 1 - for the customer and the other for the system
     */
    public static function registerTransaction(int $userId, float $value, float $tax, int $commentId)
    {
        $attributes = [
            'user_id' => $userId,
            'comment_id' => $commentId,
            'value' => $value
        ];

        $transactions = Transactions::create($attributes);

        $attributesTax = [
            'user_id' => $userId,
            'comment_id' => $commentId,
            'value' => $tax,
            'transaction_id' => $transactions->id
        ];

        Transactions::create($attributesTax);
    }

    /**
     * @param int $notificationFrom
     * @param int $notificationTo
     * @param int $commentId
     * @param int $postId
     *
     * This function create a notification to the user
     */
    public static function notify(int $notificationFrom, int $notificationTo, int $commentId, int $postId)
    {
        $notificationFrom = User::findOrFail($notificationFrom);
        $notificationTo = User::findOrFail($notificationTo);
        $comment = Comments::findOrFail($commentId);
        $post = Posts::findOrFail($postId);

        $attributes = [
            'notification_from' => $notificationFrom->id,
            'notification_to' => $notificationTo->id,
            'comment_id' => $comment->id,
            'post_id' => $post->id
        ];
        $title = 'You have a new comment on ' . $post->title;
        $content = 'User: ' . $notificationFrom->username . 'commented: ' . $comment->content;

        Helper::sendEmail($title, $content, $notificationTo);

        Notifications::create($attributes);
    }

    /**
     * @param string $title
     * @param string $content
     * @param User $user
     * Function that send email to the user
     */
    public static function sendEmail(string $title, string $content, User $user)
    {
//        try {
//            Mail::send($title, ['user' => $user], function ($m) use ($user, $content) {
//                $m->from('hello@app.com', 'Your Application');
//
//                $m->to($user->email, $user->name)->subject($content);
//            });
//        } catch (Exception $e) {
//            $error = $e->getMessage();
//        }
    }


    /**
     * @return float
     */
    private function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     */
    private function setTax(float $tax)
    {
        $this->tax = $tax;
    }

    /**
     * @param $coins
     * @return float
     * This function will apply the system tax
     */
    public static function applySystemTax(&$coins): float
    {
        $helper = new Helper();
        $tax = $coins * $helper->getTax();
        $coins -= $tax;

        return $tax;
    }

    /**
     * @param $number - This number will come to check if it is a positive or negative number
     * @param $correction - This will be the replace if it is negative the number
     */
    public static function fixNegativeNumbers(&$number, $correction = 0) {
        if ($number <= 0) {
            $number = $correction;
        }
    }

}
