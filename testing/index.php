<?php
ob_start();

$siteRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/vendor/autoload.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/lib/funcs.php';

$testRoot = buildLocalPath('/testing');
$loader = new Twig_Loader_Filesystem($siteRoot . '/testing/twigs');
//$twig = new Twig_Environment($loader, ['cache' => 'twigCache']);
$twig = new Twig_Environment($loader, ['cache' => false]);
// TODO: For debugging:
$twig->addExtension(new Twig_Extension_Debug());
// Common elements:
$siteTitle = 'Talz13 MPG';
$nav['id'] = 'nav';
$nav['items'][] = ['href' => $testRoot, 'caption' => 'Home', 'id' => 'index'];
$nav['items'][] = ['href' => $testRoot . '?pageId=addRecord', 'caption' => 'Add', 'id' => 'addRecord'];
//$styles[] = array('url' => '/styles/style1.css');
$styles[] = ['url' => 'https://fonts.googleapis.com/css?family=Roboto:400,700'];
$styles[] = ['url' => buildLocalPath('testing.css')];

// Page specific elements:
$pageId = filter_input(INPUT_GET, 'pageId');

switch ($pageId) {
    case 'addRecord':
        $pageTitle = 'Add Mileage';
        $bodyTwigs[] = ['file' => 'submit.twig'];
        break;
    default:
        $pageId = 'index';
        $pageTitle = 'Index';
        $scripts[] = ['type' => 'text/javascript', 'url' => 'https://www.google.com/jsapi'];
        $scripts[] = ['type' => 'text/javascript', 'url' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'];
        $scripts[] = ['type' => 'text/javascript', 'url' => buildLocalPath('/lib/googleChartApi.js')];
//        $divs[] = ['id' => 'chart_div_mpg', 'class' => 'chart'];
//        $divs[] = ['id' => 'chart_div_price', 'class' => 'chart'];
//        $divs[] = ['id' => 'table_div', 'class' => 'table'];
}

//echo $twig->render('index.twig', ['siteName' => $siteTitle, 'pageName' => $pageTitle, 'pageId' => $pageId, 'nav' => $nav, 'scripts' => $scripts, 'styles' => $styles, 'divs' => $divs]);
//echo $twig->render('index.twig', ['siteName' => $siteTitle, 'pageName' => $pageTitle, 'pageId' => $pageId, 'nav' => $nav, 'scripts' => $scripts, 'styles' => $styles]);
$htmlContent = $twig->render('index.twig', ['siteName' => $siteTitle, 'pageName' => $pageTitle, 'pageId' => $pageId, 'nav' => $nav, 'scripts' => $scripts, 'styles' => $styles]);

//$config = array(
//            'indent'         => true,
//            'output-xhtml'   => true,
//            'wrap'           => 200);
//phpinfo();
//$tidy = new tidy;
//$tidy->parseString($htmlContent, $config, 'utf8');
//$tidy->cleanRepair();
$escaped = addslashes($htmlContent);
$goodHtml = `echo "$escaped" | tidy -indent`;

echo $goodHtml;
