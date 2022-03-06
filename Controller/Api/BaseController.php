<?php

class BaseController
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', self::HTTP_NOT_FOUND);
    }

    /**
     * Get URI elements.
     *
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Get querystring params.
     */
    protected function getQueryStringParams()
    {
        parse_str($_SERVER['QUERY_STRING'], $query);

        return $query;
    }

    /**
     * Send API output.
     *
     * @param mixed $data
     */
    protected function sendOutput($data, int $statusCode = self::HTTP_OK)
    {
        header_remove('Set-Cookie');
        header('Content-Type: application/json');
        header('HTTP/1.1 ' . $statusCode);

        echo $data;
        exit;
    }

    /**
     * @param string $content
     */
    protected function log(string $content)
    {
        file_put_contents('var/log_' . date("j.n.Y") . '.log', $content, FILE_APPEND);
    }
}
