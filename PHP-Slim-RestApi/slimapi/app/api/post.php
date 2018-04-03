<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get All Posts
$app->get('/api/posts', function (Request $request, Response $response){
    $sql = "SELECT * FROM categories INNER JOIN posts ON posts.category_id=categories.id ORDER BY posts.id DESC";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($posts);
    }catch (PDOException $e){
        echo '{"error": {"text":'.$e->getMessage().'}}';
    }
});

// Get Single Post

$app->get('/api/post/{id}', function (Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM posts WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $post = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($post);
    }catch (PDOException $e){
        echo '{"error": {"text":'.$e->getMessage().'}}';
    }
});

// Add Post
$app->post('/api/post/add', function (Request $request, Response $response){
    $title = $request->getParam('title');
    $category_id = $request->getParam('category_id');
    $body = $request->getParam('body');

    $sql = "INSERT INTO posts (title, category_id, body) VALUES (:title, :category_id, :body)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':body', $body);

        $stmt->execute();

        echo '{"notice": {"text":"Post Added"}}';

    }catch (PDOException $e){
        echo '{"error": {"text":'.$e->getMessage().'}}';
    }
});

// Update Post
$app->put('/api/post/update/{id}', function (Request $request, Response $response){
    $id = $request->getAttribute('id');
    $title = $request->getParam('title');
    $category_id = $request->getParam('category_id');
    $body = $request->getParam('body');

    $sql = "UPDATE posts SET title = :title, category_id = :category_id, body = :body WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':body', $body);

        $stmt->execute();

        echo '{"notice": {"text":"Post '.$id.' Updated"}}';

    }catch (PDOException $e){
        echo '{"error": {"text":'.$e->getMessage().'}}';
    }
});

// Delete Post

$app->delete('/api/post/delete/{id}', function (Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM posts WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text":"Post '.$id.' Deleted"}}';
    }catch (PDOException $e){
        echo '{"error": {"text":'.$e->getMessage().'}}';
    }

});