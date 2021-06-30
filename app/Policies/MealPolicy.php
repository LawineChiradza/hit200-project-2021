<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function view(User $user, Meal $meal)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function update(User $user, Meal $meal)
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function delete(User $user, Meal $meal)
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function restore(User $user, Meal $meal)
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Meal  $meal
     * @return mixed
     */
    public function forceDelete(User $user, Meal $meal)
    {
        return $user->admin;
    }

    public function advise(User $user)
    {
        return $user->admin;
    }
}
