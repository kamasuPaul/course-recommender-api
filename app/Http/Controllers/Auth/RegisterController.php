<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\UserRepository;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\WeakPasswordRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Log;
use Ramsey\Uuid\Uuid;
use Jenssegers\Agent\Agent;


class RegisterController extends Controller
{
    use ValidatesRequests;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private UserRepository $userRepository)
    {
        $this->middleware('guest');
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param User    $user
     * @return mixed
     */
    protected function registered(Request $request, User $user)
    {
        // $this->guard()->logout();
        $user = $request->user();
        $data = $this->getDeviceInfo($request);
        $data['user_id'] = $user->id;

        // $this->createNewLoginHistory($user, $data);
        // $this->clearLoginAttempts($request);

        $token = (string)$this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return $this->respondWithCustomData([
            'token'     => $token,
            'tokenType' => 'Bearer',
            'expiresIn' => $expiration - time(),
        ],Response::HTTP_CREATED);

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => [
                'required',
                'string',
                'max:255',
            ],
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                new WeakPasswordRule(),
            ],
            'locale'   => [
                'nullable',
                'string',
                'in:en_US,pt_BR',
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data): Model
    {
        return $this->userRepository->store([
            'email'                       => $data['email'],
            'name'                        => $data['name'],
            'email_token_confirmation'    => Uuid::uuid4()->toString(),
            'email_token_disable_account' => Uuid::uuid4()->toString(),
            'password'                    => bcrypt($data['password']),
            'is_active'                   => 1,
            'email_verified_at'           => null,
            'locale'                      => $data['locale'] ?? 'pt_BR',
        ]);
    }
    private function getDeviceInfo(Request $request)
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        $agent->setHttpHeaders($request->headers);

        $geoip = geoip($request->ip());

        return [
            'user_id'          => auth()->id(),
            'ip'               => $request->ip(),
            'device'           => $agent->device(),
            'platform'         => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser'          => $agent->browser(),
            'browser_version'  => $agent->version($agent->browser()),
            'city'             => $geoip->getAttribute('city'),
            'region_code'      => $geoip->getAttribute('state'),
            'region_name'      => $geoip->getAttribute('state_name'),
            'country_code'     => $geoip->getAttribute('iso_code'),
            'country_name'     => $geoip->getAttribute('country'),
            'continent_code'   => $geoip->getAttribute('continent'),
            'latitude'         => $geoip->getAttribute('lat'),
            'longitude'        => $geoip->getAttribute('lon'),
            'zipcode'          => $geoip->getAttribute('postal_code'),
        ];
    }
}
