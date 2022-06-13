<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Activities;
use App\Models\Friends;
use App\Models\message_room;
use App\Models\messages;
use App\Models\images;
use App\Models\posts_reviews;
use App\Models\likes;
use App\Models\comments;
use App\Models\files;
use App\Models\compartments;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // SEARCH WEB
    public function search_people(Request $request)
    {
        if (empty($request->name))
            $name = '';
        else $name = $request->name;

        $myFriends = $this->my_friends();

        $users = User::select('id', db::raw('concat(name, " ",surname) as name'), 'city', 'country', 'user_type')
            ->where('id', '!=', auth()->user()->id)->get();
        foreach ($users as $key => $user) {
            if ($user->user_type != 'universitate') {
                $universities = Activities::select('name_institution')->where('user_id', $user->id)->get();
                if (count($universities) > 0) {
                    $users[$key]->universities = $universities;
                }
            } else {
                $users[$key]->universities = $user->name;
            }
            $this->relation($users[$key], $user->id);
            $this->common_friends($users[$key], $user->id, $myFriends);
        }
        return view('pages/authentificated/search')->with(['name_search' => $name, 'users' => $users]);
    }
    // FRIENDS WEB
    public function friends(Request $request)
    {
        if (empty($request->id) && !(Auth::check())) {
            return redirect()->back();
        }
        if (empty($request->id)) $request->id = auth()->user()->id;
        if (count(user::where('id', $request->id)->limit(1)->get()->toArray()) > 0) {
            if (Auth::check()) {
                // aflu toti userii pentur a-i arata in sugestii, calculez prietenii comuni etc.
                $users = User::select('id', db::raw("concat(name, ' ', surname) as name"), db::raw('null as common_friends'), db::raw('"not_friends" as status'));
                $users = $users->where('id', 'not like', auth()->user()->id)->get()->toArray();
                $friendFor = Friends::select('user_id as prieten_id')->where([['friend_id', auth()->user()->id], ['active', 1], ['blocked_by_id', null]])->get()->toArray();
                $friendsMine = Friends::select('friend_id as prieten_id')->where([['user_id', auth()->user()->id], ['active', 1], ['blocked_by_id', null]])->get()->toArray();
                $friendsTotal = array_merge($friendFor, $friendsMine);

                foreach ($users as $key => $user) {

                    // relationship with me for this user 
                    $relation_with_user = array_merge(friends::where([['user_id', auth()->user()->id], ['friend_id', $user['id']]])->get()->toArray(), friends::where([['friend_id', auth()->user()->id], ['user_id', $user['id']]])->get()->toArray());
                    $nr_friends = count($relation_with_user);
                    if ($nr_friends == 0)
                        $status = 'not_friends';
                    else if ($nr_friends == 1) {
                        $friendship = $relation_with_user[0];
                        if ($friendship['blocked_by_id'] == null)
                            if ($friendship['active'] == 0)
                                if ($friendship['user_id'] == auth()->user()->id)
                                    $status = 'invited_by_me';
                                else $status = 'invite_me';
                            else
                                $status = 'friends';
                        else if ($friendship['blocked_by_id'] == auth()->user()->id)
                            $status = 'blocked_by_me';
                        else $status = 'block_me';
                    }
                    $users[$key]['status'] = $status;

                    // calculate common friends
                    $userFriendFor = friends::select('user_id as prieten_id')->where([['friend_id', $users[$key]['id']], ['active', '1'], ['blocked_by_id', null], ['user_id', '!=', auth()->user()->id]])->get()->toArray();
                    $friendsOfUser = friends::select('friend_id as prieten_id')->where([['user_id', $users[$key]['id']], ['active', '1'], ['blocked_by_id', null], ['friend_id', '!=', auth()->user()->id]])->get()->toArray();
                    $friendsUserTotal = array_merge($userFriendFor, $friendsOfUser);
                    $common_friends = 0;
                    foreach ($friendsTotal as $key1 => $friendMy) {
                        foreach ($friendsUserTotal as $key2 => $friendOfUser) {
                            if ($friendOfUser['prieten_id'] == $friendMy['prieten_id']) {
                                $common_friends++;
                                break;
                            }
                        }
                    }
                    $users[$key]['common_friends'] = $common_friends;
                }
                // 
            } else {
                $users = User::select('id', db::raw("concat(name, ' ', surname) as name"), db::raw('null as common_friends'), db::raw('"not_friends" as status'))->get()->toArray();
            }

            // prietenii pentru userul selectat
            $friends = Friends::select('user_id as prieten_id')
                ->where([['friend_id', $request->id], ['active', 1], ['blocked_by_id', null]])
                ->get();
            $friends = array_merge(
                $friends->toArray(),
                Friends::select('friend_id as prieten_id')
                    ->where([['user_id', $request->id], ['active', 1], ['blocked_by_id', null]])
                    ->get()
                    ->toArray(),
            );

            if (Auth::check()) {
                $user_login = 1;
                if ($request->id == auth()->user()->id) {
                    $my_profile = 1;
                } else {
                    $my_profile = 0;
                }
            } else {
                $user_login = 0;
                $my_profile = 0;
            }
            // dd($friends_confirmed_of_selected_user);
            return view('pages.authentificated.friends')->with(['users' => $users, 'page' => 'friends', 'friends' => $friends, 'my_profile' => $my_profile, 'user_login' => $user_login]);
        }
        return redirect()->back();
    }
    // TIMELINE CARD WEB
    public function timeline(Request $request)
    {
        if (empty($request->id))
            $request->id = auth()->user()->id;
        if (!(user::where('id', $request->id)->limit(1)->get())) {
            return redirect()->back();
        } else {
            // culegere info despre user
            $info = user::where('id', $request->id)->limit(1)->get();
            if ($info[0]->user_type == 'student0') {
                $info[0]->user_type = 'In cautare';
            } else if ($info[0]->user_type == 'student1') {
                $info[0]->user_type = 'Student';
                $info[0]->activities = activities::where('user_id', $request->id)->get();
            } else if ($info[0]->user_type == 'universitate') {
                $info[0]->user_type = 'Universitate';
            } else if ($info[0]->user_type == 'profesor') {
                $info[0]->user_type = 'Profesor';
                $info[0]->activities = activities::where('user_id', $request->id)->get();
            }
        }
        $info = $info[0];
        return view('pages.authentificated.timeline')->with(['user_info' => $info, 'page' => 'timeline']);
    }
    // FRIENDS API
    public function edit_friendship(Request $request)
    {
        $friendsOfMine = Friends::where([['user_id', auth()->user()->id], ['friend_id', $request->user['id']]])->limit(1)->get();
        $friendFor = Friends::where([['friend_id', auth()->user()->id], ['user_id', $request->user['id']]])->limit(1)->get();
        $friends = $friendsOfMine->merge($friendFor);
        $nr_friends = count($friends);

        if ($nr_friends == 0) {
            $friendship = new Friends;
            $friendship->friend_id = $request->user['id'];
            $friendship->user_id = auth()->user()->id;
            if ($request->action == 'block') {
                $friendship->blocked_by_id = auth()->user()->id;
            }
            $friendship->save();
        } else if ($nr_friends == 1) {
            $friends = $friends[0];
            $friendship = Friends::find($friends)[0];
            if ($request->action == 'friends') {
                $friendship->active = 1;
                $friendship->save();
            } else if ($request->action == 'not_friends') {
                $friendship->delete();
            } else if ($request->action == 'block' && $friends['blocked_by_id'] == null) {
                $friendship->blocked_by_id = auth()->user()->id;
                $friendship->save();
            }
        }
        $friendsOfMine = Friends::where('user_id', auth()->user()->id)->where('friend_id', $request->user['id'])->limit(1)->get();
        $friendFor = Friends::where('friend_id', auth()->user()->id)->where('user_id', $request->user['id'])->limit(1)->get();
        $friends = $friendsOfMine->merge($friendFor);
        $nr_friends = count($friends);

        if ($nr_friends == 0)
            $status = 'not_friends';
        else if ($nr_friends == 1) {
            $friendship = $friends[0];
            if ($friendship->blocked_by_id == null)
                if ($friendship->active == 0)
                    if ($friendship->friend_id == $request->user['id'])
                        $status = 'invited_by_me';
                    else $status = 'invite_me';
                else
                    $status = 'friends';

            else if ($friendship->blocked_by_id == auth()->user()->id)
                $status = 'blocked_by_me';
            else $status = 'block_me';
        }

        return $status;
    }
    // CHAT WEB
    public function show_messages(Request $request)
    {
        $user_id = $request->selected_user_id;
        if (!empty($user_id) && $user_id !== 0) {
            // check status with this user 
            $status = friends::where([['user_id', auth()->user()->id], ['friend_id', $user_id]])->orWhere([['friend_id', auth()->user()->id], ['user_id', $user_id]])
                ->limit(1)->get()->toArray();
            if (count($status) == 0 || $status[0]['blocked_by_id'] == null)
                $status = 0;
            else 
            if ($status[0]['blocked_by_id'] == auth()->user()->id)
                $status = 'blocked_by_me';
            else
                $status = 'block_me';

            $user = user::select('id', db::raw('concat(name, " ", surname) as name'), 'avatar_path', db::raw('"No one message" as last_message'))
                ->where('id', $user_id)
                ->get()->toArray();
            $room_id = message_room::where([['creator_id', auth()->user()->id], ['friend_id', $user_id]])
                ->orWhere([['creator_id', $user_id], ['friend_id', auth()->user()->id]])
                ->select('id')->get()->toArray();
            if ($room_id) {
                $room_id = $room_id[0]['id'];
                if ($request->messages) {
                    $id = last($request->messages)['id'];
                    $messages = messages::where([['room_id', $room_id], ['id', '>', $id]])->get()->toArray();
                } else
                    $messages = messages::where([['room_id', $room_id]])->get()->toArray();
                $last_message = messages::where([['room_id', $room_id], ['sender_id', $user_id]])
                    ->orWhere([['room_id', $room_id], ['sender_id', auth()->user()->id]])
                    ->select('updated_at')->orderBy('updated_at', 'desc')->limit(1)->get()->toArray();
                if ($last_message) {
                    $last_message = $last_message[0];
                    $user[0]['last_message'] = $last_message['updated_at'];
                }
            }
            if (!empty($messages)) {
                for ($i = 0; $i < count($messages); $i++) {
                    $images_for_message = images::where([['table', 'messages'], ['foreign_id', $messages[$i]['id']]])->get()->toArray();
                    if (count($images_for_message) == 0)
                        $messages[$i]['images'] = 0;
                    else $messages[$i]['images'] = $images_for_message;
                }
            } else $messages = [];
        } else {
            $user_id = auth()->user()->id;
            $messages = [];
            $status = 0;
        }
        $users_with_have_conversations_half1 = message_room::where([['creator_id', auth()->user()->id], ['friend_id', '!=', $user_id]])
            ->select('id as room_id', 'friend_id as prieten_id')->get()->toArray();
        $users_with_have_conversations_half2 = message_room::where([['friend_id', auth()->user()->id], ['creator_id', '!=', $user_id]])
            ->select('id as room_id', 'creator_id as prieten_id')->get()->toArray();
        $users_with_have_conversations = array_merge($users_with_have_conversations_half1, $users_with_have_conversations_half2);

        $users_other = [];
        if ($users_with_have_conversations) {
            foreach ($users_with_have_conversations as $key => $user1) {
                $user_other = user::select('id', db::raw('concat(name, " ", surname) as name'), 'avatar_path', db::raw('"No one message" as last_message'))
                    ->where('id', $user1['prieten_id'])
                    ->limit(1)->get()->toArray();
                if ($user_other) {
                    $user_other = $user_other[0];
                    array_push($users_other, $user_other);
                }
                $last_message = messages::where([['room_id', $user1['room_id']], ['sender_id', $user1['prieten_id']]])
                    ->orWhere([['room_id', $user1['room_id']], ['sender_id', auth()->user()->id]])
                    ->select('updated_at')->orderBy('updated_at', 'desc')->limit(1)->get()->toArray();
                if ($last_message) {
                    $last_message = $last_message[0];
                    $users_other[$key]['last_message'] = $last_message['updated_at'];
                }
            }
            $columns = array_column($users_other, 'last_message');
            array_multisort($columns, SORT_DESC, $users_other);
            if ($user_id !== 0 && $user_id !== auth()->user()->id)
                $users = array_merge($user, $users_other);
            else
                $users = $users_other;
        } else if ($user_id !== 0 && $user_id !== auth()->user()->id)
            $users = $user;
        else $users = [];
        return ['users' => $users, 'messages' => $messages, 'status' => $status];
    }
    // CHAT API
    public function insert_message(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'files*' => 'image',
        ]);
        $room = message_room::where([['creator_id', auth()->user()->id], ['friend_id', $request->selected_user]])->orWhere([['friend_id', auth()->user()->id], ['creator_id', $request->selected_user]])
            ->limit(1)->get()->toArray();
        if (empty($room)) {
            $room = new message_room();
            $room->creator_id = auth()->user()->id;
            $room->friend_id = $request->selected_user;
            $room->save();
            $room_id = DB::getPdo()->lastInsertId();
            // return public_path();
            $path = '/img/messages/room_' . $room_id;
            File::makeDirectory(public_path() . $path);
        } else {
            $room_id = $room[0]['id'];
            $path = '/img/messages/room_' . $room_id;
        }
        $message = new messages();
        $message->sender_id = auth()->user()->id;
        $message->room_id = $room_id;
        $message->text = $request->message;
        $message->save();
        $message_id = DB::getPdo()->lastInsertId();

        if (!empty($request->file('files')) && count($request->file('files')) > 0) {
            for ($i = 0; $i < count($request->file('files')); $i++) {
                $image = new images();
                $image->foreign_id = $message_id;
                $image->table = 'messages';

                $extension = $request->file('files')[$i]->getClientOriginalExtension();
                $file_name = rand() . time() . '.' . $extension;
                $request->file('files')[$i]->move(public_path() . $path, $file_name);
                $image->path = $path . '/' . $file_name;
                $image->save();
            }
        }
    }
    // POST API
    public function insert_post(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required',
            'type_of_post' => 'required',
            'note' => 'exclude_if:type_of_post,post|required|numeric|min:1|max:5',
            'files*' => 'image',
        ]);
        if (Auth::check())
            if ($request->selected_user == 0) $request->selected_user = auth()->user()->id;

        if ($request->action == 'insert') {
            $post = new posts_reviews();
        } else {
            $post = posts_reviews::where([['id', $request->post_id], ['by_user', auth()->user()->id]])->get()[0];
        }
        $post->text = $request->text;
        $post->by_user = auth()->user()->id;
        $post->for_user = $request->selected_user;
        $post->type = $request->type_of_post;
        if ($request->type_of_post == 'reviews')
            $post->note = $request->note;
        $post->save();
        if ($request->action == 'insert') {
            $post_id = $post->id;
        } else $post_id = $request->post_id;

        if (!empty($request->files_already_exist) && count($request->files_already_exist) > 0) {
            $images_to_delete = images::where([['foreign_id', $request->post_id]]);
            for ($i = 0; $i < count($request->files_already_exist); $i++) {
                $images_to_delete = $images_to_delete->where('id', '!=', $request->files_already_exist[$i]);
            }
            $images_to_delete = $images_to_delete->get();
            foreach ($images_to_delete as $key => $image) {
                $images_to_delete[$key]->delete();
            }
        } else if ($request->action == 'update') images::where([['foreign_id', $request->post_id]])->delete();
        if (!empty($request->file('files')) && count($request->file('files')) > 0) {
            if ($request->type_of_post == 'post')
                $path = '/img/posts_reviews/post_' . $post_id;
            else
                $path = '/img/posts_reviews/review_' . $post_id;
            if ($request->action == 'update') {
                images::where('foreign_id', $request->post_id)->delete();
                File::deleteDirectory(public_path() . $path);
            }

            File::makeDirectory(public_path() . $path);

            for ($i = 0; $i < count($request->file('files')); $i++) {
                $image = new images();
                $image->foreign_id = $post_id;
                $image->table = 'posts_reviews';

                $extension = $request->file('files')[$i]->getClientOriginalExtension();
                $file_name = rand() . time() . '.' . $extension;
                $request->file('files')[$i]->move(public_path() . $path, $file_name);

                $image->path = $path . '/' . $file_name;
                $image->save();
            }
        }

        $myFriends = $this->my_friends();

        $posts = posts_reviews::where([['id', $post_id], ['by_user', auth()->user()->id]])->limit(1)->get();
        $this->likes($posts[0], $post_id, $myFriends, 'posts_reviews');
        $this->images($posts[0], 'posts_reviews');
        $posts[0]->user = auth()->user();
        return ['posts' => $posts];
    }
    public function get_posts_reviews(Request $request)
    {
        $myFriends = $this->my_friends();

        if ($request->type_post == 'post') {
            if ($request->selected_user > 0)
                $posts = posts_reviews::where([['for_user', $request->selected_user], ['type', 'post']]);
            else {
                $posts = posts_reviews::where('type', 'post');
                if (count($myFriends) > 0)
                    foreach ($myFriends as $key => $value) {
                        $posts = $posts->where('for_user', $value->id);
                    }
                else $posts = $posts->where('for_user', auth()->user()->id);
            }
        } else
            $posts = posts_reviews::where([['for_user', $request->selected_user], ['type', 'reviews']]);
        $posts = $posts->get();

        foreach ($posts as $key => $post) {
            // default info
            $this->likes($posts[$key], $post->id, $myFriends, 'posts_reviews');
            $this->images($posts[$key], 'posts_reviews');
            // culegem informata despre autorul postarii
            $posts[$key]->user = user::where('id', $posts[$key]->by_user)->get()[0];
        }
        if (Auth::check())
            $my_avatar = auth()->user()->avatar_path;
        else $my_avatar = null;
        return ['posts' => $posts, 'my_avatar' => $my_avatar];
    }
    public function delete_post(Request $request)
    {
        posts_reviews::where([['id', $request->post_id], ['by_user', auth()->user()->id]])->delete();
        images::where([['foreign_id', $request->post_id], ['table', 'posts_reviews']])->delete();
    }
    public function like(Request $request)
    {
        $exist_likes_from_me = likes::where([['foreign_id', $request->id], ['table', $request->table_name], ['user_id', auth()->user()->id]])->get();
        if (count($exist_likes_from_me) > 0) {
            $row_with_like = likes::find($exist_likes_from_me[0]->id);
            if ($exist_likes_from_me[0]->type == $request->action)
                $row_with_like->delete();
            else {
                $row_with_like->type = $request->action;
                $row_with_like->save();
            }
        } else {
            $row_for_like = new likes;
            $row_for_like->table = $request->table_name;
            $row_for_like->foreign_id = $request->id;
            $row_for_like->type = $request->action;
            $row_for_like->user_id = auth()->user()->id;
            $row_for_like->save();
        }
        if ($request->table_name == 'posts_reviews')
            $item = posts_reviews::where('id', $request->id)->get()[0];
        else
            $item = comments::where('id', $request->id)->get()[0];

        $myFriends = $this->my_friends();
        $this->likes($item, $request->id, $myFriends, $request->table_name);
        return ['item' => $item];
    }
    // COMMENTS API
    public function get_comments(Request $request)
    {
        $comments = comments::where([['reply', '0'], ['post_id', $request->post_id]])->orderByDesc('updated_at')->get();
        $myFriends = $this->my_friends();
        for ($i = 0; $i < count($comments); $i++) {
            $this->relation($comments[$i], $comments[$i]->user_id);
            $this->likes($comments[$i], $comments[$i]->id, $myFriends, 'comentarii');
            $comments[$i]->user = user::where('id', $comments[$i]->user_id)->get()[0];
            $replies = comments::where('reply', $comments[$i]->id)->get();
            $comments[$i]->replies = $replies;
            for ($i1 = 0; $i1 < count($replies); $i1++) {
                $this->relation($comments[$i]->replies[$i1], $comments[$i]->replies[$i1]->user_id);
                $this->likes($comments[$i]->replies[$i1], $comments[$i]->replies[$i1]->id, $myFriends, 'comentarii');
                $comments[$i]->replies[$i1]->user = user::where('id', $comments[$i]->replies[$i1]->user_id)->get()[0];
            }
        }
        return ['comments' => $comments];
    }
    public function insert_comment(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'post_id' => 'required',
        ]);
        $comment = new comments();
        $comment->user_id = auth()->user()->id;
        $comment->message = $request->message;
        $comment->post_id = $request->post_id;
        if (!empty($request->id_reply)) {
            $comment->reply = $request->id_reply;
        }
        $comment->save();
        $comment->likes = 0;
        $comment->dislikes = 0;
        $comment->replies = [];
        $comment->user = auth()->user();
        if ($request->id_reply != 0) {
            $comment->replies = 0;
        }
        return ['comment' => $comment];
    }
    public function delete_comment(Request $request)
    {
        comments::where([['id', $request->id], ['user_id', auth()->user()->id]])->delete();
        comments::where('reply', $request->id)->delete();
        likes::where([['foreign_id', $request->id], ['table', 'comments']])->delete();
    }

    // repeat functions
    function images(&$object, $table_name)
    {
        // culegem imaginile
        $object->images = images::where([['foreign_id', $object->id], ['table', $table_name]])->get();
        foreach ($object->images as $key1 => $image) {
            $path = public_path() . $object->images[$key1]->path;
            $object->images[$key1]->size = floor(filesize($path) / 1024) . 'Kb';
            $object->images[$key1]->extension = pathinfo($path, PATHINFO_EXTENSION);
            $object->images[$key1]->name = basename($path, $object->images[$key1]->extension) . $object->images[$key1]->extension;
        }
    }
    function my_friends()
    {
        if (Auth::check())
            return
                Friends::select('user_id as prieten_id')->where([['friend_id', auth()->user()->id], ['active', 1], ['blocked_by_id', null]])->get()
                ->merge(Friends::select('friend_id as prieten_id')->where([['user_id', auth()->user()->id], ['active', 1], ['blocked_by_id', null]])->get());
        else return null;
    }
    function relation(&$object, $selected_id)
    {
        if (Auth::check() == false)
            return 'not_friends';
        $friends = Friends::where([['user_id', auth()->user()->id], ['friend_id', $selected_id]])
            ->orWhere([['friend_id', auth()->user()->id], ['user_id', $selected_id]])->limit(1)->get();
        $nr_friends = count($friends);

        if ($nr_friends == 0)
            $status = 'not_friends';
        else if ($nr_friends == 1) {
            $friendship = $friends[0];
            if ($friendship->blocked_by_id == null)
                if ($friendship->active == 0)
                    if ($friendship->friend_id == $selected_id)
                        $status = 'invited_by_me';
                    else $status = 'invite_me';
                else
                    $status = 'friends';

            else if ($friendship->blocked_by_id == auth()->user()->id)
                $status = 'blocked_by_me';
            else $status = 'block_me';
        }
        $object->status = $status;
    }
    function common_friends(&$object, $selected_id, $myFriends)
    {
        if (Auth::check() == false)
            return 0;
        $friendsUserTotal = friends::select('user_id as prieten_id')->where([['friend_id', $selected_id], ['active', '1'], ['blocked_by_id', null], ['user_id', '!=', auth()->user()->id]])->get()
            ->merge(friends::select('friend_id as prieten_id')->where([['user_id', $selected_id], ['active', '1'], ['blocked_by_id', null], ['friend_id', '!=', auth()->user()->id]])->get());
        $common_friends = 0;
        foreach ($myFriends as $key1 => $friendMy) {
            foreach ($friendsUserTotal as $key2 => $friendOfUser) {
                if ($friendOfUser['prieten_id'] == $friendMy['prieten_id']) {
                    $common_friends++;
                    break;
                }
            }
        }

        $object->common_friends = $common_friends;
    }
    function likes(&$object, $post_id, $myFriends, $table)
    {
        // culeg like-urile si dislike-urile pentru fiecare postare;
        $object->likes = likes::select('user_id', 'avatar_path', db::raw('concat(name," ",surname) as name'))->where([['foreign_id', $post_id], ['table', $table], ['type', 'like']])->join('users', 'users.id', '=', 'likes.user_id')->get();
        $object->dislikes = likes::select('user_id', 'avatar_path', db::raw('concat(name," ",surname) as name'))->where([['foreign_id', $post_id], ['table', $table], ['type', 'dislike']])->join('users', 'users.id', '=', 'likes.user_id')->get();
        foreach ($object->likes as $key1 => $like) {
            $this->relation($object->likes[$key1], $like->user_id);
            $this->common_friends($object->likes[$key1], $like->user_id, $myFriends);
        }
        foreach ($object->dislikes as $key1 => $dislike) {
            $this->relation($object->dislikes[$key1], $dislike->user_id);
            $this->common_friends($object->dislikes[$key1], $dislike->user_id, $myFriends);
        }
        // aflu daca am pus sau nu like/dislike la aceasta postare
        if (Auth::check()) {
            $liked_by_me = likes::where([['foreign_id', $post_id], ['table', $table], ['user_id', auth()->user()->id]])->get();
            if (count($liked_by_me)) {
                if ($liked_by_me[0]->type == 'like') {
                    $object->liked_by_me = 1;
                } else $object->liked_by_me = -1;
            } else $object->liked_by_me = 0;
        } else $object->liked_by_me = 0;
    }
    function delete_file($table_name, $id)
    {
        if ($table_name == 'files') {
            $file = files::where('id', $id)->first();
            if ($file)
                unlink($file->file_path);
        } else if ($table_name == 'compartments') {
            rmdir(public_path('img/files/compartment_' . $id));
        }
    }

    // FILES API
    public function delete_something(Request $request)
    {
        if (is_array($request->id) > 0)
            foreach ($request->id as $key => $value) {
                $this->delete_file($request->type, $value);
                db::table($request->type)->where('id', $value)->delete();
            }
        else {
            $this->delete_file($request->type, $request->id);
            db::table($request->type)->where('id', $request->id)->delete();
        }
    }
    public function get_info_files(Request $request)
    {
        if ($request->type == 'files') {

            if ($request->compartmentID)
                $send_info  = compartments::where([['id', $request->compartmentID], ['user_id', $request->selectedUserID]])->first();
            else {
                $send_info = compartments::where('user_id', $request->selectedUserID)->first();
            }
            $send_info  = $send_info->files()->orderBy('files.updated_at', 'desc')->get();
        } else
            return compartments::where('user_id', $request->selectedUserID)->get();
        return $send_info;
    }
    public function new_info(Request $request)
    {
        if ($request->table == 'compartments') {
            $request->validate([
                'input.name' => 'required|string|max:50',
                'input.description' => 'required|string|max:100',
                'selectedUserID' => 'required|integer|exists:users,id',
            ]);
            $new_compartment = new compartments;
            $new_compartment->name = $request->input['name'];
            $new_compartment->description = $request->input['description'];
            $new_compartment->user_id = $request->selectedUserID;
            $new_compartment->save();
        } else {
            $request->validate([
                'files*' => 'mimetypes:application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                text/plain, application/pdf',
                'compartmentID' => 'integer|exists:compartments,id',
            ]);
            if (!empty($request->file('files')) && count($request->file('files')) > 0) {
                $path = public_path() . '/img/files/compartment_' . $request->compartmentID;
                // return $request->file('files');
                if (File::isDirectory($path) == false) {
                    File::makeDirectory($path);
                }
                for ($i = 0; $i < count($request->file('files')); $i++) {

                    $extension = $request->file('files')[$i]->getClientOriginalExtension();
                    $file_name = rand() . time() . '.' . $extension;
                    if (file_exists(public_path() . $file_name) == false)
                        $request->file('files')[$i]->move($path, $file_name);

                    $file = files::create(
                        [
                            'name' => $request->file('files')[$i]->getClientOriginalName(),
                            'file_path' => $path . '/' . $file_name
                        ]
                    );
                    compartments::where('id', $request->compartmentID)->first()->files()->save($file);
                }
            }
        }
        return;
    }
}
