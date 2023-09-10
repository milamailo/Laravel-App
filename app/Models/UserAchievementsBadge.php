<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievementsBadge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'comment_level',
        'Lesson_level',
        'total_comments',
        'total_lessons',
        'badge_level',
    ];

    /**
     * Define an accessor to calculate the total achievements.
     *
     * @return int
     */
    public function getTotalAchievements()
    {
        return $this->Lesson_level + $this->comment_level;
    }
}
