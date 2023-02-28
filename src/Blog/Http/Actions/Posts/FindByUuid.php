<?php

namespace Beffi\advancephp\Blog\Http\Actions\Posts;

use Beffi\advancephp\Blog\Exceptions\HttpException;
use Beffi\advancephp\Blog\Exceptions\PostNotFoundException;
use Beffi\advancephp\Blog\Http\Actions\ActionInterface;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\Response;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;
use Beffi\advancephp\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Beffi\advancephp\Blog\UUID;

class FindByUuid implements ActionInterface{
    

    /**
     */
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function handle(Request $request): Response {
        try {
            $postuuid = $request->query('uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $post = $this->postsRepository->get(new UUID($postuuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            "title"=> $post->getTitle(),
            "text"=> $post->getText(),
        ]);
	}
}