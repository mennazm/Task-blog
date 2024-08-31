<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function stats()
    {
        $stats = Cache::remember('stats', 60*60, function () {
            $totalUsers = User::count();
            $totalPosts = Post::count();
            $usersWithNoPosts =  User::has('posts', '=', 0)->count();

            return [
                'total_users' => $totalUsers,
                'total_posts' => $totalPosts,
                'users_with_no_posts' => $usersWithNoPosts,
            ];
        });

        return response()->json($stats);
    }
}
