<?php

namespace simplerest\transformers;

use simplerest\core\Controller;

class UsersTransformer implements \simplerest\core\interfaces\ITransformer
{
	public function transform(object $user, Controller $controller = NULL)
    {
        return [
			'id' => $user->id,
			'username' => $user->username,
			'active' => $user->active,
			'email' => $user->email,
			'confirmed_email' => $user->confirmed_email,
			'password' => $user->password,
			'firstname' => 'Mr. ' . $user->firstname,
			'lastname' => $user->lastname,
			'full_name' => "{$user->firstname} {$user->lastname}",
			'deleted_at' => $user->deleted_at,
			'belongs_to' => $user->belongs_to
        ];
	}
}







