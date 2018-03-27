<?php
namespace App\Api;

use App\Constants;
use Doctrine\DBAL\Connection;
use Upsell\DbHelper\SqlServer\DBALHelper;
use Upsell\HttpApiHandler\Response\ResponseProvider;
use Upsell\HttpApiHandler\Request\ResourceParam;

class ProjectsApi
{
    /**
     *
     * @var Connection
     */
    private $db;

    /**
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     *
     * @param ResourceParam $resourceParam
     * @param int $idClient
     * @return ResponseProvider
     */
    public function getAll(ResourceParam $resourceParam): ResponseProvider
    {
        $queryBuilder = DBALHelper::getQueryBuilderFromResourceParam($this->db, $resourceParam);
        
        $queryBuilder->select('*')
                     ->from(Constants::PROJECTS_TABLE);
        
        $res = DBALHelper::fetchAllByQueryBuilder($queryBuilder);
    
        return ResponseProvider::createResponse($res, 200);
    }

    /**
     * @param string $projectId
     * @return ResponseProvider
     */
    public function getOne(string $projectId): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(Constants::PROJECTS_TABLE)
            ->where('id = ' . $queryBuilder->createPositionalParameter
                ($projectId));

        $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);

        return ResponseProvider::createResponse($results[0], 200);
    }

    /**
     * @param array $body
     * @return ResponseProvider
     */
    public function addOne(array $body): ResponseProvider
    {  
        $body['languages'] = json_encode($body['languages']);
        $body['links'] = json_encode($body['links']);;
    
        
        $queryBuilder = $this->db->createQueryBuilder();

        $id = DBALHelper::insert($queryBuilder, Constants::PROJECTS_TABLE, $body);
        
        $response = $this->getOne($id);

        return $response;
    }

    /**
     * @param string $projectId
     * @param array $body
     */
    public function updateOne(string $projectId, array $body): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->where("id = " . $queryBuilder->createNamedParameter(
                $projectId
            ));

        DBALHelper::update($queryBuilder, Constants::PROJECTS_TABLE, $body);
        
        $response = $this->getOne($projectId);

        return $response;
    }

    /**
     * @param string $projectId
     * @return ResponseProvider
     */
    public function deleteOne(string $projectId): ResponseProvider
    {
        $delete = "DELETE FROM projects
        WHERE id = $projectId";

        $this->db->query($delete);

        return ResponseProvider::createResponse([], 204);
    }

}
