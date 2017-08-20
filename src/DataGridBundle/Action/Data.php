<?php

declare(strict_types=1);

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\DataGridBundle\Action;

use CSBill\CoreBundle\Response\AjaxResponse;
use CSBill\CoreBundle\Traits\SerializeTrait;
use CSBill\DataGridBundle\Repository\GridRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Data implements AjaxResponse
{
    use SerializeTrait;

    /**
     * @var GridRepository
     */
    private $repository;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(GridRepository $repository, ManagerRegistry $registry)
    {
        $this->repository = $repository;
        $this->registry = $registry;
    }

    public function __invoke(Request $request, string $name): Response
    {
        $grid = $this->repository->find($name);

        $grid->setParameters($request->get('parameters', []));

        return $this->serialize($grid->fetchData($request, $this->registry->getManager()));
    }
}