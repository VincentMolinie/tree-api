<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});

$app->get('/tree', function() {
    $from = intval($this->request->getQuery('from')) ?: 0;
    $size = intval($this->request->getQuery('size')) ?: 3;

    if ($from < 0)
        $from = 0;
    if ($size <= 0)
        $size = 1;

    echo json_encode(['trees' => Tree::find(['limit' => $size, 'offset' => $from])]);
});