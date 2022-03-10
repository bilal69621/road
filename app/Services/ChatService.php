<?php

namespace App\Services;

use App\Chat;
use App\ChatMember;
use App\ChatMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatService
{
    public function users_to_chat_with(){
        $list = [];
        $users = [];
        $authorized_adults = [];

        if(Auth::user()->type == 'provider'){
            $parents = User::where('linked_to', Auth::user()->id)->where('type', 'parent')->get()->toArray();
            $staff = User::where('linked_to', Auth::user()->id)->where('type', 'staff')->get()->toArray();
            foreach ($parents as $one){
                $record = User::where('linked_to', $one['id'])->where('type', 'authorized_adult')->get()->toArray();
                $authorized_adults = array_merge($authorized_adults, $record);
            }

            $users = array_merge($users, $parents);
            $users = array_merge($users, $authorized_adults);
            $users = array_merge($users, $staff);
        }

//        if(Auth::user()->type == 'staff'){
//            $provider = User::where('id', Auth::user()->linked_to)->where('type', 'provider')->first()->toArray();
//            $parents = User::where('linked_to', Auth::user()->linked_to)->where('type', 'parent')->get()->toArray();
//            $staff = User::where('linked_to', Auth::user()->linked_to)->where('type', 'staff')->where('id', '!=', Auth::user()->id)->get()->toArray();
//            foreach ($parents as $one){
//                $record = User::where('linked_to', $one['id'])->where('type', 'authorized_adult')->get()->toArray();
//                $authorized_adults = array_merge($authorized_adults, $record);
//            }
//            array_push($users, $provider);
//            $users = array_merge($users, $parents);
//            $users = array_merge($users, $authorized_adults);
//            $users = array_merge($users, $staff);
//        }

        if(Auth::user()->type == 'parent'){
            $provider = User::where('id', Auth::user()->linked_to)->where('type', 'provider')->first()->toArray();
            $staff = User::where('linked_to', Auth::user()->linked_to)->where('type', 'staff')->get()->toArray();
            $authorized_adults = User::where('linked_to', Auth::user()->id)->where('type', 'authorized_adult')->get()->toArray();

            array_push($users, $provider);
            $users = array_merge($users , $staff);
            $users = array_merge($users , $authorized_adults);
        }

        if(Auth::user()->type == 'authorized_adult'){
            $parent = User::where('id', Auth::user()->linked_to)->where('type', 'parent')->first()->toArray();
            $provider = User::where('id', $parent['linked_to'])->where('type', 'provider')->first()->toArray();
            $staff = User::where('linked_to', $parent['linked_to'])->where('type', 'staff')->get()->toArray();

            array_push($users, $provider);
            array_push($users, $parent);
            $users = array_merge($users , $staff);
        }

        foreach($users as $one){
            $chat = Chat::with('members.user')->where('type', 'single')->whereHas('members', function ($query) use ($one) {
                $query->where('member_id', Auth::user()->id)->orWhere('member_id', $one['id']);
                $query->havingRaw('COUNT(*) = 2');
            })->first();

            if ( isset($chat) ) { continue; } else { $list[] = $one; }
        }
        return $list;
    }

    public function create_chat(Request $request){
        // dd($request);

        $validator = Validator::make($request->all(), [
            'members' => 'required',
            'type' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'data' => null], 200);
        }

        $member_ids = $request->members;

        if($request->type == 'single'){
            $member_id = $member_ids[0];
            $user = User::where('id', $member_id)->first();
            if(!isset($user)){
                return response()->json(['status' => false, 'message' => "Failed to create chat, second user not found.", 'data' => null], 200);
            }
            $chat = Chat::with('members.user')->where('type', 'single')->whereHas('members', function ($query) use ($member_id) {
                $query->where('member_id', Auth::user()->id)->orWhere('member_id', $member_id);
                $query->havingRaw('COUNT(*) = 2');
            })->first();

            if($chat){
                if($chat->members[0]->user->id == Auth::user()->id){
                    if($chat->members[0]->is_deleted == 1){
                        if(isset($request->message)){
                            $message = new ChatMessage;
                            $message->chat_id = $chat->id;
                            $message->sender_id = Auth::user()->id;
                            $message->message = $request->message;
                            $message->save();

                            if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
                                $file = $request->file('file');
                                $random = substr(md5(mt_rand()), 0, 20);
                                $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
                                $uploaded_path =  'storage/messages/media/';

                                $file->move($uploaded_path, $file_name);
                                $message->file_path = 'storage/messages/media/' . $file_name;
                                $message->file_ratio = '';
                                $message->file_type = $file->getClientOriginalExtension();
                                $message->save();
                            }
                        }

                        $chat->members[0]->is_deleted = 0;
                        $chat->members[0]->save();

                        $data = Chat::where('id', $chat->id)->with('members.user')->first();
                        $data['messages'] = $data;

                        return response()->json(['status' => true, 'message' => 'Chat created successful.', 'data' => $data], 200);
                    }
                }
                else{
                    if($chat->members[1]->is_deleted == 1){
                        if(isset($request->message)){
                            $message = new ChatMessage;
                            $message->chat_id = $chat->id;
                            $message->sender_id = Auth::user()->id;
                            $message->message = $request->message;
                            $message->save();

                            if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
                                $file = $request->file('file');
                                $random = substr(md5(mt_rand()), 0, 20);
                                $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
                                $uploaded_path =  'storage/messages/media/';

                                $file->move($uploaded_path, $file_name);
                                $message->file_path = 'storage/messages/media/' . $file_name;
                                $message->file_ratio = '';
                                $message->file_type = $file->getClientOriginalExtension();
                                $message->save();
                            }
                        }

                        $chat->members[0]->is_deleted = 0;
                        $chat->members[0]->save();

                        $data = Chat::where('id', $chat->id)->with('members.user')->first();
                        $data['messages'] = $data;
                        return response()->json(['status' => true, 'message' => 'Chat created successful.', 'data' => $data], 200);
                    }
                }
                $data = $chat;
                return response()->json(['status' => false, 'message' => 'Chat has been already created.', 'data' => $data], 200);
            }
        }

        if ($request->group_photo != '' && $request->group_photo != null && $request->hasFile('group_photo')) {
            $file = $request->file('group_photo');
            $random = substr(md5(mt_rand()), 0, 20);
            $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
            $uploaded_path =  'storage/messages/media/';
            $file->move($uploaded_path, $file_name);
            $group_photo = 'storage/messages/media/' . $file_name;
        }
        else{
            $group_photo = 'storage/messages/media/default-icon.png';
        }
        $chat = new Chat;
        $chat->admin_id = Auth::user()->id;
        $chat->title = $request->title;
        $chat->type = $request->type;
        $chat->group_photo = @$group_photo;

        $chat->save();

        if($chat){
            foreach($member_ids as $member_id){
                $member = new ChatMember;
                $member->chat_id = $chat->id;
                $member->member_id = $member_id;
                $member->save();
            }
            $member = new ChatMember;
            $member->chat_id = $chat->id;
            $member->member_id = Auth::user()->id;
            $member->save();

            if($request->message){
                $message = new ChatMessage;
                $message->chat_id = $chat->id;
                $message->sender_id = Auth::user()->id;
                $message->message = $request->message;
                $message->save();

                if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
                    $file = $request->file('file');
                    $random = substr(md5(mt_rand()), 0, 20);
                    $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
                    $uploaded_path =  'storage/messages/media/';
                    $file->move($uploaded_path, $file_name);
                    $message->file_path = 'storage/messages/media/' . $file_name;
                    $message->file_ratio = '';
                    $message->file_type = $file->getClientOriginalExtension();
                    $message->save();
                }
            }
            $data['messages']  = Chat::where('id', $chat->id)->with('members.user')->first();
            return response()->json(['status' => true, 'message' => 'Chat created successful.', 'data' => $data], 200);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong with the request.', 'data' => null], 200);
    }


    public function create_chat_web(Request $request){

        $validator = Validator::make($request->all(), [
            'members' => 'required',
            'type' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'data' => null], 200);
        }

        $member_ids = $request->members;

        if($request->type == 'single'){
            $member_id = $member_ids[0];
            $user = User::where('id', $member_id)->first();
            if(!isset($user)){
                return response()->json(['status' => false, 'message' => "Failed to create chat, second user not found.", 'data' => null], 200);
            }
            $chat = Chat::with('members.user')->where('type', 'single')->whereHas('members', function ($query) use ($member_id) {
                $query->where('member_id', Auth::user()->id)->orWhere('member_id', $member_id);
                $query->havingRaw('COUNT(*) = 2');
            })->first();

            if($chat){
                if($chat->members[0]->user->id == Auth::user()->id){
                    if($chat->members[0]->is_deleted == 1){
                        if(isset($request->message)){
                            $message = new ChatMessage;
                            $message->chat_id = $chat->id;
                            $message->sender_id = Auth::user()->id;
                            $message->message = $request->message;
                            $message->save();

                            if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
                                $file = $request->file('file');
                                $random = substr(md5(mt_rand()), 0, 20);
                                $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
                                $uploaded_path =  'storage/messages/media/';

                                $file->move($uploaded_path, $file_name);
                                $message->file_path = 'storage/messages/media/' . $file_name;
                                $message->file_ratio = '';
                                $message->file_type = $file->getClientOriginalExtension();
                                $message->save();
                            }
                        }

                        $chat->members[0]->is_deleted = 0;
                        $chat->members[0]->save();

                        $data = Chat::where('id', $chat->id)->with('members.user')->first();

                        return response()->json(['status' => true, 'message' => 'Chat created successful.', 'data' => $data], 200);
                    }
                }
                else{
                    if($chat->members[1]->is_deleted == 1){
                        if(isset($request->message)){
                            $message = new ChatMessage;
                            $message->chat_id = $chat->id;
                            $message->sender_id = Auth::user()->id;
                            $message->message = $request->message;
                            $message->save();

                            if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
                                $file = $request->file('file');
                                $random = substr(md5(mt_rand()), 0, 20);
                                $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
                                $uploaded_path =  'storage/messages/media/';

                                $file->move($uploaded_path, $file_name);
                                $message->file_path = 'storage/messages/media/' . $file_name;
                                $message->file_ratio = '';
                                $message->file_type = $file->getClientOriginalExtension();
                                $message->save();
                            }
                        }

                        $chat->members[0]->is_deleted = 0;
                        $chat->members[0]->save();

                        $data = Chat::where('id', $chat->id)->with('members.user')->first();
                        return response()->json(['status' => true, 'message' => 'Chat created successful.', 'data' => $data], 200);
                    }
                }
                $data = $chat;
                return response()->json(['status' => false, 'message' => 'Chat has been already created.', 'data' => $data], 200);
            }
        }

        if ($request->group_photo != '' && $request->group_photo != null && $request->hasFile('group_photo')) {
            $file = $request->file('group_photo');
            $random = substr(md5(mt_rand()), 0, 20);
            $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
            $uploaded_path =  'storage/messages/media/';
            $file->move($uploaded_path, $file_name);
            $group_photo = 'storage/messages/media/' . $file_name;
        }
        else{
            $group_photo = 'storage/messages/media/default-icon.png';
        }
        $chat = new Chat;
        $chat->admin_id = Auth::user()->id;
        $chat->title = $request->title;
        $chat->type = $request->type;
        $chat->group_photo = @$group_photo;

        $chat->save();

        if($chat){
            foreach($member_ids as $member_id){
                $member = new ChatMember;
                $member->chat_id = $chat->id;
                $member->member_id = $member_id;
                $member->save();
            }
            $member = new ChatMember;
            $member->chat_id = $chat->id;
            $member->member_id = Auth::user()->id;
            $member->save();

            if($request->message){
                $message = new ChatMessage;
                $message->chat_id = $chat->id;
                $message->sender_id = Auth::user()->id;
                $message->message = $request->message;
                $message->save();

                if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
                    $file = $request->file('file');
                    $random = substr(md5(mt_rand()), 0, 20);
                    $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
                    $uploaded_path =  'storage/messages/media/';
                    $file->move($uploaded_path, $file_name);
                    $message->file_path = 'storage/messages/media/' . $file_name;
                    $message->file_ratio = '';
                    $message->file_type = $file->getClientOriginalExtension();
                    $message->save();
                }
            }
            $data  = Chat::where('id', $chat->id)->with('members.user')->first();
            return response()->json(['status' => true, 'message' => 'Chat created successful.', 'data' => $data], 200);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong with the request.', 'data' => null], 200);
    }

    public function send_message(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'message' => 'required',
        ]);
        if($validator->fails()){
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        ChatMember::where('chat_id', $request->chat_id)
            ->where('member_id', '!=', Auth::user()->id)
            ->update(['is_deleted' => false, 'unread_count' => DB::raw('unread_count + 1')]);

        $message = new ChatMessage;
        $message->chat_id = $request->chat_id;
        $message->sender_id = Auth::user()->id;
        $message->message = $request->message;
        $message->file_type = $request->file_type ?? null;
        $message->save();

        if($request->file_path != '' && $request->file_path != null){
            $message->file_path = $request->file_path ?? null;
            $message->file_ratio = '';
            $message->save();
        }

        if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
            $file = $request->file('file');
            $random = substr(md5(mt_rand()), 0, 20);
            $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
            $uploaded_path =  'storage/messages/media/';
            $file->move($uploaded_path, $file_name);
            $message->file_path = 'storage/messages/media/' . $file_name;
            $message->file_ratio = '';
            $message->file_type = $request->file_type ?? null;
            $message->save();
        }

        $check = ChatMember::where('chat_id', $request->chat_id)->where('member_id', Auth::user()->id)->where('is_deleted', false)->select('last_message_id')->first();
        $data = ChatMessage::with('sender')
            ->where('chat_id', $request->chat_id)
            ->where('id', '>', $check->last_message_id)
            ->whereRaw("id NOT IN (SELECT message_id from deleted_messages where user_id = ".Auth::user()->id." AND chat_id = ".$request->chat_id.")")
            ->orderBy('created_at', 'DESC')
            ->first();

        $current_date_time = Carbon::now()->toDateTimeString();
        $chat1 = Chat::find($request->chat_id);
        $chat1->updated_at = $current_date_time;
        $chat1->update();

        if($request->member_id){
            $first_user = $data->sender->id;
            $user = ChatMember::where('member_id','!=', $first_user)->where('chat_id', $request->chat_id)->first();
            $data->receiver_image = $user->user->profile_picture;

        }
        $data->chat_type = $chat1->type;
        $data->group_photo = $chat1->group_photo;

        return sendSuccess('Message sent successfully.', $data);
    }

    public function send_message_mobile(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'data' => null], 200);
        }

        ChatMember::where('chat_id', $request->chat_id)
            ->where('member_id', '!=', Auth::user()->id)
            ->update(['is_deleted' => false, 'unread_count' => DB::raw('unread_count + 1')]);

        $message = new ChatMessage;
        $message->chat_id = $request->chat_id;
        $message->sender_id = Auth::user()->id;
        $message->message = $request->message;
        $message->save();

        if($request->file_path != '' && $request->file_path != null){
            $message->file_path = $request->file_path;
            $message->file_ratio = '';
            $message->file_type = '';
            $message->save();
        }

        if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
            $file = $request->file('file');
            $random = substr(md5(mt_rand()), 0, 20);
            $file_name = $random . '.' . 'message_file' . '.' . $file->getClientOriginalExtension();
            $uploaded_path =  'storage/messages/media/';
            $file->move($uploaded_path, $file_name);
            $message->file_path = 'storage/messages/media/' . $file_name;
            $message->file_ratio = '';
            $message->file_type = $file->getClientOriginalExtension();
            $message->save();
        }

        $check = ChatMember::where('chat_id', $request->chat_id)->where('member_id', Auth::user()->id)->where('is_deleted', false)->select('last_message_id')->first();
        $data['messages'] = ChatMessage::with('sender')
            ->where('chat_id', $request->chat_id)
            ->where('id', '>', $check->last_message_id)
            ->whereRaw("id NOT IN (SELECT message_id from deleted_messages where user_id = ".Auth::user()->id." AND chat_id = ".$request->chat_id.")")
            ->orderBy('created_at', 'ASC')
            ->get();

        $current_date_time = Carbon::now()->toDateTimeString();
        $chat1 = Chat::find($request->chat_id);
        $chat1->updated_at = $current_date_time;
        $chat1->update();

        if($request->member_id){
            $first_user = $data->sender->id;
            $user = ChatMember::where('member_id','!=', $first_user)->where('chat_id', $request->chat_id)->first();
            $data->receiver_image = $user->user->profile_picture;

        }
        $members = ChatMember::where('chat_id', $request->chat_id)->where('member_id', '!=', Auth::user()->id)->get();
        $participants = [];
        foreach($members as $one){
            $participants[] = User::where('id', $one->member_id)->first();
        }

        $data['members'] = $participants;
        $data['chat_type'] = $chat1->type;
        $data['group_photo'] = $chat1->group_photo;
        $data['title'] = $chat1->title;

        return response()->json(['status' => true, 'message' => 'Message sent successfully.', 'data' => $data], 200);
    }

    public function upload_media(Request $request){
        if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
            $img = $request->file('file');

            $type = $img->getMimeType();
            $type = explode('/', $type);
            $file_type = $type[0];

            $random = substr(md5(mt_rand()), 0, 20);
            //       $filename1 = pathinfo($img, PATHINFO_FILENAME);
            //       $nam = $filename1.'_'.rand(9,99).time();
            $file_name = $random . '.' . $img->getClientOriginalExtension();
            $uploaded_path =  'public/svg/';
            $img->move($uploaded_path, $file_name);

            $thumbnail1 = null;
            if($file_type == 'video'){
                $movie = $uploaded_path.$file_name;
                $thumbnail = $uploaded_path.$random .'.jpg';
                $thumbnail1 = $random .'.jpg';

                if($ffmpeg = \FFMpeg\FFMpeg::create()){
                    $video = $ffmpeg->open($movie);
                    $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))
                    ->save($thumbnail);
                }
            }
            $file_path = $file_name;
            $data = array(
                'file_path' => $file_path,
                'thumbnail' => $thumbnail1
            );
            return sendSuccess('File uploaded successfully.', $data);
        }

        //        if ($request->file != '' && $request->file != null && $request->hasFile('file')) {
        //            $file = $request->file('file');
        //            $random = substr(md5(mt_rand()), 0, 20);
        //            $file_name = $random . '.' . $file->getClientOriginalExtension();
        //            $uploaded_path =  'public/svg/';
        //            $file->move($uploaded_path, $file_name);
        //            $file_path = $file_name;
        //            $data = array(
        //                'file_path' => $file_path
        //            );
        //            return sendSuccess('File uploaded successfully.', $data);
        //        }
        else{
            return sendError('No file found.', 200);
        }
    }

    public function get_chat(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required'
        ]);
        if($validator->fails()){
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        $check = ChatMember::where('chat_id', $request->chat_id)->where('member_id', Auth::user()->id)->where('is_deleted', false)->select('last_message_id')->first();
        $data['chat'] = ChatMessage::with('sender')
            ->where('chat_id', $request->chat_id)
//            ->where('id', '>', $check->last_message_id)
            /*->whereRaw("id NOT IN (SELECT message_id from deleted_messages where user_id = ".Auth::user()->id." AND chat_id = ".$request->chat_id.")")*/
            ->orderBy('created_at', 'ASC')
            ->get();

        ChatMember::where('chat_id', $request->chat_id)
            ->where('member_id', '=', Auth::user()->id)
            ->update(['unread_count' => 0]);

        $members = ChatMember::where('chat_id', $request->chat_id)->where('member_id', '!=', Auth::user()->id)->get();
        $participants = [];
        foreach($members as $one){
            $participants[] = User::where('id', $one->member_id)->first();
        }
        $data['participants'] = $participants;
        $data['members'] = $participants;
        $data['messages'] = $data['chat'];
        $chat=Chat::find($request->chat_id);
        $data['chat_type'] = $chat->type;
        $data['title'] = $chat->title;
        $data['group_photo'] = $chat->group_photo;
        return sendSuccess('Chat successfully loaded.', $data);
    }

    public function read_chat(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required'
        ]);
        if($validator->fails()){
            $messages = $validator->messages()->all();
            $messages = join("\n", $messages);
            return sendError($messages, 401);
        }

        ChatMember::where('chat_id', $request->chat_id)
            ->where('member_id', '=', Auth::user()->id)
            ->update(['unread_count' => 0]);

        return sendSuccess('Chat read successfully.', null);
    }

    public function get_all_chats(Request $request){
        $chats = Chat::whereHas('lastMessage')->with(['lastMessage' =>function($query){
            $query->with('sender');
        },'messages' => function($query){
            $query->with('sender');
            $query->orderBy('created_at','DESC');
        }, 'members' => function($query){
            $query->with('user');
//            ->where('member_id', '!=', Auth::user()->id);
        }])->whereHas('members', function ($query) {
            $query->where('member_id', Auth::user()->id)
                ->where('is_deleted', false);
        })->whereHas('members', function ($query) {
            $query->where('member_id', '!=', Auth::user()->id);
        })
            ->orderBy('updated_at','ASC');

        $all_chats = $chats->select('chats.*', DB::raw("(select unread_count from chat_members where chat_id = chats.id and member_id = ".Auth::user()->id." limit 1) as unread_count"))

//        $all_chats = $chats->select('chats.*', DB::raw("(select unread_count from chat_members where chat_id = chats.id and member_id = ".Auth::user()->id." limit 1) as unread_count"),DB::raw("(select member_id from chat_members where chat_id = chats.id and chat_members.member_id != ".Auth::user()->id.") as other_user_id"))
            ->orderBy('updated_at','ASC')->get();
        // dd($all_chats);
        if(count($all_chats) == 0){
            return response()->json(['status' => true, 'message' => 'Failed to load chats, not found.', 'data' => []], 200);
        }
        $data = $all_chats->toArray();

//        print_r($data); exit;
        // usort($data, 'timeSortFunction');
        return response()->json(['status' => true, 'message' => 'Chats successfully loaded.', 'data' => $data], 200);
    }

    public function get_all_chats_web(Request $request){
        $chats = Chat::whereHas('lastMessage')->with(['lastMessage','messages' => function($query){
            $query->orderBy('created_at','DESC');
        }, 'members' => function($query){
            $query->with('user')->where('member_id', '!=', Auth::user()->id);
        }])->whereHas('members', function ($query) {
            $query->where('member_id', Auth::user()->id)
                ->where('is_deleted', false);
        })->whereHas('members', function ($query) {
            $query->where('member_id', '!=', Auth::user()->id);
        })
            ->orderBy('updated_at','ASC');
        $all_chats = $chats->select('chats.*', DB::raw("(select unread_count from chat_members where chat_id = chats.id and member_id = ".Auth::user()->id." limit 1) as unread_count"))

//        $all_chats = $chats->select('chats.*', DB::raw("(select unread_count from chat_members where chat_id = chats.id and member_id = ".Auth::user()->id." limit 1) as unread_count"),DB::raw("(select member_id from chat_members where chat_id = chats.id and chat_members.member_id != ".Auth::user()->id.") as other_user_id"))
            ->orderBy('updated_at','ASC')->get();
        // dd($all_chats);
        if(count($all_chats) == 0){
            return response()->json(['status' => true, 'message' => 'Failed to load chats, not found.', 'data' => []], 200);
        }
        $data = $all_chats->toArray();

//        print_r($data); exit;
        // usort($data, 'timeSortFunction');
        return response()->json(['status' => true, 'message' => 'Chats successfully loaded.', 'data' => $data], 200);
    }

    public function delete_chat(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'data' => null], 200);
        }
        $check = ChatMessage::where('chat_id', $request->chat_id)->orderBy('id', 'DESC')->select('id')->first();
        if(!$check){
            return response()->json(['status' => false, 'message' => 'Failed to load chat, not found.', 'data' => null], 200);
        }
        ChatMember::where('chat_id', $request->chat_id)
            ->where('member_id', Auth::user()->id)
            ->update(['is_deleted' => true, 'unread_count' => 0, 'last_message_id' => $check->id]);

        return response()->json(['status' => true, 'message' => 'Chat deleted successfully.', 'data' => null], 200);
    }

    public function delete_message(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'message_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'data' => null], 200);
        }
        $message = ChatMessage::where('chat_id', $request->chat_id)->where('id', $request->message_id)->where('sender_id', Auth::user()->id)->first();
        if(!$message){
            return response()->json(['status' => false, 'message' => 'Failed to load chat, message not found.', 'data' => null], 200);
        }
        $deleted_message = new DeletedMessage();
        $deleted_message->user_id = Auth::user()->id;
        $deleted_message->chat_id = $message->chat_id;
        $deleted_message->message_id = $message->id;
        $deleted_message->save();

        return response()->json(['status' => true, 'message' => 'Message deleted successfully.', 'data' => null], 200);
    }

    public function read_chat_messages(Request $request){
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => false, 'message' => $validator->errors()->all(), 'data' => null], 200);
        }
        $check = ChatMember::where('chat_id', $request->chat_id)->where('member_id', Auth::user()->id)->where('is_deleted', false)->select('last_message_id')->first();
        $data = ChatMessage::with('sender')
            ->where('chat_id', $request->chat_id)
