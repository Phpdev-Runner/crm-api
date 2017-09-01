<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;


class ApiController extends Controller {
	
	#region CLASS PROPERTIES
	protected $statusCode = 200;
	#endregion
	
	#region MAIN METHODS
	
	public function respond($data, $headers = [])
	{
		return Response::json($data, $this->getStatusCode(),$headers);
	}
	
	public function respondNoContent($message = 'No Content')
	{
		return $this->setStatusCode(406)->respondWithError($message);
	}
	
	public function respondNotFound($message = 'Not Found')
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respondValidationFailed($message = "")
    {
        return $this->setStatusCode(422)->respondWithError($message);
    }
	
	public function respondBadRequest($message = 'Bad Request')
	{
		return $this->setStatusCode(400)->respondWithError($message);
	}

	public function respondDataConflict($message = 'Provided data has conflict')
    {
        return $this->setStatusCode(409)->respondWithError($message);
    }

    public function respondActionForbidden($message = 'You are not authorized to perform such action')
    {
        return $this->setStatusCode(403)->respondWithError($message);
    }
	
	public function respondCreated($message)
	{
		return $this->setStatusCode(201)->respond([
			'status' => 'created',
			'message' => $message
		]);
	}
	
	public function respondUpdated($message = 'entity updated')
	{
		return $this->setStatusCode(202)->respond([
			'status' => 'updated',
			'message' => $message
		]);
	}
	
	public function respondDeleted($message = 'entity deleted')
	{
		return $this->setStatusCode(202)->respond([
			'status' => 'deleted',
			'message' => $message
		]);
	}
	#endregion
	
	#region SERVICE METHODS
	private function getStatusCode()
	{
		return $this->statusCode;
	}
	
	private function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}
	
	private function respondWithError($message)
	{
		return $this->respond([
				'error' => [
					'message' => $message,
					'status_code' => $this->getStatusCode()
				]
			] // = 1st param - $data
		);
	}
	#endregion
}