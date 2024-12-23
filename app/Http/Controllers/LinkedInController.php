<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\LinkedIn;
use Illuminate\Support\Facades\Session;

class LinkedInController extends Controller
{
    private $linkedIn;

    public function __construct()
    {
        $this->linkedIn = new LinkedIn([
            'clientId'     => env('LINKEDIN_CLIENT_ID'),
            'clientSecret' => env('LINKEDIN_CLIENT_SECRET'),
            'redirectUri'  => route('linkedin.callback'),
        ]);
    }

    /**
     * Redirect the user to LinkedIn's authentication page.
     */
    public function redirect()
    {
        $authUrl = $this->linkedIn->getAuthorizationUrl([
            'scope' => ['openid', 'profile', 'email', 'w_member_social'],
        ]);

        Session::put('linkedin_oauth_state', $this->linkedIn->getState());
        \Log::info('LinkedIn OAuth state stored:', ['state' => $this->linkedIn->getState()]);

        return redirect($authUrl);
    }

    /**
     * Handle LinkedIn callback and obtain an access token.
     */
    public function handleCallback(Request $request)
    {
        $state = $request->input('state');
        $sessionState = Session::get('linkedin_oauth_state');
    
        if (!$state || $state !== $sessionState) {
            \Log::error('Invalid LinkedIn authorization state.', [
                'state' => $state,
                'sessionState' => $sessionState,
            ]);
            return redirect('/')->with('error', 'Invalid LinkedIn authorization state.');
        }
    
        \Log::info('Authorization code received.', ['code' => $request->input('code')]);
    
        try {
            // Fetch access token
            $token = $this->linkedIn->getAccessToken('authorization_code', [
                'code' => $request->input('code'),
            ]);
            \Log::info('Access token retrieved successfully.', ['token' => $token->getToken()]);
    
            // Fetch user profile via OpenID Connect
            $response = $this->linkedIn->getHttpClient()->request('GET', 'https://api.linkedin.com/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token->getToken(),
                ],
            ]);
    
            $userData = json_decode($response->getBody(), true);
    
            if (!isset($userData['sub'])) {
                \Log::error('Unexpected response from LinkedIn /v2/userinfo', ['response' => $userData]);
                return redirect('/')->with('error', 'Failed to fetch LinkedIn user data.');
            }
    
            // Extract user information
            $linkedinUserId = $userData['sub']; // Unique LinkedIn user ID
            $email = $userData['email'] ?? 'Not provided';
            $name = $userData['name'] ?? 'Not provided';
            $profilePicture = $userData['picture'] ?? null;
    
            // Store user details in session or database
            Session::put('linkedin_user_id', $linkedinUserId);
            Session::put('linkedin_user_email', $email);
            Session::put('linkedin_user_name', $name);
            Session::put('linkedin_user_picture', $profilePicture);
            Session::put('linkedin_access_token', $token->getToken());
    
            \Log::info('LinkedIn user connected successfully.', [
                'LinkedIn ID' => $linkedinUserId,
                'Email' => $email,
                'Name' => $name,
            ]);
    
            // Redirect to a page where posting can be initiated
            $msg = $this->postToLinkedIn($request);

            if($msg == 201){
                return redirect('/Job/dissatisfaction');
            }

        } catch (\Exception $e) {
            \Log::error('Failed to handle LinkedIn callback.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect('/')->with('error', 'Failed to connect to LinkedIn: ' . $e->getMessage());
        }
    }
    


    /**
     * Post content to LinkedIn.
     */
    public function postToLinkedIn(Request $request)
    { 
        $accessToken = Session::get('linkedin_access_token');
        $linkedinUserUrn = Session::get('linkedin_user_id');
        $description = $request->job_description_text;
       
        if(!$accessToken) {
            $description = Session::put('description', $request->job_description_text);
            return redirect()->route('linkedin.auth')->with('error', 'Please connect to LinkedIn first.');
        }

        try {
            $response = $this->linkedIn->getHttpClient()->request('POST', 'https://api.linkedin.com/v2/ugcPosts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'author' => 'urn:li:person:' . $linkedinUserUrn,
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $description,
                            ],
                            'shareMediaCategory' => 'NONE',
                        ],
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
                    ],
                ],
            ]);

            if(Session::get('description') == null){
                return redirect('/Job/dissatisfaction');
            }else{
                $request->session()->forget('description');
                return $response->getStatusCode();
            }


        } catch (\Exception $e) {
            \Log::error('LinkedIn Post Error: ' . $e->getMessage(), [
                'response' => $e,
            ]);
            return redirect('/')->with('error', 'Failed to post on LinkedIn: ' . $e);
        }
    }
    

}
