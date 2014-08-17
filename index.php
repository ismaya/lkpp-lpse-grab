<?php
include('init.php');
include('functions.php');

$do = get_request('do');

switch($do) {
    case 'page':
        $result = get_page();
        break;
    case 'auctions':
        $result = get_auctions();
        break;
    case 'winners':
        $result = get_winners();;
        break;
    default:
        $_SESSION = [];
        include('view.php');
}

function get_page()
{
    global $config;

    $_SESSION = [];
    $time_start = microtime(true);

    $page_count = 0;

    $url = $config['base_target_url'] . 'eproc/lelang/pemenangcari';
    $html = getHTML($url, 10);
    $pattern = "/t-data-grid-pager\">(.*)<\/div><\/div>/";
    preg_match($pattern, $html, $matches);
    if (isset($matches[1])) {
        $link = explode('</a><a', $matches[1]);
        $page_count = strip_tags('<a '. $link[count($link)-1]);
    }
    // $page_count = 10;

    $result = (object) [
        'page_count' => $page_count,
        'execution_time' => number_format(microtime(true) - $time_start, 2),

    ];

    $_SESSION['result'] = $result;

    die(json_encode($result));
}

function get_auctions()
{
    global $config;

    $result = $_SESSION['result'];

    $time_start = (isset($result->auctions) && isset($result->time_start)? $result->time_start : microtime(true));

    $limit = (isset($result->limit) ? $result->limit : (! is_null(get_request('limit')) ? get_request('limit') : 10) );
    $page_count = (isset($result->page_count) ? $result->page_count : 0);
    $page = (isset($result->page) ? $result->page : 0);
    $auctions = (isset($result->auctions) ? $result->auctions : []);
    if ($page_count == 0) die();
    $page_count = ($limit <= 0 ? $page_count : ( $limit <= $page_count ? $limit : 10));

    $page++;

    $url = $config['base_target_url'] . 'eproc/lelang/pemenangcari.gridtable.pager/' . $page;
    $html = getHTML($url, 10);
    foreach ($config['statuses'] as $status => $label) {
        $pattern = "/<td class=\"tahap\"><a height=\"400\" width=\"600\" class=\"jpopup\" href=\"\/eproc\/lelang\/tahap\/(.*)\">" . $label ."<\/a><\/td>/";
        preg_match_all($pattern, $html, $match);

        if (isset($match[1])) {
            foreach ($match[1] as $item) {
                $part = explode(';', $item);
                $id = $part[0];
                $auction = (object) [
                    'id' => $id,
                    'status' => $label,
                ];

                $auctions[] = $auction;
            }
        }
    }

    $result = (object) [
        'time_start' => $time_start,
        'execution_time' => number_format(microtime(true) - $time_start, 2),
        'limit' => $limit,
        'page_count' => $page_count,
        'page' => $page,
        'auctions' => $auctions,
    ];

    $_SESSION['result'] = $result;

    die(json_encode($result));
}

function get_winners()
{
    global $config;

    $result = $_SESSION['result'];

    $time_start = (isset($result->winners) && isset($result->time_start) ? $result->time_start : microtime(true));

    $ids = (isset($result->ids) ? $result->ids : (! is_null(get_request('ids')) ? get_request('ids') : null) );
    $auctions = (isset($result->auctions) ? $result->auctions : null);
    if (is_null($ids) || is_null($auctions)) {
        die();
    }
    $ids = (is_array($ids) ? $ids : [$ids]);

    $index = (isset($result->index) ? $result->index : 0);
    $winners = (isset($result->winners) ? $result->winners : []);

    $url = $config['base_target_url'] . 'eproc/lelang/pemenang/' .$ids[$index];
    $html = getHTML($url, 10);

    $winners[] = (object) [
        'id' => $ids[$index],
        'title' => get_field($html, 'title'),
        'category' => get_field($html, 'category'),
        'satker' => get_field($html, 'satker'),
        'status' => $auctions[$index]->status,
        'winner' => get_field($html, 'winner'),
        'npwp' => get_field($html, 'npwp'),
        'price' => get_field($html, 'price'),
    ];

    $index++;

    $result = (object) [
        'time_start' => $time_start,
        'execution_time' => number_format(microtime(true) - $time_start, 2),
        'ids' => $ids,
        'index' => $index,
        'winners' => $winners,
        'auctions' => $auctions,
    ];

    $_SESSION['result'] = $result;

    die(json_encode($result));
}