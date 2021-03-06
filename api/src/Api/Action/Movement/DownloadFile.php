<?php

namespace App\Api\Action\Movement;

use App\Entity\User;
use App\Service\Movement\DownloadFileService;
use App\Service\Request\RequestService;
use function explode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFile
{
    private const RESPONSE_CONTENT_TYPE = 'application/octet-stream';

    private DownloadFileService $downloadFileService;

    public function __construct(DownloadFileService $downloadFileService)
    {
        $this->downloadFileService = $downloadFileService;
    }

    /**
     * @throws JsonException
     */
    public function __invoke(Request $request, User $user): Response
    {
        $filePath = RequestService::getField($request, 'filePath');
        $file = $this->downloadFileService->downloadFile($user, $filePath);

        $response = new Response($file);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('movement-file.%s', explode('.', $filePath)[1])
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', self::RESPONSE_CONTENT_TYPE);

        return $response;
    }
}
