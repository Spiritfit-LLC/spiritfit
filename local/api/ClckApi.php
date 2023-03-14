<?php
\Bitrix\Main\Loader::includeModule('rest');


class ClckApi extends \IRestService{
    private const SCOPE = 'clck';
    private const TABLE_NAME = 'custom_clck';
    private static $chars = "123456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ";


    public static function OnRestServiceBuildDescription(){
        return [
            'custom.'.static::SCOPE => [
                'clck.createlink' => [
                    'callback' => [__CLASS__, "createLink"],
                    'options' => [],
                ],
            ],
        ];
    }

    private static function validateLink($link){
        return filter_var($link, FILTER_VALIDATE_URL,FILTER_FLAG_HOST_REQUIRED);
    }

    private static function existLink($link, $connection){
        $sql_query = "SELECT CLCK_LINK FROM `".self::TABLE_NAME."` WHERE `REAL_LINK` = '" .$link. "' LIMIT 1;";
        $result = $connection->query($sql_query);
        if ($ar = $result->fetch()){
            $clck_link=sprintf(
                "%s://%s/clck/%s/",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['SERVER_NAME'], $ar["CLCK_LINK"]);
            return $clck_link;
        }
        else{
            return false;
        }
    }

    private static function insertRealLink($link, Bitrix\Main\DB\Connection $connection){
        $id = $connection->add(self::TABLE_NAME, ["REAL_LINK"=>$link]);
        return $id;
    }

    private static function insertClckLink($row_id, $link, Bitrix\Main\DB\Connection $connection){
        $sql_query="UPDATE " . self::TABLE_NAME . " SET CLCK_LINK = '$link' WHERE ID=$row_id;";
        $connection->queryExecute($sql_query);
    }

    private static function convertIntToShortCode($id)
    {
        $id = intval($id);
        if ($id < 1) {
            throw new Exception("Не удалось создать короткую ссылку::Ошибка добавления ссылки в БД");
        }
        $length = strlen(self::$chars);
        if ($length < 10) {
            throw new Exception("Длина строки мала");
        }

        $code = "";
        while($id>$length-1){
            $code = self::$chars[$id % $length] . $code;

            $id = floor($id/$length);
        }

        $code = self::$chars[$id].$code;

        return $code;
    }

    public static function createLink($query, $n, \CRestServer $server){
        if($query['error'])
        {
            throw new \Bitrix\Rest\RestException(
                '',
                'WRONG_REQUEST',
                \CRestServer::STATUS_WRONG_REQUEST
            );
        }

        if (empty($query["link"])){
            throw new \Bitrix\Rest\RestException(
                "Отсутствует обязательный параметр link",
                'WRONG_REQUEST',
                \CRestServer::STATUS_WRONG_REQUEST
            );
        }

        if (!self::validateLink($query["link"])){
            throw new \Bitrix\Rest\RestException(
                "Ссылка не валидна",
                'WRONG_REQUEST',
                \CRestServer::STATUS_WRONG_REQUEST
            );
        }

        $connection = Bitrix\Main\Application::getConnection();
        if (!$connection->isTableExists("custom_clck")){
            $connection->createTable("custom_clck",
                [
                    'ID' => new Bitrix\Main\Entity\IntegerField(
                        'ID',
                        [
                            'primary' => true,
                            'column_name' => 'ID',
                        ]
                    ),
                    "CLCK_LINK" => new Bitrix\Main\Entity\StringField(
                        'CLCK_LINK',
                        [
                            'column_name' => 'CLCK_LINK',
                        ]
                    ),
                    "REAL_LINK" => new Bitrix\Main\Entity\StringField(
                        'REAL_LINK',
                        [
                            'column_name' => 'REAL_LINK',
                        ]
                    ),
                ],
                ['ID'],
                ['ID']
            );
        }


        if ($result = self::existLink($query["link"], $connection)){
            $connection->disconnect();
            return $result;
        }

        $row_id = self::insertRealLink($query["link"], $connection);
        try{
            $code = self::convertIntToShortCode($row_id);
        }
        catch (Exception $err){
            $connection->disconnect();
            throw new \Bitrix\Rest\RestException(
                $err->getFile() . "::" . $err->getLine() . "::" . $err->getMessage(),
                'INTERNAL_ERROR',
                \CRestServer::STATUS_INTERNAL
            );
        }

        try{
            self::insertClckLink($row_id, $code, $connection);
        }
        catch (Exception $err){
            $connection->disconnect();
            throw new \Bitrix\Rest\RestException(
                $err->getFile() . "::" . $err->getLine() . "::" . $err->getMessage(),
                'INTERNAL_ERROR',
                \CRestServer::STATUS_INTERNAL
            );
        }



        $connection->disconnect();
        $clck_link=sprintf(
            "%s://%s/clck/%s/",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'], $code);
        return $clck_link;

    }

    public static function getRealClick($clck_link){
        $connection = Bitrix\Main\Application::getConnection();
        if (!$connection->isTableExists(self::TABLE_NAME)){
            return false;
        }

        $sql_query = "SELECT REAL_LINK FROM " . self::TABLE_NAME . " WHERE CLCK_LINK = '" . $clck_link . "' LIMIT 1;";
        $db_result = $connection->query($sql_query);
        if ($result = $db_result->fetch()){
            return $result["REAL_LINK"];
        }
        else{
            return false;
        }
    }
}

AddEventHandler('rest', 'OnRestServiceBuildDescription', Array("ClckApi", "OnRestServiceBuildDescription"));