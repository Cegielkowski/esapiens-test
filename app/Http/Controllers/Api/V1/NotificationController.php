<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationController  extends Controller
{

    public function __construct()
    {
        //
    }

    public function get(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = User::findOrFail($request->get('user_id'));

        $notificationModel = new Notifications();
        $notifications = $notificationModel->get($user->id, $request->get('page'));
        $firstView = [];

        foreach ($notifications as $notification) {
            if (empty($notification['viewed'])) {
                $firstView[] = $notification['id'];
            }
        }

        if (!empty($firstView)) {
            $notificationModel->viewed($firstView);
        }

        $result['data'] = [
            'notifications' => $notifications,
        ];

        return $this->response->array($result)->setStatusCode(201);
    }

}