<?php namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Storage\User\UserRepository;
use App\Storage\Media\MediaRepository;
use Auth;

class ProfileComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $user;

    protected $media;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $user, MediaRepository $media)
    {
        // Dependencies automatically resolved by service container...
        $this->user = $user;
        $this->media = $media;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $mediaId = Auth::user()->avatarId();
        $avatar = null;

        if($mediaId){
            $media = $this->media->skipPresenter()->find($mediaId);
            $avatar = ($media ? $media->getSize(150, 100) : null);            
        }
        
        $view->with('avatar_48', $avatar);
    }
}