<?php

namespace App\Controller;

use Interop\Container\ContainerInterface;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Upsell\HttpApiHandler\Request\RequestParser;
use App\Constants;
use App\Api\SkillsApi;
use Doctrine\DBAL\Connection;

class SkillsController
{

    /**
     *
     * @var ContainerInterface
     */
    private $container;
    
    /**
     *
     * @var Connection
     */
    private $db;
    
    /**
     *
     * @var Logger
     */
    private $logger;
    
    /**
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        
        $this->db = $this->container->get(Constants::DB_RATTRAPAGE);
    }
    
    /**
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return Response
     */
    public function getAll($request, $response, $args): Response
    {
        $skillsApi = new SkillsApi($this->db);
        
        $resourceParam = RequestParser::parseResourceParam($args);
        
        $responseProvider = $projectsApi->getAll($resourceParam);
        
        return $response->withJson($responseProvider->renderBody(), $responseProvider->renderStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $args
     * @return Response
     */
    public function getOne($request, $response, $args): Response
    {
        $skillsApi = new SkillsApi($this->db);

        $skillId = $args['skillId'];

        $responseProvider = $skillsApi->getOne($skillId);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return Response
     */
    public function addOne($request, $response, $args): Response
    {
        $skillsApi = new SkillsApi($this->db);

        $body = $request->getParsedBody();

        if(!array_key_exists('data', $body)) {
            throw new ApiException("[data] key is empty.", 500);
        }

        $responseProvider = $skillsApi->addOne($body['data']);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return Response
     */
    public function updateOne($request, $response, $args): Response
    {
        $skillsApi = new SkillsApi($this->db);

        $skillId = $args['skillId'];

        $body = $request->getParsedBody();

        if (!array_key_exists('data', $body)) {
            throw new ApiException("[data] key is empty.", 500);
        }

        $responseProvider = $skillsApi->updateOne($skillId,
            $body['data']);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

    /**
     * @param $request
     * @param $response
     * @param $args
     * @return Response
     */
    public function deleteOne($request, $response, $args): Response
    {
        $skillsApi = new SkillsApi($this->db);

        $skillId = $args['skillId'];

        $body = $request->getParsedBody();

        $responseProvider = $skillsApi->deleteOne($skillId);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $args
     * @return Response
     */
    public function getSkillByUser($request, $response, $args): Response
    {
        $usersApi = new UsersApi($this->db);

        $userId = $args['userId'];

        $responseProvider = $usersApi->getSkillByUser($userId);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $args
     * @return Response
     */
    public function getUserBySkillOrName($request, $response, $args): Response
    {
        $usersApi = new UsersApi($this->db);

        $type = $args['type'];

        $responseProvider = $usersApi->getUserBySkillOrName($type);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $args
     * @return Response
     */
    public function getUserBySkillOrNote($request, $response, $args): Response
    {
        $usersApi = new UsersApi($this->db);

        $type = $args['type'];

        $note = $args['note'];

        $responseProvider = $usersApi->getUserBySkillOrName($type, $note);

        return $response->withJson($responseProvider->renderBody(),
            $responseProvider->renderStatus());
    }

}
