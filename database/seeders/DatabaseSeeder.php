<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Dữ liệu mẫu khớp schema users của dự án
     * (first_name/last_name/full_name/nickname/avatar/bio/tick/followings_count/likes_count/followed).
     */
    public function run(): void
    {
        $users = [
            ['Chloe', 'Ng', 'chloeeee', true, false, 120000, 2400000, 47],
            ['Mèo', 'Béo', 'cogaimeo', false, false, 2400, 98000, 32],
            ['Linh', 'Trần', 'travelwithlinh', true, false, 45000, 1200000, 12],
            ['Thịnh', 'Nguyễn', 'chefthinh', false, true, 8900, 420000, 59],
            ['Daily', 'Life', 'dailylife', true, true, 320000, 5400000, 68],
            ['Mimi', 'Phạm', 'mimin', false, false, 1300, 25000, 25],
            ['Tech', 'Review', 'techreview', true, false, 78000, 2100000, 3],
            ['Moon', 'Light', 'moonlight', false, true, 5400, 130000, 45],
            ['Happy', 'Day', 'happyday', false, false, 990, 12000, 14],
            ['Bảo', 'Lê', 'baole', true, false, 18000, 760000, 51],
        ];

        foreach ($users as [$first, $last, $nickname, $tick, $followed, $followings, $likes, $img]) {
            User::updateOrCreate(
                ['nickname' => $nickname],
                [
                    'first_name' => $first,
                    'last_name' => $last,
                    'full_name' => trim("$first $last"),
                    'avatar' => "https://i.pravatar.cc/150?img=$img",
                    'bio' => "Xin chào, mình là $first",
                    'tick' => $tick,
                    'followings_count' => $followings,
                    'likes_count' => $likes,
                    'followed' => $followed,
                ]
            );
        }
    }
}
