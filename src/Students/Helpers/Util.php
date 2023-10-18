<?php

namespace Students\Helpers;

class Util {
    
    public static function highlightSearchResult(string $string, string $search = ''): string
    {    
        if(!empty($search)) {
            ;
            $result = str_replace($search, "<mark class='search-mark'>$search</mark>", $string);
            return $result;
        }

        return $string;
    }

    public static function showSortingArrow(string $requiredOrder, string $currentOrder, string $direction): void {
        
        if ($requiredOrder === $currentOrder && $direction === "DESC") {
            echo '▼';
        } elseif ($requiredOrder === $currentOrder && $direction === "ASC") {
            echo '▲';
        }
    }

    public static function getSortingLink(string $header, string $search = ''): string
    {
    
        if(!isset($_GET['direction']) || $_GET['direction'] == 'ASC') {
            $direction = 'DESC';
        } else {
            $direction = 'ASC';
        }

        switch ($header) {
            case 'firstName':
                $order = 'first_name'; 
                break;
            case 'lastName':
                $order = 'last_name';
                break;
            case 'groupNumber':
                $order = 'group_number';
                break;
            default:
                $order = 'points';
        }

        return http_build_query(array(
           "order" => $order,
           "direction" => $direction,
           "search" => $search
        ));
    }
}