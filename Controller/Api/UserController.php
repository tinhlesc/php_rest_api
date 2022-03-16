<?php

use Validation\UserValidation;

class UserController extends BaseController
{
    protected $userModel = null;
    protected $userValidation = null;

    public function __construct(UserModel $userModel, UserValidation $userValidation)
    {
        $this->userModel = $userModel;
        $this->userValidation = $userValidation;
    }

    /**
     * Get list of users
     */
    public function getAction()
    {
        $strErrorDesc = 'Something went wrong! Please contact support.';
        $arrQueryStringParams = $this->getQueryStringParams();

        try {
            $intLimit = 10;
            if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                $intLimit = $arrQueryStringParams['limit'];
            }

            $arrUsers = $this->userModel->getUsers($intLimit);
            $responseData = json_encode($arrUsers);

            $this->sendOutput($responseData);
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $strErrorDesc = 'Something went wrong! Please contact support.';
        }

        $this->sendOutput(json_encode(['error' => $strErrorDesc]), self::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function deleteAction()
    {
        $strErrorDesc = 'Something went wrong! Please contact support.';
        $arrUriSegments = $this->getUriSegments();

        try {
            $idUser = (isset($arrUriSegments[2])) ? $arrUriSegments[2] : 0;

            $userById = $this->userModel->getUserByID($idUser);
            if (!$userById) {
                $this->sendOutput(json_encode(['error' => "User is not found!"]), self::HTTP_BAD_REQUEST);
            }

            $isDeleted = $this->userModel->deleteUser($idUser);
            if ($isDeleted) {
                $this->sendOutput(json_encode(['success' => 'Delete successfully.']), self::HTTP_OK);
            }
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $strErrorDesc = 'Something went wrong! Please contact support.';
        }

        $this->sendOutput(json_encode(['error' => $strErrorDesc]), self::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function createAction()
    {
        $result = [
            'errors' => [],
            'user' => []
        ];
        $strErrorDesc = '';
        try {
            $payload = (array) json_decode(file_get_contents('php://input'), true);
            $payload['existUsername'] = false;

            $findUser = $this->userModel->getUserByUsername($payload['username']);
            if ($findUser) {
                $payload['existUsername'] = true;
                $this->sendOutput(json_encode(['error' => "Username already exists"]), self::HTTP_BAD_REQUEST);
            }

            $result['errors'] = $this->userValidation->validateInput($payload);
            if (!empty($result['errors'])) {
                $this->sendOutput(json_encode(['error' => $result['errors']]), self::HTTP_BAD_REQUEST);
            }
            unset($payload['existUsername']);

            $idUser = $this->userModel->createUser($payload);

            if ($idUser) {
                $result['user'] = $this->userModel->getUserById($idUser);
                $this->sendOutput(json_encode(['success' => $result['user']]), self::HTTP_CREATED);
            }
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $strErrorDesc = 'Something went wrong! Please contact support.';
        }

        $this->sendOutput(json_encode(['error' => $strErrorDesc]), self::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update of users
     */
    public function updateAction()
    {
        $strErrorDesc = '';
        $arrUriSegments = $this->getUriSegments();
        $updateData = $this->getObjectContent();

        try {
            $idUser = (isset($arrUriSegments[2])) ? $arrUriSegments[2] : 0;
            $userById = $this->userModel->getUserByID($idUser);
            if (!$userById) {
                $this->sendOutput(json_encode(['error' => "User is not found!"]), self::HTTP_BAD_REQUEST);
            }

            $updateData['existUsername'] = false;
            $username = (isset($updateData['username'])) ? $updateData['username'] : '';
            if ($userById[0]['username'] != $username) {
                $userByUsername = $this->userModel->getUserByUsername($username);
                if ($userByUsername) {
                    $updateData['existUsername'] = true;
                }
            }

            $errorMessage = $this->userValidation->validateInput($updateData);
            if ($errorMessage) {
                $this->sendOutput(json_encode(['error' => $errorMessage]), self::HTTP_BAD_REQUEST);
            }

            $updateData['password'] = (isset($updateData['password'])) ? sha1($updateData['password']) : $userById[0]['password'];

            $updateUser = $this->userModel->updateUser($idUser, $updateData);
            if ($updateUser) {
                $result['user'] = $this->userModel->getUserById($idUser);
                $this->sendOutput(json_encode(['success' => $result['user']]), self::HTTP_NO_CONTENT);
            }
        } catch (\Exception $e) {
            $this->log($e->getMessage());
            $strErrorDesc = 'Something went wrong! Please contact support.';
        }

        $this->sendOutput(json_encode(['error' => $strErrorDesc]), self::HTTP_INTERNAL_SERVER_ERROR);
    }

}
