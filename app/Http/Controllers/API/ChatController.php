<?php

namespace App\Http\Controllers\API;
use App\Chat;
use App\ChatMember;
use App\ChatMessage;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\ChatService;

class ChatController extends Controller
{
    protected $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->middleware('auth');
        $this->chatService = $chatService;
    }
    public function users_to_chat_with(){
        $list= $this->chatService->users_to_chat_with();
        if (count($list) > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully.',
                'data' => $list
            ], 200);
        }else{
            return response()->json([
                'status' => true,
                'message' => 'No user found.',
                'data' => []
            ], 200);
        }
    }
    public function create_chat(Request $request){
        return $this->chatService->create_chat_web($request);
    }

    public function send_message(Request $request){
        return $this->chatService->send_message($request);
    }

    public function upload_media(Request $request){
        return $this->chatService->upload_media($request);
    }

    public function get_chat(Request $request){
        return $this->chatService->get_chat($request);
    }

    public function read_chat(Request $request){
        return $this->chatService->read_chat($request);
    }

    public function get_all_chats(Request $request){
        return $this->chatService->get_all_chats_web($request);
    }

    public function delete_chat(Request $request){
        return $this->chatService->delete_chat($request);
    }

    public function delete_message(Request $request){
        return $this->chatService->delete_message($request);
    }

    public function read_chat_messages(Request $request){
        return $this->chatService->read_chat_messages($request);
    }

    public function update_unread_count(Request $request){
        return $this->chatService->update_unread_count($request);
    }

    public function searchChat(Request $request)
    {
        return $this->chatService->searchChat($request);
    }

}
