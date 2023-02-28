<?php

namespace Beffi\advancephp\Blog\Http\Actions\Comments;

use Beffi\advancephp\Blog\Comment;
use Beffi\advancephp\Blog\Exceptions\HttpException;
use Beffi\advancephp\Blog\Exceptions\PostNotFoundException;
use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\Http\Actions\ActionInterface;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\Response;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;
use Beffi\advancephp\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use Beffi\advancephp\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\UUID;
use SebastianBergmann\Diff\InvalidArgumentException;

class CreateComment implements ActionInterface{
    

    /**
     * @param UsersRepositoryInterface $usersRepository
     * @param PostsRepositoryInterface $postsRepository
     * @param CommentsRepositoryInterface $commentsRepository
     */
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository
    ) {
    }
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function handle(Request $request): Response {
        // POST http://127.0.0.1:8000/posts/comment
        // {
        // "author_uuid": "<UUID>",
        // "post_uuid": "<UUID>",
        // "text": "<TEXT>",
        // }
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $user=$this->usersRepository->get($authorUuid);
            } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
            }
        try {
            $post=$this->postsRepository->get($postUuid);
            } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
            }
            $newCommentUuid = UUID::random();
            try {
                // Пытаемся создать объект статьи
                // из данных запроса
                $comment = new Comment(
                $newCommentUuid,
                $user,
                $post,
                $request->jsonBodyField('text'),
                );
                } catch (HttpException $e) {
                return new ErrorResponse($e->getMessage());
            }
            $this->commentsRepository->save($comment);
            return new SuccessfulResponse([
                'post_title' => $post->getTitle(),
                'comment'=>$comment->getText()
                ]);
	}
}