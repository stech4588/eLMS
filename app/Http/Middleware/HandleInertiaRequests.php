<?php

namespace App\Http\Middleware;

use App\Helpers\MetaTagHelper;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $profileIncomplete = false;

        if ($user) {
            $profileIncomplete = empty($user->phone_number) ||
                                 empty($user->profile_picture) ||
                                 empty($user->primary_learning_goal) ||
                                 empty($user->preferred_topic_ids) ||
                                 empty($user->resume_path);
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'profile_incomplete' => $profileIncomplete,
            ],
            'stripe' => [
                'key' => config('services.stripe.key'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'course_id' => fn () => $request->session()->get('course_id'),
            ],
            'meta' => MetaTagHelper::getMetaTags(),
        ];
    }
}
