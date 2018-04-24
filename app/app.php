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

$app->get('/trees', function() {
    $from = intval($this->request->getQuery('from')) ?: 0;
    $size = intval($this->request->getQuery('size')) ?: 3;

    if ($from < 0)
        $from = 0;
    if ($size <= 0)
        $size = 1;

    $trees = iterator_to_array(Tree::find(['limit' => $size, 'offset' => $from]));

    echo json_encode(['data' => array_map(function($tree) {
        return [
            'type' => 'trees',
            'id' => $tree->id,
            'attributes' => [
                'name' => $tree->name,
                'description' => $tree->description,
                'picture-url' => $tree->picture_url
            ]
        ];
    }, $trees)]);
});

$app->get('/trees/{tree_id}', function($tree_id) {
    $tree = Tree::findById($tree_id);
    if ($tree) {
        echo json_encode(['tree' => $tree]);
    } else {
        echo json_encode(['error' => 'NOT_FOUND']);
    }
});