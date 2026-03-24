<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use App\Models\UserAssignment;

class FoodRuService
{

    public function __construct() {}

    public static function getToken(): string
    {
        return (new \App\Lib\AuthX5ID())->getActualAccessToken();
    }

    public static function pullActions(User $user): void
    {
        try {
            $token = static::getToken();
            $actionStatus = (new \App\Lib\FoodRuApi($token))->status($user->x5id);
        } catch (\Exception $e) {
            return;
        }
        //$actionStatus = ['action_1' => true, 'action_2' => true, 'action_3' => true, 'action_4' => true];
        $actions = ['action_2', 'action_3', 'action_4'];
        $asByAction = Assignment::query()->whereIn('payload', $actions)->get()->keyBy('payload');
        foreach ($actions as $action) {
            $assignment = $asByAction[$action];
            $isActionFinished = (bool)$actionStatus[$action];
            if ($isActionFinished) {
                $fields = ['assignment_id' => $assignment->id, 'user_id' => $user->id];
                $wasFinished = UserAssignment::query()->where($fields)->exists();
                if (!$wasFinished) {
                    if ($finishAt = $actionStatus[$action . '_finish_at']) { // format "2024-11-26T11:27:54.835355"
                        $fields['finish_at'] = \Carbon\Carbon::parse($finishAt);
                    }
                    UserAssignment::create([...$fields, 'points' => $assignment->points]);
                }
            }
        }
    }

    public static function registerUser(User $user): bool
    {
        try {
            $token = static::getToken();
            $registered = (new \App\Lib\FoodRuApi($token))->register($user);
            return $registered;
        } catch (\Exception $e) {
            return false;
        }
    }
}
