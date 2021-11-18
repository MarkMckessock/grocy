<?php

namespace Grocy\Controllers;

class GenericEntityController extends BaseController
{
	public function UserentitiesList(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args)
	{
		return $this->renderPage($response, 'userentities', [
			'userentities' => $this->getDatabase()->userentities()->orderBy('name', 'COLLATE NOCASE')
		]);
	}

	public function UserentityEditForm(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args)
	{
		if ($args['userentityId'] == 'new')
		{
			return $this->renderPage($response, 'userentityform', [
				'mode' => 'create'
			]);
		}
		else
		{
			return $this->renderPage($response, 'userentityform', [
				'mode' => 'edit',
				'userentity' => $this->getDatabase()->userentities($args['userentityId'])
			]);
		}
	}

	public function UserfieldEditForm(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args)
	{
		if ($args['userfieldId'] == 'new')
		{
			return $this->renderPage($response, 'userfieldform', [
				'mode' => 'create',
				'userfieldTypes' => $this->getUserfieldsService()->GetFieldTypes(),
				'entities' => $this->getUserfieldsService()->GetEntities(),
				'fields' => $this->getUserfieldsServer()->GetAllFields()
			]);
		}
		else
		{
			return $this->renderPage($response, 'userfieldform', [
				'mode' => 'edit',
				'userfield' => $this->getUserfieldsService()->GetField($args['userfieldId']),
				'userfieldTypes' => $this->getUserfieldsService()->GetFieldTypes(),
				'fields' => $this->getUserfieldsServer()->GetAllFields(),
				'entities' => $this->getUserfieldsService()->GetEntities()
			]);
		}
	}

	public function UserfieldsList(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args)
	{
		return $this->renderPage($response, 'userfields', [
			'userfields' => $this->getUserfieldsService()->GetAllFields(),
			'entities' => $this->getUserfieldsService()->GetEntities()
		]);
	}

	public function UserobjectEditForm(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args)
	{
		$userentity = $this->getDatabase()->userentities()->where('name = :1', $args['userentityName'])->fetch();

		if ($args['userobjectId'] == 'new')
		{
			return $this->renderPage($response, 'userobjectform', [
				'userentity' => $userentity,
				'mode' => 'create',
				'userfields' => $this->getUserfieldsService()->GetFields('userentity-' . $args['userentityName'])
			]);
		}
		else
		{
			return $this->renderPage($response, 'userobjectform', [
				'userentity' => $userentity,
				'mode' => 'edit',
				'userobject' => $this->getDatabase()->userobjects($args['userobjectId']),
				'userfields' => $this->getUserfieldsService()->GetFields('userentity-' . $args['userentityName'])
			]);
		}
	}

	public function UserobjectsList(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response, array $args)
	{
		$userentity = $this->getDatabase()->userentities()->where('name = :1', $args['userentityName'])->fetch();

		return $this->renderPage($response, 'userobjects', [
			'userentity' => $userentity,
			'userobjects' => $this->getDatabase()->userobjects()->where('userentity_id = :1', $userentity->id),
			'userfields' => $this->getUserfieldsService()->GetFields('userentity-' . $args['userentityName']),
			'userfieldValues' => $this->getUserfieldsService()->GetAllValues('userentity-' . $args['userentityName'])
		]);
	}
}
