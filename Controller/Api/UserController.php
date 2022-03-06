<?php

class UserController extends BaseController
{
    protected $userModel = null;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
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
}
