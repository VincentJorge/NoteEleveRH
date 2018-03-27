<?php
namespace App\Api;

use App\Constants;
use Doctrine\DBAL\Connection;
use Upsell\DbHelper\SqlServer\DBALHelper;
use Upsell\HttpApiHandler\Response\ResponseProvider;
use Upsell\HttpApiHandler\Request\ResourceParam;

class UsersApi
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
                     ->from("users");
        
        $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);
        
        return ResponseProvider::createResponse($results, 200);
    }

    /**
     * @param string $userId
     * @return ResponseProvider
     */
    public function getOne(string $userId): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(Constants::USER_TABLE)
            ->where('id = ' . $queryBuilder->createPositionalParameter
                ($userId));

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

        $id = DBALHelper::insert($queryBuilder, Constants::USER_TABLE, $body);
        
        $response = $this->getOne($id);

        return $response;
    }

    /**
     * @param string $userId
     * @param array $body
     */
    public function updateOne(string $userId, array $body): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->where("id = " . $queryBuilder->createNamedParameter(
                $userId
            ));

        DBALHelper::update($queryBuilder, Constants::USER_TABLE, $body);
        
        $response = $this->getOne($userId);

        return $response;
    }

    /**
     * @param string $userId
     * @return ResponseProvider
     */
    public function deleteOne(string $userId): ResponseProvider
    {
        $delete = "DELETE FROM users
        WHERE id = $userId";

        $this->db->query($delete);

        return ResponseProvider::createResponse([], 204);
    }

    /**
     * @param string $userId
     * @return ResponseProvider
     */
    public function getSkillByUser(string $userId): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(Constants::USER_SKILLS_VIEW)
            ->where('user_skills.userId = ' . $queryBuilder->createPositionalParameter
                ($userId));

        $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);
        
        return ResponseProvider::createResponse($results[0], 200);
    }
    
    /**
     * @param string $type
     * @return ResponseProvider
     */
    public function getUserBySkillOrName(string $type): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(Constants::FIND_USER_BY_SKILL)
            ->where('find_user_by_skill.name = ' . $queryBuilder->createPositionalParameter
                ($type));

        $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);

        if(count($results) == 0)
        {
            $queryBuilder = $this->db->createQueryBuilder();

            $queryBuilder->select('*')
            ->from(Constants::FIND_USER_BY_SKILL)
            ->where('find_user_by_skill.type = ' . $queryBuilder->createPositionalParameter
                ($type));

            $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);
        }
        
        return ResponseProvider::createResponse($results[0], 200);
    }

    /**
     * @param string $type
     * @param
     * @return ResponseProvider
     */
    public function getUserBySkillOrNote(string $type, string $note): ResponseProvider
    {
        $queryBuilder = $this->db->createQueryBuilder();

        $queryBuilder->select('*')
            ->from(Constants::FIND_USER_BY_NOTE_AND_SKILL)
            ->where('find_user_by_note_and_skill.name = ' . $queryBuilder->createPositionalParameter
                ($type))
            ->andWhere('find_user_by_note_and_skill.note = '. $queryBuilder->createPositionalParameter
            ($note));

        $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);
        
        if(count($results) == 0)
        {
            $queryBuilder = $this->db->createQueryBuilder();

            $queryBuilder->select('*')
            ->from(Constants::FIND_USER_BY_NOTE_AND_SKILL)
            ->where('find_user_by_note_and_skill.type = ' . $queryBuilder->createPositionalParameter
                ($type))
            ->andWhere('find_user_by_note_and_skill.note = '. $queryBuilder->createPositionalParameter
            ($note));

            $results = DBALHelper::fetchAllByQueryBuilder($queryBuilder);

        }
        if(count($results === 0))
        {
            echo "0 résultat mdr";
            die();
        }
        return ResponseProvider::createResponse($results[0], 200);
    }
}
