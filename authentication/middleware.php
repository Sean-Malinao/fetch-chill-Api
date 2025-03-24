<?
require_once __DIR__ . '/token.php';

class AuthMiddleware{
    public static function verifytoken(){
        $Authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if($Authorization){
            if(strpos($Authorization, 'Bearer') === 0){
                $jwt = substr($Authorization, 7);
                $decoded = JwtHelper::decode($jwt);
                if($decoded === null){
                    echo json_encode(['message' => 'Invalid token']);
                    exit;
                }
            } else{
                echo json_encode(['message' => 'token is malformed']);
                exit;
            }
        } else{
            echo json_encode(['message' => 'token is missing']);
            exit;
        }
    }
}
?>