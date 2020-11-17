<?php


namespace App\Support\GraphQL;


use App\Constants\ErrorCode;
use App\Graph\Mutation\MRoot;
use App\Graph\Query\QRoot;
use App\Support\Annotation\JwtAuth;
use App\Support\Entity\ViewObject\ResultVo;
use App\Support\Parent\Controller\AbstractController;
use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use GraphQL\Error\Error;
use Psr\Http\Message\ResponseInterface;

/**
 * Class GraphController
 * @package App\Support\GraphQL
 * @Controller()
 */
class GraphController extends AbstractController
{
    /**
     * @RequestMapping(path="/api/graph/[{action}]")
     * @param GraphTypeFactory $typeFactory
     * @return ResponseInterface|ResultVo
     */
    public function root(GraphTypeFactory $typeFactory)
    {
        $schema = new Schema([
            'query' => $typeFactory->get(QRoot::class),
            'mutation' => $typeFactory->get(MRoot::class),
        ]);

        $query = $this->request->input('query');
        $variables = $this->request->input('variables');
        $isDebug = $this->request->has('debug');

        $rootValue = [];

        $output = GraphQL::executeQuery($schema, $query, $rootValue, [], $variables)
            ->setErrorsHandler(function (array $errors, callable $formatter) use ($isDebug) {
                if ($isDebug) {
                    return array_map($formatter, $errors);
                } else {
                    /** @var Error $error */
                    $error = array_pop($errors);
                    if (empty($error->getPrevious())) {
                        throw $error;
                    } else {
                        throw $error->getPrevious();
                    }
                }
            })
            ->toArray(
                $isDebug ? Debug::INCLUDE_DEBUG_MESSAGE | Debug::RETHROW_INTERNAL_EXCEPTIONS : false
            );

        if (!array_key_exists('data', $output)) {
            return $this->response->json(ResultVo::error(
                ErrorCode::SERVER_ERROR,
                '系统错误，请联系管理员',
                $isDebug ? $output : null
            ))->withStatus(500);
        }

        return ResultVo::success($output['data']);
    }
}