//            ->where('id', '>', $check->last_message_id)
            ->whereRaw("id NOT IN (SELECT message_id from deleted_messages where user_id = ".Auth::user()->id." AND chat_id = ".$request->chat_id.")")
            ->orderBy('created_at', 'ASC')
            ->get();
        return response()->json(['status' => true, 'message' => 'Chat successfully loaded.', 'data' => $data], 200);
    }

    public function update_unread_count(Request $request){
        ChatMember::where('chat_id', $request->chat_id)
            ->where('member_id', '=', Auth::user()->id)
            ->update(['unread_count' => 0]);

        return sendSuccess('Unread count updated successfully.', null);
    }



    public function searchChat(Request $request)
    {
        $chats = Chat::whereHas('lastMessage')->with(['lastMessage', 'members' => function($query) use($request){
            $query->with(['user' => function ($q) use($request){
                $q->where('first_name', 'LIKE', '%' . $request->query_string . '%');
                $q->orWhere('first_name', 'LIKE', $request->query_string . '%');
                $q->orWhere('first_name', 'LIKE', '%' . $request->query_string);
                $q->orWhere('last_name', 'LIKE', '%' . $request->query_string . '%');
                $q->orWhere('last_name', 'LIKE', $request->query_string . '%');
                $q->orWhere('last_name', 'LIKE', '%' . $request->query_string);
            }
            ])->where('member_id', '!=', Auth::user()->id);
        }])->whereHas('members', function ($query) use($request){
            $query->where('member_id', Auth::user()->id)
                ->where('is_deleted', false);
        })
            ->whereHas('members', function ($query) use($request){
                $query->where('member_id', '!=', Auth::user()->id);
            })
            ->whereHas('members', function ($query) use($request){

            });
        $all_chats = $chats->select('chats.*', DB::raw("(select unread_count from chat_members where chat_id = chats.id and member_id = ".Auth::user()->id." limit 1) as unread_count"))->get();
        if(count($all_chats) == 0){
            return response()->json(['status' => true, 'message' => 'Failed to load chats, not found.', 'data' => []], 200);
        }
        $data = $all_chats->toArray();
        usort($data, 'timeSortFunction');
        return response()->json(['status' => true, 'message' => 'Chats successfully loaded.', 'data' => $data], 200);

    }

}
