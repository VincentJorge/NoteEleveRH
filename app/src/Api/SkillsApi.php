<?php
namespace App\Api;

use App\Constants;
use Doctrine\DBAL\Connection;
use Upsell\DbHelper\SqlServer\DBALHelper;
use Upsell\HttpApiHandler\Response\ResponseProvider;
use Upsell\HttpApiHandler\Request\ResourceParam;

class SkillsApi
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
     * @param string $userId
     * @return ResponseProvider
     */
    public function getOne(string $skillId): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(Constants::SKILLS_TABLE)
            ->where('id = ' . $queryBuilder->createPositionalParameter
                ($skillId));

        $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);

        return ResponseProvider::createResponse($results, 200);
    }

    /**
     * @param array $body
     * @return ResponseProvider
     */
    public function addOne(array $body): ResponseProvider
    {  
        $queryBuilder = $this->db->createQueryBuilder();

        $id = DBALHelper::insert($queryBuilder, Constants::SKILLS_TABLE, $body);

        $response = $this->getOne($id);

        return $response;
    }

    /**
     * @param string $projectId
     * @param array $body
     */
    public function updateOne(string $skillId, array $body): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->where("id = " . $queryBuilder->createNamedParameter(
                $skillId
            ));

        DBALHelper::update($queryBuilder, Constants::SKILLS_TABLE, $body);
        
        $response = $this->getOne($skillId);

        return $response;
    }

    /**
     * @param string $projectId
     * @return ResponseProvider
     */
    public function deleteOne(string $skillId): ResponseProvider
    {
        $delete = "DELETE FROM skills
        WHERE id = $skillId";

        $this->db->query($delete);

        return ResponseProvider::createResponse([], 204);
    }

}
