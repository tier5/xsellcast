<?php

namespace App\Storage\Csr;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Storage\Csr\CsrRepository;
use App\Storage\Csr\Csr;
use App\Storage\Csr\CsrValidator;
use Snowfire\Beautymail\Beautymail;
use App\Storage\User\User;
use App\Storage\Role\Role;

/**
 * Class CsrRepositoryEloquent
 * @package namespace App\Storage\Csr;
 */
class CsrRepositoryEloquent extends BaseRepository implements CsrRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Csr::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function sendEmailAllNotification($subject, $view_body)
    {
        $csrs = $this->model->get();

        foreach($csrs as $csr)
        {
            if(!$csr->is_email_notify)
            {
                continue;
            }

            // $beautymail = app()->make(Beautymail::class);
            // $user = $csr->user;

            // $beautymail->send($view_body, [], function($message) use($user, $subject)
            // {

            //   $message
            //         ->from('admin@xsellcast.com')
            //         ->to($user->email, $user->firstname . ' ' . $user->lastname)
            //         ->subject($subject);
            // });
        }
    }

    public function sendUnmatchLeadNotify()
    {
        $this->sendEmailAllNotification('Unmatched Lead Alert', 'emails.csr.unmatched-lead');

    }

    public function createOne($data)
    {
        $role = Role::where('name', 'csr')->first();
        $user         = User::create([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'password'  => bcrypt($data['password']),
            'email'     => $data['email'] ]);

        $user->roles()->save($role);

        return $user->csr;
    }
}
