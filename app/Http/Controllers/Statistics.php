<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activities;
use App\Models\Friends;
use App\Models\message_room;
use App\Models\messages;
use App\Models\images;
use App\Models\posts_reviews;
use App\Models\likes;
use App\Models\comments;

class Statistics extends Controller
{
    //
    public function GetStatistics(Request $request)
    {
        $getStatistics = $request->dictionar;
        for ($i = 0; $i < count($getStatistics); $i++) {
            if ($getStatistics[$i]['title'] == 'Clienti totali' || $getStatistics[$i]['title'] == 'Clienti online') {
                $getStatistics[$i]['count'] = User::count();
            } else if ($getStatistics[$i]['title'] == 'Postari totale') {
                $getStatistics[$i]['count'] = posts_reviews::where('type', 'post')->count();
            } else if ($getStatistics[$i]['title'] == 'Recenzii totale') {
                $getStatistics[$i]['count'] = posts_reviews::where('type', 'reviews')->count();
            } else if ($getStatistics[$i]['title'] == 'Like-uri totale') {
                $getStatistics[$i]['count'] = likes::where('type', 'like')->count();
            } else if ($getStatistics[$i]['title'] == 'Dislike-uri totale') {
                $getStatistics[$i]['count'] = likes::where('type', 'dislike')->count();
            }
        }
        return $getStatistics;
    }
}
