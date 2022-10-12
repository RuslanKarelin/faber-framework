<?php

namespace Faber\Core\Controllers\Auth;

use App\Models\User;
use Faber\Core\Contracts\Validator\Validator as IValidator;
use Faber\Core\Enums\Http;
use Faber\Core\Facades\Hash;
use Faber\Core\Facades\Validator;
use Faber\Core\Request\Request;
use Faber\Core\Response\Response;

class RegisterController
{
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
            'login' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function registerForm()
    {
        $this->response->setStatus(Http::OK)->view('auth.register', [],
            config('app.resourcesPath.faber'));
    }

    public function register()
    {
        $requestData = $this->request->all();
        if ($this->validator($requestData)->validate()) {
            if ($requestData['password']) $requestData['password'] = Hash::make($requestData['password']);
            User::create($requestData);
            $this->response->redirectTo($this->redirectTo);
        }
        $this->response->redirectTo($this->request->session()->previous());
    }
}