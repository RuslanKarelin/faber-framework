<?php

namespace Faber\Core\Controllers\Auth;

use App\Models\User;
use Faber\Core\Contracts\Validator\Validator as IValidator;
use Faber\Core\Enums\Http;
use Faber\Core\Facades\Hash;
use Faber\Core\Facades\Validator;
use Faber\Core\Request\Request;
use Faber\Core\Response\Response;
use Faber\Core\Enums\Session as SessionEnum;

class LoginController
{
    protected string $login = 'login';
    protected string $redirectTo = '/';

    public function __construct(
        protected Response $response,
        protected Request $request
    )
    {
    }

    protected function validator(array $data): IValidator
    {
        return Validator::make($data, [
            $this->login => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    public function loginForm()
    {
        $this->response->setStatus(Http::OK)->view('auth.login', [],
            config('app.resourcesPath.faber'));
    }

    public function login()
    {
        $session = $this->request->session();
        if ($this->validator($this->request->all())->validate()) {
            $user = User::where($this->login, $this->request->get($this->login))
                ->limit(1)
                ->get()
                ->first();
            if ($user && Hash::check($this->request->get('password'), $user->password)) {
                $session->set(SessionEnum::USER_ID, $user->{$user->getKeyName()});
                $session->write();
                $this->response->redirectTo($this->redirectTo);
            }
        }
        $session->error('auth.fail', trans('validator.errors.auth.fail'));
        $session->write();
        $this->response->redirectTo($session->previous());
    }

    public function logout()
    {
        $session = $this->request->session();
        $session->delete(SessionEnum::USER_ID);
        $session->write();
        $this->response->redirectTo($this->redirectTo);
    }
}