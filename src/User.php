<?php
declare(strict_types=1);

namespace RocketChat;

use Httpful\Request;

class User
{
    /** @var Client */
    private $client;
	private $username;
	private $password;
	public $id;
	public $nickname;
	public $email;

	public function __construct(Client $client, string $username, string $password, $fields = array())
    {
        $this->client = $client;
		$this->username = $username;
		$this->password = $password;
		if( isset($fields['nickname']) ) {
			$this->nickname = $fields['nickname'];
		}
		if( isset($fields['email']) ) {
			$this->email = $fields['email'];
		}
	}

	public function getClient(): Client
    {
        return $this->client;
    }

	/**
	* Authenticate with the REST API.
	*/
    /**
     * @param bool $save_auth
     * @return bool
     * @throws \Httpful\Exception\ConnectionErrorException
     */
	public function login($save_auth = true): bool
    {
		$response = Request::post( $this->client->getApi() . 'login' )
			->body(array( 'user' => $this->username, 'password' => $this->password ))
			->send();

		if( $response->code == 200 && isset($response->body->status) && $response->body->status == 'success' ) {
			if( $save_auth) {
				// save auth token for future requests
				$tmp = Request::init()
					->addHeader('X-Auth-Token', $response->body->data->authToken)
					->addHeader('X-User-Id', $response->body->data->userId);
				Request::ini( $tmp );
			}
			$this->id = $response->body->data->userId;
			return true;
		} else {
			throw new Exception($response->body->message);
		}
	}

	/**
	* Gets a user’s information, limited to the caller’s permissions.
	*/
    /**
     * @return array|bool|object|string
     * @throws \Httpful\Exception\ConnectionErrorException
     */
	public function info() {
		$response = Request::get( $this->client->getApi() . 'users.info?userId=' . $this->id )->send();

		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
			$this->id = $response->body->user->_id;
			$this->nickname = $response->body->user->name;
			$this->email = $response->body->user->emails[0]->address;
			return $response->body;
		} else {
            throw new Exception($response->body->error);
		}
	}

	/**
	* Create a new user.
	*/
	public function create() {
		$response = Request::post( $this->api . 'users.create' )
			->body(array(
				'name' => $this->nickname,
				'email' => $this->email,
				'username' => $this->username,
				'password' => $this->password,
			))
			->send();

		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
			$this->id = $response->body->user->_id;
			return $response->body->user;
		} else {
			echo( $response->body->error . "\n" );
			return false;
		}
	}

	/**
	* Deletes an existing user.
	*/
	public function delete() {

		// get user ID if needed
		if( !isset($this->id) ){
			$this->me();
		}
		$response = Request::post( $this->api . 'users.delete' )
			->body(array('userId' => $this->id))
			->send();

		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
			return true;
		} else {
			echo( $response->body->error . "\n" );
			return false;
		}
	}
}
