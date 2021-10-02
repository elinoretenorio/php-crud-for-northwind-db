<?php

declare(strict_types=1);

namespace Northwind\CustomerDemo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class CustomerDemoController 
{
    const ERROR_INVALID_INPUT = "Invalid input";

    private ICustomerDemoService $service;

    public function __construct(ICustomerDemoService $service)
    {
        $this->service = $service;        
    }

    public function insert(RequestInterface $request, array $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var CustomerDemoModel $model */
        $model = $this->service->createModel($data);

        $result = $this->service->insert($model);

        return new JsonResponse(["result" => $result]);
    }

    public function update(RequestInterface $request, array $args): ResponseInterface
    {
        $customerDemoId = (int) ($args["customer_demo_id"] ?? 0);
        if ($customerDemoId <= 0) {
            return new JsonResponse(["result" => $customerDemoId, "message" => self::ERROR_INVALID_INPUT]);
        }

        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var CustomerDemoModel $model */
        $model = $this->service->createModel($data);
        $model->setCustomerDemoId($customerDemoId);

        $result = $this->service->update($model);

        return new JsonResponse(["result" => $result]);
    }

    public function get(RequestInterface $request, array $args): ResponseInterface
    {
        $customerDemoId = (int) ($args["customer_demo_id"] ?? 0);
        if ($customerDemoId <= 0) {
            return new JsonResponse(["result" => $customerDemoId, "message" => self::ERROR_INVALID_INPUT]);
        }

        /** @var CustomerDemoModel $model */
        $model = $this->service->get($customerDemoId);

        return new JsonResponse(["result" => $model->jsonSerialize()]);
    }

    public function getAll(RequestInterface $request, array $args): ResponseInterface
    {
        $models = $this->service->getAll();

        $result = [];

        /** @var CustomerDemoModel $model */
        foreach ($models as $model) {
            $result[] = $model->jsonSerialize();
        }

        return new JsonResponse(["result" => $result]);
    }

    public function delete(RequestInterface $request, array $args): ResponseInterface
    {
        $customerDemoId = (int) ($args["customer_demo_id"] ?? 0);
        if ($customerDemoId <= 0) {
            return new JsonResponse(["result" => $customerDemoId, "message" => self::ERROR_INVALID_INPUT]);
        }

        $result = $this->service->delete($customerDemoId);

        return new JsonResponse(["result" => $result]);
    }
}