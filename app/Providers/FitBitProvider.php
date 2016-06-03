<?php
/**
 * Created by PhpStorm.
 * User: michaelpouris
 * Date: 2015-11-01
 * Time: 9:37 PM
 */

namespace App\Providers;


use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use GuzzleHttp\ClientInterface;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;

class FitBitProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['activity', 'profile'];

    /**
     * The fields that are included in the profile.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl)
    {
        $this->scopeSeparator = ' ';
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://www.fitbit.com/oauth2/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.fitbit.com/oauth2/token';
    }

    /**
     * Get the access token for the given code.
     *
     * @param  string  $code
     * @return string
     */
    public function getAccessToken($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
            ],
            $postKey => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody());
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.fitbit.com/1/user/-/profile.json', [
            'headers' => [
                'Authorization' => 'Bearer '. $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['user']['encodedId'],
            'nickname' => null,
            'name' => $user['user']['fullName'],
            'avatar' => $user['user']['avatar'],
            'avatar_original' => $user['user']['avatar'],
        ]);
    }

    /**
     * Set the user fields to request from FitBit.
     *
     * @param  array  $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }
}