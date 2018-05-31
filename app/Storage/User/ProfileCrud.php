<?php namespace App\Storage\User;

use App\Storage\Crud\Box;
use App\Storage\Crud\CrudForm;
use App\Storage\LocalApiRequest\LocalApiRequest;

class ProfileCrud {

    public static function csrForm($opts) {
        $apiRequest   = new LocalApiRequest();
        $access_token = $apiRequest->getToken();
        $fields       = new CrudForm('post');
        $user         = $opts['view_args']['user'];

        $fields->setRoute('admin.settings.profile.save.csr');

        $fields->addField(array(
            'name'      => 'avatar',
            'label'     => 'Change Photo',
            'accepts'   => 'image/*',
            'is_single' => true,
            'type'      => 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
            'col-class' => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'   => 'Change Photo',
            'value'     => [$user->avatarId()],
            'clear_all' => true));
        $fields->addField(array(
            'name'      => 'firstname',
            'label'     => 'First name',
            'type'      => 'text',
            'value'     => $user->firstname,
            'col-class' => 'col-md-6 col-xs-12'));
        $fields->addField(array(
            'name'      => 'lastname',
            'label'     => 'Last name',
            'type'      => 'text',
            'value'     => $user->lastname,
            'col-class' => 'col-md-6 col-xs-12'));
        $fields->addField(array(
            'name'      => 'email',
            'label'     => 'Email',
            'type'      => 'email',
            'col-class' => 'col-md-6 col-xs-12',
            'value'     => $user->email,
            'clear_all' => true));

        $fields->showDefaultSubmit(false)->addSubmitBtn('update', 'Update Profile');

        $info = array(
            'box_title'    => 'Profile',
            'column_size'  => 12,
            'column_class' => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

    public static function salesrepForm($opts) {
        $apiRequest   = new LocalApiRequest();
        $access_token = $apiRequest->getToken();
        $fields       = new CrudForm('post');
        $user         = $opts['view_args']['user'];
        $salesrep     = $user->salesRep;
        $dealer       = $salesrep->dealers->first();
        $showModal    = \Request::session()->get('show_salesrep_agree_modal', false);

        $fields->setRoute('admin.settings.profile.save.salesrep');

        $fields->addField(array(
            'name'       => 'salesrep_agreement',
            'type'       => 'App\Storage\Crud\CustomFields@salesrepAgreement',
            'value'      => (string) $salesrep->is_agreement,
            'show_modal' => $showModal,
            'col-class'  => 'col-xs-12',
            'agreed'     => $salesrep->is_agreement,
            'clear_all'  => true));

        $fields->addField(array(
            'name'      => 'avatar',
            'label'     => 'Change Photo',
            'accepts'   => 'image/*|video/*',
            'is_single' => true,
            'type'      => 'App\Storage\Media\MediaFieldCustomFields@mediaUpload',
            'col-class' => 'col-lg-8 col-md-12 col-sm-12 col-xs-12',
            'btn_txt'   => 'Change Photo',
            'value'     => [$user->avatarId()],
            'clear_all' => true));

        $fields->addField(array(
            'name'       => 'local_agreed_at_date',
            'type'       => 'text',
            'label'      => 'Agreement Accepted',
            'value'      => ($salesrep->local_agreed_at ? $salesrep->local_agreed_at->format('m/d/Y \a\t h:i a') : ''),
            'field-attr' => ['disabled' => 'disabled'],
            'col-class'  => 'col-md-6 col-xs-12'));

        $fields->addField(array(
            'name'      => 'terms_buttons',
            'col-class' => 'col-md-6 col-xs-12',
            'type'      => 'App\Storage\Crud\CustomFields@termsButton',
            'clear_all' => true));

        $fields->addField(array(
            'name'      => 'firstname',
            'label'     => 'First name',
            'type'      => 'text',
            'value'     => $user->firstname,
            'col-class' => 'col-md-6 col-xs-12'));
        $fields->addField(array(
            'name'      => 'lastname',
            'label'     => 'Last name',
            'type'      => 'text',
            'value'     => $user->lastname,
            'col-class' => 'col-md-6 col-xs-12'));
        $fields->addField(array(
            'name'      => 'jobtitle',
            'label'     => 'Job Title',
            'type'      => 'text',
            'col-class' => 'col-md-6 col-xs-12',
            'value'     => $salesrep->job_title,
            'clear_all' => true));
        $fields->addField(array(
            'name'         => 'dealer',
            'label'        => 'Dealer',
            'type'         => 'App\Storage\Crud\CustomFields@selectDealerModal',
            'col-class'    => 'col-md-6 col-xs-12',
            'value'        => (isset($dealer->id) ? [$dealer->id, $dealer->name] : null),
            'access_token' => $access_token,
            'clear_all'    => true));

        $fields->addField(array(
            'name'      => 'email',
            'label'     => 'Personal Email',
            'type'      => 'email',
            'col-class' => 'col-md-6 col-xs-12',
            'value'     => $user->email));
        $fields->addField(array(
            'name'      => 'work_email',
            'label'     => 'Work Email',
            'type'      => 'email',
            'value'     => $salesrep->email_work,
            'col-class' => 'col-md-6 col-xs-12'));

        $fields->addField(array(
            'name'       => 'cellphone',
            'label'      => 'Cell Phone',
            'type'       => 'text',
            'value'      => $salesrep->cellphone,
            'field-attr' => ['data-mask' => '(999) 999-9999'],
            'col-class'  => 'col-md-6 col-xs-12'));
        $fields->addField(array(
            'name'       => 'officephone',
            'label'      => 'Office Phone',
            'type'       => 'text',
            'value'      => $salesrep->officephone,
            'field-attr' => ['data-mask' => '(999) 999-9999'],
            'col-class'  => 'col-md-6 col-xs-12'));
        $fields->addField(array(
            'label'     => 'Prospects should be shown:',
            'type'      => 'App\Storage\Crud\CustomFields@h2Field',
            'col-class' => 'col-md-12 col-xs-12'));
        $fields->addField(array(
            'name'      => 'show_fields',
            'type'      => 'App\Storage\User\ProfileCrud@showField',
            'col-class' => 'col-md-6 col-xs-12',
            'list'      => ['show_cellphone' => 'Cell Phone', 'show_email' => 'Email', 'show_officephone' => 'Office Phone'],
            'value'     => ['show_cellphone' => $salesrep->show_cellphone, 'show_officephone' => $salesrep->show_officephone, 'show_email' => $salesrep->show_email],
            'clear_all' => true));
        $fields->addField(array(
            'label'     => 'Social Accounts',
            'type'      => 'App\Storage\Crud\CustomFields@h2Field',
            'col-class' => 'col-md-12 col-xs-12'));
        $fields->addField(array(
            'name'      => 'facebook',
            'label'     => 'Facebook',
            'type'      => 'text',
            'value'     => $salesrep->facebook,
            'col-class' => 'col-md-4 col-xs-12',
        ));
        $fields->addField(array(
            'name'      => 'twitter',
            'label'     => 'Twitter',
            'type'      => 'text',
            'value'     => $salesrep->twitter,
            'col-class' => 'col-md-4 col-xs-12'));
        $fields->addField(array(
            'name'      => 'linkedin',
            'label'     => 'LinkedIn',
            'type'      => 'text',
            'value'     => $salesrep->linkedin,
            'col-class' => 'col-md-4 col-xs-12'));
        $fields->addField(array(
            'name'      => 'pinterest',
            'label'     => 'Pinterest',
            'type'      => 'text',
            'value'     => $salesrep->pinterest,
            'col-class' => 'col-md-4 col-xs-12'));
        $fields->addField(array(
            'name'      => 'instagram',
            'label'     => 'Instagram',
            'type'      => 'text',
            'value'     => $salesrep->instagram,
            'col-class' => 'col-md-4 col-xs-12'));
        $fields->addField(array(
            'name'      => 'youtube',
            'label'     => 'Youtube',
            'type'      => 'text',
            'value'     => $salesrep->youtube,
            'col-class' => 'col-md-4 col-xs-12'));

        $fields->showDefaultSubmit(false)->addSubmitBtn('update', 'Update Profile');

        $info = array(
            'box_title'    => 'Profile',
            'column_size'  => 12,
            'column_class' => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

    public function showField($crud_field) {
        return view('admin.settings.fields.profile-show', compact('crud_field'));
    }

    public static function changePass() {
        $fields = new CrudForm('post');

        $fields->setRoute('admin.settings.save.password');
        $fields->showDefaultSubmit(false)->addSubmitBtn('save', 'Save');

        $fields->addField(array(
            'name'      => 'current_password',
            'type'      => 'password',
            'label'     => 'Current password',
            'value'     => '',
            'col-class' => 'col-md-6 col-xs-12',
            'clear_all' => true));

        $fields->addField(array(
            'name'      => 'new_password',
            'type'      => 'password',
            'label'     => 'New password',
            'value'     => '',
            'col-class' => 'col-md-6 col-xs-12'));

        $fields->addField(array(
            'name'      => 'new_password_confirmation',
            'type'      => 'password',
            'label'     => 'Confirm new password',
            'value'     => '',
            'col-class' => 'col-md-6 col-xs-12',
            'clear_all' => true));

        $info = array(
            'box_title'    => 'Change Password',
            'column_size'  => 12,
            'column_class' => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

    public static function fbUserSetPass() {
        $fields = new CrudForm('post');

        $fields->setRoute('admin.settings.save.password');
        $fields->showDefaultSubmit(false)->addSubmitBtn('save', 'Save');

        $fields->addField(array(
            'name'      => 'current_password',
            'type'      => 'hidden',
            'value'     => 1,
            'label'     => 'Current password',
            'col-class' => 'col-md-6 col-xs-12',
            'clear_all' => true));

        $fields->addField(array(
            'name'      => 'new_password',
            'type'      => 'password',
            'label'     => 'Password',
            'col-class' => 'col-md-6 col-xs-12'));

        $fields->addField(array(
            'name'      => 'new_password_confirmation',
            'type'      => 'password',
            'label'     => 'Confirm password',
            'col-class' => 'col-md-6 col-xs-12',
            'clear_all' => true));

        $info = array(
            'box_title'    => 'Set Password',
            'column_size'  => 12,
            'column_class' => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }

    public static function notificationsForm($opts) {
        $fields = new CrudForm('post');
        $fields->setRoute('admin.settings.notifications.save');

        $fields->addField(array(
            'type'      => 'App\Storage\Crud\CustomFields@paragraph',
            'label'     => 'Would you like to receive email notifications anytime you receive new messages in Xsellcast?',
            'col-class' => 'col-md-6',
            'clear_all' => true));

        $fields->addField(array(
            'name'      => 'email_notify',
            'type'      => 'radio',
            'label'     => ' ',
            'value'     => $opts['view_args']['user']->is_email_notify,
            'list'      => [1 => 'Yes', 0 => 'No'],
            'col-class' => 'col-md-6',
            'clear_all' => true));

        $fields->showDefaultSubmit(false)->addSubmitBtn('save', 'Save');

        $info = array(
            'box_title'    => 'Enable Notification',
            'column_size'  => 12,
            'column_class' => 'col-sm-12 col-xs-12');

        $box = new Box($info);
        $box->setForm($fields);

        return $box;
    }
}