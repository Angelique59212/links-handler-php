<?php

namespace App\Model\Manager;

use App\Model\Connect;
use App\Model\Entity\Links;
use JetBrains\PhpStorm\NoReturn;

class LinksManager
{
    public const TABLE = 'mdf58_links';


    /**
     * @param Links $links
     * @return bool
     */
    #[NoReturn] public static function addNewLink(Links $links): bool
    {
        $stmt = Connect::dbConnect()->prepare("
            INSERT INTO " . self::TABLE . " (name,link, image, user_fk)
            VALUES (:name,:link, :image, :user_fk)
        ");

        $stmt->bindValue(':name', $links->getName());
        $stmt->bindValue(':link', $links->getLink());
        $stmt->bindValue(':image', $links->getImage());
        $stmt->bindValue(':user_fk', $links->getLinksUser()->getId());


        $result = $stmt->execute();
        $links->setId(Connect::dbConnect()->lastInsertId());
        return $result;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function linkExist(int $id): bool
    {
        $result = Connect::dbConnect()->query(" SELECT count(*) as cnt FROM " . self::TABLE . " WHERE id = $id");
        return $result ? $result->fetch()['cnt'] : 0 ;
    }

    /**
     * @param int $id
     * @return Links|null
     */
    public static function getLinkById(int $id): ?Links
    {
        $result = Connect::dbConnect()->query("SELECT * FROM " . self::TABLE . " WHERE id = $id");
        return $result ? self::makeLink($result->fetch()) : null;
    }

    /**
     * @param array $data
     * @return Links
     */
    private static function makeLink(array $data): Links
    {
        return (new Links())
            ->setId($data['id'])
            ->setName($data['name'])
            ->setLink($data['link'])
            ->setImage($data['image'])
            ->setLinksUser(UserManager::getUserById($data['user_fk']))
            ;
    }

    /**
     * @param Links|null $links
     * @return false|int
     */
    public static function deleteLink(?Links $links): bool|int
    {
        if (self::linkExist($links->getId())) {
            return Connect::dbConnect()->exec("
                DELETE FROM " . self::TABLE . " WHERE id = {$links->getId()}
            ");
        }
        return false;
    }

    /**
     * @param int $id
     * @return array
     */
    public static function getAllLinkByUserId(int $id): array
    {
        $links = [];
        $result = Connect::dbConnect()->query("SELECT * FROM " . self::TABLE . " WHERE user_fk = $id");
        if ($result) {
            foreach ($result->fetchAll() as $data) {
                $links[] = self::makeLink($data);
            }
        }
        return $links;
    }

}