<?php

namespace Students\Helpers;
use Students\Services\Db;

class Pager {

    /** @var int */
    protected $itemsPerPage;
    
    /** @var string class name */
    protected $class;

    /**
     * @param int $itemsPerPage
     * 
     * @param string $class class name
     */
    public function __construct($itemsPerPage, $class)
    {
        $this->itemsPerPage = $itemsPerPage;
        $this->class = $class;
    }

    /** @return int */
    public function getItemsPerPage(): int 
    {
        return $this->itemsPerPage;
    }

    /** @return int */
    public function getPagesCount(): int 
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT COUNT(*) AS cnt FROM ' . $this->class::getTableName() . ';');
        return ceil($result[0]->cnt / $this->itemsPerPage);
    }

    /** 
     * @param string $keyword text from search field
     * 
     * @return int
     */
    public function getPagesCountByKeyword(string $keyword): int 
    {
        $db = Db::getInstance();
        $result = $db->query("SELECT COUNT(*) AS cnt FROM " . $this->class::getTableName() . " WHERE CONCAT(`first_name`, ' ',`last_name`, ' ', `group_number`, ' ', `points`) LIKE CONCAT('%',:keyword,'%');",
        ['keyword' => $keyword],
        $this->class
        );
        return ceil($result[0]->cnt / $this->itemsPerPage);
    }

    /**
     * @param int $pageNum
     * 
     * @param string $order
     * 
     * @param string $direction
     * 
     * @return array
     */
    public function getPage(int $pageNum, string $order = 'points', string $direction = 'DESC'): array 
    {
        $itemsPerPage = $this->getItemsPerPage();

        $db = Db::getInstance();
        return $db->query(
            sprintf(
                "SELECT * FROM `%s` ORDER BY {$order} {$direction} LIMIT %d OFFSET %d;",
                $this->class::getTableName(),
                $itemsPerPage,
                ($pageNum - 1) * $itemsPerPage
            ),
            [],
            $this->class
        ); 
    }

    /**
     * @param int $pageNum
     * 
     * @param string $keyword text from search field
     * 
     * @param string $order
     * 
     * @param string $direction
     * 
     * @return array
     */
    public function getPageByKeyword(int $pageNum, string $keyword, string $order = 'points', string $direction = 'DESC'): array 
    {
        $itemsPerPage = $this->getItemsPerPage();

        $db = Db::getInstance();
        return $db->query(
            sprintf(
                "SELECT * FROM `%s` WHERE CONCAT(`first_name`, ' ',`last_name`, ' ', `group_number`, ' ', `points`) LIKE CONCAT('%%',:keyword,'%%') ORDER BY {$order} {$direction} LIMIT %d OFFSET %d;",
                $this->class::getTableName(),
                $itemsPerPage,
                ($pageNum - 1) * $itemsPerPage
            ),
            ['keyword' => $keyword],
            $this->class
        );
    }

    /** 
     * @param array $pageLinks
     * 
     * @param string $order
     * 
     * @param string $direction
     * 
     * @param string $search text from search field
     * 
     * @return array
     */
    public function addUrlsToPageLinks(array $pageLinks, string $order = 'points', string $direction = 'DESC', string $search = ''): array 
    {    
        foreach($pageLinks as $key => $pageNum) {
            $pageLinks[$key]['url'] = "?order=$order&direction=$direction&search=$search";
        }

        return $pageLinks;
    }

    /** 
     * @param int $pageNum
     * 
     * @param int $pagesCount
     * 
     * @param string $order
     * 
     * @param string $direction
     * 
     * @param string $search text from search field
     * 
     * @return array
     */
    public function getPageLinks(int $pageNum, int $pagesCount, string $order = 'points', string $direction = 'DESC', string $search = ''): array 
    {    
        $pageLinks = [];

        if($pageNum == 1 || $pageNum == 2) {
            for($i = 1; $i <= 5; $i++) {
                $pageLinks[]['pageNum'] = $i;
            }
            return $this->addUrlsToPageLinks($pageLinks, $order, $direction, $search);
        }

        if($pageNum == $pagesCount || $pageNum == $pagesCount - 1) {
            for($i = $pagesCount - 4; $i <= $pagesCount; $i++) {
                $pageLinks[]['pageNum'] = $i;
            }
            return $this->addUrlsToPageLinks($pageLinks, $order, $direction, $search);
        }

        for($i = $pageNum - 2; $i <= $pageNum + 2; $i++) {
            $pageLinks[]['pageNum'] = $i;
        }

        return $this->addUrlsToPageLinks($pageLinks, $order, $direction, $search);
    }
}