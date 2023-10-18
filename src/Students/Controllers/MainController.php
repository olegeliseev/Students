<?php

namespace Students\Controllers;

use Students\Models\Users\User;
use Students\Controllers\AbstractController;
use Students\Helpers\Pager;

class MainController extends AbstractController
{

    public function main(int $pageNum = 1)
    {
        $pageParams = $this->getPageParams($pageNum);
        $this->view->renderHtml('main/main.php', [
            'students' => $pageParams['students'],
            'pagesCount' => $pageParams['pagesCount'],
            'itemsPerPage' => $pageParams['itemsPerPage'],
            'currentPageNum' => $pageNum,
            'pageLinks' => $pageParams['pageLinks'],
            'previousPageLink' => $pageParams['previousPageLink'],
            'nextPageLink' => $pageParams['nextPageLink'],
            'beginningLink' => $pageParams['beginningLink'],
            'endLink' => $pageParams['endLink'],
            'search' => $pageParams['search'],
            'order'=> $pageParams['order'],
            'direction' => $pageParams['direction']
        ]);
    }

    public function getPageParams(int $pageNum): array
    {
        $search = !empty($_GET['search']) ? true : false;
        $pager = new Pager(10, User::class);
        $params['pager']  = $pager;
        $params['itemsPerPage'] = $pager->getItemsPerPage();
        $params['search'] = $_GET['search'] ?? '';
        $params['pagesCount'] = $search ? $pager->getPagesCountByKeyword($params['search']) : $pager->getPagesCount();
        $params['order'] = !empty($_GET['order']) ? strval($_GET['order']) : 'points';
        $params['direction'] = !empty($_GET['direction']) ? strval($_GET['direction']) : 'DESC';
        $httpQuery = http_build_query(array(
            'order' => $params['order'],
            'direction' => $params['direction'],
            'search' => $params['search']
        ));
        $params['pageLinks'] = $pager->getPageLinks($pageNum, $params['pagesCount'], $params['order'], $params['direction'], $params['search']);
        $params['previousPageLink'] = $pageNum > 1 ? '/' . ($pageNum - 1) . "?" . $httpQuery : null;
        $params['nextPageLink'] = $pageNum < $params['pagesCount'] ? '/' . ($pageNum + 1) . "?" . $httpQuery : null;
        $params['beginningLink'] = 1 . "?" . $httpQuery;
        $params['endLink'] = $params['pagesCount'] . "?" . $httpQuery;

        if ($params['pagesCount'] < 1) {
            $params['students'] = $search ? User::findByKeyWord($params['search'], $params['order'], $params['direction']) : User::findAll($params['order'], $params['direction']);
        } else {
            $params['students'] = $search ? $pager->getPageByKeyword($pageNum, $params['search'], $params['order'], $params['direction']) : $pager->getPage($pageNum, $params['order'], $params['direction']);
        }
        
        return $params;
    }
}
