<?php
declare(strict_types=1);

namespace RocketChat;

use Httpful\Request;

class Channel
{

	public $id;
	public $name;
	public $member;

	public function __construct($name, User $member){
	    $this->member = $member;
		if( is_string($name) ) {
			$this->name = $name;
		} else if( isset($name->_id) ) {
			$this->name = $name->name;
			$this->id = $name->_id;
		}
	}

	/**
	* Creates a new channel.
	*/


//	public function create(){
//		// get user ids for members
////		$members_id = array();
////		foreach($this->members as $member) {
////			if( is_string($member) ) {
////				$members_id[] = $member;
////			} else if( isset($member->username) && is_string($member->username) ) {
////				$members_id[] = $member->username;
////			}
////		}
//
//		$response = Request::post( $this->member->getClient()->getApi() . 'channels.create' )
//			->body(array('name' => $this->name, 'members' => $members_id))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			$this->id = $response->body->channel->_id;
//			return $response->body->channel;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}

	/**
	* Retrieves the information about the channel.
	*/

    /**
     * @return array|bool|object|string
     * @throws \Httpful\Exception\ConnectionErrorException
     */
	public function info() {
//		$response = Request::get( $this->member->getClient()->getApi() . 'channels.info?roomId=' . $this->id )->send();
		$response = Request::get( $this->member->getClient()->getApi() . 'channels.info?roomName=' . $this->name )->send();

		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
			$this->id = $response->body->channel->_id;
			return $response->body;
		} else {
            throw new Exception($response->body->error);
		}
	}

	/**
	* Post a message in this channel, as the logged-in user
	*/
	public function postMessage( $text ) {
		$message = is_string($text) ? array( 'text' => $text ) : $text;
		if( !isset($message['attachments']) ){
			$message['attachments'] = array();
		}

		$response = Request::post( $this->member->getClient()->getApi() . 'chat.postMessage' )
			->body( array_merge(array('channel' => '#'.$this->name), $message) )
			->send();

		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
			return true;
		} else {
			if( isset($response->body->error) )	echo( $response->body->error . "\n" );
			else if( isset($response->body->message) )	echo( $response->body->message . "\n" );
			return false;
		}
	}

//	/**
//	* Removes the channel from the user’s list of channels.
//	*/
//	public function close(){
//		$response = Request::post( $this->api . 'channels.close' )
//			->body(array('roomId' => $this->id))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			return true;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}
//
//	/**
//	* Delete the channel
//	*/
//	public function delete(){
//		$response = Request::post( $this->api . 'channels.delete' )
//			->body(array('roomId' => $this->id))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			return true;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}
//
//	/**
//	* Removes a user from the channel.
//	*/
//	public function kick( $user ){
//		// get channel and user ids
//		$userId = is_string($user) ? $user : $user->id;
//
//		$response = Request::post( $this->api . 'channels.kick' )
//			->body(array('roomId' => $this->id, 'userId' => $userId))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			return true;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}
//
//	/**
//	 * Adds user to channel.
//	 */
//	public function invite( $user ) {
//
//		$userId = is_string($user) ? $user : $user->id;
//
//		$response = Request::post( $this->api . 'channels.invite' )
//			->body(array('roomId' => $this->id, 'userId' => $userId))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			return true;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}
//
//	/**
//	 * Adds owner to the channel.
//	 */
//	public function addOwner( $user ) {
//
//		$userId = is_string($user) ? $user : $user->id;
//
//		$response = Request::post( $this->api . 'channels.addOwner' )
//			->body(array('roomId' => $this->id, 'userId' => $userId))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			return true;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}
//
//	/**
//	 * Removes owner of the channel.
//	 */
//	public function removeOwner( $user ) {
//
//		$userId = is_string($user) ? $user : $user->id;
//
//		$response = Request::post( $this->api . 'channels.removeOwner' )
//			->body(array('roomId' => $this->id, 'userId' => $userId))
//			->send();
//
//		if( $response->code == 200 && isset($response->body->success) && $response->body->success == true ) {
//			return true;
//		} else {
//			echo( $response->body->error . "\n" );
//			return false;
//		}
//	}

}

