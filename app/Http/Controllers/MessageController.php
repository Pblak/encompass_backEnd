<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function searchUserForChat(Request $request): JsonResponse
    {
        if (!$request->search) {
            return response()->json([]);
        }
        // Fetch all users and attach type
        $users = User::Where('email', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->get()
            ->map(function ($user) {
                $user->type = 'users';
                return $user;
            });

        $teachers = Teacher::Where('email', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->get()
            ->map(function ($teacher) {
                $teacher->type = 'teachers';
                return $teacher;
            });

        $parents = Parents::Where('email', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->get()
            ->map(function ($parent) {
                $parent->type = 'parents';
                return $parent;
            });

        $students = Student::Where('email', 'like', '%' . $request->search . '%')
            ->orWhere('first_name', 'like', '%' . $request->search . '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->get()
            ->map(function ($student) {
                $student->type = 'students';
                return $student;
            });

        // Merge all collections into a single array
        $results = $users->concat($teachers)->concat($parents)->concat($students);

        return response()->json($results);
    }

    public function getConversation(Request $request): JsonResponse
    {

        $request->validate([
            'selected_user_id' => 'required|integer', // ID of the selected user
            'selected_user_type' => 'required|string', // Type of the selected user (e.g., 'users', 'teachers', etc.)
        ]);

        $authUser = auth()->user();

        // Determine the table name of the authenticated user
        $authUserTable = $authUser->getTable();

        $messages = Message::where(function ($query) use ($authUser, $authUserTable, $request) {
            $query->where('from_id', $authUser->id)
                ->where('from_id_table', $authUserTable)
                ->where('to_id', $request->selected_user_id)
                ->where('to_id_table', $request->selected_user_type);
        })->orWhere(function ($query) use ($authUser, $authUserTable, $request) {
            $query->where('to_id', $authUser->id)
                ->where('to_id_table', $authUserTable)
                ->where('from_id', $request->selected_user_id)
                ->where('from_id_table', $request->selected_user_type);
        })->orderBy('created_at', 'asc') // Order messages by time
        ->get();

        // Mark received messages as read
        Message::where('to_id', $authUser->id)
            ->where('to_id_table', $authUserTable)
            ->where('from_id', $request->selected_user_id)
            ->where('from_id_table', $request->selected_user_type)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);


        return response()->json($messages);
    }


    public function getChatUsers(Request $request): JsonResponse
    {
        $authUser = auth()->user();
        $authUserTable = $authUser->getTable();

        // Get unique chat users
        $chatUsers = Message::where('from_id', $authUser->id)
            ->where('from_id_table', $authUserTable)
            ->orWhere('to_id', $authUser->id)
            ->where('to_id_table', $authUserTable)
            ->selectRaw('
            CASE
                WHEN from_id = ? AND from_id_table = ? THEN to_id
                ELSE from_id
            END as id,
            CASE
                WHEN from_id = ? AND from_id_table = ? THEN to_id_table
                ELSE from_id_table
            END as type',
                [$authUser->id, $authUserTable, $authUser->id, $authUserTable]
            )
            ->distinct()
            ->get();

        // Attach last message for each user
        $chatUsers = $chatUsers->map(function ($user) use ($authUser, $authUserTable) {
            $messages = Message::where(function ($query) use ($authUser, $authUserTable, $user) {
                $query->where('from_id', $authUser->id)
                    ->where('from_id_table', $authUserTable)
                    ->where('to_id', $user->id)
                    ->where('to_id_table', $user->type);
            })->orWhere(function ($query) use ($authUser, $authUserTable, $user) {
                $query->where('to_id', $authUser->id)
                    ->where('to_id_table', $authUserTable)
                    ->where('from_id', $user->id)
                    ->where('from_id_table', $user->type);
            })->latest('created_at');

            $lastMessage = $messages->first();

//            $unReadMessages = $messages->get()->filter(function ($message) use ($authUser, $authUserTable) {;
//                return !$message->read_at;
//            });
//            dd($unReadMessages);
            // Get user model dynamically based on table name
            $userModel = DB::table($user->type)->where('id', $user->id)->first();

            // Extract avatar from JSON 'infos' column
            $avatar = isset($userModel->infos) ? json_decode($userModel->infos)->avatar ?? null : null;

            // Check if the last message is not mine and if it's read
            if ($lastMessage->from_id === $authUser->id
                && $lastMessage->from_id_table === $authUserTable) {
                $isRead = true;
            } else {
                $isRead = $lastMessage->read_at;
            }
            // Attach last message and avatar
            $user->last_message = $lastMessage;
            $user->avatar = $avatar;
            $user->name = isset($userModel->name) ? $userModel->name : $userModel->first_name . ' ' . $userModel->last_name;
            $user->is_read = $isRead;
            return $user;
        });

        return response()->json($chatUsers);
    }


    // createMessage
    public function createMessage(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'message' => 'required|string|max:500',
            'to_id' => 'required|integer',
            'to_id_table' => 'required|string',
        ]);
        // get logged-in user table name
        DB::beginTransaction();
        try {
            Message::create([
                'content' => $request->message,
                'to_id' => $request->to_id,
                'from_id' => $user->id,
                'from_id_table' => $user->getTable(),
                'to_id_table' => $request->to_id_table,
                'type' => $request->type
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Message created successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Message creation failed',
                'error' => $e->getMessage(),
                "_t" => "error",
            ]);
        }
    }
}
