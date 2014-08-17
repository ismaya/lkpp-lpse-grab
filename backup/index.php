<?php
include('init.php');
include('functions.php');

$do = get_request('do');
switch ($do) {
    case 'page' : get_page(); break;
    case 'auctions' : get_auctions(); break;
    case 'winners' : get_winners(); break;
    case 'dummy' : dummy(); break;
    default : get_start();
}

function get_start()
{
    global $config;

    $_SESSION = array();

    include('view.php');
}

function dummy()
{
    $progress = (isset($_SESSION['progress']) ? $_SESSION['progress'] : 0);
    $progress += 1;
    $_SESSION['progress'] = $progress;

    $data = (object) [
        'data' => null,
        'progress' => $progress,
    ];
    die(json_encode($data));
}

function get_page()
{
    global $config;

    $_SESSION = array();

    $url = $config['base_target_url'] . 'eproc/lelang/pemenangcari';
    $pattern = "/<div class=\"t-data-grid-pager\">(.*)<\/div>/";
    $html = getHTML($url, 10);
    preg_match($pattern, $html, $match);
    $content = (isset($match[1]) ? $match[1] : null);

    $page_count = null;
    if (! is_null($content)) {
        $content_array = explode('<a', $content);
        $page_count = strip_tags('<a ' . $content_array[count($content_array)-1]);
    }

    $page_count = 10;

    $result = (object) [
        'page_count' => $page_count,
        'progress' => 100,
    ];

    $_SESSION['result'] = $result;
    die(json_encode($result));
}

function get_auctions()
{
    global $config;

    $result = (isset($_SESSION['result']) ? $_SESSION['result'] : null);
    if (is_null($result)) {
        header($config['base_url']);
    }

    $page_count = $result->page_count;
    $job_index = (isset($result->job_index) ? ($result->job_index + 1) : 1);
    $progress = floor(($job_index * 100) / $page_count);
    $auctions = (isset($result->auctions) ? $result->auctions : []);

    $url = $config['base_target_url'] . 'eproc/lelang/pemenangcari.gridtable.pager/' . $job_index;
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
        'auctions' => $auctions,
        'page_count' => $page_count,
        'job_index' => ($progress < 100 ? $job_index : 1),
        'progress' => $progress,
    ];

    $_SESSION['result'] = $result;
    die(json_encode($result));
}

function get_winners()
{
    global $config;

    $result = (isset($_SESSION['result']) ? $_SESSION['result'] : null);
    if (is_null($result)) {
        header($config['base_url']);
    }

    $auctions = $result->auctions;
    $job_index = (isset($result->job_index) ? ($result->job_index + 1) : 1);
    $progress = floor(($job_index * 100) / count($auctions));
    $winners = (isset($result->winners) ? $result->winners : []);


    $url = $config['base_target_url'] . 'eproc/lelang/pemenang/' .$auctions[$job_index - 1]->id;
    $html = getHTML($url, 10);

    $winners[] = (object) [
        'id' => $auctions[$job_index - 1]->id,
        'title' => get_field($html, 'title'),
        'category' => get_field($html, 'category'),
        'satker' => get_field($html, 'satker'),
        'status' => $auctions[$job_index - 1]->status,
        'winner' => get_field($html, 'winner'),
        'npwp' => get_field($html, 'npwp'),
        'price' => get_field($html, 'price'),
    ];

    $result = (object) [
        'winners' => $winners,
        'auctions' => $auctions,
        'job_index' => ($progress < 100 ? $job_index : 1),
        'progress' => $progress,
    ];

    $_SESSION['result'] = $result;
    die(json_encode($result));
}