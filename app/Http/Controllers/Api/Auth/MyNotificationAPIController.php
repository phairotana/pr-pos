<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\Api\V1\MyNotificationResource;

class MyNotificationAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->count) {
            $count = $this->shareQuery()->ReadOrUnread($request->only)
                ->OrderByDefault()->count();
            return response()->json(['count' => $count]);
        } else {
            $notifies = $this->shareQuery()->ReadOrUnread($request->only)
                ->OrderByDefault();
        }
        return MyNotificationResource::collection($notifies->paginate(10));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notify = $this->shareQuery()->findOrFail($id);

        return new MyNotificationResource($notify);
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
        $notify = $this->shareQuery()->findOrFail($id);

        if ($request->only === 'read' || $request->to === 'read') {
            $notify->markAsRead();
        } else if ($request->only === 'unread' || $request->to === 'unread') {
            $notify->markAsUnread();
        } else if ($request->to === 'readall') {
            $notify = $this->shareQuery()->whereNull('read_at')->update(['read_at' => now(), 'updated_at' => now()]);
        }

        return new MyNotificationResource($notify);
    }
    public function readAll()
    {
        return $this->shareQuery()->whereNull('read_at')->update(['read_at' => now(), 'updated_at' => now()]);
    }
    public function unreadAll()
    {
        return $this->shareQuery()->whereNotNull('read_at')->update(['read_at' => NULL, 'updated_at' => now()]);
    }
    public function deleteAll()
    {
        return $this->shareQuery()->delete();
    }

    public function returnMessage($type, $id)
    {
        $update = false;

        $query = $this->shareQuery()->where('id', $id);
        switch ($type) {
            case "read":
                $message = 'Notification read successfully.';
                $update = $query->whereNull('read_at')->update(['read_at' => now(), 'updated_at' => now()]);
                break;
            case "unread":
                $message = 'Notification unread successfully.';
                $update = $query->whereNotNull('read_at')->update(['read_at' => NULL, 'updated_at' => now()]);
                break;
            case "delete":
                $message = 'Notification delete successfully.';
                $update = $query->delete();
                break;
        }

        return [
            'update' => $update ?? false,
            'fail' => 'Notification update fail.',
            'success' => $message ?? 'Notification update successfully.',
        ];
    }

    public function readOrUnreadOrDelete(Request $request)
    {
        $message = $this->returnMessage($request->type, $request->id);

        if (!$message['update']) {
            return response([
                'message' => 'The process has been failed.'
            ],400);
        }
        return response([
            'message' => 'The process has been success.'
        ],200);
    }
    protected function shareQuery()
    {
        return Notification::where('notifiable_id', \Auth::user()->id);
    }

    protected function isAssoc($value)
    {
        if (is_array($value) || ($value instanceof \Countable && $value instanceof \ArrayAccess)) {
            for ($i = count($value) - 1; $i >= 0; $i--) {
                if (!isset($value[$i]) && !array_key_exists($i, $value)) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }
}
