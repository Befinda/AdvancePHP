<?php
namespace Beffi\advancephp\Blog\Http\Actions\Posts;
use Beffi\advancephp\Blog\Exceptions\HttpException;
use Beffi\advancephp\Blog\Exceptions\InvalidArgumentException;
use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\Http\Actions\ActionInterface;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\Response;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;
use Beffi\advancephp\Blog\Post;
use Beffi\advancephp\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\UUID;

class CreatePost implements ActionInterface{
    

    /**
      * Undocumented function
      *
      * @param PostsRepositoryInterface $postsRepository
      * @param UsersRepositoryInterface $usersRepository
      */
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,

    ) {
    }
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function handle(Request $request): Response {
        try {
            
$authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        
    } catch (HttpException | InvalidArgumentException $e) {
    return new ErrorResponse($e->getMessage());
    }
    // Пытаемся найти пользователя в репозитории
    try {
    $user=$this->usersRepository->get($authorUuid);
    } catch (UserNotFoundException $e) {
    return new ErrorResponse($e->getMessage());
    }
    // Генерируем UUID для новой статьи
    $newPostUuid = UUID::random();
    try {
        // Пытаемся создать объект статьи
        // из данных запроса
        $post = new Post(
        $newPostUuid,
        $user,
        $request->jsonBodyField('title'),
        $request->jsonBodyField('text'),
        );
        } catch (HttpException $e) {
        return new ErrorResponse($e->getMessage());
    }
    // Сохраняем новую статью в репозитории
    $this->postsRepository->save($post);
    // Возвращаем успешный ответ,
    // содержащий UUID новой статьи
    return new SuccessfulResponse([
    'uuid' => (string)$newPostUuid,
    ]);
        
    
	}
}