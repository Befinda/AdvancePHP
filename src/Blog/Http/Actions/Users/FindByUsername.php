<?php

namespace Beffi\advancephp\Blog\Http\Actions\Users;

use Beffi\advancephp\Blog\Exceptions\HttpException;
use Beffi\advancephp\Blog\Http\ErrorResponse;
use Beffi\advancephp\Blog\Http\SuccessfulResponse;
use Beffi\advancephp\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Beffi\advancephp\Blog\Exceptions\UserNotFoundException;
use Beffi\advancephp\Blog\Http\Actions\ActionInterface;
use Beffi\advancephp\Blog\Http\Request;
use Beffi\advancephp\Blog\Http\Response;

class FindByUsername implements ActionInterface{
	
	/**
	 */
	public function __construct(
		private UsersRepositoryInterface $usersRepository
	) {
	}
	/**
	 * @param \Beffi\advancephp\Blog\Http\Request $request
	 * @return \Beffi\advancephp\Blog\Http\Response
	 */
	public function handle(Request $request): Response {
		try {
			// Пытаемся получить искомое имя пользователя из запроса
			$username = $request->query('username');
			} catch (HttpException $e) {
			// Если в запросе нет параметра username -
			// возвращаем неуспешный ответ,
			// сообщение об ошибке берём из описания исключения
			return new ErrorResponse($e->getMessage());
			}
			try {
			// Пытаемся найти пользователя в репозитории
			$user = $this->usersRepository->getByUsername($username);
			} catch (UserNotFoundException $e) {
			// Если пользователь не найден -
			// возвращаем неуспешный ответ
			return new ErrorResponse($e->getMessage());
			}
			// Возвращаем успешный ответ
			return new SuccessfulResponse([
			'username' => $user->username(),
			'name' => $user->name()->getName()->first() . ' ' . $user->name()->getName()->last(),
			]);
	}

}