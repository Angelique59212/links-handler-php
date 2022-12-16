<?php

namespace App\Model\Manager;

use App\Model\Connect;
use App\Model\Entity\User;

class UserManager
{
    public const TABLE = 'mdf58_user';

    /**
     * @param User $user
     * @return bool
     */
    public static function addUser(User $user):bool
    {
        $stmt = Connect::dbConnect()->prepare("
            INSERT INTO " . self::TABLE . " (pseudo, email, password) 
            VALUES (:pseudo, :email, :password)
        ");

        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':pseudo', $user->getPseudo());
        $stmt->bindValue(':password', $user->getPassword());

        $result = $stmt->execute();
        $user->setId(Connect::dbConnect()->lastInsertId());

        return $result;
    }

    /**
     * @param array $data
     * @return User
     */
    private static function makeUser(array $data): User
    {
        return (new User())
            ->setId($data['id'])
            ->setPseudo($data['pseudo'])
            ->setEmail($data['email'])
            ->setPassword($data['password']);
    }

    /**
     * return user by id
     * @param int $id
     * @return User|null
     */
    public static function getUserById(int $id): ?User
    {
        $result = Connect::dbConnect()->query("SELECT * FROM " . self::TABLE . " WHERE id = $id");
        return $result ? self::makeUser($result->fetch()) : null;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function userExists(int $id): bool
    {
        $result = Connect::dbConnect()->query("SELECT count(*) as cnt FROM " . self::TABLE . " WHERE id = $id");
        return $result ? $result->fetch()['cnt'] : 0;
    }

    /**
     * @param string $mail
     * @return bool
     */
    public static function mailExist(string $mail): bool
    {
        $result = Connect::dbConnect()->query("SELECT count(*) as cnt FROM " . self::TABLE . " WHERE email = \"$mail\"");
        return $result ? $result->fetch()['cnt'] : 0;
    }

    /**
     * @param string $mail
     * @return User|null
     */
    public static function getUserByMail(string $mail): ?User
    {
        $stmt = Connect::dbConnect()->prepare("SELECT * FROM " . self::TABLE . " WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $mail);
        $result = $stmt->execute();
        if($result && $data = $stmt->fetch()) {
            return self::makeUser($data);
        }
        return null;
    }
}
