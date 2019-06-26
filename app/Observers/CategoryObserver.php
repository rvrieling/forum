<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Sub;
use App\Models\User;

class CategoryObserver
{
    /**
     * Handle the category "created" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        $users = User::get();

        foreach ($users as $user) {
            Sub::create([
                'category_id' => $category->id,
                'user_id'     => $user->id,
                'subscribed'  => 0
            ]);
        }
    }
    public function deleted(Category $category)
    {
        Sub::query()
            ->where('category_id', $category->id)
            ->delete();
    }
}
